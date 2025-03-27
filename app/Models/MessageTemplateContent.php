<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageTemplateContent extends Model
{
    use HasFactory;

    protected $fillable = [
        "template_id",
        "content_type",
        "content_text",
        "image_path",
        "display_order"
    ];
}
