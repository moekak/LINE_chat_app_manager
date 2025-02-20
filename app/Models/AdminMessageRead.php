<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminMessageRead extends Model
{
    use HasFactory;

    protected $fillable = [
        "chat_user_id",
        "admin_account_id",
        "unread_count",
        "last_unread_message_id",
        "last_message_type"
    ];
}
