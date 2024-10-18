<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatUser extends Model
{
    use HasFactory;

    protected $fillable = [
        "line_name"
    ];


    public function lineAccount(){
        return $this->belongsTo(LineAccount::class, "account_id");
    }
}
