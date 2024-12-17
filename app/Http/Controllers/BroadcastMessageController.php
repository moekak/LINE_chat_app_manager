<?php

namespace App\Http\Controllers;

use App\Http\Requests\BroadcastMessageRequest;
use App\Models\BroadcastMessage;
use App\Models\BroadcastMessagesGroup;
use App\Models\MessageSummary;
use App\Services\ImageService;
use App\Services\MessageCountService;
use App\Services\MessageSummaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BroadcastMessageController extends Controller
{
    public function store(Request $request, $admin_id){
        try{

            DB::beginTransaction();
            $validated = $request->input();
            $savingData = [];
            $responseData = [];


            // 一斉送信グループに保存
            $broadcastMessageGroup = BroadcastMessagesGroup::create();
            $broadcastMessage = "";

            $allContent = [];

            // 画像とメッセージを1つの配列にまとめる
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $key => $image) {
                    $allContent[] = [
                        'type' => 'image',
                        'content' => $image,
                        'order' => $key
                    ];
                }
            }

            if (isset($validated['messages'])) {
                foreach ($validated['messages'] as $order => $contentItem) {
                    $allContent[] = [
                        'type' => 'text',
                        'content' => $contentItem,
                        'order' => $order
                    ];
                }
            }

            // orderで並び替え
            usort($allContent, function($a, $b) {
                return $a['order'] - $b['order'];
            });

            $responseData = [];

            // 順番に処理
            foreach ($allContent as $item) {
                if ($item['type'] === 'image') {
                    $imageService = new ImageService();
                    $fileName = $imageService->saveImage($item['content']);
                    
                    $savingData = [
                        "admin_id" => $admin_id,
                        "broadcast_message_group_id" => $broadcastMessageGroup->id,
                        "resource" => $fileName,
                        "resource_type" => "broadcast_img",
                        "message_order" => $item['order'],
                    ];
                    $broadcastMessage = BroadcastMessage::create($savingData);
                    $responseData[] = [
                        "resource" => $fileName, 
                        "type" => "broadcast_img", 
                        "order" => $broadcastMessage->message_order
                    ];
                } else {
                    $savingData = [
                        "admin_id" => $admin_id,
                        "broadcast_message_group_id" => $broadcastMessageGroup->id,
                        "resource" => $item['content'],
                        "resource_type" => "broadcast_text",
                        "message_order" => $item['order']
                    ];
                    $broadcastMessage = BroadcastMessage::create($savingData);
                    $responseData[] = [
                        "resource" => $item['content'], 
                        "type" => "broadcast_text", 
                        "order" => $broadcastMessage->message_order
                    ];
                }
            }

            // 最新メッセージ管理テーブルの更新
            MessageSummaryService::updateLatestMessage($admin_id, $broadcastMessage->created_at, $broadcastMessage->resource_type);
            DB::commit();
            Log::debug($responseData);
            $created_at = $broadcastMessage->created_at->format('H:i');
            return response()->json(["created_at"=> $created_at, "data" => $responseData]);

        }catch(\Exception $e) {
            Log::debug($e);
            DB::rollBack();
        }
        
    }
}
