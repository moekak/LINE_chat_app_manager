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
                <input type="text" name="search" placeholder="メッセージを検索..." id="search_input">
                <input type="hidden" name="admin_id" value="{{$adminId}}">
                <span class="search-icon">🔍</span>
            </div>
            <button class="submit button disabled js_message_search">検索</button>
            <a href="{{ route('broadcast_message.list', ['id' => $adminId]) }}" class="reset-button">すべて表示</a>
        </form>
        
        <form class="filter-row" method="POST" action="{{route("search.date")}}">
            @csrf
            <div class="filter-label">配信期間:</div>
            <div class="date-range">
                <input type="date" name="start_date" class="js_start_date">
                <span>〜</span>
                <input type="date" name="end_date"  class="js_end_date">
                <input type="hidden" name="admin_id" value="{{$adminId}}">
            </div>
            <button class="submit button disabled js_date_search">検索</button>
        </form>
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
                            (mb_strlen($messages[0]->resource) > 100 ? mb_substr($messages[0]->resource, 0, 100) . '...' : $messages[0]->resource) 
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
                                        <img src="{{ Storage::disk('s3')->url('images/' . $message->resource) }}" alt="" class="broadcast_message_img">
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
    @if(isset($paginator))
        {{ $paginator->links('pagination::custom') }}
    @endif
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


    const messageSearchBtn = document.querySelector(".js_message_search");
    const dateSearchBtn = document.querySelector(".js_date_search");

    document.getElementById("search_input").addEventListener("input", (e)=>{
        const value = e.target.value
        messageSearchBtn.classList.toggle("disabled", value.length <= 0)
    })


    let hasStartValue = false
    let hasEndValue = false
    const startDate = document.querySelector(".js_start_date")
    const endDate = document.querySelector(".js_end_date")

    startDate.addEventListener("input", (e)=>{
        const value = e.target.value
        hasStartValue = value.length > 0
        hasValidData()
    })
    
    endDate.addEventListener("input", (e)=>{
        const value = e.target.value
        hasEndValue = value.length > 0
        hasValidData()
    })

    function hasValidData(){
        dateSearchBtn.classList.toggle("disabled", !hasStartValue || !hasEndValue)
    }
    

</script>
@endsection