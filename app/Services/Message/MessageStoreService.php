<?php

namespace App\Services\Message;

use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

abstract class MessageStoreService{
      protected $userId;
      protected $request;
      protected $messageGroupModel;
      protected $messageModel;
      protected $cropAreaModel;
      protected $resourceImageType;
      protected $resourceTextType;
      protected $userIdField;


      public function __construct($userId, Request $request)
      {
            $this->request = $request;
            $this->userId = $userId;
      }

      public function store(){
            try{
                  DB::beginTransaction();

                  // メッセージグループ作成
                  $messageGroup = $this->createMessageGroup();
                   // コンテンツを処理して保存
                  $responseData = $this->processAndSaveContent($messageGroup->id);

                  DB::commit();
                  
                  return $this->prepareResponse($responseData, $this->userId);
            }catch(\Exception $e){
                  Log::debug($e);
                  DB::rollBack();
                  return response()->json(['error' => $e->getMessage()], 500);
            }
      }

      // メッセージグループをDBに保存する
      protected function createMessageGroup(){
            $groupModelClass = $this->messageGroupModel;
            return $groupModelClass::create();
      }

      // 取得したデータをデータベース保存しやすい用にデータ生成型をおこなう処理
      protected function prepareContentArray(){
            $validated = $this->request->all();
            $allContent = [];

            // 画像処理
            if ($this->request->hasFile('images')) {
                  foreach ($this->request->file('images') as $key => $image) {
                        $meta = $this->request->input("images.{$key}.meta");
                        $metaDecoded = $meta ? json_decode($meta, true) : null;

                        $allContent[] = [
                              'type' => 'image',
                              'content' => $image,
                              'order' => $key,
                              "cropArea" => $metaDecoded["cropArea"] ?? [],
                              "url" => $metaDecoded["url"] ?? "",
                              "isUpdateImage" => false
                        ];
                  }
            }else if($this->request->input("images")){
                  foreach ($this->request->input('images') as $key => $image){
                        $allContent[] = [
                              'type' => 'image',
                              'content' => $image,
                              'order' => $key,
                              "cropArea" => $metaDecoded["cropArea"] ?? [],
                              "url" => $metaDecoded["url"] ?? "",
                              "isUpdateImage" => true
                        ];
                  }
                  
            }

            // テキスト処理
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

            return $allContent;
      }

      protected function processAndSaveContent($groupId){
            $allContent = $this->prepareContentArray();
            $responseData = [];
            $lastMessageData = [];

            foreach($allContent as $index => $item){
                  if ($item['type'] === 'image') {
                        $messageData = $this->saveImageContent($item, $groupId);
                  } else {
                        $messageData = $this->saveTextContent($item, $groupId);
                  }

                  $responseData[$index] = $messageData;
            
                  // 一番最初のメッセージを記録
                  if ($index === 0) {
                        $lastMessageData["message_id"] = $messageData["id"];
                        $lastMessageData["message_type"] = $messageData["type"];
                  }
            }


                  
            return [
                  'responseData' => $responseData,
                  'lastMessageData' => $lastMessageData,
                  'lastMessage' => $messageData ?? null
            ];
      }


      protected function saveImageContent($item, $groupId){
            $imageService = new ImageService();
            Log::debug($item["content"] );
            $fileName = $item["isUpdateImage"] ? $item["content"]["content"] :  $imageService->saveImage($item['content']);
            if($item["isUpdateImage"]){
                  $item["cropArea"] = $item["content"]["meta"];
            }

            $groupIdField = $this->getGroupIdFieldName();

            $savingData = [
                  $this->userIdField => $this->userId,
                  $groupIdField => $groupId,
                  "resource" => $fileName,
                  "resource_type" => $this->resourceImageType,
                  "message_order" => $item['order'],
            ];


            Log::debug($savingData);
            $messageModelClass = $this->messageModel;
            $message = $messageModelClass::create($savingData);

            $responseData = [
                  "id" => $message->id,
                  "resource" => $fileName,
                  "type" => $this->resourceImageType,
                  "order" => $message->message_order,
                  "cropArea" => [],
            ];

            if ($item["cropArea"]) {
                  $cropData = $this->saveCropData($item, $message->id);
                  $responseData["cropArea"] = $cropData;
            }
            
            return $responseData;
      }

      protected function saveCropData($item, $messageId){
            $cropArea = json_decode($item["cropArea"], true); // 第2引数をtrueにして連想配列としてデコード\
            $messageIdField = $this->getMessageIdFieldName();
            $cropData = [
                  $messageIdField => $messageId,
                  "url" => isset($item["url"]) && $item["url"] !== "" ? $item["url"] : $cropArea["url"],
                  "x_percent" => $cropArea->xPercent ?? $cropArea["x_percent"] ?? $cropArea["xPercent"],
                  "y_percent" => $cropArea->yPercent ?? $cropArea["y_percent"] ?? $cropArea["yPercent"],
                  "width_percent" => $cropArea->widthPercent ?? $cropArea["width_percent"] ?? $cropArea["widthPercent"],
                  "height_percent" => $cropArea->heightPercent ?? $cropArea["height_percent"] ?? $cropArea["heightPercent"],
            ];
            $cropModelClass = $this->cropAreaModel;
            $cropModelClass::create($cropData);
            
            return [
                  "x_percent" => $cropArea->xPercent ?? $cropArea["x_percent"] ?? $cropArea["xPercent"],
                  "y_percent" => $cropArea->yPercent ?? $cropArea["y_percent"] ?? $cropArea["yPercent"],
                  "width_percent" => $cropArea->widthPercent ?? $cropArea["width_percent"] ?? $cropArea["widthPercent"],
                  "height_percent" => $cropArea->heightPercent ?? $cropArea["height_percent"] ?? $cropArea["heightPercent"],
                  "url" => isset($item["url"]) && $item["url"] !== ""? $item["url"] : $cropArea["url"]
            ];
      }

      protected function saveTextContent($item, $groupId)
      {
            $groupIdField = $this->getGroupIdFieldName();
            
            $savingData = [
                  $this->userIdField => $this->userId,
                  $groupIdField => $groupId,
                  "resource" => $item['content'],
                  "resource_type" => $this->resourceTextType,
                  "message_order" => $item['order']
            ];


            $messageModelClass = $this->messageModel;
            $message = $messageModelClass::create($savingData);
            
            return [
                  "id" => $message->id,
                  "resource" => $item['content'],
                  "type" => $this->resourceTextType,
                  "order" => $message->message_order,
                  "cropArea" => [],
            ];
      }



      // サブクラスでオーバーライド可能な追加処理
      protected function performAdditionalOperations($data, $userId, $created_at){}
      

      // サブクラスで実装する必要があるメソッド
      abstract protected function getGroupIdFieldName();
      abstract protected function getMessageIdFieldName();
      abstract protected function prepareResponse($data, $userId);
}

