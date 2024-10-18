<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserEntity extends Model
{
    use HasFactory;
    protected $fillable = [
        "related_id",
        "entity_type"
    ];

    protected static function boot()
    {
        parent::boot();

        // レコード作成時にUUIDを生成
        static::creating(function ($model) {
            if (empty($model->entity_uuid)) {
                $model->entity_uuid = (string) Str::uuid(); // UUIDを生成してセット
            }
        });
    }
}
