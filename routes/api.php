<?php

use App\Http\Controllers\BroadcastMessageController;
use App\Http\Controllers\ChatUserController;
use App\Http\Controllers\DecryptDataController;
use App\Http\Controllers\GreetingMessageController;
use App\Http\Controllers\LineAccountController;
use App\Http\Controllers\LineMessageController;
use App\Http\Controllers\LineTestSenderController;
use App\Http\Controllers\MessageTemplate;
use App\Http\Controllers\RedirectTokenController;
use Illuminate\Support\Facades\Route;


// ルート定義の例
Route::get("/user/{sender_id}/account/{receiver_id}", [ChatUserController::class, "getUserData"]);
Route::get("/user/{user_id}", [ChatUserController::class, "getUserName"]);
Route::get("/account/{account_id}", [LineAccountController::class, "hasSecondAccount"]);
Route::get("/account/{account_id}/status/{status_id}/current_stautus/{current_status_name}/update", [LineAccountController::class, "updateStatus"]);
Route::get("/line/message/{admin_id}", [LineMessageController::class, "getMessage"]);
Route::get("/greetingMessage/adminId/{admin_id}", [GreetingMessageController::class, "getGreetingMessages"]);

Route::post("/broadcast_message/store/{admin_id}", [BroadcastMessageController::class, "store"])->name("broadcast.store");
Route::post("/greeting_message/store/{admin_id}", [GreetingMessageController::class, "store"]);
Route::post("/decrypt", [DecryptDataController::class, "decryptData"]);

Route::post("/user/lists/{admin_id}", [LineAccountController::class, "fetchScrollData"]);
Route::post("/account/lists/{admin_id}/{status_id}", [LineAccountController::class, "fetchScrollAcocuntData"]);
Route::post("/fetch/account", [LineAccountController::class, "fetchSpecificAccount"]);

Route::get("/token/generate", [RedirectTokenController::class, "fetchToken"]);
Route::post("/create/category", [MessageTemplate::class, "createCategory"]);
// Route::post("/get/categories", [MessageTemplate::class, "fetchCategories"]);
Route::post("/create/templates", [MessageTemplate::class, "store"]);
Route::post("/update/templates", [MessageTemplate::class, "update"]);
Route::get("/templates/get/{admin_id}", [MessageTemplate::class, "fetchTemplate"]);
Route::get("/fetch/template/{category_id}", [MessageTemplate::class, "fetchTemplateByCategory"]);


Route::post("/template/order", [MessageTemplate::class, "updateOrder"]);
Route::post("/template/delete", [MessageTemplate::class, "destroy"]);
Route::post("/category/create", [MessageTemplate::class, "categoryStore"]);

Route::post("/category/edit", [MessageTemplate::class, "categoryEdit"]);
Route::post("/test/message/store/{admin_id}", [LineTestSenderController::class, "store"]);