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
}
