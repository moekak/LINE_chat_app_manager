<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagerChatAccess extends Model
{
    use HasFactory;

    protected $fillable = [
        "chat_identity_id",
        "assigned_admin_id"
    ];
}
