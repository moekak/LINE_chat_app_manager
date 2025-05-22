
{{-- アカウント削除確認モーダル --}}
<section class='modal__container js_modal hidden confirm_modal' id='js_delete_test_user' style='width: 500px;'>
      <h2 class='modal__container-ttl' style='color: red; font-weight: bold;'>本当に削除しますか？</h2>
      <p>テストユーザーを削除してもいいですか？</p>
      {{-- <div style='margin-top: 15px'></div>
      <p>アカウント名：<span id='js_account_name'></span></p> --}}
      <div class='delete_account-btn-container'>
            <div class='cancel-btn btn-box js_delete_account_cancel'>
                  <button class='delete_account-btn js_cancel_test_user_delete' type="button" readonly>キャンセル</button>
            </div>
            <div method='post' class='btn-box delete-btn'>
                  <button class='delete_test-account-btn' type='button' style='color: #fff;' readonly>削除</button>
            </div>
      </div>
</section>