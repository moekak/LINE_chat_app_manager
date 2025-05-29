{{-- アカウント追加モーダル --}}
<section class='modal__container js_modal js_loader hidden' id='js_create_account_modal' style='width: 640px;'>
      <div class='modal-header'>
            <h2>アカウント追加</h2>
      </div>
      <div class='modal-content'>
            @if ($errors->any())
                  <div class='alert alert-danger alert_danger_container js_alert_danger' role='alert'>
                        <ul>
                              @foreach ($errors->all() as $error)
                              <li class='alert_danger'>{{$error}}</li>
                              @endforeach  
                        </ul>   
                  </div>  
            @endif
            <form action='{{route('account.create')}}' method='post' id='js_add_account_form'>
                  @csrf
                  <div class='row'>
                        <div class='col-md-6'>
                              <label for='formGroupExampleInput' class='form-label'>アカウント名 <span style='color: red; font-size: 13px;'>※</span></label>
                              <input type='text' class='form-control js_account_name_input' id='formGroupExampleInput' name='account_name' value='{{old('account_name')}}'>
                        </div>  
                        <div class='col-md-6'>
                              <label for='formGroupExampleInput' class='form-label'>チャネルシークレット<span style='color: red; font-size: 13px;'>※</span></label>
                              <input type='text' class='form-control js_channel_secret_input' id='formGroupExampleInput' name='channelsecret' value='{{old('channelsecret')}}'>
                        </div> 
                  </div>
                  <div class='row'>
                        <div class='col-12'>
                              <label for='formGroupExampleInput' class='form-label'>チャネルアクセストークン<span style='color: red; font-size: 13px;'>※</span></label>
                              <input type='text' class='form-control js_channel_access_token_input' id='formGroupExampleInput' name='channelaccesstoken' value='{{old('channelaccesstoken')}}'>
                        </div>  
                  </div>
      
                  
                  <div class='row'>
                        <div class='col-md-6'>
                              <label for='formGroupExampleInput' class='form-label js_url_input'>友達追加用URL <span style='color: red; font-size: 13px;'>※</span></label>
                              <input type='text' class='form-control js_' id='formGroupExampleInput' name='account_url' value='{{old('account_url')}}'>
                        </div>
                        <div class='col-md-6'>
                              <label for='formGroupExampleInput' class='form-label '>ステータス <span style='color: red; font-size: 13px;'>※</span></label>
                              <select class='form-select  js_status_select' name='account_status'>
                                    <option disabled {{ old('account_status') ? '' : 'selected' }}>ステータスを選択してください</option>
                                    @foreach ($account_status as $status)
                                          @if ($status->id !== 4)
                                                <option value='{{ $status->id }}' {{ old('account_status') == $status->id ? 'selected' : '' }}>{{$status->status}}</option> 
                                          @endif
                                    @endforeach
                              </select>
                        </div>
                  </div>
      
                  
                  <div class='col-12'>
                        <label for='formGroupExampleInput' class='form-label'>BANされた時の予備アカウント（友だち保存URLを入力）</label>
                        <select class='form-select mb-3 js_second_account_id' name='second_account_id'>
                              <option value='' disabled {{ old('second_account_id') ? '' : 'selected' }}>予備アカウントを選択してください</option>
                              @foreach ($second_accounts as $account)
                                    <option value='{{ $account->id }}' {{ old('second_account_id') == $account->id ? 'selected' : '' }}>{{$account->account_name}}</option> 
                              @endforeach
                        </select>
                  </div>
                  <div class='col-12' style='text-align: right;'>
                        <button type='submit' class='modal__container-btn'>保存</button> 
                  </div>
            </form> 
      </div>
</section>