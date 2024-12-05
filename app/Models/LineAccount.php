<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class LineAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        "account_name",
        "user_id",
        "account_id",
        "channel_access_token",
        "channel_secret",
        "is_active",
        "user_picture",
        "account_status",
        "account_url"
    ];

    public function chatUser(){
        return $this->hasMany(ChatUser::class, "account_id");
    }

    // channel_access_tokenの暗号化
    public function setChannelAccessTokenAttribute($value){
        $this->attributes["channel_access_token"] = Crypt::encryptString($value);
    }

    // channel_secretの暗号化
    public function setChannelSecretAttribute($value)
    {
        $this->attributes['channel_secret'] = Crypt::encryptString($value);
    }

    public function userEntity(){
        return $this->hasOne(UserEntity::class, "related_id", "id");
    }



}
