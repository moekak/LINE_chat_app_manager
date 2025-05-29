{{-- 一斉配信モーダル閉じる前の確認 --}}
<section class='modal__container js_modal hidden confirm_modal' id='js_broadcast_confirm_modal' style='width: 500px;'>
      <h2 class='modal__container-ttl' style='color: red; font-weight: bold;'>設定画面を閉じてよろしいですか？</h2>
      <p>一斉配信設定画面を閉じてよろしいですか？閉じると、設定した全てのデータが完全に消去され、元に戻すことはできません。</p>
      <div style='margin-top: 15px'></div>
      <div class='delete_account-btn-container'>
            <div class='btn-box'>
                  <button id='js_cancel_btn' readonly style='width: 100%;'>キャンセル</button>
            </div>
            <div class='cancel-btn btn-box  delete-btn '>
                  <button style='color: #fff;width: 100%;' readonly ><a href='{{route('dashboard')}}' style='width: 100%; display: block;'>閉じる</a></button>
            </div>
      </div>
</section>
