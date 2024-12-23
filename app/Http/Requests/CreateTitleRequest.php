<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTitleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        session()->flash("route_name", "title.create");
        return [
            "title" => ["required", "string", "max:50"],
            "admin_id" => ["required","exists:line_accounts,id"],
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
