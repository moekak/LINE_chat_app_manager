<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class GreetingMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        "admin_id",
        "greeting_message_group_id",
        "resource",
        "resource_type",
        "message_order"
    ];

    public function greetingMessageGroup(){
        return $this->belongsTo(GreetingMessagesGroup::class, "greeting_message_group_id", "id");
    }

    public function greetingImagesCropAreas(){
        return $this->hasMany(GreetingImagesCropArea::class, "greeting_message_id", "id");
    }

    public function scopeWithTables($query){
        return $query->with(["greetingMessageGroup", "greetingMessageGroup.greetingMessagesLinks", "greetingImagesCropAreas:id,url,x_percent,y_percent,width_percent,height_percent"]);
    }

    public static  function getLatestGreetingMessage($admin_id){
        $latestGreetingMessageGroupId = static::getLatestGreetingMessageGroupId($admin_id);
        return GreetingMessage::withTables()
            ->where('greeting_message_group_id', $latestGreetingMessageGroupId)
            ->orderBy('greeting_messages.message_order', 'asc')
            ->get();
    }


    public static function getLatestGreetingMessageGroupId($id){

        $groupData = collect([
            GreetingMessagesLink::select("greeting_group_id as group_id", "created_at")
                ->where("admin_id", $id)
                ->orderBy("created_at", "desc")
                ->first(),
            
            GreetingMessage::select("greeting_message_group_id as group_id", "created_at")
                ->where("admin_id", $id)
                ->orderBy("created_at", "desc")
                ->first(),
        ])->filter(); 

        $latestGroup = $groupData->sortByDesc("created_at")->first();

        return $latestGroup?->group_id;

    }


}
