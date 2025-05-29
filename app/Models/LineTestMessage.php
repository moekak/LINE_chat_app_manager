<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineTestMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "test_message_group_id",
        "type",
        "resource",
        "resource_type",
        "message_order"
    ];
}
