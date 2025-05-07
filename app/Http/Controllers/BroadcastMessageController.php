<?php

namespace App\Http\Controllers;

use App\Models\AdminMessageRead;
use App\Models\BroadcastImagesCropArea;
use App\Models\BroadcastMessage;
use App\Models\BroadcastMessagesGroup;
use App\Models\ChatUser;
use App\Services\ImageService;
use App\Services\MessageSummaryService;
use App\Services\UnreadMessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class BroadcastMessageController extends Controller
{

    public function index(string $id){
        $broadcastMessages = BroadcastMessage::getBroadcastMessage($id);
        return view('admin.broadcastMessage.broadcastMessageList', ["broadcastMessages" => $broadcastMessages["messages"], "paginator" => $broadcastMessages["paginator"], "adminId" => $id]);
    }

    public function store(Request $request, $admin_id){
        try{

            DB::beginTransaction();
            $validated = $request->all();
            $savingData = [];
            $responseData = [];

            // 一斉送信グループに保存
            $broadcastMessageGroup = BroadcastMessagesGroup::create();
            $broadcastMessage = "";

            $allContent = [];
            $cropData = [];

            // 画像とメッセージを1つの配列にまとめる
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $key => $image) {

                    $meta = $request->input("images.{$key}.meta");
                    $metaDecoded = $meta ? json_decode($meta, true) : null;

                    $allContent[] = [
                        'type' => 'image',
                        'content' => $image,
                        'order' => $key,
                        "cropArea" => $metaDecoded["cropArea"] ?? [],
                        "url" => $metaDecoded["url"] ?? ""
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


            $lastMessageData = [];
            // 順番に処理
            foreach ($allContent as $index =>$item) {

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

                    if($item["cropArea"]){
                        $cropArea = json_decode($item["cropArea"]);

                        $cropData = [
                            "broadcast_message_id" => $broadcastMessage->id,
                            "url" => $item["url"],
                            "x_percent" => $cropArea->xPercent,
                            "y_percent" => $cropArea->yPercent,
                            "width_percent" => $cropArea->widthPercent,
                            "height_percent" => $cropArea->heightPercent,
                        ];
    
                        BroadcastImagesCropArea::create($cropData);

                        $responseData[$index] = [
                            "id" => $broadcastMessage->id,
                            "resource" => $fileName, 
                            "type" => "broadcast_img", 
                            "order" => $broadcastMessage->message_order,
                            "cropArea" => ["x_percent" => $cropArea->xPercent, "y_percent" => $cropArea->yPercent, "width_percent" => $cropArea->widthPercent, "height_percent" => $cropArea->heightPercent, "url" => $item["url"]],
                        ];

                    }else{
                        $responseData[$index] = [
                            "id" => $broadcastMessage->id,
                            "resource" => $fileName, 
                            "type" => "broadcast_img", 
                            "order" => $broadcastMessage->message_order,
                            "cropArea" => [],
                        ];
                    }

                
                } else {
                    $savingData = [
                        "admin_id" => $admin_id,
                        "broadcast_message_group_id" => $broadcastMessageGroup->id,
                        "resource" => $item['content'],
                        "resource_type" => "broadcast_text",
                        "message_order" => $item['order']
                    ];
                    $broadcastMessage = BroadcastMessage::create($savingData);
                    $responseData[$index] = [
                        "id" => $broadcastMessage->id,
                        "resource" => $item['content'], 
                        "type" => "broadcast_text", 
                        "order" => $broadcastMessage->message_order,
                        "cropArea" => [],
                    ];
                }

                // 一斉メッセージの一番最初のメッセージを取得する
                if($index === 0){
                    $lastMessageData["message_id"] = $broadcastMessage->id;
                    $lastMessageData["message_type"] = $broadcastMessage->resource_type;
                }
            }

            // 既読未読管理テーブル(admin_message_reads)を更新する(既存の未読カウント＋1)
            $unreadCount = count($allContent);
            UnreadMessageService::unreadCountDBOperation($admin_id, $lastMessageData, $unreadCount);


            // 最新メッセージ管理テーブルの更新
            MessageSummaryService::updateLatestMessage($admin_id, $broadcastMessage->created_at, $broadcastMessage->resource_type);
            DB::commit();
            
            $created_at = $broadcastMessage->created_at->format('H:i');
            return response()->json(["created_at"=> $created_at, "data" => $responseData]);

        }catch(\Exception $e) {
            Log::debug($e);
            DB::rollBack();
        }
        
    }


    public function searchByMessage($admin_id, Request $request){
        $search = $request->query('search');
        $broadcastMessages = BroadcastMessage::searchByMessage($search, $admin_id);
        return view('admin.broadcastMessage.broadcastMessageSearchList', ["searchWord" => $search, "broadcastMessages" => $broadcastMessages["messages"], "paginator" => $broadcastMessages["paginator"], "adminId" => $admin_id]);
    }

    public function searchByDate($admin_id, Request $request){
        $start_date = $request->query('start_date');
        $end_date = $request->input("end_date");

        $broadcastMessages = BroadcastMessage::searchByDate($start_date, $end_date, $admin_id);
        return view('admin.broadcastMessage.broadcastMessageSearchList', ["startDate" => $start_date, "endDate" => $end_date,"broadcastMessages" => $broadcastMessages["messages"], "paginator" => $broadcastMessages["paginator"], "adminId" => $admin_id]);
    }

}
