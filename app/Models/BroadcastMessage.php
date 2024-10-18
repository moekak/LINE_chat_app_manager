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
}
