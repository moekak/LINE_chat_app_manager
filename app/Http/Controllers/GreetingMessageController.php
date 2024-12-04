<?php

namespace App\Http\Controllers;

use App\Http\Requests\GreetingMessageRequest;
use App\Models\GreetingMessage;
use App\Models\GreetingMessagesGroup;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GreetingMessageController extends Controller
{
    public function store(GreetingMessageRequest $request){
        // インスタンスを作成
        try{
            $validated = $request->validated();
            $savingData = [];
    
            // 一斉送信グループに保存
            $greetingMessageGroup = GreetingMessagesGroup::create();
    
    
    
            foreach ($request->input('content') as $key => $contentItem){
                if($contentItem["type"] == "greeting_text"){
    
                    $savingData = [
                        "admin_id" => $validated["admin_id"],
                        "greeting_message_group_id" => $greetingMessageGroup->id,
                        "resource" => $contentItem["data"],
                        "resource_type" => $contentItem["type"],
                        "message_order" => $key
                    ];
    
                    GreetingMessage::create($savingData);
                    
                }else{
    
                    $imageService   = new ImageService();
                    $savingData = [
                        "admin_id" => $validated["admin_id"],
                        "greeting_message_group_id" => $greetingMessageGroup->id,
                        "resource" =>  $imageService ->saveBase64Image($contentItem["data"]),
                        "resource_type" => $contentItem["type"],
                        "message_order" => $key
                    ];
    
                    GreetingMessage::create($savingData);
                }
            };
    
            return response()->json(['status' => 'success'], 201);
        }catch (\Exception $e) {
            Log::debug($e);
        }
        

    }
}
