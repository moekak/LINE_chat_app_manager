<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BroadcastImagesCropArea extends Model
{
    use HasFactory;
    
    protected $fillable = [
        "broadcast_message_id",
        "x_percent",
        "y_percent",
        "width_percent",
        "height_percent",
        "url"
    ];
}
