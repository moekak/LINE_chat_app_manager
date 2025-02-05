<?php

namespace App\Http\Controllers;

use App\Http\Requests\LineDisplayTextRequest;
use App\Models\LineDisplayText;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LineDisplayTextController extends Controller
{
    public function store(LineDisplayTextRequest $request){
        $validated = $request->validated();

        if($validated["is_show"] == "0"){
            $validated["text"] = "";
        }

        $line_display_text = LineDisplayText::where("admin_id", $validated["admin_id"])->first();

        if($line_display_text){
            $text = "";
            if($validated["is_show"] == "1"){
                $text = $validated["text"];
            }
            $line_display_text->update(["text" => $text, "is_show" => $validated["is_show"]]);
        }else{
            LineDisplayText::create($validated); 
        }

        
        return redirect()->route("account.show", ["id" => $validated["admin_id"]])->with("success", "追加時テキストの変更に成功しました。"); 
    }
}
