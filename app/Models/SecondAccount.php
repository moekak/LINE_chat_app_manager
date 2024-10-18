<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecondAccount extends Model
{
    use HasFactory;


    protected $fillable = [
        "current_account_id",
        "second_account_id"
    ];
}
