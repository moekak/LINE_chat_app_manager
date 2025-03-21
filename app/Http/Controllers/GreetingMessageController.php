<?php

namespace App\Http\Controllers;

use App\Models\GreetingImagesCropArea;
use App\Models\GreetingMessage;
use App\Models\GreetingMessagesGroup;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GreetingMessageController extends Controller
{
    public function store(Request $request, $admin_id){
        // インスタンスを作成
        try{

            DB::beginTransaction();
            $validated = $request->all();
            $savingData = [];


            // 一斉送信グループに保存
            $greetingMessageGroup = GreetingMessagesGroup::create();
            $greetingMessage = "";

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

                    if($item["cropArea"]){
                        $cropArea = json_decode($item["cropArea"]);

                        $cropData = [
                            "greeting_message_id" => $greetingMessage->id,
                            "url" => $item["url"],
                            "x_percent" => $cropArea->xPercent,
                            "y_percent" => $cropArea->yPercent,
                            "width_percent" => $cropArea->widthPercent,
                            "height_percent" => $cropArea->heightPercent,
                        ];
    
                        GreetingImagesCropArea::create($cropData);
                    }

                
                } else {
                    $savingData = [
                        "admin_id" => $admin_id,
                        "greeting_message_group_id" => $greetingMessageGroup->id,
                        "resource" => $item['content'],
                        "resource_type" => "greeting_text",
                        "message_order" => $item['order']
                    ];
                    $greetingMessage = GreetingMessage::create($savingData);

                }
            }

            DB::commit();

            return response()->json(["success"=> 200]);

        }catch(\Exception $e) {
            Log::debug($e);
            DB::rollBack();
        }
        

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
