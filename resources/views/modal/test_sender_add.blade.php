
<section class="sender_modal js_modal js_loader hidden" id="js_test_sender_add">
      <div class="modal-header">
            <h2 class="modal-title">LINEテスト送信ユーザー設定</h2>
            <button class="close-btn">&times;</button>
      </div>
      <div class="modal-body">
            <div class="tab-container">
                  <div class="tab-buttons">
                        <button class="tab-btn active" data-tab="qr-tab">QRコード</button>
                        <button class="tab-btn" data-tab="url-tab">友達追加URL</button>
                  </div>
                  
                  <div id="qr-tab" class="tab-content active">
                        <div class="qr-container">
                              <img src="https://qr-official.line.me/gs/M_161lxwjo_GW.png?oat_content=qr" alt="LINE友達追加QRコード" class="qr-code">
                              <p class="info-text">このQRコードをスキャンして友達追加してください</p>
                              <p class="info-text">追加後、自動的にテスト送信ユーザーとして登録されます</p>
                        </div>
                  </div>
                  
                  <div id="url-tab" class="tab-content">
                        <p class="info-text">以下のURLを共有して友達追加することもできます</p>
                        <div class="url-input-group">
                              <input type="text" value="https://lin.ee/1a2b3c4d" readonly>
                              <button class="copy-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                              </button>
                        </div>
                        <p class="info-text">または直接LINEアプリで開く</p>
                        <button class="btn btn-primary" style="width: 100%; margin-top: 10px; display: flex; justify-content: center; align-items: center;">
                              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
                              LINEで開く
                        </button>
                  </div>
            </div>
      </div>
            <div class="modal-footer">
                  <button class="btn btn-primary">保存</button>
            </div>
      </div>
      
      <div class="notification-container">
            <div class="notification">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="check-icon"><polyline points="20 6 9 17 4 12"></polyline></svg>
                  URLがコピーされました
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
      
      // Copy URL functionality
      document.querySelector('.copy-btn').addEventListener('click', () => {
            // document.querySelector(".notification").classList.a
            const urlInput = document.querySelector('input[type="text"]');
            urlInput.select();
            document.execCommand('copy');
      });
      
      // Close modal functionality
      document.querySelector('.close-btn').addEventListener('click', () => {
            document.querySelector('.modal').style.display = 'none';
      });
      
      // Cancel button functionality
      document.querySelector('.btn-secondary').addEventListener('click', () => {
            document.querySelector('.modal').style.display = 'none';
      });
      

</script>