@extends('layouts.default')


@section('main')
<input type="hidden" value="{{$user->id}}" id="js_admin_account_id">
<div class="dashboard__wrapper-main bg-lightgray">
      @php
            // セッションに 'success' キーが存在するかチェック
            if (session("success")) {
                  // 成功メッセージがある場合、アラートを表示
                  $style = "block";
                  $text = session("success");
            } else {
                  // 成功メッセージがない場合、アラートを非表示に
                  $style = "none";
                  $text = "";
            }
      @endphp
      <div class="alert alert-success alert-dismissible fade show success_alert" style="display: {{$style}}; width: max-content;" role="alert" id="js_alert_success">
            {{$text}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>     

      <div class="dashboard__wrapper-table-area">
            <div class="dashboard__wrapper-table-box p-20">
                  <div class="dashboard__wrapper-table-ttl">
                        <p>使用中アカウント一覧</p>
                  </div>  
            </div>
            <div class="dashboard__wrapper-table" data-status-id="1">
                  <table class="table table-striped">
                        <thead>
                              <tr>
                                    <th scope="col">アカウント名</th>
                                    <th scope="col">未読数</th>
                                    <th scope="col">ステータス</th>
                                    <th scope="col">最新受信日</th>
                                    <th scope="col">作成日時</th>
                                    <th scope="col" style="width: 20px;">管理</th>
                              </tr>
                        </thead>
                        <tbody class="js_table js_parentEl1">
                              
                              @foreach ($active_accounts as $account)
                                    <tr class="js_account_id" data-id={{$account["entity_uuid"]}}>
                                          <td w20 class="account_name" data-simplebar><?= $account["account_name"]?></td>
                                          <td class=" text-center total_message-count">
                                                <div class="message_count js_mesage_count js_total_count" style="display: {{ $account["unread_count"] > 0 ? 'flex' : 'none' }}; font-weight: bold;">
                                                      {{ $account["unread_count"] }}
                                                </div>
                                          </td>
                                          <td data-id={{$account["id"]}} class="js_status" style="color: #008000; cursor: pointer;">
                                                <div class="btn-group">
                                                      <button class="btn btn-secondary btn-sm dropdown-toggle js_status_btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            使用中
                                                      </button>
                                                      <ul class="dropdown-menu">
                                                            @foreach ($account_status as $other_status)
                                                                  @if ($other_status->status !== "使用中")
                                                                        <li class="dropdown-item js_status_choices" data-current-status="使用中" data-status-name={{$other_status->status}}  data-status-id={{$other_status->id}} data-account-id={{{$account["id"]}}}>{{$other_status->status}}</li> 
                                                                  @endif
                                                            @endforeach
                                                      </ul>
                                                </div>
                                          </td>
                                          <td class="js_latest_message_date">{{$account["latest_message_date"] ?? ""}}</td>
                                          <td>{{$account["created_at"]->format('Y-m-d H:i')}}</td>
                                          <td class="operation">
                                                <a href="{{route("account.show", ["id" => $account["id"]])}}"><button class="operation_icon user-list_icon" title="リスト"><img src="{{asset("img/icons8-user-24.png")}}" alt=""></button></a>
                                                <button class="operation_icon js_send_message_btn relative" data-id=<?= $account["id"]?> title="一斉送信"><img src="{{asset("img/icons8-send-24.png")}}" alt=""></button>
                                                <button class="operation_icon js_edit_account_btn relative" data-id=<?= $account["id"]?> title="情報"><img src="{{asset("img/icons8-edit-24.png")}}" alt=""></button>
                                                <button class="operation_icon js_delete_account_btn relative" type="submit" data-id=<?= $account["id"]?> data-name={{$account["account_name"]}} title="削除"><img src="{{asset("img/icons8-delete-24.png")}}" alt=""></button>
                                          </td>
                                    </tr>
                              @endforeach
                        
                        </tbody>
                  </table>
                  <div class="loader-container">
				<div class="loader2 js_loader hidden"></div>
			</div>
            </div> 
      </div>
      <div class="dashboard__wrapper-table-area">

            <div class="dashboard__wrapper-table-box p-20">
                  <div class="dashboard__wrapper-table-ttl">
                        <p>未使用アカウント一覧</p>
                  </div>  
            </div>
            <div class="dashboard__wrapper-table" data-status-id="2">
                  <table class="table table-striped">
                        <thead>
                              <tr>
                                    <th scope="col">アカウント名</th>
                                    <th scope="col">未読数</th>
                                    <th scope="col">ステータス</th>
                                    <th scope="col">最新受信日</th>
                                    <th scope="col">作成日時</th>
                                    <th scope="col">管理</th>
                              </tr>
                        </thead>
                        <tbody class="js_table js_parentEl2">
                              
                              @foreach ($inactive_accounts as $account)
                                    <tr class="js_account_id" data-id={{$account["entity_uuid"]}}>
                                          <td w20 class="account_name" data-simplebar><?= $account["account_name"]?></td>

                                          <td class=" text-center total_message-count">
                                                <div class="message_count js_mesage_count js_total_count" style="display: {{ $account["unread_count"] > 0 ? 'flex' : 'none' }}; font-weight: bold;">
                                                      {{ $account["unread_count"] }}
                                                </div>
                                          </td>
                                          <td data-id={{$account["id"]}} class="js_status" style="color: #008000; cursor: pointer;">
                                                <div class="btn-group">
                                                      <button class="btn btn-secondary btn-sm dropdown-toggle js_status_btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            未使用
                                                      </button>
                                                      <ul class="dropdown-menu">
                                                            @foreach ($account_status as $other_status)
                                                                  @if ($other_status->status !== "未使用")
                                                                        <li class="dropdown-item js_status_choices" data-current-status="未使用" data-status-name={{$other_status->status}}  data-status-id={{$other_status->id}} data-account-id={{{$account["id"]}}}>{{$other_status->status}}</li> 
                                                                  @endif
                                                            @endforeach
                                                      </ul>
                                                </div>
                                          </td>
                                          <td class="js_latest_message_date">{{$account["latest_message_date"] ?? ""}}</td>
                                          <td>{{$account["created_at"]->format('Y-m-d H:i')}}</td>
                                          <td class="operation">
                                                <a href="{{route("account.show", ["id" => $account["id"]])}}"><button class="operation_icon user-list_icon" title="リスト"><img src="{{asset("img/icons8-user-24.png")}}" alt=""></button></a>
                                                <button class="operation_icon js_send_message_btn relative" data-id=<?= $account["id"]?> title="一斉送信"><img src="{{asset("img/icons8-send-24.png")}}" alt=""></button>
                                                <button class="operation_icon js_edit_account_btn relative" data-id=<?= $account["id"]?> title="情報"><img src="{{asset("img/icons8-edit-24.png")}}" alt=""></button>
                                                <button class="operation_icon js_delete_account_btn relative" type="submit" data-id=<?= $account["id"]?> data-name={{$account["account_name"]}} title="削除"><img src="{{asset("img/icons8-delete-24.png")}}" alt=""></button>
                                          </td>
                                    </tr>
                              @endforeach
                        
                        </tbody>
                  </table>
                  <div class="loader-container">
				<div class="loader2 js_loader hidden"></div>
			</div>
            </div> 
      </div>
      <div class="dashboard__wrapper-table-area">

            <div class="dashboard__wrapper-table-box p-20">
                  <div class="dashboard__wrapper-table-ttl">
                        <p>停止アカウント一覧</p>
                        {{-- <img src="{{asset("img/icons8-create-50.png")}}" alt="" class="dashboard__wrapper-table-icon"> --}}
                  </div>  
            </div>
            <div class="dashboard__wrapper-table" data-status-id="3">
                  <table class="table table-striped">
                        <thead>
                              <tr>
                                    <th scope="col">アカウント名</th>
                                    <th scope="col">未読数</th>
                                    <th scope="col">ステータス</th>
                                    <th scope="col">最新受信日</th>
                                    <th scope="col">作成日時</th>
                                    <th scope="col">管理</th>
                              </tr>
                        </thead>
                        <tbody class="js_table js_parentEl3" >
                              
                              @foreach ($suspended_accounts as $account)
                                    <tr class="js_account_id" data-id={{$account["entity_uuid"]}}>
                                          <td w20 class="account_name" data-simplebar><?= $account["account_name"]?></td>

                                          <td class=" text-center total_message-count">
                                                <div class="message_count js_mesage_count js_total_count" style="display: {{ $account["unread_count"] > 0 ? 'flex' : 'none' }}; font-weight: bold;">
                                                      {{ $account["unread_count"] }}
                                                </div>
                                          </td>
                                          <td data-id={{$account["id"]}} class="js_status" style="color: #008000; cursor: pointer;">
                                                <div class="btn-group">
                                                      <button class="btn btn-secondary btn-sm dropdown-toggle js_status_btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            停止
                                                      </button>
                                                      <ul class="dropdown-menu">
                                                            @foreach ($account_status as $other_status)
                                                                  @if ($other_status->status !== "停止")
                                                                        <li class="dropdown-item js_status_choices" data-current-status="停止" data-status-name={{$other_status->status}}  data-status-id={{$other_status->id}} data-account-id={{{$account["id"]}}}>{{$other_status->status}}</li> 
                                                                  @endif
                                                            @endforeach
                                                      </ul>
                                                </div>
                                          </td>
                                          <td class="js_latest_message_date">{{$account["latest_message_date"] ?? ""}}</td>
                                          <td>{{$account["created_at"]->format('Y-m-d H:i')}}</td>
                                          <td class="operation">
                                                <a href="{{route("account.show", ["id" => $account["id"]])}}"><button class="operation_icon user-list_icon" title="リスト"><img src="{{asset("img/icons8-user-24.png")}}" alt=""></button></a>
                                                <button class="operation_icon js_send_message_btn relative" data-id=<?= $account["id"]?> title="一斉送信"><img src="{{asset("img/icons8-send-24.png")}}" alt=""></button>
                                                <button class="operation_icon js_edit_account_btn relative" data-id=<?= $account["id"]?> title="情報"><img src="{{asset("img/icons8-edit-24.png")}}" alt=""></button>
                                                <button class="operation_icon js_delete_account_btn relative" type="submit" data-id=<?= $account["id"]?> data-name={{$account["account_name"]}} title="削除"><img src="{{asset("img/icons8-delete-24.png")}}" alt=""></button>
                                          </td>
                                    </tr>
                              @endforeach
                        
                        </tbody>
                  </table>
                  <div class="loader-container">
				<div class="loader2 js_loader hidden"></div>
			</div>
            </div> 
      </div>
      <div class="dashboard__wrapper-table-area">

            <div class="dashboard__wrapper-table-box p-20">
                  <div class="dashboard__wrapper-table-ttl">
                        <p>バンアカウント一覧</p>
                  </div>  
            </div>
            <div class="dashboard__wrapper-table" data-status-id="4">
                  <table class="table table-striped">
                        <thead>
                              <tr>
                                    <th scope="col">アカウント名</th>
                                    <th scope="col">未読数</th>
                                    <th scope="col">ステータス</th>
                                    <th scope="col">最新受信日</th>
                                    <th scope="col">作成日時</th>
                                    <th scope="col">管理</th>
                              </tr>
                        </thead>
                        <tbody class="js_table js_parentEl4">
                              
                              @foreach ($banned_accounts as $account)
                                    <tr class="js_account_id" data-id={{$account["entity_uuid"]}}>
                                          <td w20 class="account_name" data-simplebar><?= $account["account_name"]?></td>

                                          <td class=" text-center total_message-count">
                                                <div class="message_count js_mesage_count js_total_count" style="display: {{ $account["unread_count"] > 0 ? 'flex' : 'none' }}; font-weight: bold;">
                                                      {{ $account["unread_count"] }}
                                                </div>
                                          </td>
                                          <td data-id={{$account["id"]}} class="js_status" style="color: #008000; cursor: pointer;">
                                                <div class="btn-group">
                                                      <button class="btn btn-secondary btn-sm dropdown-toggle js_status_btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            バン
                                                      </button>
                                                      <ul class="dropdown-menu">
                                                            @foreach ($account_status as $other_status)
                                                                  @if ($other_status->status !== "バン")
                                                                        <li class="dropdown-item js_status_choices" data-current-status="バン" data-status-name={{$other_status->status}}  data-status-id={{$other_status->id}} data-account-id={{{$account["id"]}}}>{{$other_status->status}}</li> 
                                                                  @endif
                                                            @endforeach
                                                      </ul>
                                                </div>
                                          </td>
                                          <td class="js_latest_message_date">{{$account["latest_message_date"] ?? ""}}</td>
                                          <td>{{$account["created_at"]->format('Y-m-d H:i')}}</td>
                                          <td class="operation">
                                                <a href="{{route("account.show", ["id" => $account["id"]])}}"><button class="operation_icon user-list_icon" title="リスト"><img src="{{asset("img/icons8-user-24.png")}}" alt=""></button></a>
                                                <button class="operation_icon js_send_message_btn relative" data-id=<?= $account["id"]?> title="一斉送信"><img src="{{asset("img/icons8-send-24.png")}}" alt=""></button>
                                                <button class="operation_icon js_edit_account_btn relative" data-id=<?= $account["id"]?> title="情報"><img src="{{asset("img/icons8-edit-24.png")}}" alt=""></button>
                                                <button class="operation_icon js_delete_account_btn relative" type="submit" data-id=<?= $account["id"]?> data-name={{$account["account_name"]}} title="削除"><img src="{{asset("img/icons8-delete-24.png")}}" alt=""></button>
                                          </td>
                                    </tr>
                              @endforeach
                        
                        </tbody>
                  </table>
                  <div class="loader-container">
				<div class="loader2 js_loader hidden"></div>
			</div>
            </div> 
      </div>
      {{-- @endforeach --}}
</div>

{{-- 保存モーダル --}}
<section class="modal__container js_modal js_loader hidden" id="js_create_account_modal" style="width: 640px;">
      <div class="modal-header">
            <h2>アカウント追加</h2>
      </div>
      <div class="modal-content">
            @if ($errors->any())
                  <div class="alert alert-danger alert_danger_container js_alert_danger" role="alert">
                        <ul>
                              @foreach ($errors->all() as $error)
                              <li class="alert_danger">{{$error}}</li>
                              @endforeach  
                        </ul>   
                  </div>  
            @endif
            <form action="{{route("account.create")}}" method="post" id="js_add_account_form">
                  @csrf
                  <div class="row">
                        <div class="col-md-6">
                              <label for="formGroupExampleInput" class="form-label">アカウント名 <span style="color: red; font-size: 13px;">※</span></label>
                              <input type="text" class="form-control js_account_name_input" id="formGroupExampleInput" name="account_name" value="{{old("account_name")}}">
                        </div>  
                        <div class="col-md-6">
                              <label for="formGroupExampleInput" class="form-label">チャネルシークレット<span style="color: red; font-size: 13px;">※</span></label>
                              <input type="text" class="form-control js_channel_secret_input" id="formGroupExampleInput" name="channelsecret" value="{{old("channelsecret")}}">
                        </div> 
                  </div>
                  <div class="row">
                        <div class="col-12">
                              <label for="formGroupExampleInput" class="form-label">チャネルアクセストークン<span style="color: red; font-size: 13px;">※</span></label>
                              <input type="text" class="form-control js_channel_access_token_input" id="formGroupExampleInput" name="channelaccesstoken" value="{{old("channelaccesstoken")}}">
                        </div>  
                  </div>
      
                  
                  <div class="row">
                        <div class="col-md-6">
                              <label for="formGroupExampleInput" class="form-label js_url_input">友達保存用URL <span style="color: red; font-size: 13px;">※</span></label>
                              <input type="text" class="form-control js_" id="formGroupExampleInput" name="account_url" value="{{old("account_url")}}">
                        </div>
                        <div class="col-md-6">
                              <label for="formGroupExampleInput" class="form-label ">ステータス <span style="color: red; font-size: 13px;">※</span></label>
                              <select class="form-select  js_status_select" name="account_status">
                                    <option disabled {{ old('account_status') ? '' : 'selected' }}>ステータスを選択してください</option>
                                    @foreach ($account_status as $status)
                                          @if ($status->id !== 4)
                                                <option value="{{ $status->id }}" {{ old('account_status') == $status->id ? 'selected' : '' }}>{{$status->status}}</option> 
                                          @endif
                                    @endforeach
                              </select>
                        </div>
                  </div>
      
                  
                  <div class="col-12">
                        <label for="formGroupExampleInput" class="form-label">BANされた時の予備アカウント（友だち保存URLを入力）</label>
                        <select class="form-select mb-3 js_second_account_id" name="second_account_id">
                              <option value="" disabled {{ old('second_account_id') ? '' : 'selected' }}>予備アカウントを選択してください</option>
                              @foreach ($second_accounts as $account)
                                    <option value="{{ $account->id }}" {{ old('second_account_id') == $account->id ? 'selected' : '' }}>{{$account->account_name}}</option> 
                              @endforeach
                        </select>
                  </div>
                  <div class="col-12" style="text-align: right;">
                        <button type="submit" class="modal__container-btn">保存</button> 
                  </div>
            </form> 
      </div>
</section>
{{-- 編集モーダル --}}
<section class="modal__container js_modal js_loader  hidden" id="js_edit_account_modal">
      <div class="modal-header">
            <h2>アカウント編集</h2>
      </div>
      <div class="modal-content">
            @if ($errors->any())
                  <div class="alert alert-danger alert_danger_container js_alert_danger" role="alert">
                        <ul>
                              @foreach ($errors->all() as $error)
                              <li class="alert_danger">{{$error}}</li>
                              @endforeach  
                        </ul>   
                  </div>  
            @endif
            <form action="{{route("account.update", ["id"=> "ID_PLACEHOLDER"])}}" method="post" class="js_edit_account_form">
                  @csrf
                  <div class="mb-3">
                        <label for="formGroupExampleInput" class="form-label ">アカウント名 <span style="color: red; font-size: 13px;">※</span></label>
                        <input type="text" class="form-control js_edit_account_input" id="formGroupExampleInput" name="account_name" value="{{old("account_name")}}">
                  </div>
                  <div class="mb-3">
                        <label for="formGroupExampleInput" class="form-label">友達保存用URL　<span style="color: red; font-size: 13px;">※</span></label>
                        <input type="text" class="form-control js_edit_url_input" id="formGroupExampleInput" name="account_url" value="{{old("account_url")}}">
                  </div>
                  <label for="formGroupExampleInput" class="form-label">BANされた時の予備アカウント（友だち保存URLを入力）</label>
                  <select class="form-select  js_select_element mb-3" aria-label="Default select example" name="second_account_id">
                        <option disabled {{ old('second_account_id') ? 'selected' : '' }} class="js_edit_secondAccount_input"></option>
                        @foreach ($second_accounts as $account)
                              <option value="{{ $account->id }}" {{ old('second_account_id') == $account->id ? 'selected' : '' }} class="js_second_account">{{$account->account_name}}</option> 
                        @endforeach
                  </select>
                  <input type="hidden" name="account_id" value="{{old("account_id")}}" class="js_account_id_input">
                  <div style="text-align: right;">
                        <button type="submit" class="modal__container-btn">保存</button>   
                  </div>
                  
            </form>
      </div>
      
</section>

{{-- 一斉配信モーダル --}}
<section class="modal__container js_modal hidden broadcasting_message_modal" id="js_messageSetting_modal" style="width: 530px;">
      <div class="modal-header">
            <h2>一斉送信メッセージ</h2>
      </div>
      <div class="modal-content">
            <div class="alert alert-danger alert_danger_container js_alert_danger hidden js_broadcast_error" role="alert">
                  <ul>
                        <li class="alert_danger js_error_txt">メッセージを入力して保存ボタンを押してください。<br> または画像を選択してください。</li> 
                  </ul>   
            </div>  
      
            <div class="broadcast_message_area">
                  <div class="broascast_message-list">
                        <div id="accordion" class="js_accordion_wrapper">
                  </div>
                  <div class="mb-3 mt-3">
                        <label for="formGroupExampleInput" class="form-label">本文 <span style="color: red; font-size: 13px;">※</span></label>
                        <textarea class="form-control js_message_input" id="exampleFormControlTextarea1" rows="6" name="body">{{ old('body') }}</textarea>
                        <div class="mt-3"></div>
                        <input type="file" class="js_upload" id="fileInput" accept="image/png, image/jpeg">
                  </div>
                  <input type="hidden" name="admin_account_id" id="js_account_id">
                  <div class="broadcast_message_submit_btn-box">
                        <button type="submit" class="modal__container-btn js_message_display_btn disabled_btn">追加</button>  
                        <button type="submit" class="modal__container-btn js_message_submit_btn disabled_btn">保存</button>  
                  </div>
            </div>      
      </div>
      
</section>

{{-- 一斉配信モーダル閉じる前の確認 --}}
<section class="modal__container js_modal hidden confirm_modal" id="js_broadcast_confirm_modal" style="width: 500px;">
      <h2 class="modal__container-ttl" style="color: red; font-weight: bold;">設定画面を閉じてよろしいですか？</h2>
      <p>一斉配信設定画面を閉じてよろしいですか？閉じると、設定した全てのデータが完全に消去され、元に戻すことはできません。</p>
      <div style="margin-top: 15px"></div>
      <div class="delete_account-btn-container">
            <div class="btn-box">
                  <button id="js_cancel_btn" readonly style="width: 100%;">キャンセル</button>
            </div>
            <div class="cancel-btn btn-box  delete-btn ">
                  <button style="color: #fff;width: 100%;" readonly ><a href="{{route("dashboard")}}" style="width: 100%; display: block;">閉じる</a></button>
            </div>
      </div>
</section>


{{-- アカウント削除確認モーダル --}}
<section class="modal__container js_modal hidden confirm_modal" id="js_delete_account_modal" style="width: 500px;">
      <h2 class="modal__container-ttl" style="color: red; font-weight: bold;">本当に削除しますか？</h2>
      <p>本当にこのアカウントを削除しますか？削除すると、全てのデータが完全に消去され、元に戻すことはできません。この操作は取り消せませんので、十分にご注意ください。</p>
      <div style="margin-top: 15px"></div>
      <p>アカウント名：<span id="js_account_name"></span></p>
      <div class="delete_account-btn-container">
            <div class="cancel-btn btn-box js_delete_account_cancel">
                  <button class="delete_account-btn" readonly>キャンセル</button>
            </div>
            <form action="{{route('account.destroy', ['id' => 'ID_PLACEHOLDER'])}}" method="post" class="btn-box delete-btn js_delete_account_from">
                  @csrf
                  @method("DELETE")
                  <button class="delete_account-btn" type="submit" style="color: #fff;" readonly>削除</button>
            </form>
      </div>
</section>
{{-- アカウントステータス変更確認モーダル--}}
<div class="alert alert-danger modal__container_no_bg js_modal hidden js_alert_model js_loader" role="alert">
      <h4 class="alert-heading">アラート</h4>
      <p>予備アカウントがまだ設定されていません。設定を完了してから、もう一度お試しください。</p>
      <hr>
      <p class="mb-3">予備アカウントの設定は下記から行えます。<p>
      <p>
            <button class="btn dropdown-toggle dropdown_style" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                  設定フォーム
            </button>
      </p>
      <div class="collapse" id="collapseExample">
            <div class="card card-body" style="border: none;">
                  <form action="{{route("secondAccount.create")}}" method="post">
                        @csrf
                        <div class="mb-3">
                              <label for="formGroupExampleInput" style="color: #000;">BANされた時の予備アカウント</label>
                              <select class="form-select js_select_element mb-3" aria-label="Default select example" name="second_account_id">
                                    <option disabled {{ old('second_account_id') ? '' : 'selected' }} class="js_edit_secondAccount_input"></option>
                                    @foreach ($second_accounts as $account)
                                          <option value="{{ $account->id }}" {{ old('second_account_id') == $account->id ? 'selected' : '' }} class="js_second_account">{{$account->account_name}}</option> 
                                    @endforeach
                              </select>
                        </div>
                        <input type="hidden" name="current_account_id" value="{{old("current_account_id")}}" id="js_edit_second_account_id">
                        <button type="submit" class="btn" style="background-color: #2088d7; border: none; color: white;">保存</button>
                  </form>
            </div>
      </div>
</div>

{{-- LINE送信文言変更モーダル --}}
<section class="modal__container js_modal js_loader hidden" id="js_update_message_modal">
      <div class="modal-header">
            <h2>LINE送信文言変更</h2>
      </div>
      <div class="modal-content">
            @if ($errors->any())
                  <div class="alert alert-danger alert_danger_container js_alert_danger" role="alert">
                        <ul>
                              @foreach ($errors->all() as $error)
                              <li class="alert_danger">{{$error}}</li>
                              @endforeach  
                        </ul>   
                  </div>  
            @endif
            <form action="{{route('message.update', ['id' => 'ID_PLACEHOLDER'])}}"  method="post" class="js_update_sendingMsg">
                  @csrf
                  <div class="mb-3">
                        <label for="formGroupExampleInput" class="form-label">文言 <span style="color: red; font-size: 13px;">※</span></label>
                        <input type="text" class="form-control js_line_message_input" id="formGroupExampleInput" name="message" value="{{old("message")}}">
                  </div>
                  <div style="text-align: right;">
                        <button type="submit" class="modal__container-btn">保存</button>  
                  </div>
                  
            </form>  
      </div>
      
</section>

{{-- 画像のURL編集モーダル --}}
<section class="image_edit_modal hidden" id="js_image_edit_modal">
      <div class="crop_loader hidden"></div>
      <div class="preview_box">
            <div class="preview_box-top">
                  <p>プレビュー</p>
                  <small>※送信時に画像が劣化する場合があります。</small>
            </div>
            <div class="prview_box-area">
                  <div class="preview_box-img" id="image-container">
                        <img src="" alt="" id="image">
                  </div> 
            </div>
            
            <div class="preview_box-desc">
                  <div>対応形式: .png/.jpg</div>
                  <div>最大データ容量: 5MBまで</div>
            </div>
      </div>
      <div class="url_setting_area">
            <p class="url_setting_txt">タップ時アクションの利用</p>
            <div class="radio_btn">
                  <div class="form-check">
                        <input class="form-check-input" value="off" type="radio" name="choice" id="flexRadioDefault1" checked>
                        <label class="form-check-label" for="flexRadioDefault1">利用しない</label>
                  </div>
                  <div class="form-check">
                        <input class="form-check-input" value="on" type="radio" name="choice" id="flexRadioDefault2">
                        <label class="form-check-label" for="flexRadioDefault2">利用する</label>
                  </div> 
            </div>
            <div class="url_setting_wrapper" id="js_url_setting">
                  <div style="margin-top: 20px;"></div>
                  <p class="url_setting_txt">URL設定</p>
                  <input type="url" name="url" id="js_url_input" class="url_input" placeholder="https://example.com" maxlength="2048">
                  <small class="hidden js_url_error" style="color: red; font-size: 12px; padding-top: 14px;">URLの形式にしてください</small><br>
                  <small class="hidden js_image_error" style="color: red; font-size: 12px; padding-top: 14px;"></small>
                  <div class="btn_area">
                        <button id="js_changeImg_btn"><label for="fileInput" class="change_img">画像変更</label></button>
                        <button id="js_change_area" class="disabled_btn">選択範囲確定</button>
                  </div>
                  <small style="color: gray;font-size: 12px;">※画像を変更する際は、タップ時アクションが設定されている場合設定が解除されます。</small><br>
                  <div style="margin-top: 10px;"></div>
                  <small style="color: gray; font-size: 12px; margin-top: 3px;">※範囲を選択してから「選択範囲確定」ボタンを押してください。</small><br>
                  <div style="margin-top: 10px;"></div>
                  <small style="color: gray;  font-size: 12px; margin-top: 3px;">※再度範囲を選択したい場合は、「選択範囲変更」ボタンを押してください。</small>  
            </div>
            <div style="margin-top: 14px;">
                  <button class="preview_submit_btn disabled_btn" id="js_preview_submit_btn">送信</button>
            </div>
      </div>
</section>
{{-- @include('modal.test_sender') --}}


<section class="modal__container js_modal js_loader" id="js_test_sender">
	<div class="modal-header">
		<h2>テストユーザー</h2>
	</div>
	<div class="modal-content">
		@if ($errors->any())
		<div class="alert alert-danger alert_danger_container js_alert_danger" role="alert">
			<ul>
				@foreach ($errors->all() as $error)
				<li class="alert_danger">{{$error}}</li>
				@endforeach  
			</ul>   
		</div>  
		@endif
            <div class="tabs">
                  <div class="tab active" >一覧・選択</div>
                  <div class="tab">追加</div>
            </div>

            {{-- テストユーザー一覧・選択 --}}
            <div class="test_user_list">
                  <table>
                        <thead>
                              <tr>
                                    <th w40>カテゴリー名</th>
                                    <th>操作</th>
                              </tr>
                        </thead>
                        <tbody id="js_category_list">
                              @foreach($test_senders as $sender)
                                    <tr>
                                          <td class="sender_user-box">
                                                <img src="{{$sender->user_picture}}" alt="" class="sender_user-icon">
                                                <p>{{$sender->account_name}}</p>
                                          </td>
                                          <td>
                                                {{-- 操作用のボタンなどを入れる --}}
                                                <p>aaa</p>
                                          </td>
                                    </tr>
                              @endforeach
                        </tbody>
                  </table>
            </div>

	</div>
	
</section>

@endsection
@section('script')
<script src="{{ mix("js/dashboard.js")}}"></script>
<script src="{{ mix("js/broadcastMessage.js")}}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>

@if ($errors->any())
      @php
            $currentRoute = session('route_name');
      @endphp
      @if ($currentRoute === 'account.create')
            <script>
                  document.getElementById("js_create_account_modal").classList.remove("hidden")
                  document.querySelector(".bg").classList.remove("hidden")
            </script>
      @elseif ($currentRoute === 'account.edit')
            <script>
                  document.getElementById("js_edit_account_modal").classList.remove("hidden")
                  document.querySelector(".bg").classList.remove("hidden")

                  let form = document.querySelector('.js_edit_account_form');
                  let action = form.getAttribute('action');
                  action = action.replace('ID_PLACEHOLDER', document.querySelector(".js_account_id_input").value);
                  form.setAttribute('action', action)
            </script>  
      @elseif ($currentRoute === 'lineMessage.create')
            <script>
                  document.getElementById("js_update_message_modal").classList.remove("hidden")
                  document.querySelector(".bg").classList.remove("hidden")

                  let form = document.querySelector('.js_update_sendingMsg');
                  let action = form.getAttribute('action');
                  action = action.replace('ID_PLACEHOLDER', document.getElementById("js_admin_account_id").value);
                  form.setAttribute('action', action)
            </script>  
      @endif
@endif
@endsection