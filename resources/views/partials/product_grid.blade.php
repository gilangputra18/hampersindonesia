@forelse ($products as $item)
  @include('partials.card', ['item' => $item, 'from' => true])
@empty
  <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: #64748b;">
    <div style="font-size: 32px; margin-bottom: 12px;">🥐</div>
    <h3 style="font-size: 18px; font-weight: 600; color: #334155; margin-bottom: 6px;">Tidak ada produk yang sesuai filter</h3>
    <p style="font-size: 14px;">Coba atur ulang atau kurangi filter untuk melihat produk lainnya.</p>
  </div>
@endforelse
