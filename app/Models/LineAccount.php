<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class LineAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        "account_name",
        "user_id",
        "account_id",
        "is_active",
        "account_status",
        "account_url"
    ];

    public function chatUser(){
        return $this->hasMany(ChatUser::class, "account_id");
    }


}
