<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateLineAccountRequest extends FormRequest
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

        session()->flash("route_name", "account.create");

        return [
            "account_name" => ["required", "string", "max:50"],
            "account_url" => ["required", "url", "max:255"],
            "account_id" => ["required", "string", "size:33", "alpha_num"],
            "account_status" => ["required", "integer", "exists:account_statuses,id"],
        ];
    }

    public function messages(): array
    {
         // 初期メッセージ
        $messages = [
            "account_name.required" => "アカウント名は必須です。",
            "account_name.max" => "アカウント名は50文字以内で入力してください。",
            "account_id.required" => "アカウントIDは必須です。",
            "account_id.size" => "アカウントIDは32文字で入力してください。",
            "account_id.alpha_num" => "アカウントIDは英数字で入力してください。",
            "account_status.required" => "ステータスは必須です。",
            "account_status.exists" => "ステータスが有効ではありません。",
            "account_url.url" => "友達追加用URLはURLの形式にしてください。",
            "account_url.required" => "友達追加用URLは必須です。",
            "account_url.max" => "友達追加用URLは255文字以内で入力してください。",
        ];

        return $messages;
    }
}
