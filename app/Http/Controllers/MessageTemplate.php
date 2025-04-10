<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\CreateMessageTemplateCategory;
use App\Http\Requests\EditMessageTemplateCategory;
use App\Models\MessageTemplate as ModelsMessageTemplate;
use App\Models\MessageTemplateContent;
use App\Models\MessageTemplatesCategory;
use App\Models\MessageTemplatesGroup;
use App\Services\ImageService;
use AWS\CRT\HTTP\Message;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MessageTemplate extends Controller
{
    public function createCategory(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                "category_name" => ["required", "string", "max:255"],
                "admin_id" => ["required", "exists:line_accounts,id"],
            ], [
                'category_name.required' => 'カテゴリー名は必須項目です。',
                'category_name.max' => 'カテゴリー名は255文字以内で入力してください。',
                "admin_id.required" => "無効なデータです",
                "admin_id.exists" => "無効なデータです"
            ]);
    
            if ($validator->fails()) {
                throw new \Illuminate\Validation\ValidationException($validator);
            }

            $validated = $validator->validated();

            $category = MessageTemplatesCategory::create($validated);
            return response()->json(["category_name" => $category["category_name"], "id" => $category["id"], "status" => 201]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::debug($e);
            // バリデーションエラーの場合
            return response()->json([
                "error" => $e->errors(),
                "status" => 422
            ]);
        } catch (\Exception $e) {
            // その他のエラーの場合
            Log::error('Category creation failed: ' . $e->getMessage());
            return response()->json([
                "error" => $e->getMessage(),
                "status" => 500
            ]);
        }
    }

    public function fetchCategories(Request $request){
        try{

            $admin_id = $request->input('admin_id');
            $categories = MessageTemplatesCategory::select("id", "category_name")->where("admin_id", $admin_id)->get();
            return response()->json(["categories" => $categories]);

        }catch(\Exception $e){
            Log::error($e);
        }

    }

    public function store(Request $request){
        try{
            return DB::transaction(function() use ($request){
                $validator = Validator::make($request->all(), [
                    "category_id" => ["required", "string", "exists:message_templates_categories,id"],
                    "admin_id" => ["required", "exists:line_accounts,id"],
                    "template_name" => ["required", "string", "max:255"],
                ], [
                    'template_name.required' => 'テンプレート名は必須項目です。',
                    'template_name.max' => 'テンプレート名は255文字以内で入力してください。',
                    "admin_id.required" => "無効なデータです",
                    "admin_id.exists" => "無効なデータです",
                    "category_id.required" => "カテゴリーを選択してください",
                    "category_id.exists" => "無効なデータです",
                ]);
        
                if ($validator->fails()) {
                    throw new \Illuminate\Validation\ValidationException($validator);
                }
    
    
                $admin_id = $request->input("admin_id");
                $category_id = $request->input("category_id");
                $template_name = $request->input("template_name");
        
                // メッセージテンプレートグループへのデータ追加
                $messageTemplateGroup = MessageTemplatesGroup::create(['admin_id' => $admin_id]);
                // メッセージテンプレート作成
                $messageTemplate = ModelsMessageTemplate::create(["category_id" => $category_id, "admin_id" => $admin_id, "group_id" => $messageTemplateGroup->id, "template_name" => $template_name]);
                $messageContents = $request->input("content_texts");
                $imageContents = $request->input("image_path");
                if(isset($messageContents)){
        
                    $templates = [];
                    foreach($messageContents as $messageContent){
                        $template_id = $messageTemplate->id;
                        $content_type = "text";
                        $content_text = $messageContent["content"];
                        $display_order = $messageContent["order"];
            
                        $templates[] = [
                            "template_id" => $template_id,
                            "admin_id" => $admin_id,
                            "content_type" => $content_type,
                            "image_path" => null,
                            "content_text" => $content_text,
                            "display_order" => $display_order,
                            "created_at" => now(),
                            "updated_at" => now()
                        ];
                    }
                
                    // テキストコンテンツをバルク挿入
                    if (!empty($templates)) {
                        DB::table("message_template_contents")->insert($templates);
                    }
                }

        
                // // 画像ファイルの取り出し
                // 画像コンテンツの処理 - 個別に挿入してIDを取得
                Log::debug($imageContents);
                if(isset($imageContents)){
                    foreach ($imageContents as $index => $imageData) {
                        $fileKey = "image_path.{$index}.content";
                        if ($request->hasFile($fileKey)) {
                            $file = $request->file($fileKey);
                            Log::debug($file);
                            $imageService = new ImageService();
                            $fileName = $imageService->saveImage($file);

                            Log::debug($fileName);
                            
                            // 画像コンテンツを個別に挿入してIDを取得
                            $contentId = DB::table("message_template_contents")->insertGetId([
                                "template_id" => $messageTemplate->id,
                                "admin_id" => $admin_id,
                                "content_type" => "image",
                                "image_path" => $fileName,
                                "content_text" => null,
                                "display_order" => $imageData["order"],
                                "created_at" => now(),
                                "updated_at" => now()
                            ]);
                            
                            // クロップデータがある場合は処理
                            if (isset($imageData["cropData"]) && isset($imageData["cropData"]["url"])) {
                                $cropAreaJson = $imageData["cropData"]["cropArea"];
                                $cropArea = json_decode($cropAreaJson, true);
                                // クロップデータを別テーブルに挿入
                                DB::table("message_template_crop_data")->insert([
                                    "message_template_contents_id" => $contentId,
                                    "url" => $imageData["cropData"]["url"] ?? null,
                                    "x_percent" => $cropArea["xPercent"] ?? 0,
                                    "y_percent" => $cropArea["yPercent"] ?? 0,
                                    "width_percent" => $cropArea["widthPercent"] ?? 100,
                                    "height_percent" => $cropArea["heightPercent"] ?? 100,
                                ]);
                            }
                        }
                    }
                }
                // return redirect()->route("account.show", ["id" => $admin_id])->with("success", "テンプレートの作成に成功しました"); 
                return response()->json(["status" => 201]);
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::debug($e);
            // バリデーションエラーの場合
            return response()->json([
                "error" => $e->errors(),
                "status" => 422
            ]);
        }catch(\Exception $e){
            Log::error($e);
            return response()->json(["status" => 500]);
        }
        
    }

    public function update(Request $request){
        try{
            Log::debug($request->all());
            return DB::transaction(function() use ($request){
                $validator = Validator::make($request->all(), [
                    "category_id" => ["required", "string", "exists:message_templates_categories,id"],
                    "template_id" => ["required", "exists:message_templates,id"],
                    "admin_id" => ["required", "exists:line_accounts,id"],
                    "template_name" => ["required", "string", "max:255"],
                ], [
                    'template_name.required' => 'テンプレート名は必須項目です。',
                    'template_name.max' => 'テンプレート名は255文字以内で入力してください。',
                    "tenplate_id.required" => "無効なデータです",
                    "tenplate_id.exists" => "無効なデータです",
                    "admin_id.required" => "無効なデータです",
                    "admin_id.exists" => "無効なデータです",
                    "category_id.required" => "カテゴリーを選択してください",
                    "category_id.exists" => "無効なデータです",
                ]);
        
                if ($validator->fails()) {
                    throw new \Illuminate\Validation\ValidationException($validator);
                }
    
                $admin_id = $request->input("admin_id");
                $template_id = $request->input("template_id");
                $group_id = $request->input("group_id");
                $category_id = $request->input("category_id");
                $template_name = $request->input("template_name");
        
                // メッセージテンプレート既存のデータの削除
                MessageTemplateContent::where("template_id", $template_id)->delete();


                // メッセージテンプレートの更新
                $templateData = ModelsMessageTemplate::findOrFail($template_id);
                $templateData->update(["category_id" => $category_id, "group_id" => $group_id, "template_name" => $template_name]);

                $messageContents = $request->input("content_texts");
                $imageContents = $request->input("image_path");
                $imageContentsUpdate = $request->input("image_path_update");
                if(isset($messageContents)){
        
                    $templates = [];
                    foreach($messageContents as $messageContent){
                        $template_id = $template_id;
                        $content_type = "text";
                        $content_text = $messageContent["content"];
                        $display_order = $messageContent["order"];
            
                        $templates[] = [
                            "template_id" => $template_id,
                            "admin_id" => $admin_id,
                            "content_type" => $content_type,
                            "image_path" => null,
                            "content_text" => $content_text,
                            "display_order" => $display_order,
                            "created_at" => now(),
                            "updated_at" => now()
                        ];
                    }
                
                    // テキストコンテンツをバルク挿入
                    if (!empty($templates)) {
                        DB::table("message_template_contents")->insert($templates);
                    }
                }


                // // 画像ファイルの取り出し
                // 画像コンテンツの処理 - 個別に挿入してIDを取得
                if(isset($imageContentsUpdate)){
                    foreach ($imageContentsUpdate as $index => $imageData) {
                        
                        // 画像コンテンツを個別に挿入してIDを取得
                        $contentId = DB::table("message_template_contents")->insertGetId([
                            "template_id" => $template_id,
                            "admin_id" => $admin_id,
                            "content_type" => "image",
                            "image_path" => $imageData["contentUrl"],
                            "content_text" => null,
                            "display_order" => $imageData["order"],
                            "created_at" => now(),
                            "updated_at" => now()
                        ]);
                        
                        // クロップデータがある場合は処理
                        if (isset($imageData["cropData"])) {
                            $cropArea = json_decode($imageData["cropData"], true);
                            // クロップデータを別テーブルに挿入
                            DB::table("message_template_crop_data")->insert([
                                "message_template_contents_id" => $contentId,
                                "url" => $cropArea["url"]?? null,
                                "x_percent" => $cropArea["x_percent"] ?? 0,
                                "y_percent" => $cropArea["y_percent"] ?? 0,
                                "width_percent" => $cropArea["width_percent"] ?? 100,
                                "height_percent" => $cropArea["height_percent"] ?? 100,
                            ]);
                        }
                    }
                }

        
                // // 画像ファイルの取り出し
                // 画像コンテンツの処理 - 個別に挿入してIDを取得
                if(isset($imageContents)){
                    foreach ($imageContents as $index => $imageData) {
                        $fileKey = "image_path.{$index}.content";
                        
                        if ($request->hasFile($fileKey)) {
                            $file = $request->file($fileKey);
                            $imageService = new ImageService();
                            $fileName = $imageService->saveImage($file);
                            
                            // 画像コンテンツを個別に挿入してIDを取得
                            $contentId = DB::table("message_template_contents")->insertGetId([
                                "template_id" => $template_id,
                                "admin_id" => $admin_id,
                                "content_type" => "image",
                                "image_path" => $fileName,
                                "content_text" => null,
                                "display_order" => $imageData["order"],
                                "created_at" => now(),
                                "updated_at" => now()
                            ]);
                            
                            // クロップデータがある場合は処理
                            if (isset($imageData["cropData"]) && isset($imageData["cropData"]["url"])) {
                                $cropAreaJson = $imageData["cropData"]["cropArea"];
                                $cropArea = json_decode($cropAreaJson, true);
                                // クロップデータを別テーブルに挿入
                                DB::table("message_template_crop_data")->insert([
                                    "message_template_contents_id" => $contentId,
                                    "url" => $imageData["cropData"]["url"] ?? null,
                                    "x_percent" => $cropArea["xPercent"] ?? 0,
                                    "y_percent" => $cropArea["yPercent"] ?? 0,
                                    "width_percent" => $cropArea["widthPercent"] ?? 100,
                                    "height_percent" => $cropArea["heightPercent"] ?? 100,
                                ]);
                            }
                        }
                    }
                }
                
                return response()->json(["status" => 201]);
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::debug($e);
            // バリデーションエラーの場合
            return response()->json([
                "error" => $e->errors(),
                "status" => 422
            ]);
        }catch(\Exception $e){
            Log::error($e);
            return response()->json(["status" => 500]);
        }
    }

    public function fetchTemplate(string $admin_id){
        $templates = MessageTemplateContent::getMessageTemplatesForAdmin($admin_id);
        return response()->json($templates);
    }

    public function destroy(Request $request){
        try{
            ModelsMessageTemplate::destroy($request->input("template_id"));
            return redirect()->route("account.show", ["id" => $request->input("admin_id")])->with("success", "テンプレートの削除に成功しました。"); 
        }catch(\Exception $e){
            Log::debug($e);
        }
        
    }
    

    public function categoryStore(CreateMessageTemplateCategory $request){
        try{
            $validated = $request->validated();
            MessageTemplatesCategory::create($validated);
            return redirect()->route("account.show", ["id" => $validated["admin_id"]])->with("success", "カテゴリーの追加に成功しました。");  
        }catch(\Exception $e){
            Log::debug($e);
        }
    }
    
    public function categoryEdit(EditMessageTemplateCategory $request){
        try{
            $validated = $request->validated();
            $category = MessageTemplatesCategory::findOrFail($validated["id"]);
            $category->update(["category_name" => $validated["category_name_edit"]]);
            return redirect()->route("account.show", ["id" => $validated["admin_id"]])->with("success", "カテゴリーの更新に成功しました。");  
        }catch(\Exception $e){
            Log::debug($e);
        }
    }
}
