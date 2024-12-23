<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTitleRequest;
use App\Models\PageTitle;
use Illuminate\Http\Request;

class CreateTitleController extends Controller
{
    public function store(CreateTitleRequest $request){
        $validatd = $request->validated();

        $pageTitle = PageTitle::where("admin_id", $request["admin_id"])->first();

        if($pageTitle){
            $pageTitle->update(["title" => $validatd["title"]]);
        }else{
            PageTitle::create($validatd);
        }
        return redirect()->route("account.show", ["id" => $validatd["admin_id"]])->with("success", "タイトル表示の更新に成功しました。");  
    }
}
