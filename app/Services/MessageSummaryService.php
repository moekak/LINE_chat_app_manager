<?php

namespace App\Services;

use App\Models\ChatUser;
use App\Models\MessageSummary;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MessageSummaryService{

    public static function updateLatestMessage($adminId, $date, $type) {
        // 1. ブロックされていないユーザーIDを取得
        $notBlockedUserIds = ChatUser::where("account_id", $adminId)->whereNotExists(function($query) {
            $query->select(DB::raw(1))
                ->from('block_chat_users')
                ->whereColumn('chat_users.id', 'block_chat_users.chat_user_id')
                ->where('is_blocked', "1")
                ->orderBy('created_at', 'desc')
                ->limit(1);
        })
        ->pluck('id');

        // 2. 既存のメッセージサマリーを取得
        $existingRecords = MessageSummary::where('admin_id', $adminId)
            ->whereIn('user_id', $notBlockedUserIds)
            ->pluck('user_id');

        // 3. 更新データを準備
        $updateData = [
            "latest_all_message" => "一斉メッセージを送信しました",
            "latest_all_message_date" => $date,
            "latest_all_message_type" => $type
        ];


        try{
            // 4. 既存レコードを一括更新
            if ($existingRecords->isNotEmpty()) {
                MessageSummary::where('admin_id', $adminId)
                    ->whereIn('user_id', $existingRecords)
                    ->update($updateData);
            }

            // 5. 新規レコードを一括作成
            $newRecords = $notBlockedUserIds->diff($existingRecords);
            if ($newRecords->isNotEmpty()) {
                $createData = $newRecords->map(function($userId) use ($adminId, $updateData) {
                    return array_merge($updateData, [
                        'user_id' => $userId,
                        'admin_id' => $adminId,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                })->all();

                MessageSummary::insert($createData);
            }
        }catch(\Exception $e){
            Log::debug($e);
        }
    
    }
}