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
<div class="card card-hover-flip"
     style="display: flex; flex-direction: column; justify-content: space-between; height: 100%; border-radius: 12px; overflow: hidden; transition: all 0.3s ease; background: #0c2217; border: 1px solid rgba(212, 175, 55, 0.35); padding: 14px; box-shadow: 0 8px 25px rgba(0,0,0,0.35);">
  <div>
    <a href="{{ $detailUrl }}" class="card-clickable-area" style="text-decoration: none; color: inherit; display: block;">
      <span class="ph" style="border-radius: 8px; overflow: hidden; display: block; position: relative; aspect-ratio: 1/1; border: 1px solid rgba(212, 175, 55, 0.2); background: radial-gradient(circle at center, #1b382b 0%, #0a1811 100%);">
        <img src="{{ $imageUrl }}" alt="{{ $name }}" class="card-img-primary" loading="lazy" decoding="async" onerror="this.onerror=null; this.src='{{ asset('images/cat-hampers.jpg') }}';" style="width: 100%; height: 100%; object-fit: cover; transition: opacity 0.4s ease, transform 0.4s ease;">
        <img src="{{ $secondaryUrl }}" alt="{{ $name }} Alternative" class="card-img-secondary" loading="lazy" decoding="async" onerror="this.onerror=null; this.src='{{ asset('images/cat-mooncake.jpg') }}';" style="width: 100%; height: 100%; object-fit: cover; position: absolute; inset: 0; opacity: 0; transition: opacity 0.4s ease, transform 0.4s ease;">
      </span>
      <span class="n" style="font-family: 'Cormorant Garamond', serif; font-size: 18px; font-weight: 700; color: #fef08a; margin-top: 12px; letter-spacing: 0.5px; display: block;">{{ $name }}</span>
      <span class="p" style="color: #e6ca65; font-size: 15px; font-weight: 700; margin-top: 4px; display: block;">{{ !empty($from) ? 'Mulai dari ' : '' }}Rp {{ number_format($price, 0, ',', '.') }}</span>
      <span style="display: inline-flex; align-items: center; gap: 4px; margin-top: 6px; font-size: 11px; color: #fbbf24; font-weight: 600;">🎁 Lihat Detail Produk →</span>
    </a>
  </div>
  @if ($id > 0)
    <form action="{{ route('cart.add') }}" method="POST" class="ajax-add-cart-form" style="margin-top: 14px;" onclick="event.stopPropagation();">
      @csrf
      <input type="hidden" name="product_id" value="{{ $id }}">
      <input type="hidden" name="quantity" value="1">
      <button type="submit" style="width: 100%; padding: 11px 14px; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; font-weight: 800; background: linear-gradient(135deg, #c5a059 0%, #e6ca65 40%, #c59b27 75%, #a37c1e 100%); color: #07150e; border: 1px solid #fff3bd; border-radius: 6px; cursor: pointer; transition: all 0.25s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.3);" onmouseover="this.style.background='linear-gradient(135deg, #ffffff, #fef08a)'; this.style.color='#0d2116';" onmouseout="this.style.background='linear-gradient(135deg, #c5a059 0%, #e6ca65 40%, #c59b27 75%, #a37c1e 100%)'; this.style.color='#07150e';">
        🛒 + TAMBAH KE KERANJANG
      </button>
    </form>
  @endif
</div>
