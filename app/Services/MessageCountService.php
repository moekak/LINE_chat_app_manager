<?php

namespace App\Services;

use App\Models\UserMessage;
use App\Models\UserMessageImage;
use App\Models\UserMessageRead;

class MessageCountService{

    public function getLatestMessageAndImageData( $admin_id, $user_id = null){
         // 共通のクエリ部分を処理
        $messageQuery = UserMessage::where("admin_id", $admin_id)
            ->orderBy("created_at", "desc");

        $messageImageQuery = UserMessageImage::where("admin_id", $admin_id)
            ->orderBy("created_at", "desc");

          // user_idがある場合はuser_idも条件に追加
        if ($user_id) {
            $messageQuery->where("user_id", $user_id);
            $messageImageQuery->where("user_id", $user_id);
        }

        // メッセージIDを取得
        $current_message_data         = $messageQuery->first();
        $current_message_image_data  = $messageImageQuery->first();

        return ["current_message_data" => $current_message_data, "current_message_image_data" => $current_message_image_data];
    }




    public function getLatestUserMessageDate($admin_id, $user_id = null){
        if($user_id){
            $current_message_data = $this->getLatestMessageAndImageData($admin_id, $user_id)["current_message_data"];
            $current_message_image_data = $this->getLatestMessageAndImageData($admin_id, $user_id)["current_message_image_data"]; 
        }else{
            $current_message_data = $this->getLatestMessageAndImageData($admin_id)["current_message_data"];
            $current_message_image_data = $this->getLatestMessageAndImageData($admin_id)["current_message_image_data"]; 
        }
        

        // 両方がnullの場合はnullを返す
        if (!$current_message_data && !$current_message_image_data) {
            return null;
        }

        // 両方の`created_at`を比較して新しい方の`message_id`を取得
        if ($current_message_data && $current_message_image_data) {
            if ($current_message_data->created_at > $current_message_image_data->created_at) {
                return $current_message_data->created_at;
            } else {
                return $current_message_image_data->created_at;
            }
        }

        // 片方がnullの場合は、nullでない方の`message_id`を返す
        if ($current_message_data) {
            return $current_message_data->created_at;
        }

        if ($current_message_image_data) {
            return $current_message_image_data->created_at;
        }

    }
    public function selectTotalMessageCount($admin_id, $user_id, $periods = []){
        $unread_count = UserMessageRead::where("chat_user_id", $user_id)->where("admin_account_id", $admin_id)->value("unread_count");
        return $unread_count;
    } 

}






