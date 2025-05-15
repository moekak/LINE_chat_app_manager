
<section class="sender_list-wrapper hidden" id="js_test_sender">
      <div class="sender_list_modal">
            <div class="modal-header">
                  <h2 class="modal-title">LINEテスト送信ユーザー設定</h2>
                  <button class="close-btn">&times;</button>
            </div>
            <div>
                  <div class="loader-wrapper relative hidden">
                        <div class="loader_test_sender"></div>   
                  </div>
                  <div class="modal-body">
                        <div class="tab-container">
                              <div id="users-tab" class="user-list">
                                    <div class="user-list">
                                          <div class="user-list-header">
                                                <span>現在のテストユーザー (3)</span>
                                                <div class="user-list-actions">
                                                      <button class="refresh-btn">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="refresh-icon"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                                                            更新
                                                      </button>
                                                </div>
                                          </div>
                                          
                                          <div class="selection-controls">
                                                <label class="select-all-container">
                                                <input type="checkbox" id="select-all-users">
                                                      <span class="checkmark"></span>
                                                      全て選択
                                                </label>
                                                <div class="bulk-actions">
                                                      <span id="selected-count">0人選択中</span>
                                                      <button id="send-to-selected" class="action-button js_sending_btn" disabled>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                                                            選択したユーザーに送信
                                                      </button>
                                                </div>
                                          </div>
            
                                          @foreach ($test_senders as $sender)
                                                <div class="user-item">
                                                      <label class="user-checkbox">
                                                            <input type="checkbox" class="user-select" data-user-id="{{$sender->user_id}}">
                                                            <span class="checkmark"></span>
                                                      </label>
                                                      @if ($sender->user_picture)
                                                            <img src="{{$sender->user_picture}}" alt="" class="user-avatar">
                                                      @else
                                                            <img src="{{ asset('img/user (1).png') }}" alt="" class="user-avatar">
                                                      @endif
                                                      <div class="user-info">
                                                            <h4 class="user-name">{{$sender->account_name}}</h4>
                                                            <p class="user-id">ID: {{$sender->user_id}}</p>
                                                      </div>
                                                      <div class="user-actions">
                                                            <button class="action-btn single-send js_send_individual_btn" title="このユーザーにテスト送信" data-user-id="{{$sender->user_id}}">
                                                                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                                                            </button>
                                                            <button class="action-btn" title="削除">
                                                                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                            </button>
                                                      </div>
                                                </div>
                                          @endforeach
                                    </div>
                              </div>
                        </div>
                  </div>
                  <div class="modal-footer">
                        <button class="btn btn-cancel" id="js_return_btn">戻る</button>
                        <button class="btn btn-primary disabled_btn js_sending_btn">送信</button>
                  </div>
            </div>

      </div>
</section>

