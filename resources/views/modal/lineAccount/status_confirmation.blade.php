{{-- アカウントステータス変更確認モーダル--}}
<div class='alert alert-danger modal__container_no_bg js_modal hidden js_alert_model js_loader' role='alert'>
      <h4 class='alert-heading'>アラート</h4>
      <p>予備アカウントがまだ設定されていません。設定を完了してから、もう一度お試しください。</p>
      <hr>
      <p class='mb-3'>予備アカウントの設定は下記から行えます。<p>
      <p>
            <button class='btn dropdown-toggle dropdown_style' type='button' data-bs-toggle='collapse' data-bs-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>
                  設定フォーム
            </button>
      </p>
      <div class='collapse' id='collapseExample'>
            <div class='card card-body' style='border: none;'>
                  <form action='{{route('secondAccount.create')}}' method='post'>
                        @csrf
                        <div class='mb-3'>
                              <label for='formGroupExampleInput' style='color: #000;'>BANされた時の予備アカウント</label>
                              <select class='form-select js_select_element mb-3' aria-label='Default select example' name='second_account_id'>
                                    <option disabled {{ old('second_account_id') ? '' : 'selected' }} class='js_edit_secondAccount_input'></option>
                                    @foreach ($second_accounts as $account)
                                          <option value='{{ $account->id }}' {{ old('second_account_id') == $account->id ? 'selected' : '' }} class='js_second_account'>{{$account->account_name}}</option> 
                                    @endforeach
                              </select>
                        </div>
                        <input type='hidden' name='current_account_id' value='{{old('current_account_id')}}' id='js_edit_second_account_id'>
                        <button type='submit' class='btn' style='background-color: #2088d7; border: none; color: white;'>保存</button>
                  </form>
            </div>
      </div>
</div>