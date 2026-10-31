@php
  $id = is_array($item) ? 0 : $item->id;
  $name = is_array($item) ? $item[0] : $item->name;
  $price = is_array($item) ? $item[1] : $item->price;
  $imageUrl = is_array($item) 
    ? asset('images/' . \Illuminate\Support\Str::slug($item[0]) . '.jpg') 
    : $item->image_url;
  $secondaryUrl = is_array($item)
    ? asset('images/cat-' . ($slug ?? 'cakes') . '.jpg')
    : $item->secondary_image_url;
  $itemSlug = is_array($item) ? ($slug ?? 'cakes') : ($item->category->slug ?? ($slug ?? 'cakes'));
  $includedItems = is_array($item) ? [] : ($item->included_items ?? []);
  $flavor = is_array($item) ? 'Original' : ($item->flavor ?? 'Original');
  $size = is_array($item) ? 'Standard' : ($item->size ?? 'Standard');
  $type = is_array($item) ? 'Gift Set' : ($item->type ?? 'Gift Set');
  $availability = is_array($item) ? 'in_stock' : ($item->availability ?? 'in_stock');
  $gallery = is_array($item) ? [$imageUrl, $secondaryUrl] : ($item->gallery_urls ?? [$imageUrl]);
  
  $productReviews = [];
  if (!is_array($item) && $item->relationLoaded('reviews') && $item->reviews->isNotEmpty()) {
      $productReviews = $item->reviews->take(4)->map(function ($r) {
          return [
              'name' => $r->reviewer_name,
              'avatar' => $r->avatar_url ?? ('https://ui-avatars.com/api/?name=' . urlencode($r->reviewer_name) . '&background=d97706&color=ffffff&bold=true'),
              'rating' => $r->rating,
              'title' => $r->title ?: 'Ulasan Pelanggan VIP',
              'body' => $r->body,
              'date' => $r->created_at ? $r->created_at->diffForHumans() : 'Baru saja'
          ];
      })->toArray();
  } else {
      $productReviews = [
          [
              'name' => 'Adeline Wijaya',
              'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80',
              'rating' => 5,
              'title' => 'Rasa Sangat Lezat & Kemasan Mewah VIP!',
              'body' => 'Bahan-bahannya terasa sangat berkualitas tinggi dan butter impornya begitu harum. Packaging hampers eksklusif dan harum banget.',
              'date' => '2 hari yang lalu'
          ],
          [
              'name' => 'Clarissa Putri',
              'avatar' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=150&auto=format&fit=crop&q=80',
              'rating' => 5,
              'title' => 'Pengiriman Cepat & Fast Response',
              'body' => 'Teksturnya lembut dan rasanya pas di lidah, pengiriman dedicated courier sangat aman tanpa cacat.',
              'date' => '4 hari yang lalu'
          ]
      ];
  }
  $prodSlug = is_array($item) ? \Illuminate\Support\Str::slug($name) : ($item->slug ?: \Illuminate\Support\Str::slug($name));
  $detailUrl = route('product.detail', $prodSlug);
@endphp
<div class="card card-hover-flip shop-card-luxury" data-product-id="{{ $id }}">
  <div class="shop-card-top">
    <a href="{{ $detailUrl }}" class="card-clickable-area" title="{{ $name }}">
      <span class="ph card-ph">
        <img src="{{ $imageUrl }}" alt="{{ $name }}" class="card-img-primary" loading="lazy" decoding="async" onerror="this.onerror=null; this.src='{{ asset('images/cat-hampers.jpg') }}';">
        @if ($secondaryUrl && $secondaryUrl !== $imageUrl)
          <img src="{{ $secondaryUrl }}" alt="{{ $name }} Alternative" class="card-img-secondary" loading="lazy" decoding="async" onerror="this.onerror=null; this.src='{{ asset('images/cat-mooncake.jpg') }}';">
        @endif
      </span>
      <span class="n card-title-text">{{ $name }}</span>
      <span class="p card-price-text">{{ !empty($from) ? 'Mulai dari ' : '' }}Rp {{ number_format($price, 0, ',', '.') }}</span>
      <span class="card-detail-hint">🎁 Detail Produk →</span>
    </a>
  </div>
  @if ($id > 0)
    <form action="{{ route('cart.add') }}" method="POST" class="ajax-add-cart-form card-cart-form" onclick="event.stopPropagation();">
      @csrf
      <input type="hidden" name="product_id" value="{{ $id }}">
      <input type="hidden" name="quantity" value="1">
      <button type="submit" class="card-btn-cart" title="Tambah ke keranjang belanja">
        <span class="btn-desktop-text">🛒 + TAMBAH KE KERANJANG</span>
        <span class="btn-mobile-text">🛒 + Keranjang</span>
      </button>
    </form>
  @else
    <a href="{{ $detailUrl }}" class="card-btn-cart" style="text-decoration: none; margin-top: 10px;">
      <span class="btn-desktop-text">Lihat Detail</span>
      <span class="btn-mobile-text">Detail</span>
    </a>
  @endif
</div>
