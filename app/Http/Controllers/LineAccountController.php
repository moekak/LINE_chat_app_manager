<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLineAccountRequest;
use App\Http\Requests\UpdateLineAccountRequest;
use App\Models\AccountStatus;
use App\Models\BlockChatUser;
use App\Models\ChatUser;
use App\Models\LineAccount;
use App\Models\PageTitle;
use App\Models\SecondAccount;
use App\Models\UserEntity;
use App\Services\MessageService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LineAccountController extends Controller
{

    const MESSAGES_PER_PAGE = 20;
    public function index(){
        $user = Auth::user();

        $accounts = LineAccount::query()
            ->where('user_id', $user->id)
            ->select([
                "line_accounts.id",
                "line_accounts.account_name",
                "line_accounts.created_at",
                "line_accounts.account_status",
            ])
            // 既存のselectSub部分は変更なし
            ->selectSub(
                DB::table('user_message_reads')
                    ->select(DB::raw('COALESCE(SUM(unread_count), 0)'))
                    ->whereColumn('admin_account_id', 'line_accounts.id'),
                'unread_count'
            )
            ->selectSub(
                DB::table('message_summaries')
                    ->select(DB::raw('DATE_FORMAT(latest_user_message_date, "%Y-%m-%d %H:%i")'))
                    ->whereColumn('admin_id', 'line_accounts.id')
                    ->whereNotNull('latest_user_message_date')  // nullを除外
                    ->orderBy('latest_user_message_date', 'desc')  // 日付で降順ソート
                    ->limit(1),  // 最新の1件のみ取得
                'latest_message_date'
            )
            ->addSelect([
                'entity_uuid' => DB::table('user_entities')
                    ->select('entity_uuid')
                    ->whereColumn('related_id', 'line_accounts.id')
                    ->where('entity_type', 'admin')
                    ->limit(1)
            ])
            ->orderBy('unread_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('account_status')
            ->map(function ($group) {
                return $group->take(self::MESSAGES_PER_PAGE);
            });

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
        $user_uuid = UserEntity::where("related_id", $id)->where("entity_type", "admin")->value("entity_uuid");
        $account_name = LineAccount::where("id", $id)->value("account_name");
        $title = PageTitle::where("admin_id", $id)->first();

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
            ->select([
                'chat_users.account_id',
                'chat_users.created_at', 
                'chat_users.line_name',
                'chat_users.id',
            ])
            ->selectSub(
                DB::table('user_entities')
                    ->select('entity_uuid')
                    ->whereColumn('related_id', 'chat_users.id')
                    ->where('entity_type', 'user')
                    ->limit(1),
                'entity_uuid'
            )
            ->selectSub(
                DB::table('user_message_reads')
                    ->select(DB::raw('COALESCE(unread_count, 0)'))
                    ->whereColumn('chat_user_id', 'chat_users.id')
                    ->limit(1),
                'unread_count'
            )
            ->selectSub(
                DB::table('message_summaries')
                    ->select(DB::raw('DATE_FORMAT(latest_user_message_date, "%Y-%m-%d %H:%i")'))
                    ->whereRaw('admin_id = ?', [$id])
                    ->whereColumn('user_id', 'chat_users.id')
                    ->limit(1),  // 最新の1件のみ取得
                'latest_message_date'
            )
            ->orderBy('unread_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(self::MESSAGES_PER_PAGE)
            ->get();
        return view("admin.account_show", ["user_uuid" => $user_uuid, "account_name" => $account_name, "chat_users" => $users, "id" => $id, "title" => $title]);
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



    public function fetchScrollData(string $admin_id, Request $request){
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
            ->whereNotIn('chat_users.id', $request->input("accountList"))
            ->where('account_id', $admin_id)
            ->select([
                'chat_users.account_id',
                'chat_users.created_at', 
                'chat_users.line_name',
                'chat_users.id',
            ])
            ->selectSub(
                DB::table('user_entities')
                    ->select('entity_uuid')
                    ->whereColumn('related_id', 'chat_users.id')
                    ->where('entity_type', 'user')
                    ->limit(1),
                'entity_uuid'
            )
            ->selectSub(
                DB::table('user_message_reads')
                    ->select(DB::raw('COALESCE(unread_count, 0)'))
                    ->whereColumn('chat_user_id', 'chat_users.id')
                    ->limit(1),
                'unread_count'
            )
            ->selectSub(
                DB::table('message_summaries')
                    ->select(DB::raw('DATE_FORMAT(latest_user_message_date, "%Y-%m-%d %H:%i")'))
                    ->whereRaw('admin_id = ?', [$admin_id])
                    ->whereColumn('user_id', 'chat_users.id')
                    ->limit(1),  // 最新の1件のみ取得
                'latest_message_date'
            )
            ->orderBy('unread_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->skip($request->input("dataCount"))
            ->take(self::MESSAGES_PER_PAGE)
            ->get();

        return response()->json($users);
    }


    public function fetchScrollAcocuntData(string $admin_id, string $status_id, Request $request){
        try{

            $accountData = LineAccount::query()
                ->where('user_id', $admin_id)
                ->whereNotIn('line_accounts.id', $request->input("accountList"))
                ->select([
                    "line_accounts.id",
                    "line_accounts.account_name",
                    "line_accounts.created_at",
                    "line_accounts.account_status",
                ])
                // 既存のselectSub部分は変更なし
                ->selectSub(
                    DB::table('user_message_reads')
                        ->select(DB::raw('COALESCE(SUM(unread_count), 0)'))
                        ->whereColumn('admin_account_id', 'line_accounts.id'),
                    'unread_count'
                )
                ->selectSub(
                    DB::table('message_summaries')
                        ->select(DB::raw('DATE_FORMAT(latest_user_message_date, "%Y-%m-%d %H:%i")'))
                        ->whereColumn('admin_id', 'line_accounts.id')
                        ->whereNotNull('latest_user_message_date')  // nullを除外
                        ->orderBy('latest_user_message_date', 'desc')  // 日付で降順ソート
                        ->limit(1),  // 最新の1件のみ取得
                    'latest_message_date'
                )
                ->addSelect([
                    'entity_uuid' => DB::table('user_entities')
                        ->select('entity_uuid')
                        ->whereColumn('related_id', 'line_accounts.id')
                        ->where('entity_type', 'admin')
                        ->limit(1)
                ])
                ->where("account_status", $status_id)
                ->orderBy('account_status')
                ->orderBy('unread_count', 'desc')  
                ->orderBy('created_at', 'desc')  // 作成日時の降順
                ->skip($request->input("dataCount"))
                ->take(self::MESSAGES_PER_PAGE)
                ->get();
                

            $categories = Cache::remember('account_status', 60*24, function() {
                return AccountStatus::all();
            });
            return response()->json(["accountData" => $accountData, "categories" => $categories]);
        }catch(\Exception $e){
            Log::debug($e);
        }
        

    }


    public function fetchSpecificAccount(Request $request){
        try{
            $data = $request->input('account_uuid', 'default-value');
            $accountData = LineAccount::query()
                ->where('id', function($query) use($data){
                    $query->select("related_id")
                            ->from("user_entities")
                            ->where("entity_uuid", $data);
                })
                ->select([
                    "line_accounts.id",
                    "line_accounts.account_name",
                    "line_accounts.created_at",
                    "line_accounts.account_status",
                    DB::raw('(
                        SELECT COALESCE(SUM(unread_count), 0)
                        FROM user_message_reads
                        WHERE admin_account_id = line_accounts.id
                    ) as unread_count'),
                    DB::raw('(
                        SELECT entity_uuid 
                        FROM user_entities 
                        WHERE related_id = line_accounts.id
                        AND entity_type = "admin"
                    ) as entity_uuid')
                ])
                ->selectSub(
                    DB::table('message_summaries')
                        ->select(DB::raw('DATE_FORMAT(latest_user_message_date, "%Y-%m-%d %H:%i")'))
                        ->whereColumn('admin_id', 'line_accounts.id')
                        ->whereNotNull('latest_user_message_date')  // nullを除外
                        ->orderBy('latest_user_message_date', 'desc')  // 日付で降順ソート
                        ->limit(1),  // 最新の1件のみ取得
                    'latest_message_date'
                )
                ->get();

                $categories = Cache::remember('account_status', 60*24, function() {
                    return AccountStatus::all();
                });
                return response()->json(["accountData" => $accountData, "categories" => $categories]);
        }catch(\Exception $e){
            Log::debug($e);
        }   
        
    }
    
}


