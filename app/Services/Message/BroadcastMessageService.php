<?php

namespace App\Services\Message;

use App\Models\BroadcastImagesCropArea;
use App\Models\BroadcastMessage;
use App\Models\BroadcastMessagesGroup;
use App\Services\MessageSummaryService;
use App\Services\UnreadMessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BroadcastMessageService extends MessageStoreService{
      public function __construct($admin_id, Request $request)
      {
            parent::__construct($admin_id, $request);
            $this->messageGroupModel = BroadcastMessagesGroup::class;
            $this->messageModel = BroadcastMessage::class;
            $this->cropAreaModel = BroadcastImagesCropArea::class;
            $this->resourceImageType = "broadcast_img";
            $this->resourceTextType = "broadcast_text";
            $this->userIdField = "admin_id";
      }

      protected function getGroupIdFieldName()
      {
            return "broadcast_message_group_id";
      }

      protected function getMessageIdFieldName()
      {
            return "broadcast_message_id";
      }

      protected function performAdditionalOperations($data, $admin_id, $created_at){

            // 既読未読管理テーブル(admin_message_reads)を更新する(既存の未読カウント＋1)
            $unreadCount = count($data["responseData"]);
            UnreadMessageService::unreadCountDBOperation($admin_id, $data["lastMessageData"], $unreadCount);

            // // 最新メッセージ管理テーブルの更新
            MessageSummaryService::updateLatestMessage($admin_id, $created_at, $data["responseData"][0]["type"]);
            DB::commit();
      }

      protected function prepareResponse($data, $admin_id)
      {
            $lastMessage = $data['lastMessage'] ?? null;
            $created_at = isset($lastMessage["created_at"]) 
                  ? $lastMessage["created_at"]->format('H:i') 
                  : now()->format('H:i');
                  
            //既読未読の管理
            $this->performAdditionalOperations($data, $admin_id, $created_at);
            return response()->json([
                  "created_at" => $created_at,
                  "data" => $data['responseData']
            ]);
      }


      protected function adjustFieldNames($data){
            
      }
      
}