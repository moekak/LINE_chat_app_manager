<?php

namespace App\Http\Controllers;

use App\Models\GreetingImagesCropArea;
use App\Models\GreetingMessage;
use App\Models\GreetingMessagesGroup;
use App\Services\ImageService;
use Illuminate\Container\Attributes\Log as AttributesLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GreetingMessageController extends Controller
{
    public function store(Request $request, $admin_id){
        // インスタンスを作成
        try{

            Log::debug("greeting");

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
}
