<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Models\MessageTemplatesCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MessageTemplate extends Controller
{
    public function createCategory(CreateCategoryRequest $request){
        try{
            $validated = $request->validated();

            $category = MessageTemplatesCategory::create($validated);
            return response()->json(["category_name" => $category["category_name"], "id" => $category["id"]]);
        }catch (\Exception $e) {
            Log::error('Category creation failed: ' . $e->getMessage());
            return response()->json([
                "message" => "カテゴリー追加に失敗しました。再度お試しください。",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function fetchCategories(Request $request){
        try{

            $admin_id = $request->input('admin_id');
            Log::debug($admin_id);

            $categories = MessageTemplatesCategory::select("id", "category_name")->where("admin_id", $admin_id)->get();
            return response()->json(["categories" => $categories]);

        }catch(\Exception $e){
            Log::error($e);
        }

    }

    public function store(Request $request){
        Log::debug($request->all());
    }
}
