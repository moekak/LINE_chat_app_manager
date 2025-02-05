<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LineDisplayTextRequest extends FormRequest
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
        session()->flash("route_name", "lineDisplayText.store");
        return [
            "text" => ["sometimes", "required_if:is_show,1,true","max:50"],
            "admin_id" => ["required","exists:line_accounts,id"],
            "is_show" => ["required", "integer", "in:0,1"],
        ];
    }


    public function messages(): array
    {
        return [
            'text.required_if' => '表示をONにする場合、テキストは必須です。',
            'text.max' => 'テキストは50文字以内で入力してください。',
            'admin_id.required' => '無効なデータです。',
            'admin_id.exists' => '無効なデータです。',
            'is_show.required' => '表示ステータスは必須です。',
            'is_show.in' => '表示ステータスは「表示する」または「表示しない」を選択してください。',
        ];
    }
    
}
