<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedirectToken extends Model
{
    use HasFactory;
    
    protected $fillable = [
        "token",
        "used",
        "expires_at"
    ];
}
