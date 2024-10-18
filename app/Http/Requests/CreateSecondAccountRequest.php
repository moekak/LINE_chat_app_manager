<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSecondAccountRequest extends FormRequest
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
            "current_account_id" =>  ['required', 'integer', 'exists:line_accounts,id'],
            "second_account_id" =>  ['required', 'integer', 'exists:line_accounts,id'],
        ];
    }


    public function messages(): array
    {
        return [
            'current_account_id.required' => '無効なデータです。再度お試しください。',
            'second_account_id.exists' => '無効なアカウントです。',
            'second_account_id.required' => 'アカウントは必須項目です。',
        ];
    }
}
