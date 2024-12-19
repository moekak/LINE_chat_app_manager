<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserDataRequest;
use App\Models\BlockChatUser;
use App\Models\ChatUser;
use App\Models\UserEntity;
use App\Services\MessageCountService;
use Illuminate\Support\Facades\DB;

class ChatUserController extends Controller
{
    public function getUserData($sender_id, $receiver_id){

        $receiver_uuid = UserEntity::where("entity_uuid", $receiver_id)->value("related_id");

        $chatUser = ChatUser::where("id", function($query) use($sender_id){
            $query->select("related_id")
                ->from("user_entities")
                ->where("entity_uuid", $sender_id)
                ->where("entity_type", "user");
            })
            ->select([
                "chat_users.id",
                "chat_users.line_name",
                DB::raw('DATE_FORMAT(chat_users.created_at, "%Y-%m-%d %H:%i")')
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
                    ->select(DB::raw('DATE_FORMAT(latest_all_message_date, "%Y-%m-%d %H:%i")'))
                    ->whereColumn('user_id', 'chat_users.id')
                    ->whereNotNull('latest_all_message_date'),
                'latest_message_date'
            )
            ->get();

        // // created_atをAsia/Tokyoに変換し、フォーマット
        // $chatUser->created_at = $chatUser->created_at->timezone('Asia/Tokyo')->format('Y-m-d H:i:s');
        return response()->json([$chatUser, "admin_account_id" => $receiver_uuid], 200);
    }


    public function getUserName($user_id){
        $userData = ChatUser::findOrfail($user_id);
        return response()->json($userData);
    }

    public function update(UpdateUserDataRequest $request, $id){
        $validated = $request->validated();
        $userData = ChatUser::findOrFail($id);
        $userData->update(["line_name" => $validated["account_name"]]);
        return redirect()->route("account.show", ["id" => $userData["account_id"]])->with("success", "ユーザー情報の更新に成功しました。");
    }

    public function block(string $id){
        $user = ChatUser::findOrFail($id);

        if($user){
            BlockChatUser::create(["chat_user_id" => $id]);
            return redirect()->route("account.show", ["id" => $user["account_id"]])->with("success", "ユーザーのブロックに成功しました。");    
        }else{
            BlockChatUser::create(["chat_user_id" => $id]);
            return redirect()->route("account.show", ["id" => $user["account_id"]])->with("success", "ユーザーのブロックに失敗しました。");    
        }  
    }
}
