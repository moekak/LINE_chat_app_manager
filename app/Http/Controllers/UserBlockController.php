<?php

namespace App\Http\Controllers;

use App\Models\BlockChatUser;
use App\Models\ChatUser;
use App\Models\LineAccount;
use App\Models\UserEntity;
use App\Services\MessageCountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserBlockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {

        $messageCountService = new MessageCountService();
        // ブロックユーザー一覧を取得
        // 配列を返す(pluck)
        $block_users_ids = BlockChatUser::where("is_blocked", "1")->pluck("chat_user_id");
        $block_users = ChatUser::whereIn("id", $block_users_ids)->where("account_id" , $id)->get();

        foreach($block_users as $user){
            $user["latest_message_date"] =  $messageCountService->getLatestUserMessageDate($id, $user->id);
        }

    
        // userUUIDと管理者アカウントのデータを取得
        $user = Auth::user();
        $user_uuid = UserEntity::where("related_id", $id)->value("entity_uuid");
        $account_data = LineAccount::where("user_id", $user->id)->get();

        

        return view("admin.block_account", ["block_lists" => $block_users, "user_uuid"=> $user_uuid, "account_data" => $account_data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
