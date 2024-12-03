<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LineAccountController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BroadcastMessageController;
use App\Http\Controllers\ChatUserController;
use App\Http\Controllers\LineMessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SecondAccountController;
use App\Http\Controllers\UserBlockController;


Route::get('/healthcheck', function () {
    return response('OK', 200);
});



Route::get("/", [LineAccountController::class, "index"])->name("dashboard")->middleware("auth");
Route::get("/signup", [UserController::class, "create"])->name("signup")->middleware("guest");
Route::post("/signup/store", [UserController::class, "store"])->name("users.store")->middleware("guest");
Route::get("/login", [AuthController::class, "showLoginForm"])->name("admin.login")->middleware("guest");
Route::post('/admin/login', [AuthController::class, 'login'])->name("login")->middleware("guest");
Route::post("/logout", [AuthController::class, "logout"])->name("logout");



Route::post("/account/create", [LineAccountController::class, "create"])->name("account.create")->middleware("auth");
Route::delete("/account/destroy/{id}", [LineAccountController::class, "destroy"])->name("account.destroy")->middleware("auth");
Route::post("/account/update/{id}", [LineAccountController::class, "update"])->name("account.update")->middleware("auth");
Route::get("/account/edit/{id}", [LineAccountController::class, "edit"])->middleware("auth");
Route::get("/account/show/{id}", [LineAccountController::class, "show"])->name("account.show")->middleware("auth");
Route::get("/account/block/user/{id}", [UserBlockController::class, "index"])->name("account.block.user")->middleware("auth");
Route::get("/account/unblock/{id}", [UserBlockController::class, "update"])->name("account.unblock")->middleware("auth");
// Route::get("/account/flag/update/invalid/{id}", [LineAccountController::class, "updateFlagForInvalid"])->name("flag.update")->middleware("auth");
// Route::get("/account/flag/update/valid/{id}", [LineAccountController::class, "updateFlagForValid"])->middleware("auth");

Route::post("/secondAccount/create", [SecondAccountController::class, "create"])->name("secondAccount.create")->middleware("auth");
Route::post("/user/update/{id}", [ChatUserController::class, "update"])->name("user.update")->middleware("auth");


Route::get("/account/block/{id}", [ChatUserController::class, "block"])->name("account.block")->middleware("auth");
Route::post("/line/message/update/{id}", [LineMessageController::class, "update"])->name("message.update")->middleware("auth");

