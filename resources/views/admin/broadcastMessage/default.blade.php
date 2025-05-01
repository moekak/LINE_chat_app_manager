@extends('layouts.default')
@section('style')
<link rel="stylesheet" href="{{ asset('css/broadcastMessageList.css') }}">
@endsection
@section('main')
<div class="container">
      <h1 style="padding: 20px 0px; font-size: 27px;">一斉配信履歴</h1>
      <div class="search-filter-section">
            <form class="search-row" method="GET" action="{{ route('search.message', ['id' => $adminId]) }}">
                  <div class="search-input">
                        <input type="text" name="search" placeholder="メッセージを検索..." id="search_input" value="{{$searchWord ?? ""}}">
                        <span class="search-icon">🔍</span>
                  </div>
                  <button class="submit button disabled js_message_search">検索</button>
                  <a href="{{ route('broadcast_message.list', ['id' => $adminId]) }}" class="reset-button">すべて表示</a>
            </form>

            <form class="filter-row" method="GET" action="{{ route('search.date', ['id' => $adminId]) }}">
                  <div class="filter-label">配信期間:</div>
                  <div class="date-range">
                        <input type="date" name="start_date" class="js_start_date">
                        <span>〜</span>
                        <input type="date" name="end_date"  class="js_end_date">
                  </div>
                  <button class="submit button disabled js_date_search">検索</button>
            </form>
      </div>
      @yield('broadcastMessage')
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
    const searchInput = document.getElementById("search_input");
    const searchForm = searchInput.closest("form"); // inputを含むフォーム要素を取得

    searchInput.addEventListener("input", (e)=>{
        const value = e.target.value
        messageSearchBtn.classList.toggle("disabled", value.length <= 0)
    })

    if (searchForm) {
    searchForm.addEventListener("submit", (e) => {
        // 入力が空の場合、送信をキャンセル
        if (searchInput.value.length <= 0) {
            e.preventDefault();
        }
    });
}


    let hasStartValue = false
    let hasEndValue = false
    const startDate = document.querySelector(".js_start_date")
    const endDate = document.querySelector(".js_end_date")
    const searchFormByDate = startDate.closest("form"); // inputを含むフォーム要素を取得

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

    searchFormByDate.addEventListener("submit", (e)=>{
        if(!hasStartValue || !hasEndValue){
            e.preventDefault();
        }
    })

    function hasValidData(){
        dateSearchBtn.classList.toggle("disabled", !hasStartValue || !hasEndValue)
    }
    

</script>
@endsection