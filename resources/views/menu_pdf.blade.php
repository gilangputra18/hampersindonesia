<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pusat Hampers Indonesia — Katalog Produk & Menu PDF</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;800&family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
  
  <style>
    :root {
      --gold: #d4af37;
      --gold-light: #f3e5ab;
      --dark-bg: #091310;
      --card-bg: #12211c;
      --text-main: #f1f5f9;
      --text-sub: #94a3b8;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: var(--dark-bg);
      color: var(--text-main);
      line-height: 1.6;
      padding-bottom: 60px;
    }

    /* Print Floating Control Toolbar */
    .print-toolbar {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 9999;
      display: flex;
      gap: 12px;
      background: rgba(18, 33, 28, 0.9);
      backdrop-filter: blur(12px);
      padding: 12px 20px;
      border-radius: 40px;
      border: 1px solid rgba(212, 175, 55, 0.4);
      box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }
    .print-btn {
      background: linear-gradient(135deg, #d4af37 0%, #aa7c11 100%);
      color: #000;
      font-weight: 700;
      font-size: 14px;
      padding: 10px 22px;
      border-radius: 30px;
      border: none;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.3s ease;
      text-decoration: none;
    }
    .print-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
    }

    /* Menu Container */
    .menu-paper {
      max-width: 1000px;
      margin: 40px auto;
      background: linear-gradient(180deg, #0d1a16 0%, #08120e 100%);
      border: 2px solid rgba(212, 175, 55, 0.35);
      border-radius: 20px;
      padding: 60px 70px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7);
      position: relative;
    }
    .menu-paper::before {
      content: '';
      position: absolute;
      inset: 12px;
      border: 1px solid rgba(212, 175, 55, 0.2);
      border-radius: 14px;
      pointer-events: none;
    }

    /* Header */
    .menu-header {
      text-align: center;
      margin-bottom: 50px;
      padding-bottom: 30px;
      border-bottom: 2px solid rgba(212, 175, 55, 0.2);
    }
    .menu-logo-text {
      font-family: 'Cinzel', serif;
      font-size: 38px;
      font-weight: 800;
      letter-spacing: 6px;
      color: var(--gold);
      text-transform: uppercase;
      margin-bottom: 6px;
      text-shadow: 0 2px 10px rgba(212, 175, 55, 0.3);
    }
    .menu-sub-logo {
      font-family: 'Playfair Display', serif;
      font-style: italic;
      font-size: 16px;
      color: var(--gold-light);
      letter-spacing: 2px;
      margin-bottom: 16px;
    }
    .menu-badge {
      display: inline-block;
      border: 1px solid var(--gold);
      padding: 6px 20px;
      border-radius: 30px;
      font-family: 'Cinzel', serif;
      font-size: 12px;
      letter-spacing: 3px;
      color: var(--gold);
      text-transform: uppercase;
    }

    /* Section Category */
    .category-section {
      margin-bottom: 50px;
    }
    .category-title {
      font-family: 'Cinzel', serif;
      font-size: 26px;
      color: var(--gold);
      letter-spacing: 3px;
      margin-bottom: 24px;
      display: flex;
      align-items: center;
      gap: 16px;
      border-bottom: 1px dashed rgba(212, 175, 55, 0.3);
      padding-bottom: 10px;
    }
    .category-title::after {
      content: '';
      flex: 1;
      height: 1px;
      background: linear-gradient(90deg, rgba(212, 175, 55, 0.4), transparent);
    }

    /* Product Grid */
    .products-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 24px 36px;
    }
    @media (max-width: 768px) {
      .products-grid {
        grid-template-columns: 1fr;
      }
      .menu-paper {
        padding: 30px 20px;
      }
    }

    .product-item {
      display: flex;
      gap: 16px;
      background: rgba(18, 33, 28, 0.5);
      border: 1px solid rgba(255, 255, 255, 0.06);
      padding: 16px;
      border-radius: 12px;
      transition: all 0.3s ease;
    }
    .product-img {
      width: 80px;
      height: 80px;
      border-radius: 10px;
      object-fit: cover;
      border: 1px solid rgba(212, 175, 55, 0.3);
      flex-shrink: 0;
    }
    .product-details {
      flex: 1;
    }
    .product-name-price {
      display: flex;
      justify-content: space-between;
      align-items: baseline;
      gap: 10px;
      margin-bottom: 4px;
    }
    .product-name {
      font-family: 'Playfair Display', serif;
      font-size: 17px;
      font-weight: 700;
      color: #ffffff;
    }
    .product-price {
      font-family: 'Inter', sans-serif;
      font-size: 15px;
      font-weight: 700;
      color: var(--gold);
      white-space: nowrap;
    }
    .product-desc {
      font-size: 13px;
      color: var(--text-sub);
      line-height: 1.4;
      margin-bottom: 6px;
    }
    .product-tags {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
    }
    .tag {
      font-size: 10px;
      padding: 2px 8px;
      border-radius: 10px;
      background: rgba(212, 175, 55, 0.12);
      color: var(--gold-light);
      border: 1px solid rgba(212, 175, 55, 0.25);
      font-weight: 500;
    }

    /* Footer */
    .menu-footer {
      margin-top: 60px;
      text-align: center;
      border-top: 1px solid rgba(212, 175, 55, 0.2);
      padding-top: 24px;
      font-size: 12px;
      color: var(--text-sub);
    }

    /* Print Styles */
    @media print {
      .print-toolbar {
        display: none !important;
      }
      body {
        background: #ffffff !important;
        color: #000000 !important;
        padding: 0 !important;
      }
      .menu-paper {
        border: none !important;
        box-shadow: none !important;
        background: #ffffff !important;
        color: #000000 !important;
        padding: 20px !important;
        max-width: 100% !important;
        margin: 0 !important;
      }
      .menu-paper::before {
        display: none !important;
      }
      .menu-logo-text {
        color: #aa7c11 !important;
        text-shadow: none !important;
      }
      .category-title, .product-price {
        color: #aa7c11 !important;
      }
      .product-item {
        background: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
      }
      .product-name {
        color: #0f172a !important;
      }
      .product-desc {
        color: #475569 !important;
      }
      .tag {
        background: #f1f5f9 !important;
        color: #334155 !important;
        border-color: #cbd5e1 !important;
      }
    }
  </style>
</head>
<body>

  <!-- Print & Download Control Bar -->
  <div class="print-toolbar">
    <button onclick="window.print()" class="print-btn">
      <span>🖨️ Cetak / Simpan PDF</span>
    </button>
    <a href="{{ route('reservations') }}" class="print-btn" style="background: rgba(255,255,255,0.1); color: #fff;">
      <span>← Kembali</span>
    </a>
  </div>

  <div class="menu-paper">
    <!-- Header -->
    <div class="menu-header">
      <h1 class="menu-logo-text">PUSAT HAMPERS INDONESIA</h1>
      <p class="menu-sub-logo">Pusat Hampers, Gift Box & Parcel Gourmet Indonesia</p>
      <span class="menu-badge">KATALOG MENU RESMI</span>
    </div>

    <!-- Category Sections -->
    @foreach($categories as $category)
      @if($category->products->count() > 0)
        <div class="category-section">
          <h2 class="category-title">✨ {{ strtoupper($category->name) }}</h2>

          <div class="products-grid">
            @foreach($category->products as $product)
              <div class="product-item">
                <div style="display: flex; gap: 6px; flex-shrink: 0;">
                  <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-img" onerror="this.src='{{ asset('images/cat-cakes.jpg') }}'">
                  @if(!empty($product->secondary_image_url))
                    <img src="{{ $product->secondary_image_url }}" alt="{{ $product->name }} Variasi" class="product-img" style="opacity: 0.85; filter: brightness(0.95);" onerror="this.style.display='none'">
                  @endif
                </div>
                <div class="product-details">
                  <div class="product-name-price">
                    <span class="product-name">{{ $product->name }}</span>
                    <span class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                  </div>
                  <p class="product-desc">{{ Str::limit($product->description, 90) }}</p>
                  <div class="product-tags">
                    @if($product->flavor)
                      <span class="tag">🍓 {{ $product->flavor }}</span>
                    @endif
                    @if($product->size)
                      <span class="tag">📏 {{ $product->size }}</span>
                    @endif
                    @if($product->is_best_seller)
                      <span class="tag" style="background: rgba(234, 179, 8, 0.2); color: #fde047; border-color: #eab308;">⭐ Best Seller</span>
                    @endif
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endif
    @endforeach

    <!-- Footer -->
    <div class="menu-footer">
      <p style="font-family: 'Cinzel', serif; font-size: 14px; color: var(--gold); margin-bottom: 4px;">PUSAT HAMPERS INDONESIA</p>
      <p>Jl. Gourmet Delights No. 88, Jakarta • Telp / WhatsApp: +62 812-3456-7890 • www.pusathampersindonesia.com</p>
      <p style="margin-top: 8px; font-size: 11px; opacity: 0.7;">Harga belum termasuk pajak pemerintah dan biaya layanan. Semua hidangan dipanggang segar setiap hari.</p>
    </div>
  </div>

</body>
</html>
