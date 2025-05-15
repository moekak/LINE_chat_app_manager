{{-- 一斉配信モーダル --}}
<section class='modal__container js_modal hidden broadcasting_message_modal' id='js_messageSetting_modal' style='width: 530px;'>
      <div class='modal-header'>
            <h2>一斉送信メッセージ</h2>
      </div>
      <div class='modal-content'>
            <div class='alert alert-danger alert_danger_container js_alert_danger hidden js_broadcast_error' role='alert'>
                  <ul>
                        <li class='alert_danger js_error_txt'>メッセージを入力して保存ボタンを押してください。<br> または画像を選択してください。</li> 
                  </ul>   
            </div>  
      
            <div class='broadcast_message_area'>
                  <div class='broascast_message-list'>
                        <div id='accordion' class='js_accordion_wrapper'>
                  </div>
                  <div class='mb-3 mt-3'>
                        <label for='formGroupExampleInput' class='form-label'>本文 <span style='color: red; font-size: 13px;'>※</span></label>
                        <textarea class='form-control js_message_input' id='exampleFormControlTextarea1' rows='6' name='body'>{{ old('body') }}</textarea>
                        <div class='mt-3'></div>
                        <input type='file' class='js_upload' id='fileInput' accept='image/png, image/jpeg'>
                  </div>
                  <input type='hidden' name='admin_account_id' id='js_account_id'>
                  <div class='broadcast_message_submit_btn-box'>
                        <button class="modal__container-btn" id="js_sender_list">
                              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                              テスト送信
                        </button>
                        <div style="display: flex; gap: 10px;">
                              <button type='submit' class='modal__container-btn js_message_display_btn disabled_btn'>追加</button>  
                              <button type='submit' class='modal__container-btn js_message_submit_btn disabled_btn'>保存</button>  
                        </div>
                  </div>
            </div>      
      </div>
      
</section>