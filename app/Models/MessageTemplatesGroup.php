<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageTemplatesGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        "admin_id"
    ];

    public function messageTemplates(){
        return $this->hasMany(MessageTemplate::class, "group_id");
    }
}
