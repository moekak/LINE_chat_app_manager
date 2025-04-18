<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BroadcastMessagesGroup extends Model
{
    use HasFactory;


    public function broadcastMessages(){
        return $this->hasMany(BroadcastMessage::class, "broadcast_message_group_id", "id");
    }
}
