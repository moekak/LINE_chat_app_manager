<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLineAccountRequest;
use App\Http\Requests\UpdateLineAccountRequest;
use App\Models\AccountStatus;
use App\Models\ChatUser;
use App\Models\LineAccount;
use App\Models\SecondAccount;
use App\Models\UserEntity;
use App\Services\MessageCountService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LineAccountController extends Controller
{

    public function index(){

        $messageCountService = new MessageCountService();

        $user = Auth::user();
        $line_accounts  = LineAccount::where("user_id", $user->id)->get();
        $second_accounts = LineAccount::where("user_id", $user->id)->whereIn("account_status", ["2", "3"])->get();

        foreach($line_accounts as $account){
            // アカウントごとの最新メッセージ時間を取得
            $latest_message_date = $messageCountService->getLatestUserMessageDate($account->id);

            // アカウントごとのユーザーをすべて取得
            $users = ChatUser::where("account_id", $account->id)
                    ->whereNotIn("id", function($query){
                        $query->select("chat_user_id")
                                ->from("block_chat_users")
                                ->where("is_blocked", '1')
                                ->get();
                    })
                    ->get();
            $totalCount = 0;

            foreach($users as $user){
                // 未読数のメッセージ数を取得
                $totalCount += $messageCountService->selectTotalMessageCount($account->id, $user->id);
                // チャットユーザーuuidの取得
                $user["uuid"] = UserEntity::where("related_id", $user->id);
            }

            $account["total_count"]         = $totalCount;
            $account["uuid"]                = UserEntity::where("related_id", $account->id)->value("entity_uuid");
            $account["latest_message_date"] = $latest_message_date ?? "";
        }

        // total_countの多い順にソート
        $line_accounts = $line_accounts->sortByDesc('total_count');

        $formatted_line_accounts = [
            "使用中" => [],
            "未使用" => [],
            "停止" => [],
            "バン" => []
        ];
        

        foreach($line_accounts as $account){
            // echo $account->account_status;
            // echo "<br>";
            if($account->account_status == "1"){
                $formatted_line_accounts["使用中"][] = $account;
            }
            if($account->account_status == "2"){
                $formatted_line_accounts["未使用"][] = $account;
            }
            if($account->account_status == "3"){
                $formatted_line_accounts["停止"][] = $account;
            }
            if($account->account_status == "4"){
                $formatted_line_accounts["バン"][] = $account;
            }
        }

        // アカウントのステータスの種類をすべて取得
        $account_status = AccountStatus::all();
        return view("admin.dashboard", [ "line_accounts" => $formatted_line_accounts, "user" => $user, "account_status" => $account_status, "second_accounts" => $second_accounts]);
    }

    public function create(CreateLineAccountRequest $request)
    {   

        $user_id = Auth::user();
        $validated = $request->validated();

        //Illuminate\Http\Client\Responseオブジェクトの形
        $lineResponse = $request->getLineApiResponse();
        // json()メソッドを使用することでPHPの配列に変換
        $accountId= $lineResponse->json("userId");
        $pictureUrl= $lineResponse->json("pictureUrl");

        $lineAccountData = [
            "user_id" => $user_id->id,
            "account_id" => $accountId,
            "channel_access_token" => $validated["channelaccesstoken"],
            "channel_secret" => $validated["channelsecret"],
            "account_name" => $validated["account_name"],
            "account_url" => $validated["account_url"],
            "user_picture" => $pictureUrl,
            "account_status" => $validated["account_status"]
        ];

        $line_account = LineAccount::create($lineAccountData);

        $data = [
            "related_id" => $line_account->id,
            "entity_type" => "admin"
        ];
        
        UserEntity::create($data);

        if($request->input("second_account_id")){
            $second_account_data = [
                "current_account_id" => $line_account->id,
                "second_account_id" => $validated["second_account_id"],
            ];
            SecondAccount::create($second_account_data);
        }

        return redirect()->route("dashboard")->with("success", "アカウントの追加に成功しました。");
    }

    public function show(string $id)
    {

        $messageCountService = new MessageCountService();
        $user = Auth::user();
        $user_uuid = UserEntity::where("related_id", $id)->value("entity_uuid");
        $account_data = LineAccount::where("user_id", $user->id)->get();

        $users = ChatUser::whereNotIn('id', function($query) {
                // サブクエリ
                $query->select('chat_user_id')
                    ->from('block_chat_users')
                    ->whereIn('id', function($subQuery) {
                        $subQuery->select('id')
                            ->from('block_chat_users')
                            ->where("is_blocked", '1')
                            ->latest()
                            ->groupBy('chat_user_id');
                    });
            })
            ->where('account_id', $id)
            ->get();

        foreach($users as $user){
            $user["latest_message_date"] = $messageCountService->getLatestUserMessageDate($id, $user->id);
            $user["message_count"] = $messageCountService->selectTotalMessageCount($id, $user->id);
            $user["uuid"] = UserEntity::where("related_id", $user->id)->value("entity_uuid");
        }
    
        return view("admin.account_show", ["user_uuid" => $user_uuid, "account_data" => $account_data, "chat_users" => $users, "id" => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
        $account_data = LineAccount::findOrFail($id);
        $second_account = [];
        $second_account["id"] = SecondAccount::where("current_account_id", $id)->value("second_account_id");
        if($second_account["id"]){
            $second_account["account_name"] = LineAccount::where("id",  $second_account["id"])->value("account_name") ;
        }
        return response()->json(["account_data" => $account_data, "second_account" => $second_account]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLineAccountRequest $request, $id)
    {
        $validated = $request->validated();

        $line_account = LineAccount::findOrFail($id);
        Log::debug($line_account->toArray());
        $line_account->update(["account_name" => $validated["account_name"], "account_url" => $validated["account_url"]]);

        if($request->input("second_account_id")){
            $second_account = SecondAccount::where("current_account_id", $id)->first();
            if($second_account){
                $second_account->update(["second_account_id" => $validated["second_account_id"]]);
            }else{
                $second_account_data = [
                    "current_account_id" => $id,
                    "second_account_id" => $validated["second_account_id"],
                ];
                SecondAccount::create($second_account_data);
            }
        }
        
        return redirect()->route("dashboard")->with("success", "アカウント情報の更新に成功しました。");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $line_account = LineAccount::findOrFail($id);
        $line_account->delete();

        return redirect()->route("dashboard")->with("success", "アカウントの削除に成功しました。");
    }


    public function hasSecondAccount(string $id){
        $second_account_id = SecondAccount::where("current_account_id", $id)->value("second_account_id");
        $data = $second_account_id ?? false;
        return response()->json($data);
    }

    public function updateStatus(string $account_id, string $status_id, $current_status_name){

        // ステータスを更新したいアカウントを取得する
        $line_account = LineAccount::findOrFail($account_id);
        // フロントに返す文言
        $is_success = "";
        // アカウントがあった場合、ステータスを変更する
        if($line_account){
            $line_account->update(["account_status" => $status_id]);

            // 使用中以外に切り替える場合、予備アカウントのステータスを使用中に切り替える
            if($status_id !== "1" && $current_status_name == "使用中"){
                // 予備アカウントIDを取得する
                $second_account_id      = SecondAccount::where("current_account_id", $account_id)->value("second_account_id");
                // 予備アカウントをIDを使用し手取得する
                $second_line_account    = LineAccount::where("id", $second_account_id)->first();

                Log::debug($second_line_account);
                $second_line_account->update(["account_status" => "1"]);
            }
            $is_success = true;
        // アカウントがない場合は、フロントにfalseを返す
        }else{
            $is_success = false;
        }
        return response()->json($is_success);
    }
    
}


