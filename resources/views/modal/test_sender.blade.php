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
            <div class="test_user_list hidden">
                  <div class="sender-top">
                        <p>テストユーザー</p>
                        <button class="sender-all-btn">一括テスト送信</button>
                  </div>
                  <form class="sender-search-form" onsubmit="event.preventDefault();" role="search">
                        <input class="sender-search-input" id="search" type="search" placeholder="LINE名で検索"  required />
                        <button type="submit" class="sender-search-button"><i class="fa-solid fa-magnifying-glass"></i></button>    
                  </form>

                  <table class="sender-list-table">
                        <thead>
                              <tr>
                                    <th class="sender-check">
                                          <input class="form-check-input" type="checkbox" id="select-all">
                                    </th>
                                    <th class="sender-info sender-th">テストユーザー名</th>
                                    <th class="sender-th">操作</th>
                              </tr>
                        </thead>
                        <tbody id="js_category_list">
                              @foreach($test_senders as $sender)
                                    <tr class="sender-item-row">
                                          <td>
                                                <input class="form-check-input select-checkbox" type="checkbox">

                                          </td>
                                          <td class="sender-item-td">
                                                <div class="sender_user-box">
                                                      @if ($sender->user_picture)
                                                            <img src="{{$sender->user_picture}}" alt="" class="sender_user-icon">
                                                      @else
                                                            <img src="{{asset("img/user (1).png")}}" alt="" class="sender_user-icon">
                                                      @endif
                                    
                                                      <p>{{$sender->account_name}}</p> 
                                                </div>
                                                
                                          </td>
                                          <td>
                                                <div class="sender-operation-area">
                                                      <button class="sender-user-button">
                                                            テスト送信
                                                            <i class="fa-solid fa-paper-plane"></i>
                                                      </button>
                                                      <button class="sender-user-button">
                                                            <i class="fa-solid fa-user-pen"></i>
                                                      </button>
                                                      <button class="sender-user-button">
                                                            <i class="fa-solid fa-trash"></i>
                                                      </button>
                                                </div>
                                                {{-- 操作用のボタンなどを入れる --}}
                                                
                                          </td>
                                    </tr>

                              @endforeach
                        </tbody>
                  </table>
            </div>

            {{-- テストユーザー追加 --}}
            <div class="test_user_add">
                  <div class="alert alert-success" role="alert">
                        下記のQRコードから友達登録してください
                  </div>
                  <img src="https://qr-official.line.me/gs/M_161lxwjo_GW.png?oat_content=qr">   
            </div>
	
</section>


<script>
      document.addEventListener('DOMContentLoaded', function () {
            const selectAllCheckbox = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.select-checkbox');
      
            selectAllCheckbox.addEventListener('change', function () {
                  checkboxes.forEach(cb => cb.checked = selectAllCheckbox.checked);
            });
      });
</script>