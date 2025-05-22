<?php

namespace App\Http\Controllers;

use App\Models\LineTestSender;
use App\Services\Message\TestSenderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LineTestSenderController extends Controller
{
    public function store(Request $request){
        $testSenderService = new TestSenderService($request);
        return $testSenderService->store();
    }

    public function destroy($id){
        try{
            $testUser = LineTestSender::findOrFail($id);
            $testUser->delete();
            return response()->json(["status" => 201, "userId" => $testUser->user_id]);
        }catch(\Exception $e){
            Log::error($e);
            return response()->json(["status" => 500]);
        }

    }

    public function getAll(){
        try{
            $test_sender = LineTestSender::all();
            return response()->json(["status" => 201, "testSenders"=>$test_sender]);           
        }catch(\Exception $e){
            Log::error($e);
            return response()->json(["status" => 500]);
        }

    }
}
