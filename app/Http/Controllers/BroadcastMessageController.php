<?php

namespace App\Http\Controllers;

use App\Http\Requests\BroadcastMessageRequest;
use App\Models\BroadcastMessage;
use App\Models\BroadcastMessagesGroup;
use App\Services\ImageService;
use App\Services\MessageCountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BroadcastMessageController extends Controller
{
    public function store(BroadcastMessageRequest $request){
         // インスタンスを作成yarn mix
        $validated = $request->validated();
        $savingData = [];

        // 一斉送信グループに保存
        $broadcastMessageGroup = BroadcastMessagesGroup::create();



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

        $created_at = $broadcastMessage->created_at->format('H:i');
        return response()->json(["created_at"=> $created_at]);

    }
}
