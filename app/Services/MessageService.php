<?php
namespace App\Services;

use App\Models\BlockChatUser;
use Illuminate\Support\Carbon;

class MessageService{
      public function hasUserBlockHistroy($user_id){
            // ブロックしていた期間を取得する(ブロック日とブロック解除日)
            $block_history = BlockChatUser::where("chat_user_id", $user_id)->select("created_at", "updated_at", "is_blocked")->get();
            if($block_history->isEmpty()){
                  return False;
            }

            $periods = [];

            foreach($block_history as $history){

                  if($history->is_blocked == '1'){
                        $periods[] = [
                              "start" => Carbon::parse($history->created_at),
                              "end" => Carbon::now()
                        ];
                  }else{
                        $periods[] = [
                              'start' => Carbon::parse($history->created_at),
                              'end' => Carbon::parse($history->updated_at)
                        ];  
                  }
            }
            return $periods;
      }
}