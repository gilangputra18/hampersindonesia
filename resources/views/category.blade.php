@extends('layouts.app')

@section('title', ($cat ? $cat->title : 'Produk') . ' | ' . config('site.brand'))

@section('content')
<style>
  /* Luxury Shop Container Layout (Full Width, No Sidebar Clutter) */
  .shop-container {
    background-color: #f8faf9;
    min-height: 100vh;
    padding: 36px 5% 90px 5%;
    color: #122019;
    font-family: 'Outfit', sans-serif;
  }
  .crumb-shop {
    font-size: 11px;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    color: #854d0e;
    margin-bottom: 20px;
    font-weight: 600;
  }
  .crumb-shop a {
    color: #854d0e;
    text-decoration: none;
    transition: color 0.2s;
  }
  .crumb-shop a:hover {
    color: #d97706;
  }
  .shop-title-header {
    text-align: center;
    margin-bottom: 40px;
  }
  .shop-title-header h1 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 42px;
    letter-spacing: 4px;
    font-weight: 700;
    text-transform: uppercase;
    color: #122019;
    margin: 0;
  }
  .shop-title-header h1::after {
    content: '';
    display: block;
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #d97706, #fbbf24);
    margin: 12px auto 0 auto;
    border-radius: 2px;
  }

  /* Collection Grid Showcase (Matching Reference Image 4) */
  .collection-grid-showcase {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 22px;
    margin-bottom: 50px;
  }
  @media (max-width: 1050px) {
    .collection-grid-showcase {
      grid-template-columns: repeat(3, 1fr);
      gap: 16px;
    }
  }
  @media (max-width: 720px) {
    .collection-grid-showcase {
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
      margin-bottom: 35px;
    }
  }
  .collection-card {
    position: relative;
    border-radius: 14px;
    overflow: hidden;
    aspect-ratio: 1/1;
    display: block;
    text-decoration: none;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.06);
    background: #0f1c16;
    transition: transform 0.35s ease, box-shadow 0.35s ease;
  }
  .collection-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 16px 35px rgba(0, 0, 0, 0.16);
  }
  .collection-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
  }
  .collection-card:hover img {
    transform: scale(1.08);
  }
  .collection-card-badge {
    position: absolute;
    bottom: 16px;
    left: 12px;
    right: 12px;
    background: rgba(255, 255, 255, 0.94);
    backdrop-filter: blur(8px);
    color: #0f172a;
    font-family: 'Outfit', sans-serif;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-align: center;
    padding: 11px 14px;
    border-radius: 50px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.18);
    transition: all 0.25s ease;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .collection-card:hover .collection-card-badge {
    background: #ffffff;
    color: #b45309;
    box-shadow: 0 6px 20px rgba(0,0,0,0.25);
  }
  @media (max-width: 600px) {
    .collection-card-badge {
      font-size: 11px;
      padding: 8px 10px;
      bottom: 10px;
      left: 8px;
      right: 8px;
    }
  }

  /* Category Filter Pills (Instant One-Click Navigation) */
  .category-pill-nav {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 30px;
  }
  .category-pill {
    padding: 9px 20px;
    border-radius: 50px;
    font-size: 12.5px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    text-decoration: none;
    transition: all 0.2s ease;
    background: #ffffff;
    color: #475569;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
  }
  .category-pill:hover, .category-pill.active {
    background: linear-gradient(135deg, #b45309 0%, #d97706 100%);
    color: #ffffff;
    border-color: #b45309;
    box-shadow: 0 4px 12px rgba(217, 119, 6, 0.35);
  }

  /* Shop Toolbar */
  .shop-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 24px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    margin-bottom: 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    flex-wrap: wrap;
    gap: 16px;
  }
  .view-toggles {
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .view-btn {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    cursor: pointer;
    color: #64748b;
    padding: 6px 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
  }
  .view-btn.active, .view-btn:hover {
    color: #0f172a;
    background: #e2e8f0;
  }
  .product-count-label {
    font-size: 12px;
    letter-spacing: 2px;
    text-transform: uppercase;
    font-weight: 700;
    color: #475569;
  }
  .sort-select-wrapper select {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 8px 14px;
    font-size: 12px;
    letter-spacing: 1px;
    text-transform: uppercase;
    font-weight: 700;
    color: #334155;
    cursor: pointer;
    outline: none;
    transition: border-color 0.2s;
  }
  .sort-select-wrapper select:focus {
    border-color: #d97706;
  }

  /* Full Width Products Grid (No Sidebar) */
  .products-grid-container {
    display: grid;
    gap: 24px;
    width: 100%;
  }
  .products-grid-container.cols-4 {
    grid-template-columns: repeat(4, 1fr);
  }
  .products-grid-container.cols-3 {
    grid-template-columns: repeat(3, 1fr);
  }
  .products-grid-container.cols-2 {
    grid-template-columns: repeat(2, 1fr);
  }
  @media (max-width: 1100px) {
    .products-grid-container.cols-4 {
      grid-template-columns: repeat(3, 1fr);
    }
  }
  @media (max-width: 768px) {
    .products-grid-container.cols-4,
    .products-grid-container.cols-3 {
      grid-template-columns: repeat(2, 1fr);
      gap: 14px;
    }
    .shop-toolbar {
      flex-direction: column;
      gap: 12px;
      padding: 14px;
      text-align: center;
    }
    .sort-select-wrapper select {
      width: 100%;
      text-align-last: center;
    }
  }
  @media (max-width: 480px) {
    .products-grid-container.cols-4,
    .products-grid-container.cols-3,
    .products-grid-container.cols-2 {
      grid-template-columns: repeat(2, 1fr);
      gap: 10px;
    }
  }

  /* ================= LUXURY SHOP PRODUCT CARD (UNIFIED BURGUNDY & WHITE) ================= */
  .shop-card-luxury {
    display: flex !important;
    flex-direction: column !important;
    justify-content: space-between !important;
    height: 100% !important;
    border-radius: 12px !important;
    overflow: hidden !important;
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease !important;
    background: #ffffff !important;
    border: 1px solid #ecd5dc !important;
    padding: 12px !important;
    box-shadow: 0 4px 16px rgba(114, 47, 55, 0.05) !important;
    position: relative !important;
    box-sizing: border-box !important;
  }
  .shop-card-luxury:hover {
    transform: translateY(-4px) !important;
    border-color: #722F37 !important;
    box-shadow: 0 10px 26px rgba(114, 47, 55, 0.12) !important;
  }
  .shop-card-luxury .card-clickable-area {
    text-decoration: none !important;
    color: inherit !important;
    display: block !important;
  }
  .shop-card-luxury .card-ph {
    border-radius: 8px !important;
    overflow: hidden !important;
    display: block !important;
    position: relative !important;
    aspect-ratio: 1/1 !important;
    border: 1px solid #f1f5f9 !important;
    background: #f8fafc !important;
    margin-bottom: 0 !important;
  }
  .shop-card-luxury .card-img-primary,
  .shop-card-luxury .card-img-secondary {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
    display: block !important;
    transition: opacity 0.4s ease, transform 0.4s ease !important;
  }
  .shop-card-luxury:hover .card-img-primary {
    transform: scale(1.04) !important;
  }
  .shop-card-luxury .card-title-text {
    font-family: 'Playfair Display', 'Cormorant Garamond', Georgia, serif !important;
    font-size: 15px !important;
    font-weight: 700 !important;
    color: #2b0c16 !important;
    margin-top: 10px !important;
    margin-bottom: 0 !important;
    letter-spacing: 0.3px !important;
    display: -webkit-box !important;
    -webkit-line-clamp: 2 !important;
    -webkit-box-orient: vertical !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    line-height: 1.35 !important;
    min-height: 40px !important;
    transition: color 0.2s ease !important;
  }
  .shop-card-luxury:hover .card-title-text {
    color: #722F37 !important;
  }
  .shop-card-luxury .card-price-text {
    font-family: 'Outfit', sans-serif !important;
    color: #722F37 !important;
    font-size: 14.5px !important;
    font-weight: 700 !important;
    margin-top: 4px !important;
    margin-bottom: 0 !important;
    display: block !important;
    letter-spacing: 0.2px !important;
  }
  .shop-card-luxury .card-detail-hint {
    display: inline-flex !important;
    align-items: center !important;
    gap: 3px !important;
    margin-top: 5px !important;
    font-size: 11px !important;
    color: #b45309 !important;
    font-weight: 600 !important;
    transition: all 0.2s ease !important;
  }
  .shop-card-luxury:hover .card-detail-hint {
    transform: translateX(3px) !important;
    color: #722F37 !important;
  }
  .shop-card-luxury .card-cart-form {
    margin-top: 10px !important;
    width: 100% !important;
  }
  .shop-card-luxury .card-btn-cart {
    width: 100% !important;
    padding: 10px 12px !important;
    font-size: 11px !important;
    letter-spacing: 1.5px !important;
    text-transform: uppercase !important;
    font-weight: 800 !important;
    background: linear-gradient(135deg, #722F37 0%, #4a151b 100%) !important;
    color: #ffffff !important;
    border: 1px solid rgba(254, 240, 138, 0.4) !important;
    border-radius: 6px !important;
    cursor: pointer !important;
    transition: all 0.25s ease !important;
    box-shadow: 0 4px 12px rgba(114, 47, 55, 0.2) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    white-space: nowrap !important;
    text-align: center !important;
    box-sizing: border-box !important;
  }
  .shop-card-luxury .card-btn-cart:hover {
    background: linear-gradient(135deg, #8b2331 0%, #5e1620 100%) !important;
    box-shadow: 0 6px 16px rgba(114, 47, 55, 0.3) !important;
    color: #fef08a !important;
    border-color: #fef08a !important;
    transform: translateY(-1px) !important;
  }
  .shop-card-luxury .btn-desktop-text {
    display: inline !important;
  }
  .shop-card-luxury .btn-mobile-text {
    display: none !important;
  }

  /* ================= MOBILE OPTIMIZATION (COMPACT & PROPORTIONAL) ================= */
  @media (max-width: 640px) {
    .shop-container {
      padding: 16px 10px 80px 10px !important;
    }
    .crumb-shop {
      font-size: 10px !important;
      letter-spacing: 1.5px !important;
      margin-bottom: 12px !important;
    }
    .shop-title-header {
      margin-bottom: 24px !important;
    }
    .shop-title-header h1 {
      font-size: 28px !important;
      letter-spacing: 2px !important;
    }
    .shop-card-luxury {
      padding: 8px 8px 10px 8px !important;
      border-radius: 10px !important;
      box-shadow: 0 2px 8px rgba(114, 47, 55, 0.05) !important;
    }
    .shop-card-luxury .card-ph {
      border-radius: 6px !important;
      margin-bottom: 0 !important;
    }
    .shop-card-luxury .card-title-text {
      font-size: 12.5px !important;
      min-height: 30px !important;
      margin-top: 6px !important;
      line-height: 1.25 !important;
    }
    .shop-card-luxury .card-price-text {
      font-size: 12px !important;
      margin-top: 2px !important;
      line-height: 1.2 !important;
    }
    .shop-card-luxury .card-detail-hint {
      display: none !important; /* Disembunyikan pada mobile agar card proporsional dan tidak memanjang */
    }
    .shop-card-luxury .card-cart-form {
      margin-top: 6px !important;
    }
    .shop-card-luxury .card-btn-cart {
      padding: 6px 4px !important;
      font-size: 10.5px !important;
      letter-spacing: 0.3px !important;
      border-radius: 6px !important;
      height: 30px !important;
      line-height: 1 !important;
    }
    .shop-card-luxury .btn-desktop-text {
      display: none !important;
    }
    .shop-card-luxury .btn-mobile-text {
      display: inline !important;
    }
  }
</style>

<div class="shop-container">
  <!-- Breadcrumb -->
  <div class="crumb-shop" style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
    <a href="{{ route('home') }}">BERANDA</a> »
    <a href="{{ route('shop') }}">PRODUK</a>
    @if ($cat && !$isAllProducts)
      » <span>{{ strtoupper($cat->title) }}</span>
    @endif
  </div>

  <!-- Page Title Header -->
  <div class="shop-title-header">
    <h1>{{ $cat && !$isAllProducts ? $cat->title : 'PRODUK' }}</h1>
  </div>

  <!-- Category Showcase Grid (Matching Reference Image 4) -->
  <div class="collection-grid-showcase">
    @foreach ($categories as $c)
      <a href="{{ route('category', $c->slug) }}" class="collection-card">
        <img src="{{ $c->image_url }}" alt="{{ $c->title }}" loading="lazy" onerror="this.src='{{ asset('images/cat-hampers.jpg') }}';">
        <div class="collection-card-badge">
          {{ $c->title }} ({{ $c->products_count }} Produk)
        </div>
      </a>
    @endforeach
  </div>

  <!-- Category Switcher Pills -->
  <div class="category-pill-nav">
    <a href="{{ route('shop') }}" class="category-pill {{ $isAllProducts ? 'active' : '' }}">
      ✨ SEMUA PRODUK
    </a>
    @foreach ($categories as $c)
      <a href="{{ route('category', $c->slug) }}" class="category-pill {{ (!$isAllProducts && $cat && $cat->id === $c->id) ? 'active' : '' }}">
        {{ $c->title }}
      </a>
    @endforeach
  </div>

  <!-- Shop Top Toolbar -->
  <div class="shop-toolbar">
    <div class="view-toggles">
      <button type="button" class="view-btn active" data-cols="4" title="4 Kolom">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M3 4h3.5v16H3V4zm5 0h3.5v16H8V4zm5 0h3.5v16H13V4zm5 0H21v16h-3V4z"/></svg>
      </button>
      <button type="button" class="view-btn" data-cols="3" title="3 Kolom">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M4 4h4v16H4V4zm6 0h4v16h-4V4zm6 0h4v16h-4V4z"/></svg>
      </button>
      <button type="button" class="view-btn" data-cols="2" title="2 Kolom">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M4 4h7v16H4V4zm9 0h7v16h-7V4z"/></svg>
      </button>
    </div>

    <div class="product-count-label">
      <span id="product-count-display">{{ count($products) }}</span> PRODUK DITEMUKAN
    </div>

    <div class="sort-select-wrapper">
      <select id="sort-selector">
        <option value="latest" {{ ($sort ?? '') == 'latest' ? 'selected' : '' }}>URUTKAN: TERBARU ˅</option>
        <option value="price_asc" {{ ($sort ?? '') == 'price_asc' ? 'selected' : '' }}>Harga: Terendah ke Tertinggi</option>
        <option value="price_desc" {{ ($sort ?? '') == 'price_desc' ? 'selected' : '' }}>Harga: Tertinggi ke Terendah</option>
        <option value="name_asc" {{ ($sort ?? '') == 'name_asc' ? 'selected' : '' }}>Nama: A ke Z</option>
      </select>
    </div>
  </div>

  <!-- Full-Width Products Grid (Clean layout, No cluttered sidebar!) -->
  <div id="products-grid" class="products-grid-container cols-4">
    @include('partials.product_grid', ['products' => $products])
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const productsGrid = document.getElementById('products-grid');
  const productCountDisplay = document.getElementById('product-count-display');
  const sortSelector = document.getElementById('sort-selector');

  // Column switcher
  document.querySelectorAll('.view-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
      this.classList.add('active');
      const cols = this.getAttribute('data-cols');
      productsGrid.className = `products-grid-container cols-${cols}`;
    });
  });

  // AJAX Sorting
  if (sortSelector) {
    sortSelector.addEventListener('change', function () {
      const currentUrl = new URL(window.location.href);
      currentUrl.searchParams.set('sort', this.value);

      fetch(currentUrl.toString(), {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(res => res.json())
      .then(data => {
        productsGrid.innerHTML = data.html;
        if (productCountDisplay) {
          productCountDisplay.textContent = data.total;
        }
      })
      .catch(err => console.error('Sort error:', err));
    });
  }
});
</script>
@endsection
