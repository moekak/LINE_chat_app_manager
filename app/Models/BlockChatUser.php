<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockChatUser extends Model
{
    use HasFactory;

    protected $fillable = [
        "chat_user_id",
        "is_blocked"
    ];
}
