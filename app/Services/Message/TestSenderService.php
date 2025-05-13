<?php

namespace App\Services\Message;

use App\Models\LineTestCropArea;
use App\Models\LineTestMessage;
use App\Models\LineTestMessagesGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestSenderService extends MessageStoreService{
      public function __construct(Request $request)
      {

            $ids = $request->input("userIds");
            parent::__construct($ids, $request);
            $this->messageGroupModel = LineTestMessagesGroup::class;
            $this->messageModel = LineTestMessage::class;
            $this->cropAreaModel = LineTestCropArea::class;
            $this->resourceImageType = "test_img";
            $this->resourceTextType = "test_txt";
            $this->userIdField = "user_id";
      }

      protected function getGroupIdFieldName()
      {
            return "test_message_group_id";
      }

      protected function getMessageIdFieldName()
      {
            return "test_image_id";
      }


      protected function prepareResponse($data, $userIds)
      {

            return response()->json([
                  "created_at" => now()->format('H:i'),
                  "data" => $data['responseData'],
                  "userIds" => $userIds
            ]);
      }

      
}