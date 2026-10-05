@if ($paginator->hasPages())
  <div class="custom-pagination" style="display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; padding-top: 16px; border-top: 1px solid var(--panel-border);">
    <div style="font-size: 13px; color: var(--text-muted);">
      Menampilkan <strong>{{ $paginator->firstItem() }}</strong> sampai <strong>{{ $paginator->lastItem() }}</strong> dari <strong>{{ $paginator->total() }}</strong> produk
    </div>

    <div style="display: flex; gap: 6px; align-items: center;">
      {{-- Previous Page Link --}}
      @if ($paginator->onFirstPage())
        <span class="btn btn-outline" style="opacity: 0.5; cursor: not-allowed; padding: 6px 12px; font-size: 13px;">« SEBELUMNYA</span>
      @else
        <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-outline" style="padding: 6px 12px; font-size: 13px;">« SEBELUMNYA</a>
      @endif

      {{-- Page Numbers --}}
      @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
          <span style="padding: 6px 10px; color: var(--text-muted); font-size: 13px;">{{ $element }}</span>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
          @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
              <span class="btn btn-gold" style="padding: 6px 12px; font-size: 13px; font-weight: 700;">{{ $page }}</span>
            @else
              <a href="{{ $url }}" class="btn btn-outline" style="padding: 6px 12px; font-size: 13px;">{{ $page }}</a>
            @endif
          @endforeach
        @endif
      @endforeach

      {{-- Next Page Link --}}
      @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-outline" style="padding: 6px 12px; font-size: 13px;">SELANJUTNYA »</a>
      @else
        <span class="btn btn-outline" style="opacity: 0.5; cursor: not-allowed; padding: 6px 12px; font-size: 13px;">SELANJUTNYA »</span>
      @endif
    </div>
  </div>
@else
  <div style="font-size: 13px; color: var(--text-muted); padding-top: 16px; border-top: 1px solid var(--panel-border);">
    Total <strong>{{ $paginator->total() }}</strong> produk
  </div>
@endif
