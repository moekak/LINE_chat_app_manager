<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageTemplateContent extends Model
{
    use HasFactory;

    protected $fillable = [
        "template_id",
        "content_type",
        "content_text",
        "image_path",
        "display_order"
    ];

    public function messageTemplate(){
        return $this->belongsTo(MessageTemplate::class, "template_id");
    }
    public function cropData(){
        return $this->hasOne(MessageTemplateCropData::class, 'message_template_contents_id', 'id');
    }


    public function scopeWithContentsForAdmin($query, $admin_id)
    {
        return $query->where('admin_id', $admin_id)
                    ->with(['messageTemplate','messageTemplate.messageTemplatesCategory', 'messageTemplate.messageTemplatesGroup', 'cropData']);
    }

    public static function getMessageTemplatesForAdmin($admin_id)
    {
        return MessageTemplateContent::WithContentsForAdmin($admin_id)
            ->get()
            ->groupBy("template_id")
            ->map(function ($group) {
                $firstItem = $group->first();
                
                return [
                    "category_id" => $firstItem->messageTemplate->messageTemplatesCategory->id,
                    "group_id" => $firstItem->messageTemplate->messageTemplatesGroup->id,
                    'template_id' => $firstItem->template_id,
                    'template_name' => $firstItem->messageTemplate->template_name,
                    'category_name' => $firstItem->messageTemplate->messageTemplatesCategory->category_name,
                    'admin_id' => $firstItem->admin_id,
                    'contents' => $group->map(function ($item) {
                        return [
                            "id" => $item->id,
                            'content_text' => $item->content_text,
                            'image_path' => $item->image_path,
                            'cropArea' => $item->cropData && $item->cropData->width_percent ? json_encode([
                                'height_percent' => $item->cropData->height_percent,
                                'width_percent' => $item->cropData->width_percent,
                                'x_percent' => $item->cropData->x_percent,
                                'y_percent' => $item->cropData->y_percent,
                                'url' => $item->cropData->url
                            ]) : null,
                            'content_type' => $item->content_type,
                            'display_order' => $item->display_order,
                        ];
                    })->sortBy('display_order')->values()->all()
                ];
            })->values()->all(); // コレクションを配列に変換
    }
}
