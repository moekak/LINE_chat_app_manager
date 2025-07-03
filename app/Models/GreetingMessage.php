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
        Log::debug($latestGreetingMessageGroupId);
        return GreetingMessage::withTables()
            ->where('greeting_message_group_id', $latestGreetingMessageGroupId)
            ->orderBy('greeting_messages.message_order', 'asc')
            ->get();
    }


    public static function getLatestGreetingMessageGroupId($id){
        $latestGreetingMessageGroupId = GreetingMessage::leftJoin("greeting_messages_links", "greeting_messages_links.admin_id", "=", "greeting_messages.admin_id")
            ->where("greeting_messages.admin_id", $id)
            ->orderBy('greeting_messages.created_at', 'desc')
            ->value("greeting_message_group_id");

        if(!$latestGreetingMessageGroupId){
            $latestGreetingMessageGroupId = GreetingMessagesLink::getLatestGreetingGroupId($id);
        }
        
        return $latestGreetingMessageGroupId;
    }



        // public static function getLatestGreetingMessage($admin_id){
        //     return GreetingMessage::withTables()
        //         ->where('greeting_message_group_id', function($query) use($admin_id) {
        //             $query->select('greeting_group_id')
        //                 ->from('greeting_messages_links')
        //                 ->where('admin_id', $admin_id)
        //                 ->orderBy('id', 'desc')
        //                 ->limit(1);
        //         })
        //         ->where(function($query) use($admin_id) {
        //             $query->whereHas('greetingMessageGroup.greetingMessagesLinks', function($q) use($admin_id){
        //                 $q->where("admin_id", $admin_id);
        //             })
        //             ->orWhere("admin_id", $admin_id);
        //         })
        //         ->orderBy('message_order', 'asc')
        //         ->get();
        // }


    

}
