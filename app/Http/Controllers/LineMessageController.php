<?php

namespace App\Http\Controllers;

use App\Http\Requests\LineMessageCreateRequest;
use App\Models\LineMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LineMessageController extends Controller
{
    public function update(LineMessageCreateRequest $request, $id)
    {
        $validated = $request->validated();
        $user = Auth::user();

        // IDの検証
        if ($user->id != $id) {
            return redirect()
                ->back() // 前のページに戻る
                ->withErrors(['id' => 'Invalid user ID']) //エラーメッセージをセッションに追加
                ->withInput(); //入力値を保持したまま戻る
        }

        // 既にデータベースに登録されているかの確認
        $message = LineMessage::where("admin_id", $id)->first();

        if($message){
            $message->update(["messages"=> $validated["message"]]);
        }else{

            $data = [
                "admin_id" => $id,
                "messages" => $validated["message"]
            ];
            LineMessage::create($data);
        }

        return redirect()->route("dashboard")->with("success", "LINE送信文言の変更に成功しました。");
    }


    public function getMessage(int $admin_id){

        $message = LineMessage::where("admin_id", $admin_id)->value("messages");
        $result = $message ?? "チャットメッセージを受信しました。";
        return response()->json($result);
    }
}
