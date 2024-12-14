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
    public function store(BroadcastMessageRequest $request){
        try{

            DB::beginTransaction();
            $validated = $request->validated();
            $savingData = [];

            // 一斉送信グループに保存
            $broadcastMessageGroup = BroadcastMessagesGroup::create();
            $broadcastMessage = "";
            foreach ($request->input('content') as $key => $contentItem){
                if($contentItem["type"] == "broadcast_text"){

                    $savingData = [
                        "admin_id" => $validated["admin_id"],
                        "broadcast_message_group_id" => $broadcastMessageGroup->id,
                        "resource" => $contentItem["data"],
                        "resource_type" => $contentItem["type"],
                        "message_order" => $key
                    ];

                    $broadcastMessage =BroadcastMessage::create($savingData);
                    
                }else{

                    $imageService   = new ImageService();
                    $savingData = [
                        "admin_id" => $validated["admin_id"],
                        "broadcast_message_group_id" => $broadcastMessageGroup->id,
                        "resource" =>  $imageService ->saveBase64Image($contentItem["data"]),
                        "resource_type" => $contentItem["type"],
                        "message_order" => $key
                    ];

                    $broadcastMessage = BroadcastMessage::create($savingData);
                }
            };

            // 最新メッセージ管理テーブルの更新
            MessageSummaryService::updateLatestMessage($validated["admin_id"], $broadcastMessage->created_at, $broadcastMessage->resource_type);
            DB::commit();
            $created_at = $broadcastMessage->created_at->format('H:i');
            return response()->json(["created_at"=> $created_at]);

        }catch(\Exception $e) {
            Log::debug($e);
            DB::rollBack();
        }
        
    }
}
