
<section class="sender_list_modal js_modal js_loader hidden" id="js_test_sender">
      <div class="modal-header">
            <h2 class="modal-title">LINEテスト送信ユーザー設定</h2>
            <button class="close-btn">&times;</button>
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
                                          <button id="send-to-selected" class="action-button" disabled>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                                                選択したユーザーに送信
                                          </button>
                                          <button id="send-to-all" class="action-button">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                                全員に送信
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
                                                <button class="action-btn single-send" title="このユーザーにテスト送信">
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
                  <button class="btn btn-cancel">キャンセル</button>
                  <button class="btn btn-primary" id="js_sending_btn">保存</button>
            </div>
      </div>

</section>

<script>
      // Tab switching functionality
      document.querySelectorAll('.tab-btn').forEach(button => {
            button.addEventListener('click', () => {
                  // Remove active class from all tabs
                  document.querySelectorAll('.tab-btn').forEach(btn => {
                        btn.classList.remove('active');
                  });
                  document.querySelectorAll('.tab-content').forEach(content => {
                        content.classList.remove('active');
                  });
                  
                  // Add active class to clicked tab
                  button.classList.add('active');
                  document.getElementById(button.dataset.tab).classList.add('active');
            });
      });
      

      // Close modal functionality
      document.querySelector('.close-btn').addEventListener('click', () => {
            document.querySelector('.sender_list_modal').style.display = 'none';
      });
      
      // Cancel button functionality
      document.querySelector('.btn-cancel').addEventListener('click', () => {
            document.querySelector('.sender_list_modal').style.display = 'none';
      });
      
      // User selection functionality
      const selectAllCheckbox = document.getElementById('select-all-users');
      const userCheckboxes = document.querySelectorAll('.user-select');
      const selectedCountEl = document.getElementById('selected-count');
      const sendToSelectedBtn = document.getElementById('send-to-selected');
      const sendToAllBtn = document.getElementById('send-to-all');
      
      // Update selected count and button state
      function updateSelectedCount() {
            const selectedCount = document.querySelectorAll('.user-select:checked').length;
            selectedCountEl.textContent = `${selectedCount}人選択中`;
            
            if (selectedCount > 0) {
                  sendToSelectedBtn.disabled = false;
            } else {
                  sendToSelectedBtn.disabled = true;
            }
            
            // Update select all checkbox state
            if (selectedCount === userCheckboxes.length) {
                  selectAllCheckbox.checked = true;
                  selectAllCheckbox.indeterminate = false;
            } else if (selectedCount === 0) {
                  selectAllCheckbox.checked = false;
                  selectAllCheckbox.indeterminate = false;
            } else {
                  selectAllCheckbox.indeterminate = true;
            }

            // Update user-item selected class
            userCheckboxes.forEach(checkbox => {
                  const userItem = checkbox.closest('.user-item');
                  if (checkbox.checked) {
                        userItem.classList.add('selected');
                  } else {
                        userItem.classList.remove('selected');
                  }
            });
      }
      
      // Select all checkbox
      selectAllCheckbox.addEventListener('change', () => {
            userCheckboxes.forEach(checkbox => {
                  checkbox.checked = selectAllCheckbox.checked;
            });
            updateSelectedCount();
      });
      
      // Individual checkboxes
      userCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedCount);
      });
      
      // Initialize count
      updateSelectedCount();
      
      // Send to selected button
      sendToSelectedBtn.addEventListener('click', () => {
            const selectedUserIds = Array.from(document.querySelectorAll('.user-select:checked')).map(checkbox => checkbox.dataset.userId);

          // Show notification
          const notification = document.createElement('div');
          notification.className = 'notification';
          notification.innerHTML = `
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="check-icon"><polyline points="20 6 9 17 4 12"></polyline></svg>
              ${selectedUserIds.length}人のユーザーにテスト送信しました
          `;
          document.querySelector('.notification-container').appendChild(notification);
          
          // Remove notification after 3 seconds
          setTimeout(() => {
              notification.remove();
          }, 3000);
      });
      
      // Send to all button
      sendToAllBtn.addEventListener('click', () => {
          console.log('Sending to all users');
          // Here you would implement the actual sending logic
          
          // Show notification
          const notification = document.createElement('div');
          notification.className = 'notification';
          notification.innerHTML = `
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="check-icon"><polyline points="20 6 9 17 4 12"></polyline></svg>
              全てのユーザーにテスト送信しました
          `;
          document.querySelector('.notification-container').appendChild(notification);
          
          // Remove notification after 3 seconds
          setTimeout(() => {
              notification.remove();
          }, 3000);
      });
      
      // Single user send buttons
      document.querySelectorAll('.single-send').forEach(button => {
          button.addEventListener('click', (e) => {
              const userItem = e.target.closest('.user-item');
              const userName = userItem.querySelector('.user-name').textContent;
              const userId = userItem.querySelector('.user-select').dataset.userId;
              
              console.log(`Sending to single user: ${userName} (${userId})`);
              // Here you would implement the actual sending logic
              
              // Show notification
              const notification = document.createElement('div');
              notification.className = 'notification';
              notification.innerHTML = `
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="check-icon"><polyline points="20 6 9 17 4 12"></polyline></svg>
                  ${userName}にテスト送信しました
              `;
              document.querySelector('.notification-container').appendChild(notification);
              
              // Remove notification after 3 seconds
              setTimeout(() => {
                  notification.remove();
              }, 3000);
          });
      });
  </script>