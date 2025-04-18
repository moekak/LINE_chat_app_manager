<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BroadcastMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        "admin_id",
        "broadcast_message_group_id",
        "resource",
        "resource_type",
        "message_order"
    ];


    public function broadcastMessageGroup(){
        return $this->belongsTo(BroadcastMessagesGroup::class, "broadcast_message_group_id", "id");
    }

    public function broadcastImagesCropAreas(){
        return $this->hasMany(BroadcastImagesCropArea::class, "broadcast_message_id", "id");
    }
    public function scopeOfAdmin($query, $admin_id){
        return $query->where("admin_id", $admin_id);
    }

    public function scopeOfSearch($query, $search){
        return $query->where("resource", 'LIKE', "%{$search}%");
    }

    public function scopeOfWithGroup($query){
        return $query->with("broadcastMessageGroup");
    }

    public static function getBroadcastMessage($admin_id){
        return BroadcastMessage::OfAdmin($admin_id)->OfWithGroup()->get()->groupBy("broadcast_message_group_id");
    }

    public static function searchByMessage($search, $admin_id){
        return BroadcastMessage::OfAdmin($admin_id)->OfSearch($search)->get();
    }
}
