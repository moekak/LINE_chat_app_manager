<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserDataRequest extends FormRequest
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
        session()->flash("route_name", "user.edit");
        return [
            "account_name" => ["required", "string", "max:50"],
        ];
    }

    public function messages(): array
    {
        return [
            "account_name.required" => "アカウント名は必須です。",
            "account_name.max" => "アカウント名は50文字以内で入力してください。"
        ];
    }
}
