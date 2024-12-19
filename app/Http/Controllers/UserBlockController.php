<?php

namespace App\Http\Controllers;

use App\Models\BlockChatUser;
use App\Models\ChatUser;
use App\Models\LineAccount;
use App\Models\UserEntity;
use App\Services\MessageCountService;
use App\Services\MessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserBlockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {

        // ブロックユーザー一覧を取得
        $block_users_ids = BlockChatUser::where("is_blocked", "1")->pluck("chat_user_id");
        $account_name = LineAccount::where("id", $id)->value("account_name");
        
        $block_users = ChatUser::whereIn("id", $block_users_ids)
            ->where('account_id', $id)
            ->select([
                'chat_users.account_id',
                'chat_users.created_at',
                'chat_users.line_name',
                'chat_users.id',
            ])
            ->selectSub(
                DB::table('message_summaries')
                    ->select(DB::raw('DATE_FORMAT(latest_user_message_date, "%Y-%m-%d %H:%i")'))
                    ->whereRaw('admin_id = ?', [$id])
                    ->whereColumn('user_id', 'chat_users.id')
                    ->limit(1),  // 最新の1件のみ取得
                'latest_message_date'
            )
            ->get();


        // userUUIDと管理者アカウントのデータを取得
        $user = Auth::user();
        $user_uuid = UserEntity::where("related_id", $id)->value("entity_uuid");
        $account_data = LineAccount::where("user_id", $user->id)->get();


        return view("admin.block_account", ["block_lists" => $block_users, "user_uuid"=> $user_uuid, "account_data" => $account_data, "account_name" => $account_name]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(string $id)
    {
        $block_user = BlockChatUser::where("chat_user_id", $id)->latest()->first();
        $block_user->update(["is_blocked" => '0']);

        $admin_id = ChatUser::where("id", $id)->value("account_id");
        return redirect()->route("account.block.user", ['id' => $admin_id])->with("success", "ブロック解除に成功しました");
    }

}
