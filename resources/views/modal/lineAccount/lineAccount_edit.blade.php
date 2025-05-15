{{-- 編集モーダル --}}
<section class='modal__container js_modal js_loader  hidden' id='js_edit_account_modal'>
      <div class='modal-header'>
            <h2>アカウント編集</h2>
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
            <form action='{{route('account.update', ['id'=> 'ID_PLACEHOLDER'])}}' method='post' class='js_edit_account_form'>
                  @csrf
                  <div class='mb-3'>
                        <label for='formGroupExampleInput' class='form-label '>アカウント名 <span style='color: red; font-size: 13px;'>※</span></label>
                        <input type='text' class='form-control js_edit_account_input' id='formGroupExampleInput' name='account_name' value='{{old('account_name')}}'>
                  </div>
                  <div class="mb-3">
                        <label for="formGroupExampleInput" class="form-label">友達追加用URL　<span style="color: red; font-size: 13px;">※</span></label>
                        <input type="text" class="form-control js_edit_url_input" id="formGroupExampleInput" name="account_url" value="{{old("account_url")}}">
                  </div>
                  <label for='formGroupExampleInput' class='form-label'>BANされた時の予備アカウント（友だち保存URLを入力）</label>
                  <select class='form-select  js_select_element mb-3' aria-label='Default select example' name='second_account_id'>
                        <option disabled {{ old('second_account_id') ? 'selected' : '' }} class='js_edit_secondAccount_input'></option>
                        @foreach ($second_accounts as $account)
                              <option value='{{ $account->id }}' {{ old('second_account_id') == $account->id ? 'selected' : '' }} class='js_second_account'>{{$account->account_name}}</option> 
                        @endforeach
                  </select>
                  <input type='hidden' name='account_id' value='{{old('account_id')}}' class='js_account_id_input'>
                  <div style='text-align: right;'>
                        <button type='submit' class='modal__container-btn'>保存</button>   
                  </div>
                  
            </form>
      </div>
      
</section>