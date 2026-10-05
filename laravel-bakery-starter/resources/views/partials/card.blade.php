<a class="card" href="{{ route('category', $slug) }}">
  <span class="ph"><img src="{{ asset('images/'.\Illuminate\Support\Str::slug($item[0]).'.jpg') }}" alt="" loading="lazy" onerror="this.remove()"></span>
  <span class="n">{{ $item[0] }}</span>
  <span class="p">{{ !empty($from) ? 'From ' : '' }}Rp {{ number_format($item[1], 0, ',', '.') }}</span>
</a>
