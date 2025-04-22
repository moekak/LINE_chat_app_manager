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

    public function scopeWithDtartDate($query, $start_date){
        $query->whereDate("created_at", ">=", $start_date);
    }
    public function scopeWithEndDate($query, $end_date){
        $query->whereDate("created_at", "<=", $end_date);
    }

    public static function getBroadcastMessage($admin_id){
        $paginator = BroadcastMessage::OfAdmin($admin_id)->OfWithGroup()->paginate(7);
        $messages = collect($paginator->items())->groupBy("broadcast_message_group_id");
        
        return [
            'messages' => $messages,
            'paginator' => $paginator
        ];
        
    }

    public static function searchByMessage($search, $admin_id){
        $paginator = BroadcastMessage::OfAdmin($admin_id)->paginate(7);
        $messages = collect($paginator->items())->OfWithGroup()->OfSearch($search)->groupBy("broadcast_message_group_id");
        
        return [
            'messages' => $messages,
            'paginator' => $paginator
        ];
    }
    public static function searchByDate($start_date, $end_date,  $admin_id){
        $paginator = BroadcastMessage::OfAdmin($admin_id)->OfWithGroup()->WithDtartDate($start_date)->WithEndDate($end_date)->paginate(7);
        $messages = collect($paginator->items())->groupBy("broadcast_message_group_id");
        
        return [
            'messages' => $messages,
            'paginator' => $paginator
        ];
    }
}
