<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>チャットテンプレート作成モーダル</title>
    <style>
        :root {
            --primary-color: #4a6cf7;
            --secondary-color: #6e8ef9;
            --text-color: #333;
            --border-color: #e1e1e1;
            --background-color: #f9f9f9;
            --modal-bg: #ffffff;
            --danger-color: #f44336;
            --success-color: #4CAF50;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Hiragino Sans', 'Hiragino Kaku Gothic ProN', Meiryo, sans-serif;
        }

        body {
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .modal-container {
            position: relative;
            width: 90%;
            max-width: 800px;
            background-color: var(--modal-bg);
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .modal-header {
            padding: 16px 20px;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .close-btn {
            background: transparent;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            transition: background-color 0.2s;
        }

        .close-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .modal-content {
            padding: 24px;
        }

        .tabs {
            display: flex;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 20px;
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            font-weight: 500;
            color: var(--text-color);
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
        }

        .tab.active {
            color: var(--primary-color);
            border-bottom: 2px solid var(--primary-color);
        }

        /* フォーム要素のスタイル */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: var(--text-color);
        }

        input[type="text"],
        select,
        textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        input[type="text"]:focus,
        select:focus,
        textarea:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .row {
            display: flex;
            gap: 16px;
            margin-bottom: 20px;
        }

        .col {
            flex: 1;
        }

        /* カテゴリ管理 */
        .category-management {
            margin-bottom: 20px;
        }

        .category-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .category-item {
            background-color: var(--background-color);
            padding: 6px 12px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            font-size: 14px;
        }

        .delete-category {
            margin-left: 6px;
            cursor: pointer;
            color: var(--danger-color);
            font-size: 16px;
        }

        .add-category {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .add-category input {
            flex: 1;
        }

        /* ファイルのアップロードエリア */
        .file-upload {
            border: 2px dashed var(--border-color);
            padding: 30px;
            text-align: center;
            border-radius: 6px;
            margin-bottom: 20px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .file-upload:hover {
            border-color: var(--secondary-color);
            background-color: rgba(74, 108, 247, 0.05);
        }

        .file-upload p {
            margin-top: 10px;
            color: #666;
        }

        .upload-icon {
            font-size: 36px;
            color: var(--primary-color);
        }

        /* プレビューエリア */
        .preview-section {
            margin-top: 24px;
            border-top: 1px solid var(--border-color);
            padding-top: 24px;
        }

        .preview-header {
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .preview-toggle {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: var(--primary-color);
            cursor: pointer;
        }

        .preview-container {
            background-color: var(--background-color);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 16px;
            min-height: 200px;
        }

        .preview-message {
            max-width: 80%;
            background-color: white;
            padding: 12px 16px;
            border-radius: 12px;
            border-top-left-radius: 0;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            position: relative;
            margin-left: 12px;
        }

        .preview-message.with-image {
            padding-bottom: 8px;
        }

        .preview-image {
            max-width: 100%;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        /* ボタン */
        .btn-container {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }

        .btn-cancel {
            background-color: var(--background-color);
            color: var(--text-color);
        }

        .btn-cancel:hover {
            background-color: #e5e5e5;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
        }

        /* テンプレート一覧表示 */
        .template-list {
            margin-top: 20px;
        }

        .template-item {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .template-content {
            flex: 1;
        }

        .template-title {
            font-weight: 600;
            margin-bottom: 6px;
        }

        .template-category {
            display: inline-block;
            background-color: var(--background-color);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            margin-bottom: 8px;
        }

        .template-text {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 500px;
        }

        .template-actions {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            background: transparent;
            border: none;
            cursor: pointer;
            color: #666;
            transition: color 0.2s;
        }

        .edit-btn:hover {
            color: var(--primary-color);
        }

        .delete-btn:hover {
            color: var(--danger-color);
        }

        /* レスポンシブ対応 */
        @media (max-width: 768px) {
            .row {
                flex-direction: column;
                gap: 10px;
            }
            
            .template-item {
                flex-direction: column;
            }
            
            .template-actions {
                margin-top: 12px;
                align-self: flex-end;
            }
        }
    </style>
</head>
<body>
    <!-- モーダルコンテナ -->
    <div class="modal-container">
        <!-- モーダルヘッダー -->
        <div class="modal-header">
            <h2>テンプレート作成</h2>
            <button class="close-btn">&times;</button>
        </div>
        
        <!-- モーダルコンテンツ -->
        <div class="modal-content">
            <!-- タブメニュー -->
            <div class="tabs">
                <div class="tab active">新規作成</div>
                <div class="tab">一覧・編集</div>
            </div>
            
            <!-- 新規作成フォーム -->
            <div class="tab-content">
                <div class="form-group">
                    <label for="template-title">テンプレート名</label>
                    <input type="text" id="template-title" placeholder="例: 挨拶文">
                </div>
                
                <div class="category-management">
                    <label>カテゴリ</label>
                    <div class="category-list">
                        <div class="category-item">挨拶 <span class="delete-category">&times;</span></div>
                        <div class="category-item">問い合わせ <span class="delete-category">&times;</span></div>
                        <div class="category-item">依頼 <span class="delete-category">&times;</span></div>
                    </div>
                    <div class="add-category">
                        <input type="text" placeholder="新しいカテゴリを追加">
                        <button class="btn btn-primary">追加</button>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="category-select">カテゴリを選択</label>
                            <select id="category-select">
                                <option value="">カテゴリを選択</option>
                                <option value="greeting">挨拶</option>
                                <option value="inquiry">問い合わせ</option>
                                <option value="request">依頼</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="file-upload">
                    <div class="upload-icon">
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                    </div>
                    <p>画像をドラッグ＆ドロップ、または<br>クリックしてファイルを選択</p>
                </div>
                
                <div class="form-group">
                    <label for="template-message">テンプレートメッセージ</label>
                    <textarea id="template-message" placeholder="例: お問い合わせありがとうございます。担当の者が確認次第、ご連絡いたします。"></textarea>
                </div>
                
                <!-- プレビューセクション -->
                <div class="preview-section">
                    <div class="preview-header">
                        <h3>プレビュー</h3>
                        <div class="preview-toggle">
                            <span>自動更新</span>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="1" y="5" width="22" height="14" rx="7" ry="7"></rect>
                                <circle cx="16" cy="12" r="3"></circle>
                            </svg>
                        </div>
                    </div>
                    <div class="preview-container">
                        <div class="preview-message with-image">
                            <img src="/api/placeholder/400/300" alt="preview image" class="preview-image">
                            <div>お問い合わせありがとうございます。担当の者が確認次第、ご連絡いたします。</div>
                        </div>
                    </div>
                </div>
                
                <!-- ボタン -->
                <div class="btn-container">
                    <button class="btn btn-cancel">キャンセル</button>
                    <button class="btn btn-primary">保存</button>
                </div>
            </div>
        </div>
            
        <!-- 一覧・編集タブ (初期状態では非表示) -->
        <div class="tab-content" >
            <div class="template-list">
                <!-- テンプレートアイテム -->
                <div class="template-item">
                    <div class="template-content">
                        <div class="template-title">挨拶文</div>
                        <div class="template-category">挨拶</div>
                        <div class="template-text">お問い合わせありがとうございます。担当の者が確認次第、ご連絡いたします。</div>
                    </div>
                    <div class="template-actions">
                        <button class="action-btn edit-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                        </button>
                        <button class="action-btn delete-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                <line x1="14" y1="11" x2="14" y2="17"></line>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="template-item">
                    <div class="template-content">
                        <div class="template-title">問い合わせ返信</div>
                        <div class="template-category">問い合わせ</div>
                        <div class="template-text">お問い合わせいただき、ありがとうございます。現在調査中ですので、もう少しお待ちください。</div>
                    </div>
                    <div class="template-actions">
                        <button class="action-btn edit-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                        </button>
                        <button class="action-btn delete-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                <line x1="14" y1="11" x2="14" y2="17"></line>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>