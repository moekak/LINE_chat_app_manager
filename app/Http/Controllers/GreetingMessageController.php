<?php

namespace App\Http\Controllers;

use App\Models\GreetingMessage;
use App\Services\Message\GreetingMessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GreetingMessageController extends Controller
{

    // 初回メッセージの保存処理
    public function store(Request $request, $admin_id){
            $greetingMessageService = new GreetingMessageService($admin_id, $request);
            return $greetingMessageService->store();
    }

    public function getGreetingMessages(string $id) : JsonResponse{
        // 最新のデータを取得
        $latestMessage = GreetingMessage::where('admin_id', $id)
        ->orderBy('created_at', 'desc')
        ->first();

        // 最新データが存在する場合
        if ($latestMessage) {
            // greeting_message_group_id に基づいてすべてのデータを取得
            $greetingMessages = GreetingMessage::where('admin_id', $id)
                ->where('greeting_message_group_id', $latestMessage->greeting_message_group_id)
                ->leftJoin('greeting_images_crop_areas', 'greeting_messages.id', '=', 'greeting_images_crop_areas.greeting_message_id')
                ->select('greeting_messages.*', 'greeting_images_crop_areas.url', 'greeting_images_crop_areas.x_percent', 'greeting_images_crop_areas.y_percent','greeting_images_crop_areas.width_percent', 'greeting_images_crop_areas.height_percent') // 必要なカラムを指定
                ->orderBy('greeting_messages.message_order', 'asc') // orderカラムでソート
                ->get();
        } else {
            // 最新データが存在しない場合、空のコレクションを返す
            $greetingMessages = collect();
        }
        return response()->json($greetingMessages);
    }
}
