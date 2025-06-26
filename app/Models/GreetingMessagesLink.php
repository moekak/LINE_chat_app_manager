<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GreetingMessagesLink extends Model
{
    use HasFactory;

    protected $fillable = [
        "admin_id",
        "greeting_group_id"
    ];
}
