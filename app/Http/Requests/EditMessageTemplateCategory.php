<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditMessageTemplateCategory extends FormRequest
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

        session()->flash("route_name", "category");
        return [
            "id" =>  ['required', 'integer', 'exists:message_templates_categories,id'],
            "admin_id" =>  ['required', 'integer', 'exists:line_accounts,id'],
            "category_name" =>  ['required', 'string', 'max:255'],
        ];
    }


    public function messages(): array
    {
        return [
            'admin_id.required' => '無効なデータです。再度お試しください。',
            'admin_id.exists' => '無効なデータです。再度お試しください。',
            'admin_id.integer' => '無効なデータです。再度お試しください。',
            'id.required' => '無効なデータです。再度お試しください。',
            'id.exists' => '無効なデータです。再度お試しください。',
            'id.integer' => '無効なデータです。再度お試しください。',
            'category_name.required' => 'カテゴリー名は空にできません。',
            'category_name.max' => 'カテゴリー名は255文字以内で入力してください。',
        ];
    }
}
