<?php

namespace App\Http\Requests;

use App\Models\LineAccount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use function Psy\debug;

class CreateLineAccountRequest extends FormRequest
{

    private $lineApiResponse = null;
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
            "account_url" => ["required", "url", "max:2048"],
            "second_account_id" => ['exists:line_accounts,id'],
            'channelsecret' => [
                'required',
                'string',
                'size:32',
                'regex:/^[a-f0-9]+$/',
                function($attribute, $value, $fail){
                    try{
                        // チャンネルアクセストークンがかぶっているか確認
                        $exists = LineAccount::all()
                                ->contains(function($account) use ($value){
                                    $decryptedToken = Crypt::decryptString($account->getRawOriginal("channel_secret"));
                                    return $decryptedToken === $value;
                                });

                        if ($exists) {
                            $fail('既にこのチャネルシークレットは使用されています。');
                        }


                    } catch (\Exception $e) {
                        $fail('チャネルシークレットの署名検証に失敗しました。再度お試しください。');
                    }
                }
            ],
            'channelaccesstoken' => [
                'required',
                'string',
                'min:100',    // トークンの最小長
                'max:500',    // トークンの最大長
                'regex:/^[a-zA-Z0-9+\/=]+$/', // Base64文字のみ許可
                function ($attribute, $value, $fail) {
                    // LINEのAPIを呼び出してトークンの有効性を確認
                    try {
                        Log::debug($value);
                        $response = Http::withHeaders([
                            'Authorization' => 'Bearer ' . $value
                        ])->get('https://api.line.me/v2/bot/info');

                        // レスポンスを保存
                        $this->lineApiResponse = $response;
                        if ($response->failed()) {
                            $fail('チャネルアクセストークンが無効です。');
                        }

                    } catch (\Exception $e) {
                        Log::debug($e);
                        $fail('チャネルアクセストークンを確認できませんでした。');
                    }

                    try{
                        // チャンネルアクセストークンがかぶっているか確認
                        $exists = LineAccount::all()
                                ->contains(function($account) use ($value){
                                    $decryptedToken = Crypt::decryptString($account->getRawOriginal("channel_access_token"));
                                    return $decryptedToken === $value;
                                });

                        if ($exists) {
                            $fail('既にこのチャネルアクセストークンは使用されています。');
                        }
                    } catch (\Exception $e) {
                        $fail('チャネルアクセストークンを確認できませんでした。');
                    }
                }
            ],
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
            "account_status.required" => "ステータスは必須です。",
            "account_status.exists" => "ステータスが有効ではありません。",
            "account_url.url" => "友達追加用URLはURLの形式にしてください。",
            "account_url.required" => "友達追加用URLは必須です。",
            "account_url.max" => "友達追加用URLは255文字以内で入力してください。",
            'channelaccesstoken.required' => 'チャネルアクセストークンは必須です。',
            'channelaccesstoken.min' => 'チャネルアクセストークンが短すぎます。',
            'channelaccesstoken.max' => 'チャネルアクセストークンが長すぎます。',
            'channelaccesstoken.regex' => 'チャネルアクセストークンが無効です。',
            'channelsecret.required' => 'チャネルシークレットは必須です。',
            'channelsecret.size' => 'チャネルシークレットは32文字で入力してください。',
            'channelsecret.regex' => 'チャネルシークレットが無効です。',
            'second_account_id' => "予備アカウントが無効です。"
        ];

        return $messages;
    }


    // LINE APIレスポンスを取得するメソッド
    public function getLineApiResponse()
    {
        return $this->lineApiResponse;
    }
}
