@extends('layouts.default')
@section('style')
<link rel="stylesheet" href="{{ asset('css/broadcastMessageList.css') }}">
@endsection
@section('main')
<div class="container">
    <h1 style="padding: 20px 0px; font-size: 27px;">一斉配信履歴</h1>
    <div class="search-filter-section">
        <form class="search-row" method="POST" action="{{route("search.message")}}">
            @csrf
            <div class="search-input">
                <input type="text" name="search" placeholder="メッセージを検索...">
                <input type="hidden" name="admin_id" value="{{$adminId}}">
                <span class="search-icon">🔍</span>
            </div>
            <button class="submit">検索</button>
        </form>
        
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
            @foreach ($broadcastMessages as $messages)
                <div class="message-item">
                    <div class="message-header">

                        <div class="message-date">{{ $messages[0]->created_at->format("Y-m-d H:i") }}</div>
                        <div>
                            <span class="toggle-icon">▶</span>
                            {{ $messages[0]->resource_type == "broadcast_text" ? 
                            (strlen($messages[0]->resource) > 100 ? substr($messages[0]->resource, 0, 100) . '...' : $messages[0]->resource) 
                            : "画像" }}
                        </div>
                    </div>
                    <div class="message-content">
                        <div class="chat-messages">
                            @foreach ($messages as $message)
                                <div class="chat-bubble message-sent">
                                    @if ($message->resource_type === "broadcast_text")
                                        <p>{{$message->resource}}</p>
                                    @elseif($message->resource_type === "broadcast_img")
                                        <img src="{{ asset('storage/images/'.$message->resource) }}">
                                    @endif
                                    
                                    <span class="message-time">{{$message->created_at->format("H:i")}}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
            <!-- メッセージ1 -->

            
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
@endsection

@section('script')
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
@endsection