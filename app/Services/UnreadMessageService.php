<?php

namespace App\Services;

use App\Models\AdminMessageRead;
use App\Models\ChatUser;
use App\Models\RedirectToken;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UnreadMessageService{
      static public function unreadCountDBOperation($admin_id, $lastMessageData, $unreadCount){
      // 既読未読管理のデータやり取り
      $userIds = ChatUser::where("account_id", $admin_id)->pluck("id")->toArray();
      // 既読未読管理テーブルにデータが存在するか確認し、あったら取得する
      $existingRecords = AdminMessageRead::whereIn("chat_user_id", $userIds)->where("admin_account_id", $admin_id)->get();

      // 既存レコードをチャットユーザーIDをキーにした連想配列に変換
      $existingRecordsMap = $existingRecords->keyBy('chat_user_id')->toArray();

      // 挿入と更新を分けて処理
      $updates = [];
      $inserts = [];

      foreach($userIds as $userId) {
            if(isset($existingRecordsMap[$userId])) {
                  $record = $existingRecordsMap[$userId];
                  $updates[] = [
                        "id" => $record['id'], // 主キーを含める
                        "chat_user_id" => $userId,
                        "unread_count" => $record["unread_count"] + $unreadCount,
                        "last_unread_message_id" => ($record["unread_count"] == 0) ? $lastMessageData["message_id"] : $record["last_unread_message_id"],
                        "last_message_type" => ($record["unread_count"] == 0) ? $lastMessageData["message_type"]  : $record["last_message_type"],
                        "updated_at" => now()
                  ];
            } else {
                  $inserts[] = [
                        'chat_user_id' => $userId,
                        'admin_account_id' => $admin_id,
                        'last_unread_message_id' => $lastMessageData["message_id"],
                        'last_message_type' => $lastMessageData["message_type"] ,
                        'unread_count' => $unreadCount,
                  ];
            }
            }

            // 一括挿入
            if (!empty($inserts)) {
                  AdminMessageRead::insert($inserts);
            }

            // 一括更新
            if (!empty($updates)) {
                  $cases = [];
                  $ids = [];
                  $lastMessageIdCases = [];
                  $messageTypeCases = [];
                  
                  foreach ($updates as $update) {
                        $id = $update['id'];
                        $ids[] = $id;
                        $cases[] = "WHEN {$id} THEN {$update['unread_count']}";
                        $lastMessageIdCases[] = "WHEN {$id} THEN {$update['last_unread_message_id']}";
                        $messageTypeCases[] = "WHEN {$id} THEN '{$update['last_message_type']}'";
                  }
                  
                  $idList = implode(',', $ids);
                  $countCase = implode(' ', $cases);
                  $messageIdCase = implode(' ', $lastMessageIdCases);
                  $typeCase = implode(' ', $messageTypeCases);
                  
                  DB::statement("UPDATE admin_message_reads SET 
                        unread_count = CASE id {$countCase} END,
                        last_unread_message_id = CASE id {$messageIdCase} END,
                        last_message_type = CASE id {$typeCase} END,
                        updated_at = NOW()
                        WHERE id IN ({$idList})");
            }
      }
}