@if ($paginator->hasPages())
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
                                    <a href="{{ $url }}" class="page-item">{{ $page }}</a>
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