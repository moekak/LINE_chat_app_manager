@if ($paginator->hasPages())
      @php
            // コントローラからビューに渡す前に、または直接ビュー内で
            $paginator = $paginator->appends(request()->only(['start_date', 'end_date', 'search']));
      @endphp
      <div class="pagination">
            {{-- 前へ --}}
            @if ($paginator->onFirstPage())
                  <div class="page-item disabled">＜</div>
            @else
            <a href="{{ $paginator->previousPageUrl() }}" class="page-item">＜</a>
            @endif

            {{-- ページ番号 --}}
            @foreach ($elements as $element)
                  {{-- "三点リーダー" セパレータ --}}
                  @if (is_string($element))
                        <div class="page-item disabled">{{ $element }}</div>
                  @endif

                  {{-- ページ番号の配列 --}}
                  @if (is_array($element))
                        @foreach ($element as $page => $url)
                              @if ($page == $paginator->currentPage())
                                    <div class="page-item active">{{ $page }}</div>
                              @else
                                    <a href="{{ $url . (request('search') ? '&search='.request('search') : '') . (request('start_date') ? '&start_date='.request('start_date') : '') . (request('end_date') ? '&end_date='.request('end_date') : '') }}" class="page-item">{{ $page }}</a>
                              @endif
                        @endforeach
                  @endif
            @endforeach

            {{-- 次へ --}}
            @if ($paginator->hasMorePages())
                  <a href="{{ $paginator->nextPageUrl() }}" class="page-item">＞</a>
            @else
                  <div class="page-item disabled">＞</div>
            @endif
      </div>
@endif