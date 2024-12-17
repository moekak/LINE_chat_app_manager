<?php

namespace App\Http\Controllers;

use App\Http\Requests\GreetingMessageRequest;
use App\Models\GreetingMessage;
use App\Models\GreetingMessagesGroup;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GreetingMessageController extends Controller
{
    public function store(Request $request, $admin_id){
        // インスタンスを作成
        try{
            $validated = $request->input();
            $savingData = [];
            $responseData = [];

            // 一斉送信グループに保存
            $greetingMessageGroup = GreetingMessagesGroup::create();
            $greetingMessage = "";

            $allContent = [];

            // 画像とメッセージを1つの配列にまとめる
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $key => $image) {
                    Log::debug($image);
                    $allContent[] = [
                        'type' => 'image',
                        'content' => $image,
                        'order' => $key
                    ];
                }
            }

            if (isset($validated['messages'])) {
                foreach ($validated['messages'] as $order => $contentItem) {
                    Log::debug($contentItem);
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
                        "greeting_message_group_id" => $greetingMessageGroup->id,
                        "resource" => $fileName,
                        "resource_type" => "greeting_img",
                        "message_order" => $item['order'],
                    ];
                    $greetingMessage = GreetingMessage::create($savingData);
                    $responseData[] = [
                        "resource" => $fileName, 
                        "type" => "greeting_img", 
                        "order" => $greetingMessage->message_order
                    ];
                } else {
                    $savingData = [
                        "admin_id" => $admin_id,
                        "greeting_message_group_id" => $greetingMessageGroup->id,
                        "resource" => $item['content'],
                        "resource_type" => "greeting_text",
                        "message_order" => $item['order']
                    ];
                    $greetingMessage = GreetingMessage::create($savingData);
                    $responseData[] = [
                        "resource" => $item['content'], 
                        "type" => "greeting_text", 
                        "order" => $greetingMessage->message_order
                    ];
                }
            }

            return response()->json(['status' => 'success'], 201);

            // // 一斉送信グループに保存
            // $greetingMessageGroup = GreetingMessagesGroup::create();
    
    
    
            // foreach ($request->input('content') as $key => $contentItem){
            //     if($contentItem["type"] == "greeting_text"){
    
            //         $savingData = [
            //             "admin_id" => $validated["admin_id"],
            //             "greeting_message_group_id" => $greetingMessageGroup->id,
            //             "resource" => $contentItem["data"],
            //             "resource_type" => $contentItem["type"],
            //             "message_order" => $key
            //         ];
    
            //         GreetingMessage::create($savingData);
                    
            //     }else{
    
            //         $imageService   = new ImageService();
            //         $savingData = [
            //             "admin_id" => $validated["admin_id"],
            //             "greeting_message_group_id" => $greetingMessageGroup->id,
            //             "resource" =>  $imageService ->saveBase64Image($contentItem["data"]),
            //             "resource_type" => $contentItem["type"],
            //             "message_order" => $key
            //         ];
    
            //         GreetingMessage::create($savingData);
            //     }
            // };
    
            // return response()->json(['status' => 'success'], 201);
        }catch (\Exception $e) {
            Log::debug($e);
        }
        

    }
}
