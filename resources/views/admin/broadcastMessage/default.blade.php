@extends('layouts.default')
@section('style')
<link rel="stylesheet" href="{{ asset('css/broadcastMessageList.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endsection
@section('main')
<div class="container">
      <h1 style="padding: 0px 0px 5px 0px; font-size: 27px;">一斉配信履歴</h1>
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
                        <input type="text" name="start_date" class="js_start_date" value="{{$startDate ?? ""}}" placeholder="開始期間を選択">
                        <span>〜</span>
                        <input type="text" name="end_date"  class="js_end_date" value="{{$endDate ?? ""}}" placeholder="終了期間を選択">
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

      if(searchInput.value !== ""){
            messageSearchBtn.classList.remove("disabled")
      }

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
      const startDate = document.querySelector(".js_start_date")
      const endDate = document.querySelector(".js_end_date")
      

      let hasStartValue = startDate.value !== ""
      let hasEndValue = endDate.value !== ""
      const searchFormByDate = startDate.closest("form"); // inputを含むフォーム要素を取得
      hasValidData()

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
<script>
      // 日本語ロケールを直接定義
      const Japanese = {
            weekdays: {
                  shorthand: ["日", "月", "火", "水", "木", "金", "土"],
                  longhand: ["日曜日", "月曜日", "火曜日", "水曜日", "木曜日", "金曜日", "土曜日"]
            },
            months: {
                  shorthand: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
                  longhand: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"]
            },
            firstDayOfWeek: 0,
            rangeSeparator: " から ",
            time_24hr: true
      };
      
      // flatpickrの初期化
      document.addEventListener('DOMContentLoaded', function() {
            flatpickr.localize(Japanese);
            
            // 以下、既存のflatpickr初期化コード
            flatpickr(".js_start_date", {
                  dateFormat: "Y-m-d",
                  allowInput: true,
                  parseDate: (datestr, format) => {
                        if (/^\d{8}$/.test(datestr)) {
                        const year = datestr.substring(0, 4);
                        const month = datestr.substring(4, 6);
                        const day = datestr.substring(6, 8);
                        return new Date(`${year}-${month}-${day}`);
                        }
                        return flatpickr.parseDate(datestr, format);
                  }
            });
            
            flatpickr(".js_end_date", {
                  dateFormat: "Y-m-d",
                  allowInput: true,
                  parseDate: (datestr, format) => {
                        if (/^\d{8}$/.test(datestr)) {
                              const year = datestr.substring(0, 4);
                              const month = datestr.substring(4, 6);
                              const day = datestr.substring(6, 8);
                              return new Date(`${year}-${month}-${day}`);
                        }
                        return flatpickr.parseDate(datestr, format);
                  }
            });
      });
</script>
@endsection