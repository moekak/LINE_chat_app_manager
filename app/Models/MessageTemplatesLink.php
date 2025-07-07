<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MessageTemplatesLink extends Model
{
    use HasFactory;

    protected $fillable = [
        "template_id",
        "admin_id"
    ];


    public static function duplicateTemplatesToAccount($from_admin_id, $to_admin_id){
        $template_ids = static::where('admin_id', $from_admin_id)
        ->select('template_id')
        ->union(
            MessageTemplateContent::where('admin_id', $from_admin_id)
                ->select('template_id')
        )
        ->distinct()
        ->pluck('template_id');

        if ($template_ids->isEmpty()) return;

        $inserting_data = $template_ids->map(function ($template_id) use ($to_admin_id) {
            return [
                'admin_id' => $to_admin_id,
                'template_id' => $template_id,
            ];
        })->toArray();

        DB::table('message_templates_links')->insert($inserting_data);
    }
}
