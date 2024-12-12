<?php
namespace App\Services;

use App\Models\BlockChatUser;
use Illuminate\Support\Carbon;

class MessageService{
      public function buildBlockConditions(array $blockPeriods, string $column): string{
            $conditions = [];

            foreach ($blockPeriods as $period) {
                  $start = $period['start']->format('Y-m-d H:i:s');
                  $end = $period['end']->format('Y-m-d H:i:s');
                  $conditions[] = "($column BETWEEN '$start' AND '$end')";
            }

            return implode(' OR ', $conditions);
      }

      public function hasUserBlockHistroy($user_id){
            // ブロックしていた期間を取得する
            $block_history = BlockChatUser::where("chat_user_id", $user_id)
                  ->select("created_at", "updated_at", "is_blocked")
                  ->get();

            if ($block_history->isEmpty()) {
                  return [];
            }

            $periods = [];

            foreach ($block_history as $history) {
                  if ($history->is_blocked == '1') {
                        $periods[] = [
                              "start" => $history->created_at,
                              "end" => Carbon::now()
                        ];
                  } else {
                        $periods[] = [
                              'start' => $history->created_at,
                              'end' => $history->updated_at
                        ];
                  }
            }
            return $periods;
      }
      public function buildBlockConditionsForAccount(array $blockPeriods, string $column): string {
            $conditions = [];
                  
                  foreach ($blockPeriods as $userId => $userPeriods) {
                  // userPeriodsは配列の配列なので、さらにループが必要
                  foreach ($userPeriods as $period) {
                        if (!isset($period['start']) || !isset($period['end'])) {
                              continue;
                        }
                        
                        // Carbonオブジェクトのままなので->format()を使用
                        $start = $period['start']->format('Y-m-d H:i:s');
                        $end = $period['end']->format('Y-m-d H:i:s');
                        $conditions[] = "($column BETWEEN '$start' AND '$end')";
                  }
            }
                  
            return empty($conditions) ? '1=0' : implode(' OR ', $conditions);
      }



}