<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageTemplatesCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        "admin_id",
        "category_name"
    ];
}
