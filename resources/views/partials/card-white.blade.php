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
          ]
      ];
  }
@endphp

<div class="ref-white-card product-detail-trigger"
     data-id="{{ $id }}"
     data-name="{{ $name }}"
     data-price="Rp {{ number_format($price, 0, ',', '.') }}"
     data-raw-price="{{ $price }}"
     data-image="{{ $imageUrl }}"
     data-gallery="{{ json_encode($gallery) }}"
     data-reviews="{{ json_encode($productReviews) }}"
     data-category="{{ strtoupper($itemSlug) }}"
     data-flavor="{{ $flavor }}"
     data-size="{{ $size }}"
     data-type="{{ $type }}"
     data-availability="{{ $availability == 'pre_order' ? 'Pre-Order (Pesan Dulu)' : 'Ready Stock (Tersedia)' }}"
     data-items="{{ json_encode($includedItems) }}">
  <div class="ref-white-img-wrap">
    <img src="{{ $imageUrl }}" alt="{{ $name }}" loading="lazy" decoding="async" onerror="this.onerror=null; this.src='{{ asset('images/cat-hampers.jpg') }}';">
  </div>
  <div class="ref-white-body">
    <h3 class="ref-white-title" title="{{ $name }}">{{ $name }}</h3>
    <div class="ref-white-price">Rp {{ number_format($price, 0, ',', '.') }}</div>
    @if ($id > 0)
      <form action="{{ route('cart.add') }}" method="POST" class="ref-white-cart-form" onclick="event.stopPropagation();">
        @csrf
        <input type="hidden" name="product_id" value="{{ $id }}">
        <button type="submit" class="ref-white-cart-btn">Add to cart</button>
      </form>
    @else
      <a href="{{ route('shop') }}" class="ref-white-cart-btn" onclick="event.stopPropagation();">Add to cart</a>
    @endif
  </div>
</div>
