<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GreetingImagesCropArea extends Model
{
    use HasFactory;

    protected $fillable = [
        "greeting_message_id",
        "x_percent",
        "y_percent",
        "width_percent",
        "height_percent",
        "url"
    ];
}
