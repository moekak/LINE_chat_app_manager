<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

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

    public function scopeWithTemplateLink($query){
        return $query->with(['messageTemplate', 'messageTemplate.messageTemplatesLinks']);
    }


    public function scopeWithContentsForAdmin($query, $admin_id)
    {
        return $query->where('admin_id', $admin_id)
                    ->with(['messageTemplate','messageTemplate.messageTemplatesCategory', 'messageTemplate.messageTemplatesGroup', 'cropData']);
    }

    public function scopeWithFilter($query, $category_id)
    {
        return $query->with([
            'messageTemplate', 
            'messageTemplate.messageTemplatesGroup', 
            'cropData',
            'messageTemplate.messageTemplatesCategory'
        ])->whereHas('messageTemplate', function($q) use ($category_id) {
            $q->where('category_id', $category_id);
        });
    }


    public static function getMessageTemplatesForAdmin($admin_id){
        return MessageTemplateContent::with([
            'messageTemplate',
            'messageTemplate.messageTemplatesCategory', 
            'messageTemplate.messageTemplatesGroup',
            'cropData'
        ])
        ->where(function ($query) use ($admin_id) {
            $query->whereHas('messageTemplate.messageTemplatesLinks', function ($q) use ($admin_id) {
                $q->where('admin_id', $admin_id);
            })
            ->orWhereHas('messageTemplate', function ($q) use ($admin_id) {
                $q->where('admin_id', $admin_id);
            });
        })
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
                'created_at' => $firstItem->created_at,
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
        })
        ->sortByDesc('created_at')
        ->values()
        ->all();
    }



    
    public static function getMessageTemplatesByFilter($category_id, $admin_id)
    {
        return MessageTemplateContent::WithFilter($category_id)
            ->where(function ($query) use ($admin_id) {
                $query->whereHas('messageTemplate.messageTemplatesLinks', function ($q) use ($admin_id) {
                    $q->where('admin_id', $admin_id);
                })
                ->orWhereHas('messageTemplate', function ($q) use ($admin_id) {
                    $q->where('admin_id', $admin_id);
                });
            })
        ->get()
        ->groupBy("template_id")
        ->map(function ($group) {
            $firstItem = $group->first();
            
            return [
                "category_id" => $firstItem->messageTemplate->messageTemplatesCategory->id,
                "group_id" => $firstItem->messageTemplate->messageTemplatesGroup->id,
                'template_id' => $firstItem->template_id,
                "display_order" => $firstItem->messageTemplate->messageTemplatesGroup->display_order,
                'template_name' => $firstItem->messageTemplate->template_name,
                'category_name' => $firstItem->messageTemplate->messageTemplatesCategory->category_name,
                'admin_id' => $firstItem->admin_id,
                'created_at' => $firstItem->created_at, // created_at を追加
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
        })
        ->sortBy('display_order') // created_at の降順で並べ替え
        ->values()
        ->all();
    }
}
