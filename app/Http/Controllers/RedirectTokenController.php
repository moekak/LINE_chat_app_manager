<?php

namespace App\Http\Controllers;

use App\Services\RedirectTokenService;


class RedirectTokenController extends Controller
{
    public function fetchToken(){
        $redirectTokenService = new RedirectTokenService();

        $token = $redirectTokenService->generateToken();
        return response()->json($token);
    }
}
