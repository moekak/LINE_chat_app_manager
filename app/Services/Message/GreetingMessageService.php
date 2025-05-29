<?php

namespace App\Services\Message;

use App\Models\GreetingImagesCropArea;
use App\Models\GreetingMessage;
use App\Models\GreetingMessagesGroup;
use Illuminate\Http\Request;


class GreetingMessageService extends MessageStoreService{
      public function __construct($admin_id, Request $request)
      {
            parent::__construct($admin_id, $request);
            $this->messageGroupModel = GreetingMessagesGroup::class;
            $this->messageModel = GreetingMessage::class;
            $this->cropAreaModel = GreetingImagesCropArea::class;
            $this->resourceImageType = "greeting_img";
            $this->resourceTextType = "greeting_text";
            $this->userIdField = "admin_id";
      }

      protected function getGroupIdFieldName()
      {
            return "greeting_message_group_id";
      }

      protected function getMessageIdFieldName()
      {
            return "greeting_message_id";
      }


      protected function prepareResponse($data, $admin_id)
      {
            return response()->json(["success"=> 200]);
      }

      
}