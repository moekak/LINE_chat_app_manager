<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageTemplatesCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        "admin_id",
        "category_name"
    ];

    public function messageTemplates(){
        return $this->hasMany(MessageTemplate::class, "category_id", "id");
    }

    public function scopeWithMessageTemplate($query){
        return $query->with(["messageTemplates", "messageTemplates.messageTemplatesLinks"]);
    }

    static public function getTemplateCategories($admin_id){
        return self::withMessageTemplate()
        ->where(function ($query) use ($admin_id){
            $query->whereHas("messageTemplates.messageTemplatesLinks", function($q) use ($admin_id){
                $q->where("admin_id", $admin_id);
            });
        })
        ->orWhere("admin_id", $admin_id)
        ->select("id", "category_name")
        ->get();
    }
}
