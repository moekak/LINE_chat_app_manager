<?php

use App\Http\Controllers\BroadcastMessageController;
use App\Http\Controllers\ChatUserController;
use App\Http\Controllers\DecryptDataController;
use App\Http\Controllers\GreetingMessageController;
use App\Http\Controllers\LineAccountController;
use App\Http\Controllers\LineMessageController;
use Illuminate\Support\Facades\Route;


// ルート定義の例
Route::get("/user/{sender_id}/account/{receiver_id}", [ChatUserController::class, "getUserData"]);
Route::get("/user/{user_id}", [ChatUserController::class, "getUserName"]);
Route::get("/account/{account_id}", [LineAccountController::class, "hasSecondAccount"]);
Route::get("/account/{account_id}/status/{status_id}/current_stautus/{current_status_name}/update", [LineAccountController::class, "updateStatus"]);
Route::get("/line/message/{admin_id}", [LineMessageController::class, "getMessage"]);

Route::post("/broadcast_message/store", [BroadcastMessageController::class, "store"]);
Route::post("/greeting_message/store", [GreetingMessageController::class, "store"]);
Route::post("/decrypt", [DecryptDataController::class, "decryptData"]);