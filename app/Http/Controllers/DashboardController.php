<?php

namespace App\Http\Controllers;

use App\Events\MyEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(){
        $user = Auth::user();

        $data = ['message' => 'Hello, this is a test message'];
        broadcast(new \App\Events\MyEvent(['message' => 'Test message']))->toOthers();


        // イベントがブロードキャストされた後にログを記録
        Log::info('MyEvent broadcasted with data:', $data);
    
    
        // return response()->json(['status' => 'Event has been sent!']);
        return view("admin.dashboard", ["user" => $user]);
    }
}
