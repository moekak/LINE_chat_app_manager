<?php

namespace App\Http\Controllers;

// use App\Http\Requests\StoreUserRequest as RequestsStoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Hash;

;

class UserController extends Controller
{

    public function create()
    {
        return view("auth.signup");
    }

    //  ユーザー登録処理
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        // パスワードのハッシュ化
        $validated["password"]= Hash::make($validated["password"]);
        User::create($validated);

        return redirect()->route("dashboard")->with("success", "ユーザーを登録しました");

    }
}
