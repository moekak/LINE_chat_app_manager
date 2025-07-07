<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class GreetingMessagesLink extends Model
{
    use HasFactory;

    protected $fillable = [
        "admin_id",
        "greeting_group_id"
    ];

    public function scopeByAccountId($query, $account_id){
        $query->where("admin_id", $account_id);
    }


    static public function createGreetingMessageLink($from_admin_id, $to_admin_id){
        $latestGreetingGroupId = GreetingMessage::getLatestGreetingMessageGroupId($from_admin_id);
        Log::debug($latestGreetingGroupId);
        if($latestGreetingGroupId){
            static::create(["admin_id"=> $to_admin_id, "greeting_group_id" => $latestGreetingGroupId]);
        }
        
    }

    static public function getLatestGreetingGroupId($account_id){
        return static::ByAccountId($account_id)
            ->orderBy('id', 'desc')
            ->limit(1)
            ->value("greeting_group_id"); // 初回メッセージの引継ぎ
    }

}
