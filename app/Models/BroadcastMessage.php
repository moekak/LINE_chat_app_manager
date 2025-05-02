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
        return $query->where("resource", 'LIKE', "%{$search}%")->where("resource_type", "broadcast_text");
    }

    public function scopeOfWithGroup($query){
        return $query->with("broadcastMessageGroup");
    }

    public function scopeWithStartDate($query, $start_date){
        $query->whereDate("created_at", ">=", $start_date);
    }
    public function scopeWithEndDate($query, $end_date){
        $query->whereDate("created_at", "<=", $end_date);
    }
    public static function getBroadcastMessage($admin_id)
    {
        // グループIDをまず取得
        $groupIds = BroadcastMessage::OfAdmin($admin_id)
            ->select('broadcast_message_group_id')
            ->groupBy('broadcast_message_group_id')
            ->paginate(7);
        
        // 取得したグループIDに基づいてメッセージを取得
        $groupIdsArray = collect($groupIds->items())->pluck('broadcast_message_group_id')->toArray();
        
        $messages = BroadcastMessage::OfAdmin($admin_id)
            ->OfWithGroup()
            ->whereIn('broadcast_message_group_id', $groupIdsArray)
            ->get()
            ->groupBy('broadcast_message_group_id');
        
        return [
            'messages' => $messages,
            'paginator' => $groupIds
        ];
    }

    public static function searchByMessage($search, $admin_id)
    {
        // グループIDをまず取得
        $groupIds = BroadcastMessage::OfAdmin($admin_id)
            ->OfSearch($search)
            ->select('broadcast_message_group_id')
            ->groupBy('broadcast_message_group_id')
            ->paginate(7);
        
        // 取得したグループIDに基づいてメッセージを取得
        $groupIdsArray = collect($groupIds->items())->pluck('broadcast_message_group_id')->toArray();
        $messages = BroadcastMessage::OfAdmin($admin_id)
            ->OfWithGroup()
            ->whereIn('broadcast_message_group_id', $groupIdsArray)
            ->get()
            ->groupBy('broadcast_message_group_id');

        return [
            'messages' => $messages,
            'paginator' => $groupIds
        ];
    }
    
    public static function searchByDate($start_date, $end_date, $admin_id)
    {
        $groupIds = BroadcastMessage::OfAdmin($admin_id)
            ->withStartDate($start_date)
            ->withEndDate($end_date)
            ->select('broadcast_message_group_id')
            ->groupBy('broadcast_message_group_id')
            ->paginate(7);
        
        // 取得したグループIDに基づいてメッセージを取得
        $groupIdsArray = collect($groupIds->items())->pluck('broadcast_message_group_id')->toArray();
        
        $messages = BroadcastMessage::OfAdmin($admin_id)
            ->OfWithGroup()
            ->withStartDate($start_date)
            ->withEndDate($end_date)
            ->whereIn('broadcast_message_group_id', $groupIdsArray)
            ->get()
            ->groupBy('broadcast_message_group_id');
        
        return [
            'messages' => $messages,
            'paginator' => $groupIds
        ];
    }
}
