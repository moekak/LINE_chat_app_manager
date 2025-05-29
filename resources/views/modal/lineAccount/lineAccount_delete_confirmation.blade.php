
{{-- アカウント削除確認モーダル --}}
<section class='modal__container js_modal hidden confirm_modal' id='js_delete_account_modal' style='width: 500px;'>
      <h2 class='modal__container-ttl' style='color: red; font-weight: bold;'>本当に削除しますか？</h2>
      <p>本当にこのアカウントを削除しますか？削除すると、全てのデータが完全に消去され、元に戻すことはできません。この操作は取り消せませんので、十分にご注意ください。</p>
      <div style='margin-top: 15px'></div>
      <p>アカウント名：<span id='js_account_name'></span></p>
      <div class='delete_account-btn-container'>
            <div class='cancel-btn btn-box js_delete_account_cancel'>
                  <button class='delete_account-btn' readonly>キャンセル</button>
            </div>
            <form action='{{route('account.destroy', ['id' => 'ID_PLACEHOLDER'])}}' method='post' class='btn-box delete-btn js_delete_account_from'>
                  @csrf
                  @method('DELETE')
                  <button class='delete_account-btn' type='submit' style='color: #fff;' readonly>削除</button>
            </form>
      </div>
</section>