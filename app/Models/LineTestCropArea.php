<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineTestCropArea extends Model
{
    use HasFactory;

    protected $fillable = [
        "test_image_id",
        "url",
        "x_percent",
        "y_percent",
        "width_percent",
        "height_percent",
    ];
}
