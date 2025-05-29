{{-- 画像のURL編集モーダル --}}
<section class='image_edit_modal hidden' id='js_image_edit_modal'>
      <div class='crop_loader hidden'></div>
      <div class='preview_box'>
            <div class='preview_box-top'>
                  <p>プレビュー</p>
                  <small>※送信時に画像が劣化する場合があります。</small>
            </div>
            <div class='prview_box-area'>
                  <div class='preview_box-img' id='image-container'>
                        <img src='' alt='' id='image'>
                  </div> 
            </div>
            
            <div class='preview_box-desc'>
                  <div>対応形式: .png/.jpg</div>
                  <div>最大データ容量: 5MBまで</div>
            </div>
      </div>
      <div class='url_setting_area'>
            <p class='url_setting_txt'>タップ時アクションの利用</p>
            <div class='radio_btn'>
                  <div class='form-check'>
                        <input class='form-check-input' value='off' type='radio' name='choice' id='flexRadioDefault1' checked>
                        <label class='form-check-label' for='flexRadioDefault1'>利用しない</label>
                  </div>
                  <div class='form-check'>
                        <input class='form-check-input' value='on' type='radio' name='choice' id='flexRadioDefault2'>
                        <label class='form-check-label' for='flexRadioDefault2'>利用する</label>
                  </div> 
            </div>
            <div class='url_setting_wrapper' id='js_url_setting'>
                  <div style='margin-top: 20px;'></div>
                  <p class='url_setting_txt'>URL設定</p>
                  <input type='url' name='url' id='js_url_input' class='url_input' placeholder='https://example.com' maxlength='2048'>
                  <small class='hidden js_url_error' style='color: red; font-size: 12px; padding-top: 14px;'>URLの形式にしてください</small><br>
                  <small class='hidden js_image_error' style='color: red; font-size: 12px; padding-top: 14px;'></small>
                  <div class='btn_area'>
                        <button id='js_changeImg_btn'><label for='fileInput' class='change_img'>画像変更</label></button>
                        <button id='js_change_area' class='disabled_btn'>選択範囲確定</button>
                  </div>
                  <small style='color: gray;font-size: 12px;'>※画像を変更する際は、タップ時アクションが設定されている場合設定が解除されます。</small><br>
                  <div style='margin-top: 10px;'></div>
                  <small style='color: gray; font-size: 12px; margin-top: 3px;'>※範囲を選択してから「選択範囲確定」ボタンを押してください。</small><br>
                  <div style='margin-top: 10px;'></div>
                  <small style='color: gray;  font-size: 12px; margin-top: 3px;'>※再度範囲を選択したい場合は、「選択範囲変更」ボタンを押してください。</small>  
            </div>
            <div style='margin-top: 14px;'>
                  <button class='preview_submit_btn disabled_btn' id='js_preview_submit_btn'>送信</button>
            </div>
      </div>
</section>