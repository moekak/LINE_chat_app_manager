<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSecondAccountRequest;
use App\Models\SecondAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SecondAccountController extends Controller
{
    public function create(CreateSecondAccountRequest $request){
        Log::debug("22222");
        $validated = $request->validated();
        SecondAccount::create($validated);

        return redirect()->route("dashboard")->with("success", "予備アカウントの追加に成功しました。ステータスが変更可能になりました。");
    }
}
