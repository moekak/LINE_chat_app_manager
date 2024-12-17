<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LineMessageCreateRequest extends FormRequest
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
        session()->flash("route_name", "lineMessage.create");
        return [
            "message" => ["required", "string", "max:50"],
        ];
    }


    public function messages(): array
    {
        return [
            'message.required' => '文言はは必須項目です。',
            'message.max' => '文言は50文字以内で入力してください。',
        ];
    }
}
