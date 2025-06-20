<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageTemplatesLink extends Model
{
    use HasFactory;

    protected $fillable = [
        "template_id",
        "admin_id"
    ];
}
