<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLineAccountRequest extends FormRequest
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

        session()->flash("route_name", "account.edit");
        return [
            "account_id" => ["required", "integer"],
            "account_name" => ["required", "string", "max:50"],
            "account_url" => ["required", "string", "url", "max:255"],
            "second_account_id" =>  ['nullable', 'integer', 'exists:line_accounts,id']
        ];
    }

    public function messages(): array
    {
        return [
            "account_name.required" => "アカウント名は必須です。",
            "account_name.max" => "アカウント名は50文字以内で入力してください。",
            "account_url.url" => "URLの形式にしてください。" ,
            "account_url.required" => "友達追加用URLは必須項目です。" ,
            "account_url.max" => "友達追加用URLは255文字以内で入力してください。" ,
            "second_account_id.exists" => "無効なアカウントです。"
        ];
    }
}
