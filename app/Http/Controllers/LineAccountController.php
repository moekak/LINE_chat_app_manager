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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LineAccountController extends Controller
{

    public function index(){
        $messageCountService = new MessageCountService();

        $user = Auth::user();
        // 公式LINEアカウントをすべて取得する(LINEAccount)

        // ステータスごとのアカウントを1つのクエリで取得
        $accounts = LineAccount::query()
            ->where('user_id', $user->id)
            ->select([
                "line_accounts.id",
                "line_accounts.account_name",
                "line_accounts.created_at",
                "line_accounts.account_status",
                DB::raw('DATE_FORMAT(
                    (SELECT MAX(latest_date)
                    FROM (
                        SELECT created_at as latest_date
                        FROM user_messages
                        WHERE user_messages.admin_id = line_accounts.id
                        UNION ALL
                        SELECT created_at as latest_date
                        FROM user_message_images
                        WHERE user_message_images.admin_id = line_accounts.id
                    ) as combined_dates), "%Y-%m-%d %H:%i"
                ) as latest_message_date')
            ])
            ->where('account_status', '1')
            // ->limit(10)
            ->unionAll(
                LineAccount::query()
                    ->where('user_id', $user->id)
                    ->select([
                        "line_accounts.id",
                        "line_accounts.account_name",
                        "line_accounts.created_at",
                        "line_accounts.account_status",
                        DB::raw('DATE_FORMAT(
                            (SELECT MAX(latest_date)
                            FROM (
                                SELECT created_at as latest_date
                                FROM user_messages
                                WHERE user_messages.admin_id = line_accounts.id
                                UNION ALL
                                SELECT created_at as latest_date
                                FROM user_message_images
                                WHERE user_message_images.admin_id = line_accounts.id
                            ) as combined_dates), "%Y-%m-%d %H:%i"
                        ) as latest_message_date')
                    ])
                    ->where('account_status', '2')
                    // ->limit(10)
            )
            ->unionAll(
                LineAccount::query()
                    ->where('user_id', $user->id)
                    ->select([
                        "line_accounts.id",
                        "line_accounts.account_name",
                        "line_accounts.created_at",
                        "line_accounts.account_status",
                        DB::raw('DATE_FORMAT(
                            (SELECT MAX(latest_date)
                            FROM (
                                SELECT created_at as latest_date
                                FROM user_messages
                                WHERE user_messages.admin_id = line_accounts.id
                                UNION ALL
                                SELECT created_at as latest_date
                                FROM user_message_images
                                WHERE user_message_images.admin_id = line_accounts.id
                            ) as combined_dates), "%Y-%m-%d %H:%i"
                        ) as latest_message_date')
                    ])
                    ->where('account_status', '3')
                    // ->limit(10)
            )
            ->unionAll(
                LineAccount::query()
                    ->where('user_id', $user->id)
                    ->select([
                        "line_accounts.id",
                        "line_accounts.account_name",
                        "line_accounts.created_at",
                        "line_accounts.account_status",
                        DB::raw('DATE_FORMAT(
                            (SELECT MAX(latest_date)
                            FROM (
                                SELECT created_at as latest_date
                                FROM user_messages
                                WHERE user_messages.admin_id = line_accounts.id
                                UNION ALL
                                SELECT created_at as latest_date
                                FROM user_message_images
                                WHERE user_message_images.admin_id = line_accounts.id
                            ) as combined_dates), "%Y-%m-%d %H:%i"
                        ) as latest_message_date')
                    ])
                    ->where('account_status', '4')
                    // ->limit(10)
            )
            ->with(['userEntity' => function($query) {
                $query->select('id', 'related_id', 'entity_uuid')
                    ->where('entity_type', 'admin');
            }])
            ->get();



        //// 先にオブジェクトとしてアクセス
        $admin_ids = $accounts->map(function($account) {
            return $account['id']; // 配列アクセスを使用
        })->toArray();

    

        // 未読数を一括取得
        $unreadCounts = $messageCountService->getUnreadCountsByAdminIds($admin_ids);

        // 未読数をマージしてからグループ化
        $accounts = $accounts->map(function($account) use ($unreadCounts) {
            $account['unread_count'] = $unreadCounts[$account['id']] ?? 0;
            return $account;
        })->groupBy('account_status');

        $active_accounts = $accounts["1"] ?? collect();
        $inactive_accounts = $accounts["2"] ?? collect();
        $suspended_accounts = $accounts["3"] ?? collect();
        $banned_accounts = $accounts["4"] ?? collect();


        // 予備アカウントを取得する(LINEAccount)
        $second_accounts = LineAccount::where("user_id", $user->id)->whereIn("account_status", ["2", "3"])->get();


        // アカウントのステータスの種類をすべて取得(キャッシュ取得)
        $account_status = Cache::remember('account_status', 60*24, function() {
            return AccountStatus::all();
        });

        return view("admin.dashboard", [ "active_accounts" => $active_accounts, "inactive_accounts" => $inactive_accounts, "suspended_accounts" => $suspended_accounts, "banned_accounts" => $banned_accounts, "user" => $user, "account_status" => $account_status, "second_accounts" => $second_accounts]);
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

        // // node.jsに通知を送信
        return redirect()->route("dashboard")->with("success", "アカウントの追加に成功しました。");
    }

    public function show(string $id)
    {

        $messageCountService = new MessageCountService();
        $user = Auth::user();
        $user_uuid = UserEntity::where("related_id", $id)->where("entity_type", "admin")->value("entity_uuid");
        $account_data = LineAccount::where("user_id", $user->id)->get();

        $users = ChatUser::limit(20)->whereNotIn('id', function($query) {
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



    public function fetchScrollData(string $admin_id, $start){
        Log::debug($admin_id);
        $messageCountService = new MessageCountService();
        $length = 10;

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
            ->where('account_id', $admin_id)
            ->skip($start) // $start 件目からスキップ
            ->take($length) // $length 件取得
            ->get();

        foreach($users as $user){
            $user["latest_message_date"] = $messageCountService->getLatestUserMessageDate($admin_id, $user->id);
            $user["message_count"] = $messageCountService->selectTotalMessageCount($admin_id, $user->id);
            $user["uuid"] = UserEntity::where("related_id", $user->id)->value("entity_uuid");
        }
    
        return response()->json($users);
    }
    
}


