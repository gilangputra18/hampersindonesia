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

@php
  $prodSlug = is_array($item) ? \Illuminate\Support\Str::slug($name) : ($item->slug ?: \Illuminate\Support\Str::slug($name));
  $detailUrl = route('product.detail', $prodSlug);
@endphp

<div class="ref-white-card" data-product-id="{{ $id }}">
  <a href="{{ $detailUrl }}" class="ref-white-img-wrap" title="{{ $name }}">
    <img src="{{ $imageUrl }}" alt="{{ $name }}" loading="lazy" decoding="async" onerror="this.onerror=null; this.src='{{ asset('images/cat-hampers.jpg') }}';">
  </a>
  <div class="ref-white-body">
    <a href="{{ $detailUrl }}" style="text-decoration: none; color: inherit;" title="{{ $name }}">
      <h3 class="ref-white-title">{{ $name }}</h3>
    </a>
    <div class="ref-white-price">Rp {{ number_format($price, 0, ',', '.') }}</div>
    @if ($id > 0)
      <form action="{{ route('cart.add') }}" method="POST" class="ref-white-cart-form ajax-add-cart-form" onclick="event.stopPropagation();">
        @csrf
        <input type="hidden" name="product_id" value="{{ $id }}">
        <input type="hidden" name="quantity" value="1">
        <button type="submit" class="ref-white-cart-btn">Add to cart</button>
      </form>
    @else
      <a href="{{ $detailUrl }}" class="ref-white-cart-btn">Add to cart</a>
    @endif
  </div>
</div>
