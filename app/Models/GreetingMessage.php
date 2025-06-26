<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    // public static  function getLatestGreetingMessage($admin_id, $latestMessage){
    //     return GreetingMessage::withTables()
    //         ->where('greeting_message_group_id', $latestMessage->greeting_message_group_id)
    //         ->where(function($query) use($admin_id) {
    //             $query->whereHas('greetingMessageGroup.greetingMessagesLinks', function($q) use($admin_id){
    //                 $q->where("admin_id", $admin_id);
    //             })
    //             ->orWhere("admin_id", $admin_id);
    //         })
    //         ->orderBy('greeting_messages.message_order', 'asc')
    //         ->get();
    // }



        public static function getLatestGreetingMessage($admin_id){
            return GreetingMessage::withTables()
                ->where('greeting_message_group_id', function($query) use($admin_id) {
                    $query->select('greeting_group_id')
                        ->from('greeting_messages_links')
                        ->where('admin_id', $admin_id)
                        ->orderBy('id', 'desc')
                        ->limit(1);
                })
                ->where(function($query) use($admin_id) {
                    $query->whereHas('greetingMessageGroup.greetingMessagesLinks', function($q) use($admin_id){
                        $q->where("admin_id", $admin_id);
                    })
                    ->orWhere("admin_id", $admin_id);
                })
                ->orderBy('message_order', 'asc')
                ->get();
        }


    

}
