<?php

namespace App\Http\Controllers;

use App\Models\GreetingMessage;
use App\Models\GreetingMessagesLink;
use App\Services\Message\GreetingMessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GreetingMessageController extends Controller
{

    // 初回メッセージの保存処理
    public function store(Request $request, $admin_id){
        $greetingMessageService = new GreetingMessageService($admin_id, $request);
        return $greetingMessageService->store();
    }

    // public function getGreetingMessages(string $id) : JsonResponse{
    //     // 最新のデータを取得
    //     $latestMessage = GreetingMessage::where('admin_id', $id)
    //         ->orderBy('created_at', 'desc')
    //         ->first();

    //     // 最新データが存在する場合
    //     if ($latestMessage) {
    //         $greetingMessages = GreetingMessage::getLatestGreetingMessage($id, $latestMessage);

    //         Log::debug($greetingMessages->toArray());
    //     } else {
    //         // 最新データが存在しない場合、空のコレクションを返す
    //         $greetingMessages = collect();
    //     }
    //     return response()->json($greetingMessages);
    // }

    public function getGreetingMessages(string $id) : JsonResponse{
        $greetingMessages = GreetingMessage::getLatestGreetingMessage($id);
        return response()->json($greetingMessages);
    }
}
