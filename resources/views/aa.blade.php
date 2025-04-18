<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>一斉配信メッセージ履歴</title>
    <style>
        :root {
            --primary-color: #4a6fdc;
            --secondary-color: #f0f4ff;
            --accent-color: #ff6b6b;
            --text-color: #333;
            --light-gray: #f5f5f5;
            --border-color: #ddd;
            --success-color: #28a745;
            --message-sent: #e1f5fe;
            --message-received: #f1f1f1;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Arial, 'Hiragino Kaku Gothic ProN', 'Hiragino Sans', Meiryo, sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            color: var(--text-color);
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .search-filter-section {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        
        .search-row, .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 15px;
            align-items: center;
        }
        
        .search-input {
            flex: 1;
            min-width: 250px;
            position: relative;
        }
        
        input[type="text"], 
        input[type="date"], 
        select {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 14px;
        }
        
        input[type="text"] {
            padding-right: 40px;
        }
        
        .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }
        
        .date-range {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .filter-label {
            font-weight: bold;
            color: var(--text-color);
            min-width: 80px;
        }
        
        .button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        
        .button:hover {
            background-color: #3a5fc2;
        }
        
        .button.secondary {
            background-color: #6c757d;
        }
        
        .button.secondary:hover {
            background-color: #5a6268;
        }
        
        .message-list {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        
        .message-list-header {
            display: grid;
            grid-template-columns: 190px auto;
            background-color: var(--primary-color);
            color: white;
            padding: 15px 20px;
            font-weight: bold;
        }
        
        .message-list-body {
            max-height: 600px;
            overflow-y: auto;
        }
        
        .message-item {
            border-bottom: 1px solid var(--border-color);
        }
        
        .message-item:last-child {
            border-bottom: none;
        }
        
        .message-header {
            display: grid;
            grid-template-columns: 190px auto;
            padding: 15px 20px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .message-header:hover {
            background-color: var(--secondary-color);
        }
        
        .message-content {
            display: none;
            padding: 0 20px 15px 90px;
            background-color: #fafafa;
        }
        
        .message-status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
        }
        
        .status-sent {
            background-color: var(--success-color);
            color: white;
        }
        
        .status-draft {
            background-color: #ffc107;
            color: #212529;
        }
        
        .status-error {
            background-color: var(--accent-color);
            color: white;
        }
        
        .message-checkbox {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            gap: 5px;
        }
        
        .page-item {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: white;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .page-item:hover {
            background-color: var(--secondary-color);
        }
        
        .page-item.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        .actions-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        
        .actions-left, .actions-right {
            display: flex;
            gap: 10px;
        }
        
        .chat-bubble {
            position: relative;
            padding: 10px 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            max-width: 80%;
            word-wrap: break-word;
        }
        
        .chat-messages {
            display: flex;
            flex-direction: column;
            padding: 30px
        }
        
        .chat-message {
            display: flex;
            margin-bottom: 10px;
        }
        
        .message-sent {
            background-color: var(--message-sent);
            align-self: flex-end;
            border-bottom-right-radius: 0;
        }
        
        .message-received {
            background-color: var(--message-received);
            align-self: flex-start;
            border-bottom-left-radius: 0;
        }
        
        .message-time {
            font-size: 12px;
            color: #888;
            margin-top: 4px;
            display: block;
            text-align: right;
        }
        
        .toggle-icon {
            display: inline-block;
            width: 20px;
            height: 20px;
            text-align: center;
            line-height: 20px;
            transition: transform 0.3s;
        }
        
        .toggle-icon.open {
            transform: rotate(90deg);
        }
        
        .badge {
            display: inline-block;
            min-width: 20px;
            padding: 0 6px;
            font-size: 12px;
            font-weight: 700;
            line-height: 20px;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            background-color: var(--accent-color);
            border-radius: 10px;
            margin-left: 5px;
        }
        
        /* モバイル対応 */
        @media (max-width: 768px) {
            .message-list-header {
                display: none;
            }
            
            .message-header {
                display: grid;
                grid-template-columns: 50px auto 90px;
            }
            
            .message-header > div:nth-child(3) {
                display: none;
            }
            
            .search-row, .filter-row {
                flex-direction: column;
                align-items: stretch;
            }
            
            .date-range {
                flex-direction: column;
                gap: 5px;
            }
            
            .actions-bar {
                flex-direction: column;
                gap: 10px;
            }
            
            .actions-left, .actions-right {
                justify-content: center;
            }
            
            .message-content {
                padding: 0 10px 15px 60px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="search-filter-section">
            <div class="search-row">
                <div class="search-input">
                    <input type="text" placeholder="メッセージを検索...">
                    <span class="search-icon">🔍</span>
                </div>
                <button class="button">検索</button>
            </div>
            
            <div class="filter-row">
                <div class="filter-label">配信期間:</div>
                <div class="date-range">
                    <input type="date">
                    <span>〜</span>
                    <input type="date">
                </div>
            </div>
        </div>

        
        <div class="message-list">
            <div class="message-list-header">
                <div>日付</div>
                <div>メッセージ</div>
            </div>
            
            <div class="message-list-body">
                <!-- メッセージ1 -->
                <div class="message-item">
                    <div class="message-header">

                        <div class="message-date">2025/04/15 14:30</div>
                        <div>
                            <span class="toggle-icon">▶</span>
                            今月のシステムアップデートに関するお知らせです。主な変更点は以下の通りです。
                        </div>
                    </div>
                    <div class="message-content">
                        <div class="chat-messages">
                            <div class="chat-bubble message-sent">
                                今月のシステムアップデートに関するお知らせです。主な変更点は以下の通りです。
                                <span class="message-time">14:30</span>
                            </div>
                            <div class="chat-bubble message-sent">
                                1. 新機能の追加: ダッシュボード画面に分析機能が追加されました。これにより、各種データの可視化が可能になります。
                                <span class="message-time">14:31</span>
                            </div>
                            <div class="chat-bubble message-sent">
                                2. パフォーマンスの向上: データ処理速度が約30%向上しました。大量のデータも円滑に処理できるようになります。
                                <span class="message-time">14:32</span>
                            </div>
                            <div class="chat-bubble message-sent">
                                3. セキュリティの強化: 最新のセキュリティ対策を導入し、より安全にサービスをご利用いただけるようになりました。
                                <span class="message-time">14:33</span>
                            </div>
                            <div class="chat-bubble message-sent">
                                詳細については、ヘルプセンターの「アップデート情報」をご確認ください。ご不明点があれば、サポートまでお問い合わせください。
                                <span class="message-time">14:34</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="message-item">
                    <div class="message-header">
                        <div class="message-date">2025/04/15 14:30</div>
                        <div>
                            <span class="toggle-icon">▶</span>
                            今月のシステムアップデートに関するお知らせです。主な変更点は以下の通りです。
                        </div>
                    </div>
                    <div class="message-content">
                        <div class="chat-messages">
                            <div class="chat-bubble message-sent">
                                今月のシステムアップデートに関するお知らせです。主な変更点は以下の通りです。
                                <span class="message-time">14:30</span>
                            </div>
                            <div class="chat-bubble message-sent">
                                1. 新機能の追加: ダッシュボード画面に分析機能が追加されました。これにより、各種データの可視化が可能になります。
                                <span class="message-time">14:31</span>
                            </div>
                            <div class="chat-bubble message-sent">
                                2. パフォーマンスの向上: データ処理速度が約30%向上しました。大量のデータも円滑に処理できるようになります。
                                <span class="message-time">14:32</span>
                            </div>
                            <div class="chat-bubble message-sent">
                                3. セキュリティの強化: 最新のセキュリティ対策を導入し、より安全にサービスをご利用いただけるようになりました。
                                <span class="message-time">14:33</span>
                            </div>
                            <div class="chat-bubble message-sent">
                                詳細については、ヘルプセンターの「アップデート情報」をご確認ください。ご不明点があれば、サポートまでお問い合わせください。
                                <span class="message-time">14:34</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                
            </div>
        </div>
        
        <div class="pagination">
            <div class="page-item">＜</div>
            <div class="page-item active">1</div>
            <div class="page-item">2</div>
            <div class="page-item">3</div>
            <div class="page-item">4</div>
            <div class="page-item">5</div>
            <div class="page-item">＞</div>
        </div>
    </div>

    <script>

        // メッセージヘッダーのクリックでコンテンツを開閉
        document.querySelectorAll('.message-header').forEach(function(header) {
            header.addEventListener('click', function(e) {
                // チェックボックス自体がクリックされた場合は開閉しない
                if (e.target.type === 'checkbox') return;
                
                // 開閉状態を切り替え
                const content = this.nextElementSibling;
                const toggleIcon = this.querySelector('.toggle-icon');
                
                if (content.style.display === 'block') {
                    content.style.display = 'none';
                    toggleIcon.classList.remove('open');
                } else {
                    content.style.display = 'block';
                    toggleIcon.classList.add('open');
                }
            });
        });
        
        // チェックボックスのクリックイベントの伝播を停止
        document.querySelectorAll('.message-checkbox input[type="checkbox"]').forEach(function(checkbox) {
            checkbox.addEventListener('click', function(e) {
                e.stopPropagation();
                
                // 全選択の状態を更新
                updateSelectAllState();
            });
        });
        
        // 全選択のチェックボックスの状態を更新する関数
        function updateSelectAllState() {
            let allCheckboxes = document.querySelectorAll('.message-item input[type="checkbox"]');
            let checkedCheckboxes = document.querySelectorAll('.message-item input[type="checkbox"]:checked');
            
            document.getElementById('select-all').checked = allCheckboxes.length === checkedCheckboxes.length;
        }
        
        // ページネーション機能（デモ用）
        document.querySelectorAll('.page-item').forEach(function(item) {
            item.addEventListener('click', function() {
                // すべてのページアイテムからアクティブクラスを削除
                document.querySelectorAll('.page-item').forEach(function(pageItem) {
                    pageItem.classList.remove('active');
                });
                
                // クリックされたアイテムにアクティブクラスを追加
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>