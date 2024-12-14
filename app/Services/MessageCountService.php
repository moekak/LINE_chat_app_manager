<?php

namespace App\Services;
use App\Models\UserMessageRead;

class MessageCountService{
    public function selectTotalMessageCount($admin_id, $user_id){
        $unread_count = UserMessageRead::where("chat_user_id", $user_id)->where("admin_account_id", $admin_id)->value("unread_count");
        return $unread_count;
    } 
}






