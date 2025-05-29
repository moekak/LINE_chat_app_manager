<?php

namespace App\Http\Controllers;


use App\Models\BroadcastMessage;
use App\Services\Message\BroadcastMessageService;
use Illuminate\Http\Request;


class BroadcastMessageController extends Controller
{

    public function index(string $id){
        $broadcastMessages = BroadcastMessage::getBroadcastMessage($id);
        return view('admin.broadcastMessage.broadcastMessageList', ["broadcastMessages" => $broadcastMessages["messages"], "paginator" => $broadcastMessages["paginator"], "adminId" => $id]);
    }

    public function store(Request $request, $admin_id){
        $broadcastMessageService = new BroadcastMessageService($admin_id, $request);
        return $broadcastMessageService->store();
    }


    public function searchByMessage($admin_id, Request $request){
        $search = $request->query('search');
        $broadcastMessages = BroadcastMessage::searchByMessage($search, $admin_id);
        return view('admin.broadcastMessage.broadcastMessageSearchList', ["searchWord" => $search, "broadcastMessages" => $broadcastMessages["messages"], "paginator" => $broadcastMessages["paginator"], "adminId" => $admin_id]);
    }

    public function searchByDate($admin_id, Request $request){
        $start_date = $request->query('start_date');
        $end_date = $request->input("end_date");

        $broadcastMessages = BroadcastMessage::searchByDate($start_date, $end_date, $admin_id);
        return view('admin.broadcastMessage.broadcastMessageSearchList', ["startDate" => $start_date, "endDate" => $end_date,"broadcastMessages" => $broadcastMessages["messages"], "paginator" => $broadcastMessages["paginator"], "adminId" => $admin_id]);
    }

}
