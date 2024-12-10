<?php

namespace App\Services;

use App\Models\AdminMessage;
use App\Models\AdminMessageImage;
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



    // public function getLatesetUserMessageID($user_id, $admin_id){
    //     $current_message_id = UserMessage::where("user_id", $user_id)->where("admin_id", $admin_id)->orderBy("created_at", "desc")->value("message_id");
    //     $current_message_image_id = UserMessageImage::where("user_id", $user_id)->where("admin_id", $admin_id)->orderBy("created_at", "desc")->value("message_id");
    //     $latest_message_id = 0;

    //     if ($current_message_id !== null && $current_message_image_id !== null) {
    //         // 両方の値がある場合、大きい方を取得
    //         $latest_message_id = max($current_message_id, $current_message_image_id);
    //     } elseif ($current_message_id !== null) {
    //         // `UserMessage`のIDがある場合、それを採用
    //         $latest_message_id = $current_message_id;
    //     } elseif ($current_message_image_id !== null) {
    //         // `UserMessageImage`のIDがある場合、それを採用
    //         $latest_message_id = $current_message_image_id;
    //     }

    //     return $latest_message_id;
    // }

    // public function getLatesetAdminMessageID($user_id, $admin_id){
    //     $current_message_id = AdminMessage::where("user_id", $user_id)->where("admin_id", $admin_id)->orderBy("created_at", "desc")->value("message_id");
    //     $current_message_image_id = AdminMessageImage::where("user_id", $user_id)->where("admin_id", $admin_id)->orderBy("created_at", "desc")->value("message_id");
    //     $latest_message_id = 0;

    //     if ($current_message_id !== null && $current_message_image_id !== null) {
    //         // 両方の値がある場合、大きい方を取得
    //         $latest_message_id = max($current_message_id, $current_message_image_id);
    //     } elseif ($current_message_id !== null) {
    //         // `UserMessage`のIDがある場合、それを採用
    //         $latest_message_id = $current_message_id;
    //     } elseif ($current_message_image_id !== null) {
    //         // `UserMessageImage`のIDがある場合、それを採用
    //         $latest_message_id = $current_message_image_id;
    //     }

    //     return $latest_message_id;
    // }


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


    // public function selectUserMessageCount($user_id, $admin_id, $periods, $message_read){

    //     $query = UserMessage::where("user_id", $user_id)
    //             ->where("admin_id", $admin_id);

    //     if($message_read){
    //         $query->where('message_id', '>', $message_read->message_id);
    //     }

    //     if($periods){
    //         $query->where(function($q) use ($periods){
    //             foreach($periods as $period){
    //                 $q->whereNotBetween("created_at", [
    //                     $period["start"],
    //                     $period["end"]
    //                 ]);
    //             };
    //         });
    //     }
    //     return $query->count();
    // }

    // public function selectUserMessageImageCount($user_id, $admin_id, $periods, $message_read){
    //     $query = UserMessageImage::where("user_id", $user_id)
    //             ->where("admin_id", $admin_id);

    //     if($message_read){
    //         $query->where('message_id', '>', $message_read->message_id);
    //     }

    //     if($periods){
    //         $query->where(function($q) use ($periods){
    //             foreach($periods as $period){
    //                 $q->whereNotBetween("created_at", [
    //                     $period["start"],
    //                     $period["end"]
    //                 ]);
    //             };
    //         });
    //     }
    //     return $query->count();
    // }
}






