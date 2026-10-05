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
@endphp
<div class="card card-hover-flip" style="display: flex; flex-direction: column; justify-content: space-between; height: 100%; border-radius: 10px; overflow: hidden; transition: all 0.3s ease;">
  <div>
    <a href="{{ route('category', $itemSlug) }}" style="text-decoration: none; color: inherit; display: block;">
      <span class="ph" style="border-radius: 8px; overflow: hidden; display: block; position: relative; aspect-ratio: 1/1;">
        <img src="{{ $imageUrl }}" alt="{{ $name }}" class="card-img-primary" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; transition: opacity 0.4s ease, transform 0.4s ease;">
        <img src="{{ $secondaryUrl }}" alt="{{ $name }} Alternative" class="card-img-secondary" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; position: absolute; inset: 0; opacity: 0; transition: opacity 0.4s ease, transform 0.4s ease;">
      </span>
      <span class="n" style="font-family: 'Cormorant Garamond', serif; font-size: 17px; font-weight: 700; color: #122019; margin-top: 10px; letter-spacing: 0.5px;">{{ $name }}</span>
      <span class="p" style="color: #b45309; font-size: 14.5px; font-weight: 700; margin-top: 4px;">{{ !empty($from) ? 'Mulai dari ' : '' }}Rp {{ number_format($price, 0, ',', '.') }}</span>
    </a>
  </div>
  @if ($id > 0)
    <form action="{{ route('cart.add') }}" method="POST" style="margin-top: 14px;">
      @csrf
      <input type="hidden" name="product_id" value="{{ $id }}">
      <button type="submit" style="width: 100%; padding: 10px 14px; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; font-weight: 700; background: linear-gradient(135deg, #111f18 0%, #1c3026 100%); color: #fef08a; border: 1px solid rgba(217, 119, 6, 0.3); border-radius: 6px; cursor: pointer; transition: all 0.25s ease;" onmouseover="this.style.background='linear-gradient(135deg, #d97706, #f59e0b)'; this.style.color='#0d1713';" onmouseout="this.style.background='linear-gradient(135deg, #111f18 0%, #1c3026 100%)'; this.style.color='#fef08a';">
        🛒 + TAMBAH KE KERANJANG
      </button>
    </form>
  @endif
</div>
