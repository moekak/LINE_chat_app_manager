<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        return [
            "category_name" => ["required", "string", "max:255"],
            "admin_id" => ["required", "exists:line_accounts,id"],
        ];
    }


    public function messages(): array
    {
        return [
            'title.required' => 'タイトルは必須項目です。',
            'title.max' => 'タイトルは50文字以内で入力してください。',
            "admin_id.required" => "無効なデータです",
            "admin_id.exists" => "無効なデータです"
        ];
    }
}
