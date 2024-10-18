<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserDataRequest;
use App\Models\ChatUser;
use App\Models\UserEntity;
use App\Services\MessageCountService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class ChatUserController extends Controller
{
    public function getUserData($sender_id, $receiver_id){

        $sender_uuid = UserEntity::where("entity_uuid", $sender_id)->value("related_id");
        $receiver_uuid = UserEntity::where("entity_uuid", $receiver_id)->value("related_id");
        $messageCountService = new MessageCountService();
        $message_count = $messageCountService->selectTotalMessageCount($receiver_uuid, $sender_uuid);

        $chatUser = ChatUser::find($sender_uuid);
        // created_atをAsia/Tokyoに変換し、フォーマット
        $chatUser->created_at = $chatUser->created_at->timezone('Asia/Tokyo')->format('Y-m-d H:i:s');

        $chatUser["message_count"] = $message_count;
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
        $user->update(["is_blocked" => "1"]);
        return redirect()->route("account.show", ["id" => $id])->with("success", "ユーザーのブロックに成功しました。");
    }
}
