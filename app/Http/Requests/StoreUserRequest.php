<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
        return [
            "name" => ["required", "string", "max:50", 'unique:users,name'],
            "password"=>["required", "string", "min:6"]
        ];
    }


    public function messages(): array
    {
        return [
            'name.required' => 'ユーザーネームは必須項目です。',
            'name.unique' => 'このユーザーネームは既に使用されています。',
            'name.max' => 'ユーザーネームは50文字以内で設定してください。',
            'password.required' => 'パスワードは必須項目です。',
            'password.min' => 'パスワードは6文字以上で設定してください。',
        ];
    }

}
