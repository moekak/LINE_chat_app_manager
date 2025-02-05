<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineDisplayText extends Model
{
    use HasFactory;

    protected $fillable = [
        "admin_id",
        "is_show",
        "text"
    ];
}
