<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GreetingMessagesGroup extends Model
{
    use HasFactory;

    public function greetingMessagesLinks(){
        return $this->hasMany(GreetingMessagesLink::class, "greeting_group_id", "id");
    }

}
