<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class DecryptDataController extends Controller
{
    public function decryptData(Request $request){
        // return response()->json($request , 200);
        $encryptedData= $request->input("encryptedData");
        $decryptedData = [];
        try{
            foreach($encryptedData as $data){
                $decryptedChannelAccessToken = Crypt::decryptString($data["channel_access_token"]);
                $decryptedChannelSecret = Crypt::decryptString($data["channel_secret"]);
    
                array_push($decryptedData, ["channelAccessToken" => $decryptedChannelAccessToken, "channelSecret" => $decryptedChannelSecret]);
            }

            return response()->json($decryptedData , 200);
        }catch(\Exception $e){
            return response()->json(['error' => 'Failed to decrypt data'], 400);
        }
    }
}
