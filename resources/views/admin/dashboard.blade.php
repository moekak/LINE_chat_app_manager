@extends('layouts.default')
@section('styles')
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('title2')
      <h2>Dashboard</h2><p></p>
@endsection
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


      @foreach ($line_accounts as $status => $accounts)
            <div class="dashboard__wrapper-table-area">

                  <div class="dashboard__wrapper-table-box p-20">
                        <div class="dashboard__wrapper-table-ttl">
                              <p>{{$status}}アカウント一覧</p>
                              {{-- <img src="{{asset("img/icons8-create-50.png")}}" alt="" class="dashboard__wrapper-table-icon"> --}}
                        </div>  
                  </div>
                  <div class="dashboard__wrapper-table">
                        <table class="table table-striped">
                              <thead>
                                    <tr>
                                          <th scope="col"></th>
                                          <th scope="col">アカウント名</th>
                                          <th scope="col">未読数</th>
                                          <th scope="col">ステータス</th>
                                          <th scope="col">最新受信日</th>
                                          <th scope="col">作成日時</th>
                                          <th scope="col">管理</th>
                                    </tr>
                              </thead>
                              <tbody class="js_table">
                                    
                                    @foreach ($accounts as $account)
                                          <tr class="js_account_id" data-id="{{$account["uuid"]}}">
                                                <th scope="row"><input type="checkbox" id="checkbox3" name="option3" value="3"></th>
                                                <td w20><?= $account["account_name"]?></td>

                                                <td class=" text-center total_message-count">
                                                      <div class="message_count js_mesage_count js_total_count" style="display: {{ $account["total_count"] > 0 ? 'flex' : 'none' }}; font-weight: bold;">
                                                            {{ $account["total_count"] }}
                                                      </div>
                                                </td>
                                                <td data-id={{$account["id"]}} class="js_status" style="color: #008000; cursor: pointer;">
                                                      <div class="btn-group">
                                                            <button class="btn btn-secondary btn-sm dropdown-toggle js_status_btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                  {{$status}}
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                  @foreach ($account_status as $other_status)
                                                                        @if ($other_status->status !== $status)
                                                                              <li class="dropdown-item js_status_choices" data-current-status={{$status}} data-status-name={{$other_status->status}}  data-status-id={{$other_status->id}} data-account-id={{{$account["id"]}}}>{{$other_status->status}}</li> 
                                                                        @endif
                                                                  @endforeach
                                                            </ul>
                                                      </div>
                                                </td>
                                                <td class="js_latest_message_date">{{$account["latest_message_date"] ? $account["latest_message_date"]->format('Y-m-d H:i') : ""}}</td>
                                                <td>{{$account["created_at"]->format('Y-m-d H:i')}}</td>
                                                <td class="operation">
                                                      <a href="{{route("account.show", ["id" => $account["id"]])}}"><button class="operation_icon"><img src="{{asset("img/icons8-user-24.png")}}" alt=""></button></a>
                                                      <button class="operation_icon js_edit_account_btn" data-id=<?= $account["id"]?>><img src="{{asset("img/icons8-edit-24.png")}}" alt=""></button>
                                                      <button class="operation_icon js_send_message_btn" data-id=<?= $account["id"]?>><img src="{{asset("img/icons8-send-24.png")}}" alt=""></button>
                                                      <button class="operation_icon js_delete_account_btn" type="submit" data-id=<?= $account["id"]?> data-name={{$account["account_name"]}}><img src="{{asset("img/icons8-delete-24.png")}}" alt=""></button>
                                                </td>
                                          </tr>
                                    @endforeach
                              
                              </tbody>
                        </table>
                  </div> 
            </div>
      @endforeach
</div>

{{-- 追加モーダル --}}
<section class="modal__container js_modal js_loader hidden" id="js_create_account_modal">
      <h3 class="modal__container-ttl">追加</h3>
      @if ($errors->any())
      <div class="alert alert-danger alert_danger_container js_alert_danger" role="alert">
            <ul>
                  @foreach ($errors->all() as $error)
                  <li class="alert_danger">{{$error}}</li>
                  @endforeach  
            </ul>   
      </div>  
      @endif
      <form action="{{route("account.create")}}" method="post" id="js_edit_device_form">
            @csrf
            <div class="mb-3">
                  <label for="formGroupExampleInput" class="form-label">アカウント名 <span style="color: red; font-size: 13px;">※</span></label>
                  <input type="text" class="form-control js_device_account_input" id="formGroupExampleInput" name="account_name" value="{{old("account_name")}}">
            </div>
            <div class="mb-3">
                  <label for="formGroupExampleInput" class="form-label ">アカウントID <span style="color: red; font-size: 13px;">※</span></label>
                  <input type="text" class="form-control" id="formGroupExampleInput" name="account_id" value="{{old("account_id")}}">
            </div>
            <div class="mb-3">
                  <label for="formGroupExampleInput" class="form-label ">友達追加用URL <span style="color: red; font-size: 13px;">※</span></label>
                  <input type="text" class="form-control" id="formGroupExampleInput" name="account_url" value="{{old("account_url")}}">
            </div>
            <label for="formGroupExampleInput" class="form-label ">ステータス <span style="color: red; font-size: 13px;">※</span></label>
            <select class="form-select mb-3" name="account_status">
                  <option disabled {{ old('account_status') ? '' : 'selected' }}>ステータスを選択してください</option>
                  @foreach ($account_status as $status)

                        <option value="{{ $status->id }}" {{ old('account_status') == $status->id ? 'selected' : '' }}>{{$status->status}}</option> 
                  @endforeach
            </select>
            <div class="mb-3">
                  <label for="formGroupExampleInput" class="form-label">BANされた時の予備アカウント（友だち追加URLを入力）</label>
                  <select class="form-select mb-3" name="second_account_id">
                        <option disabled {{ old('second_account_id') ? '' : 'selected' }}>予備アカウントを選択してください</option>
                        @foreach ($second_accounts as $account)
                              <option value="{{ $account->id }}" {{ old('second_account_id') == $account->id ? 'selected' : '' }}>{{$account->account_name}}</option> 
                        @endforeach
                  </select>
                  {{-- <input type="text" class="form-control" id="formGroupExampleInput" name="account_url" value="{{old("account_url")}}"> --}}
            </div>
            <button type="submit" class="modal__container-btn">追加</button>
      </form>
</section>
{{-- 編集モーダル --}}
<section class="modal__container js_modal js_loader  hidden" id="js_edit_account_modal">
      <h3 class="modal__container-ttl">編集</h3>
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
                  <label for="formGroupExampleInput" class="form-label">友達追加用URL　<span style="color: red; font-size: 13px;">※</span></label>
                  <input type="text" class="form-control js_edit_url_input" id="formGroupExampleInput" name="account_url" value="{{old("account_url")}}">
            </div>
            <label for="formGroupExampleInput" class="form-label">BANされた時の予備アカウント（友だち追加URLを入力）</label>
            <select class="form-select js_select_element mb-3" aria-label="Default select example" name="second_account_id">
                  <option disabled {{ old('second_account_id') ? '' : 'selected' }} class="js_edit_secondAccount_input"></option>
                  @foreach ($second_accounts as $account)
                        <option value="{{ $account->id }}" {{ old('second_account_id') == $account->id ? 'selected' : '' }} class="js_second_account">{{$account->account_name}}</option> 
                  @endforeach
            </select>
            <input type="hidden" name="account_id" value="{{old("account_id")}}" class="js_account_id_input">
            <button type="submit" class="modal__container-btn">更新</button>
      </form>
</section>

{{-- 一斉配信モーダル --}}
<section class="modal__container js_modal hidden broadcasting_message_modal" id="js_boradcasting_modal" style="width: 530px;">
      <h3 class="modal__container-ttl">一斉送信メッセージ</h3>

      <div class="alert alert-danger alert_danger_container js_alert_danger hidden js_broadcast_error" role="alert">
            <ul>
                  <li class="alert_danger">メッセージを入力して追加ボタンを押してください。<br> または画像を選択してください。</li> 
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
                  <input type="file" class="js_upload">
            </div>
            <input type="hidden" name="admin_account_id" id="js_account_id">
            <div class="broadcast_message_submit_btn-box">
                  <button type="submit" class="modal__container-btn js_message_display_btn disabled_btn">追加</button>  
                  <button type="submit" class="modal__container-btn js_message_submit_btn">送信</button>  
            </div>
      </div>      
</section>

{{-- アカウント削除確認モーダル --}}
<section class="modal__container js_modal hidden" id="js_delete_account_modal" style="width: 500px;">
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
                        <button type="submit" class="btn" style="background-color: #20a8d7; border: none; color: white;">追加</button>
                  </form>
            </div>
      </div>
</div>

{{-- LINE送信文言変更モーダル --}}
<section class="modal__container js_modal js_loader hidden" id="js_update_message_modal">
      <h3 class="modal__container-ttl">LINE送信文言変更</h3>
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
            <button type="submit" class="modal__container-btn">追加</button>
      </form>
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

                  let form = document.getElementById('js_edit_account_form');
                  let action = form.getAttribute('action');
                  action = action.replace(':id', document.querySelector(".js_account_id_input").value);
                  form.setAttribute('action', action)
            </script>  
      @endif
@endif
@endsection