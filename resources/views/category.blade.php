@extends('layouts.app')

@section('title', $cat->title . ' | ' . config('site.brand'))

@section('content')
<style>
  /* Luxury Shop Container Layout */
  .shop-container {
    background-color: #f8faf9;
    min-height: 100vh;
    padding: 40px 5% 90px 5%;
    color: #122019;
    font-family: 'Outfit', sans-serif;
  }
  .crumb-shop {
    font-size: 11px;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    color: #854d0e;
    margin-bottom: 24px;
    font-weight: 600;
  }
  .crumb-shop a {
    color: #854d0e;
    text-decoration: none;
  }
  .shop-title-header {
    text-align: center;
    margin-bottom: 45px;
  }
  .shop-title-header h1 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 40px;
    letter-spacing: 4px;
    font-weight: 700;
    text-transform: uppercase;
    color: #122019;
    margin: 0;
  }
  .shop-title-header h1::after {
    content: '';
    display: block;
    width: 50px;
    height: 2px;
    background: linear-gradient(90deg, #d97706, transparent);
    margin: 10px auto 0 auto;
  }

  /* Top Filter Toolbar */
  .shop-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 24px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    margin-bottom: 36px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    flex-wrap: wrap;
    gap: 16px;
  }
  .view-toggles {
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .view-btn {
    background: transparent;
    border: none;
    cursor: pointer;
    color: #63756d;
    padding: 4px;
    font-size: 16px;
    display: inline-flex;
    align-items: center;
    transition: color 0.2s;
  }
  .view-btn.active, .view-btn:hover {
    color: #1e2d27;
  }
  .product-count-label {
    font-size: 12px;
    letter-spacing: 3px;
    text-transform: uppercase;
    font-weight: 600;
    color: #55675f;
    white-space: nowrap;
  }
  .sort-select-wrapper select {
    background: transparent;
    border: none;
    font-size: 12px;
    letter-spacing: 2px;
    text-transform: uppercase;
    font-weight: 600;
    color: #55675f;
    cursor: pointer;
    outline: none;
    max-width: 100%;
  }

  @media (max-width: 650px) {
    .shop-container {
      padding: 24px 16px 60px;
    }
    .crumb-shop {
      font-size: 10px;
      margin-bottom: 16px;
      text-align: center;
    }
    .shop-title-header {
      margin-bottom: 24px;
    }
    .shop-title-header h1 {
      font-size: 26px;
      letter-spacing: 2px;
    }
    .shop-toolbar {
      flex-direction: column;
      gap: 12px;
      padding: 14px 16px;
      margin-bottom: 24px;
      align-items: center;
      text-align: center;
    }
    .view-toggles {
      justify-content: center;
    }
    .product-count-label {
      font-size: 11px;
      letter-spacing: 1.5px;
    }
    .sort-select-wrapper {
      width: 100%;
      text-align: center;
    }
    .sort-select-wrapper select {
      font-size: 11px;
      letter-spacing: 1px;
      width: 100%;
      text-align-last: center;
    }
    .shop-sidebar {
      background: #ffffff;
      padding: 16px;
      border-radius: 12px;
      border: 1px solid #e2e8f0;
      box-shadow: 0 4px 15px rgba(0,0,0,0.03);
      margin-bottom: 24px;
      box-sizing: border-box;
      max-width: 100%;
      overflow: hidden;
    }
    .accordion-header {
      font-size: 12px;
      letter-spacing: 2px;
      padding: 14px 0;
      box-sizing: border-box;
      max-width: 100%;
    }
    .filter-checkbox-label {
      font-size: 12.5px;
      word-break: break-word;
    }
  }

  /* Main Shop Content: Sidebar + Grid */
  .shop-layout {
    display: grid;
    grid-template-columns: 260px 1fr;
    gap: 40px;
    align-items: start;
    max-width: 100%;
  }
  .shop-layout main {
    min-width: 0;
    width: 100%;
    max-width: 100%;
  }
  @media (max-width: 900px) {
    .shop-layout {
      grid-template-columns: 1fr;
    }
  }

  /* Accordion Sidebar */
  .shop-sidebar {
    background: transparent;
    box-sizing: border-box;
    max-width: 100%;
    overflow: hidden;
  }
  .accordion-item {
    border-bottom: 1px solid #c8d5d0;
    box-sizing: border-box;
    width: 100%;
    max-width: 100%;
    overflow: hidden;
  }
  .accordion-header {
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
    padding: 18px 0;
    background: transparent;
    border: none;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 13px;
    letter-spacing: 3px;
    font-weight: 600;
    text-transform: uppercase;
    color: #2c3e35;
    cursor: pointer;
    text-align: left;
  }
  .accordion-header .icon {
    font-size: 10px;
    transition: transform 0.2s ease;
  }
  .accordion-item.open .accordion-header .icon {
    transform: rotate(180deg);
  }
  .accordion-body {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
    padding-bottom: 0;
    box-sizing: border-box;
    width: 100%;
    max-width: 100%;
  }
  .accordion-item.open .accordion-body {
    max-height: 400px;
    padding-bottom: 20px;
  }
  .filter-option-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 100%;
    box-sizing: border-box;
  }
  .filter-checkbox-label {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    color: #4a5c53;
    cursor: pointer;
    user-select: none;
    max-width: 100%;
    word-break: break-word;
  }
  .filter-checkbox-label input[type="checkbox"] {
    accent-color: #2c3e35;
    width: 15px;
    height: 15px;
    cursor: pointer;
    flex-shrink: 0;
  }
  .price-inputs {
    display: flex;
    gap: 8px;
    align-items: center;
    margin-top: 10px;
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
  }
  .price-input {
    flex: 1 1 0;
    min-width: 0;
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
    padding: 10px 10px;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    border-radius: 6px;
    font-size: 12.5px;
    color: #1e2d27;
    transition: all 0.2s ease;
  }
  .price-input:focus {
    outline: none;
    border-color: #d97706;
    box-shadow: 0 0 8px rgba(217, 119, 6, 0.2);
  }

  /* Product Grid Layout Options */
  .products-grid-container {
    display: grid;
    gap: 24px;
    transition: all 0.3s ease;
  }
  .products-grid-container.cols-3 {
    grid-template-columns: repeat(3, 1fr);
  }
  .products-grid-container.cols-4 {
    grid-template-columns: repeat(4, 1fr);
  }
  .products-grid-container.cols-2 {
    grid-template-columns: repeat(2, 1fr);
  }
  @media (max-width: 1100px) {
    .products-grid-container.cols-3, .products-grid-container.cols-4 {
      grid-template-columns: repeat(2, 1fr);
    }
  }
  @media (max-width: 768px) {
    .shop-layout main {
      min-width: 0 !important;
      width: 100% !important;
      max-width: 100% !important;
      overflow: hidden !important;
    }
    .products-grid-container {
      display: flex !important;
      flex-wrap: nowrap !important;
      overflow-x: auto !important;
      scroll-snap-type: x mandatory !important;
      -webkit-overflow-scrolling: touch !important;
      touch-action: pan-x pan-y !important;
      gap: 16px !important;
      padding: 4px 4px 20px 4px !important;
      width: 100% !important;
      max-width: 100% !important;
      box-sizing: border-box !important;
      scrollbar-width: thin;
      scrollbar-color: #d97706 transparent;
    }
    .products-grid-container > div, .products-grid-container > .card {
      flex: 0 0 78vw !important;
      max-width: 290px !important;
      min-width: 240px !important;
      scroll-snap-align: start !important;
      box-sizing: border-box !important;
    }
  }

  .clear-filters-btn {
    display: block;
    width: 100%;
    margin-top: 20px;
    padding: 10px;
    background: transparent;
    border: 1px solid #2c3e35;
    color: #2c3e35;
    font-size: 11px;
    letter-spacing: 2px;
    text-transform: uppercase;
    font-weight: 600;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
  }
  .clear-filters-btn:hover {
    background: #2c3e35;
    color: #fff;
  }
</style>

<div class="shop-container">
  <div class="crumb-shop">
    <a href="{{ route('home') }}">BERANDA</a> / TOKO / {{ strtoupper($cat->title) }}
  </div>

  <div class="shop-title-header">
    <h1>{{ $cat->title }}</h1>
  </div>

  <!-- Filter & Layout Toolbar -->
  <div class="shop-toolbar">
    <div class="view-toggles">
      <button type="button" class="view-btn active" data-cols="3" title="3 Kolom">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M4 4h4v16H4V4zm6 0h4v16h-4V4zm6 0h4v16h-4V4z"/></svg>
      </button>
      <button type="button" class="view-btn" data-cols="4" title="4 Kolom">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M3 4h3.5v16H3V4zm5 0h3.5v16H8V4zm5 0h3.5v16H13V4zm5 0H21v16h-3V4z"/></svg>
      </button>
      <button type="button" class="view-btn" data-cols="2" title="2 Kolom">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M4 4h7v16H4V4zm9 0h7v16h-7V4z"/></svg>
      </button>
    </div>

    <div class="product-count-label">
      <span id="product-count-display">{{ count($products) }}</span> PRODUK
    </div>

    <div class="sort-select-wrapper">
      <select id="sort-selector">
        <option value="latest" {{ ($filters['sort'] ?? '') == 'latest' ? 'selected' : '' }}>URUTKAN BERDASARKAN ˅</option>
        <option value="price_asc" {{ ($filters['sort'] ?? '') == 'price_asc' ? 'selected' : '' }}>Harga: Terendah ke Tertinggi</option>
        <option value="price_desc" {{ ($filters['sort'] ?? '') == 'price_desc' ? 'selected' : '' }}>Harga: Tertinggi ke Terendah</option>
        <option value="name_asc" {{ ($filters['sort'] ?? '') == 'name_asc' ? 'selected' : '' }}>Nama: A ke Z</option>
      </select>
    </div>
  </div>

  <!-- Main Layout -->
  <div class="shop-layout">
    <!-- Sidebar Filters -->
    <aside class="shop-sidebar">
      <form id="filter-form">
        {{-- Accordion 1: TYPE --}}
        <div class="accordion-item open">
          <button type="button" class="accordion-header">
            <span>KATEGORI BENTUK</span>
            <span class="icon">▼</span>
          </button>
          <div class="accordion-body">
            <div class="filter-option-list">
              @forelse ($types as $type)
                <label class="filter-checkbox-label">
                  <input type="checkbox" name="types[]" value="{{ $type }}" {{ in_array($type, $filters['types'] ?? []) ? 'checked' : '' }}>
                  <span>{{ $type }}</span>
                </label>
              @empty
                <div style="font-size: 12px; color: #7a8b83;">Tidak ada filter bentuk tersedia</div>
              @endforelse
            </div>
          </div>
        </div>

        {{-- Accordion 2: FLAVORS --}}
        <div class="accordion-item open">
          <button type="button" class="accordion-header">
            <span>VARIAN RASA</span>
            <span class="icon">▼</span>
          </button>
          <div class="accordion-body">
            <div class="filter-option-list">
              @forelse ($flavors as $flavor)
                <label class="filter-checkbox-label">
                  <input type="checkbox" name="flavors[]" value="{{ $flavor }}" {{ in_array($flavor, $filters['flavors'] ?? []) ? 'checked' : '' }}>
                  <span>{{ $flavor }}</span>
                </label>
              @empty
                <div style="font-size: 12px; color: #7a8b83;">Tidak ada filter rasa tersedia</div>
              @endforelse
            </div>
          </div>
        </div>

        {{-- Accordion 3: SIZE --}}
        <div class="accordion-item open">
          <button type="button" class="accordion-header">
            <span>UKURAN</span>
            <span class="icon">▼</span>
          </button>
          <div class="accordion-body">
            <div class="filter-option-list">
              @forelse ($sizes as $size)
                <label class="filter-checkbox-label">
                  <input type="checkbox" name="sizes[]" value="{{ $size }}" {{ in_array($size, $filters['sizes'] ?? []) ? 'checked' : '' }}>
                  <span>{{ $size }}</span>
                </label>
              @empty
                <div style="font-size: 12px; color: #7a8b83;">Tidak ada filter ukuran tersedia</div>
              @endforelse
            </div>
          </div>
        </div>

        {{-- Accordion 4: AVAILABILITY --}}
        <div class="accordion-item open">
          <button type="button" class="accordion-header">
            <span>KETERSEDIAAN</span>
            <span class="icon">▼</span>
          </button>
          <div class="accordion-body">
            <div class="filter-option-list">
              <label class="filter-checkbox-label">
                <input type="checkbox" name="availabilities[]" value="in_stock" {{ in_array('in_stock', $filters['availabilities'] ?? []) ? 'checked' : '' }}>
                <span>Tersedia (Ready)</span>
              </label>
              <label class="filter-checkbox-label">
                <input type="checkbox" name="availabilities[]" value="pre_order" {{ in_array('pre_order', $filters['availabilities'] ?? []) ? 'checked' : '' }}>
                <span>Pre-Order (Pesan Dulu)</span>
              </label>
            </div>
          </div>
        </div>

        {{-- Accordion 5: PRICE --}}
        <div class="accordion-item open">
          <button type="button" class="accordion-header">
            <span>RENTANG HARGA</span>
            <span class="icon">▼</span>
          </button>
          <div class="accordion-body">
            <div class="price-inputs">
              <input type="number" name="min_price" id="min_price" class="price-input" placeholder="Min Rp" value="{{ request('min_price') }}">
              <span style="font-size: 12px; color: #7a8b83;">-</span>
              <input type="number" name="max_price" id="max_price" class="price-input" placeholder="Max Rp" value="{{ request('max_price') }}">
            </div>
          </div>
        </div>

        <button type="button" id="clear-filters" class="clear-filters-btn">HAPUS SEMUA FILTER</button>
      </form>
    </aside>

    <!-- Product Grid Section -->
    <main>
      <div id="products-grid" class="products-grid-container cols-3">
        @include('partials.product_grid', ['products' => $products])
      </div>
    </main>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const filterForm = document.getElementById('filter-form');
  const productsGrid = document.getElementById('products-grid');
  const productCountDisplay = document.getElementById('product-count-display');
  const sortSelector = document.getElementById('sort-selector');
  const clearFiltersBtn = document.getElementById('clear-filters');

  // Accordion toggle logic
  document.querySelectorAll('.accordion-header').forEach(header => {
    header.addEventListener('click', function () {
      const item = this.closest('.accordion-item');
      item.classList.toggle('open');
    });
  });

  // Grid column switcher
  document.querySelectorAll('.view-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
      this.classList.add('active');
      const cols = this.getAttribute('data-cols');
      productsGrid.className = `products-grid-container cols-${cols}`;
    });
  });

  // AJAX Filter trigger function
  function applyFilters() {
    const formData = new FormData(filterForm);
    const params = new URLSearchParams(formData);

    if (sortSelector.value) {
      params.set('sort', sortSelector.value);
    }

    // Fetch filtered products
    fetch(`{{ route('category', $slug) }}?${params.toString()}`, {
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(res => res.json())
    .then(data => {
      productsGrid.innerHTML = data.html;
      productCountDisplay.textContent = data.total;
    })
    .catch(err => console.error('Filter error:', err));
  }

  // Event listeners for inputs
  filterForm.querySelectorAll('input[type="checkbox"]').forEach(input => {
    input.addEventListener('change', applyFilters);
  });

  document.querySelectorAll('.price-input').forEach(input => {
    input.addEventListener('change', applyFilters);
  });

  sortSelector.addEventListener('change', applyFilters);

  clearFiltersBtn.addEventListener('click', function () {
    filterForm.querySelectorAll('input[type="checkbox"]').forEach(input => input.checked = false);
    document.querySelectorAll('.price-input').forEach(input => input.value = '');
    sortSelector.value = 'latest';
    applyFilters();
  });
});
</script>
@endsection
