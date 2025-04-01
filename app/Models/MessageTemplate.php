<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        "category_id",
        "admin_id",
        "group_id",
        "template_name"
    ];


    public function contents(){
        return $this->hasMany(MessageTemplateContent::class, "template_id");
    }

    public function messageTemplatesCategory(){
        return $this->belongsTo(MessageTemplatesCategory::class, "category_id", "id");
    }

    public function messageTemplatesGroup(){
        return $this->belongsTo(MessageTemplatesGroup::class, "group_id");
    }



}
