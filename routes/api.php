<?php

use App\Http\Controllers\BroadcastMessageController;
use App\Http\Controllers\ChatUserController;
use App\Http\Controllers\LineAccountController;
use App\Http\Controllers\LineMessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\YourController;

// ルート定義の例
Route::get("/user/{sender_id}/account/{receiver_id}", [ChatUserController::class, "getUserData"]);
Route::get("/user/{user_id}", [ChatUserController::class, "getUserName"]);
Route::get("/account/{account_id}", [LineAccountController::class, "hasSecondAccount"]);
Route::get("/account/{account_id}/status/{status_id}/current_stautus/{current_status_name}/update", [LineAccountController::class, "updateStatus"]);
Route::get("/line/message/{admin_id}", [LineMessageController::class, "getMessage"]);

Route::post("/broadcast_message/store", [BroadcastMessageController::class, "store"]);
// Route::post("/lineAccount/create", [LineAccountController::class, "create"]);