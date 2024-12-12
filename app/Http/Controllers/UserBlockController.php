<?php

namespace App\Http\Controllers;

use App\Models\BlockChatUser;
use App\Models\ChatUser;
use App\Models\LineAccount;
use App\Models\UserEntity;
use App\Services\MessageCountService;
use App\Services\MessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserBlockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {

        // ブロックユーザー一覧を取得
        // 配列を返す(pluck)
        $messageService = new MessageService();
        $block_users_ids = BlockChatUser::where("is_blocked", "1")->pluck("chat_user_id");
        $blockPeriodsList = $block_users_ids->mapWithKeys(function ($userId) use($messageService){
            return [$userId => $messageService->hasUserBlockHistroy($userId)];
        });
        
        $block_users = ChatUser::whereIn("id", $block_users_ids)
            ->where('account_id', $id)
            ->select([
                'chat_users.account_id',
                'chat_users.created_at',
                'chat_users.line_name',
                'chat_users.id',
            ])
            ->get()
            ->map(function ($user) use ($blockPeriodsList, $messageService) {
                // ユーザーのブロック期間を取得
                $blockPeriods = $blockPeriodsList->get($user->id, []);

                if (!empty($blockPeriods)) {
                    // 動的条件を生成
                    $conditions = $messageService->buildBlockConditions($blockPeriods, 'created_at');
                } else {
                    $conditions = null; // 条件がない場合
                }

                // 最新メッセージ日時を動的に取得
                $latestMessageDate = DB::table('user_messages')
                    ->select('created_at')
                    ->where('user_id', $user->id)
                    ->when($conditions, function ($query, $conditions) {
                        // ブロック期間があれば条件を追加
                        return $query->whereRaw("NOT ($conditions)");
                    })
                    ->unionAll(
                        DB::table('user_message_images')
                            ->select('created_at')
                            ->where('user_id', $user->id)
                            ->when($conditions, function ($query, $conditions) {
                                return $query->whereRaw("NOT ($conditions)");
                            })
                    )
                    ->max('created_at');

                // 結果をフォーマット
                $user->latest_message_date = $latestMessageDate
                    ? Carbon::parse($latestMessageDate)->format('Y-m-d H:i')
                    : null;

                return $user;
            });

        // userUUIDと管理者アカウントのデータを取得
        $user = Auth::user();
        $user_uuid = UserEntity::where("related_id", $id)->value("entity_uuid");
        $account_data = LineAccount::where("user_id", $user->id)->get();


        return view("admin.block_account", ["block_lists" => $block_users, "user_uuid"=> $user_uuid, "account_data" => $account_data]);
    }

    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id)
    {
        $block_user = BlockChatUser::where("chat_user_id", $id)->latest()->first();
        $block_user->update(["is_blocked" => '0']);

        $admin_id = ChatUser::where("id", $id)->value("account_id");
        return redirect()->route("account.block.user", ['id' => $admin_id])->with("success", "ブロック解除に成功しました");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
