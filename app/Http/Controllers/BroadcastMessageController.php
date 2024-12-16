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

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $key => $image) {
                    $imageService = new ImageService();
            


                    $savingData = [
                        "admin_id" => $admin_id,
                        "broadcast_message_group_id" => $broadcastMessageGroup->id,
                        "resource" => $imageService->saveImage($image)["fileName"], // 画像を保存
                        "resource_type" => "broadcast_img",          // リソースタイプを指定
                        "message_order" => $key,                       // 順序を設定
                    ];
                    $broadcastMessage = BroadcastMessage::create($savingData);
                    $responseData[] = ["resource" => $imageService->saveImage($image)["imageUrl"], "type" => "broadcast_img", "order" => $broadcastMessage->message_order];
                }
            }
            
            if (isset($validated['messages'])) {
                foreach ($validated['messages'] as $order => $contentItem) {
                    $savingData = [
                        "admin_id" => $admin_id,
                        "broadcast_message_group_id" => $broadcastMessageGroup->id,
                        "resource" => $contentItem,            // メッセージ内容
                        "resource_type" => "broadcast_text",   // メッセージタイプ
                        "message_order" => $order             // 順序を設定
                    ];
                    $broadcastMessage = BroadcastMessage::create($savingData);
                    $responseData[] = ["resource" => $contentItem, "type" => "broadcast_text", "order" => $broadcastMessage->message_order];

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
