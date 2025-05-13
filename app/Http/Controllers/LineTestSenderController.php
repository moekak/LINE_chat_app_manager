<?php

namespace App\Http\Controllers;

use App\Services\Message\TestSenderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LineTestSenderController extends Controller
{
    public function store(Request $request){
        $testSenderService = new TestSenderService($request);
        return $testSenderService->store();
    }
}
