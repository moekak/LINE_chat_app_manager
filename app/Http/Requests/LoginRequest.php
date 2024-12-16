<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name" => ["required", "string"],
            "password" => ["required"],
        ];
    }


    public function messages(): array
    {
        return [
            'name.required' => 'ユーザーネームは必須項目です',
            'password.required' => 'パスワードは必須項目です',
        ];
    }
}
