<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageTemplateCropData extends Model
{
    use HasFactory;

    public function contents(){
        return $this->hasOne(MessageTemplateContent::class, "message_template_contents_id", "id");
    }
}
