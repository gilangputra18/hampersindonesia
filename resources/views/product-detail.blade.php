@extends('layouts.app')

@section('title', $product->name . ' | ' . config('site.brand'))

@section('content')
<style>
  /* ================= LUXURY PRODUCT DETAIL PAGE ================= */
  .pdetail-page-bg {
    background: #fdfbf7;
    color: #1e293b;
    padding: 30px 20px 80px 20px;
    min-height: 80vh;
  }
  .pdetail-container {
    max-width: 1180px;
    margin: 0 auto;
  }

  /* Top Breadcrumb & Share */
  .pdetail-breadcrumb-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13px;
    color: #64748b;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 10px;
  }
  .pdetail-breadcrumb-bar a {
    color: #64748b;
    text-decoration: none;
    transition: color 0.2s;
  }
  .pdetail-breadcrumb-bar a:hover {
    color: #b45309;
  }
  .pdetail-share-btn {
    background: none;
    border: none;
    color: #64748b;
    font-size: 13px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 8px;
    border-radius: 4px;
    transition: all 0.2s;
  }
  .pdetail-share-btn:hover {
    color: #0f172a;
    background: rgba(0,0,0,0.04);
  }

  /* Top Showcase Grid (2 Columns) */
  .pdetail-showcase-grid {
    display: grid;
    grid-template-columns: 1.05fr 1fr;
    gap: 48px;
    align-items: start;
    margin-bottom: 60px;
  }
  @media (max-width: 860px) {
    .pdetail-showcase-grid {
      grid-template-columns: 1fr;
      gap: 32px;
    }
  }

  /* Gallery Left Column */
  .pdetail-gallery-col {
    display: flex;
    flex-direction: column;
    gap: 14px;
  }
  .pdetail-main-img-box {
    width: 100%;
    aspect-ratio: 1/1;
    background: #0d0208;
    border-radius: 12px;
    overflow: hidden;
    position: relative;
    box-shadow: 0 10px 30px rgba(0,0,0,0.12);
    border: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .pdetail-main-img-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease, opacity 0.3s ease;
  }
  .pdetail-thumbs-strip {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
  }
  .pdetail-thumb-item {
    aspect-ratio: 1/1;
    border-radius: 8px;
    overflow: hidden;
    background: #0d0208;
    border: 2px solid transparent;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.2s ease;
    padding: 0;
  }
  .pdetail-thumb-item:hover, .pdetail-thumb-item.active {
    border-color: #d97706;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(217, 119, 6, 0.25);
  }
  .pdetail-thumb-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  /* Info Right Column */
  .pdetail-info-col {
    display: flex;
    flex-direction: column;
  }
  .pdetail-title {
    font-family: 'Playfair Display', 'Cormorant Garamond', Georgia, serif;
    font-size: clamp(26px, 4vw, 36px);
    font-weight: 700;
    color: #12030a;
    line-height: 1.25;
    margin: 0 0 12px 0;
  }
  .pdetail-price {
    font-family: 'Outfit', sans-serif;
    font-size: 24px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 20px;
  }
  .pdetail-short-desc {
    font-size: 14.5px;
    color: #475569;
    line-height: 1.7;
    margin-bottom: 28px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e2e8f0;
  }
  .pdetail-cart-action-row {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 30px;
    flex-wrap: wrap;
  }
  .pdetail-qty-box {
    display: inline-flex;
    align-items: center;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    background: #ffffff;
    overflow: hidden;
  }
  .pdetail-qty-btn {
    width: 38px;
    height: 42px;
    background: #f8fafc;
    border: none;
    color: #1e293b;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .pdetail-qty-btn:hover {
    background: #e2e8f0;
  }
  .pdetail-qty-input {
    width: 48px;
    height: 42px;
    border: none;
    text-align: center;
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
    font-size: 15px;
    color: #0f172a;
    background: #ffffff;
  }
  .pdetail-qty-input::-webkit-outer-spin-button,
  .pdetail-qty-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }
  .pdetail-add-cart-btn {
    background: #9b3820;
    color: #ffffff;
    border: none;
    border-radius: 6px;
    font-family: 'Outfit', sans-serif;
    font-size: 13.5px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    padding: 13px 36px;
    cursor: pointer;
    transition: all 0.25s ease;
    box-shadow: 0 4px 14px rgba(155, 56, 32, 0.35);
  }
  .pdetail-add-cart-btn:hover {
    background: #b94427;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(155, 56, 32, 0.5);
  }

  /* Tabs Bar */
  .pdetail-tabs-bar {
    display: flex;
    justify-content: center;
    gap: 36px;
    border-bottom: 1px solid #e2e8f0;
    margin-bottom: 36px;
  }
  .pdetail-tab-btn {
    background: none;
    border: none;
    font-family: 'Outfit', sans-serif;
    font-size: 16px;
    font-weight: 700;
    color: #64748b;
    padding: 12px 10px 16px 10px;
    cursor: pointer;
    position: relative;
    transition: color 0.2s;
  }
  .pdetail-tab-btn.active {
    color: #12030a;
  }
  .pdetail-tab-btn.active::after {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 0;
    right: 0;
    height: 2.5px;
    background: #b45309;
  }

  /* Metadata Specification Table */
  .pdetail-meta-table {
    width: 100%;
    max-width: 850px;
    margin: 0 auto 40px auto;
    border-collapse: collapse;
  }
  .pdetail-meta-table tr {
    border-bottom: 1px solid #f1f5f9;
  }
  .pdetail-meta-table td {
    padding: 10px 14px;
    font-size: 13.5px;
    line-height: 1.6;
  }
  .pdetail-meta-table td:first-child {
    font-weight: 700;
    color: #1e293b;
    width: 140px;
  }
  .pdetail-meta-table td:last-child {
    color: #475569;
  }
  .pdetail-meta-table a {
    color: #b45309;
    text-decoration: none;
    font-weight: 600;
  }
  .pdetail-meta-table a:hover {
    text-decoration: underline;
  }

  /* Long Rich Narrative */
  .pdetail-rich-content {
    max-width: 850px;
    margin: 0 auto;
    color: #334155;
    font-size: 14.5px;
    line-height: 1.8;
  }
  .pdetail-grand-headline {
    font-family: 'Playfair Display', serif;
    font-size: clamp(22px, 3.5vw, 30px);
    font-weight: 800;
    color: #9b3820;
    line-height: 1.35;
    margin: 0 0 16px 0;
  }
  .pdetail-sub-headline {
    font-family: 'Playfair Display', serif;
    font-size: 20px;
    font-weight: 700;
    color: #12030a;
    margin: 24px 0 12px 0;
  }
  .pdetail-sec-header {
    font-family: 'Playfair Display', serif;
    font-size: 18px;
    font-weight: 700;
    color: #12030a;
    margin: 28px 0 10px 0;
    padding-bottom: 6px;
    border-bottom: 1px solid #e2e8f0;
  }
  .pdetail-rich-content ul {
    margin: 10px 0 20px 20px;
    padding: 0;
  }
  .pdetail-rich-content li {
    margin-bottom: 6px;
  }
  .pdetail-signoff-box {
    margin: 40px 0 20px 0;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 18px 24px;
    text-align: center;
    font-style: italic;
    color: #475569;
    font-size: 14px;
  }

  /* You Also Like Section */
  .pdetail-you-like-sec {
    margin-top: 60px;
    padding-top: 40px;
    border-top: 1px solid #e2e8f0;
  }
  .pdetail-you-like-title {
    font-family: 'Playfair Display', 'Cormorant Garamond', Georgia, serif;
    font-size: clamp(22px, 4vw, 30px);
    font-weight: 800;
    color: #12030a;
    text-align: center;
    margin: 0 0 28px 0;
    letter-spacing: 1px;
    text-transform: uppercase;
  }

  /* White Product Card Grid & Card Styling for Related Products */
  .ref-white-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    max-width: 1200px;
    margin: 0 auto;
  }
  .ref-white-card {
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    cursor: pointer;
    border: 1px solid #f1f5f9;
    position: relative;
  }
  .ref-white-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 28px rgba(0, 0, 0, 0.16);
  }
  .ref-white-img-wrap {
    width: 100%;
    aspect-ratio: 1/1;
    overflow: hidden;
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .ref-white-img-wrap img {
    width: 100% !important;
    height: 100% !important;
    max-width: 100% !important;
    object-fit: cover !important;
    display: block !important;
    transition: transform 0.4s ease;
  }
  .ref-white-card:hover .ref-white-img-wrap img {
    transform: scale(1.06);
  }
  .ref-white-body {
    padding: 16px 14px 18px 14px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    flex: 1;
  }
  .ref-white-title {
    font-family: 'Playfair Display', 'Cormorant Garamond', Georgia, serif;
    font-size: 15px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 6px 0;
    line-height: 1.35;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    min-height: 40px;
  }
  .ref-white-price {
    font-family: 'Outfit', sans-serif;
    font-size: 14.5px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 12px;
  }
  .ref-white-cart-form {
    width: 100%;
    margin-top: auto;
  }
  .ref-white-cart-btn {
    background: transparent;
    border: none;
    color: #0f172a;
    font-family: 'Outfit', sans-serif;
    font-size: 13px;
    font-weight: 600;
    text-decoration: underline;
    text-underline-offset: 4px;
    cursor: pointer;
    padding: 6px 14px;
    border-radius: 4px;
    transition: all 0.2s ease;
  }
  .ref-white-cart-btn:hover {
    color: #b45309;
    transform: scale(1.04);
  }

  @media (max-width: 820px) {
    .pdetail-page-bg {
      padding: 16px 12px 60px 12px;
    }
    .ref-white-grid {
      display: flex !important;
      flex-wrap: nowrap !important;
      overflow-x: auto !important;
      scroll-snap-type: x mandatory !important;
      -webkit-overflow-scrolling: touch !important;
      gap: 12px !important;
      padding: 6px 12px 18px 12px !important;
      margin-left: -12px !important;
      margin-right: -12px !important;
      scrollbar-width: none !important;
      scroll-behavior: smooth !important;
    }
    .ref-white-grid::-webkit-scrollbar {
      display: none !important;
    }
    .ref-white-card {
      flex: 0 0 170px !important;
      max-width: 180px !important;
      min-width: 155px !important;
      scroll-snap-align: start !important;
    }
    .ref-white-body {
      padding: 10px 8px 12px 8px !important;
    }
    .ref-white-title {
      font-size: 12.5px !important;
      min-height: 34px !important;
      line-height: 1.3 !important;
      margin-bottom: 4px !important;
    }
    .ref-white-price {
      font-size: 13px !important;
      margin-bottom: 8px !important;
    }
    .ref-white-cart-btn {
      font-size: 12px !important;
      padding: 4px 6px !important;
    }
  }
</style>

<div class="pdetail-page-bg">
  <div class="pdetail-container">

    <!-- Breadcrumb & Share Button -->
    <div class="pdetail-breadcrumb-bar">
      <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
        <a href="{{ route('home') }}">Beranda</a>
        <span style="margin: 0 4px; color: #94a3b8;">&gt;</span>
        <a href="{{ route('category', $product->category->slug ?? 'hampers') }}">
          {{ $product->category->title ?? 'Koleksi Produk' }}
        </a>
        <span style="margin: 0 4px; color: #94a3b8;">&gt;</span>
        <span style="color: #0f172a; font-weight: 600;">{{ $product->name }}</span>
      </div>

      <button type="button" class="pdetail-share-btn" onclick="shareCurrentProduct()" title="Bagikan produk ini">
        <span>🔗</span> <span>Bagikan</span>
      </button>
    </div>

    <!-- Top Product Showcase (2 Columns) -->
    <div class="pdetail-showcase-grid">
      <!-- Left Column: Image & Gallery Thumbnails -->
      <div class="pdetail-gallery-col">
        <div class="pdetail-main-img-box">
          <img src="{{ $gallery[0] ?? $product->image_url }}" alt="{{ $product->name }}" id="pdetailMainImg">
        </div>

        @if (count($gallery) > 1)
          <div class="pdetail-thumbs-strip">
            @foreach ($gallery as $gIdx => $gUrl)
              <button type="button" class="pdetail-thumb-item {{ $gIdx === 0 ? 'active' : '' }}" onclick="switchPdetailImage('{{ $gUrl }}', this)">
                <img src="{{ $gUrl }}" alt="{{ $product->name }} Thumbnail {{ $gIdx + 1 }}">
              </button>
            @endforeach
          </div>
        @endif
      </div>

      <!-- Right Column: Product Title, Price, Summary & Add to Cart -->
      <div class="pdetail-info-col">
        <h1 class="pdetail-title">{{ $product->name }}</h1>
        <div class="pdetail-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>

        <div class="pdetail-short-desc">
          {{ config('site.brand') }} menghadirkan berbagai pilihan kue dan bingkisan premium dengan bahan berkualitas tinggi. Setiap produk dibuat menggunakan butter Wijsman murni 100% serta rempah pilihan tanpa pemanis buatan maupun pengawet. Cocok untuk hadiah, perayaan keluarga, maupun hampers eksklusif rekan bisnis.
        </div>

        <!-- Add to Cart Form with Quantity Selector -->
        <form action="{{ route('cart.add') }}" method="POST" id="pdetailCartForm" class="ajax-add-cart-form">
          @csrf
          <input type="hidden" name="product_id" value="{{ $product->id }}">

          <div class="pdetail-cart-action-row">
            <div class="pdetail-qty-box">
              <button type="button" class="pdetail-qty-btn" onclick="adjustPdetailQty(-1)">-</button>
              <input type="number" name="quantity" id="pdetailQtyInput" value="1" min="1" max="99" class="pdetail-qty-input">
              <button type="button" class="pdetail-qty-btn" onclick="adjustPdetailQty(1)">+</button>
            </div>

            <button type="submit" class="pdetail-add-cart-btn">
              + Tambah ke Keranjang
            </button>
          </div>
        </form>

        <div style="font-size: 12.5px; color: #15803d; font-weight: 600; display: flex; align-items: center; gap: 6px;">
          <span>✓</span> <span>Stok Tersedia &amp; Selalu Dipanggang Segar</span>
        </div>
      </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="pdetail-tabs-bar">
      <button type="button" class="pdetail-tab-btn active" id="tabBtnDesc" onclick="switchTab('desc')">
        Deskripsi Produk
      </button>
      <button type="button" class="pdetail-tab-btn" id="tabBtnReviews" onclick="switchTab('reviews')">
        Ulasan ({{ $product->reviews->count() }})
      </button>
    </div>

    <!-- Tab 1: Description Content (Matching Image 1) -->
    <div id="tabContentDesc">
      <!-- Metadata Specifications Table -->
      <table class="pdetail-meta-table">
        <tbody>
          <tr>
            <td>Kode Produk (SKU)</td>
            <td>PHI-{{ strtoupper($product->category->slug ?? 'HAMP') }}-{{ str_pad($product->id, 3, '0', STR_PAD_LEFT) }}</td>
          </tr>
          <tr>
            <td>Kategori</td>
            <td>
              <a href="{{ route('category', $product->category->slug ?? 'hampers') }}">
                {{ $product->category->title ?? 'Hampers Premium' }}, Aneka Kue Premium
              </a>
            </td>
          </tr>
          <tr>
            <td>Label / Tag</td>
            <td>kue nastar toples, nastar, oleh oleh premium, hampers eksklusif, gourmet cookies</td>
          </tr>
          <tr>
            <td>Berat</td>
            <td>{{ $product->size ?: '650 g' }}</td>
          </tr>
          <tr>
            <td>Kemasan / Box</td>
            <td>{{ $product->type ?: 'Box Exclusive Hardbox Royal' }}</td>
          </tr>
        </tbody>
      </table>

      <!-- Long Rich Narrative (Matching Reference Image 1) -->
      <div class="pdetail-rich-content">
        <h2 class="pdetail-grand-headline">
          {{ $product->name }} | Full Wijsman Butter | Kue Kering Nastar Halal | Hampers Premium | {{ config('site.brand') }}
        </h2>

        <h3 class="pdetail-sub-headline">
          Koleksi Cookies &amp; Hampers Premium {{ config('site.brand') }}
        </h3>

        <p>
          Warisan cita rasa klasik yang dikemas dalam sentuhan modern dan elegan. Setiap produk dibuat untuk menghadirkan pengalaman menikmati hidangan kue kering premium dengan kualitas terbaik. Perpaduan kulit nastar yang lembut, buttery, dan lumer di mulut dengan <strong>selai nanas asli</strong> yang padat menciptakan keseimbangan rasa manis dan segar yang khas.
        </p>

        <p>
          Diproduksi menggunakan <strong>100% Full Wijsman Butter</strong> serta bahan-bahan premium pilihan, setiap butir dipanggang dengan penuh ketelitian untuk menghasilkan tekstur yang lembut, aroma butter yang kaya, dan cita rasa autentik yang konsisten. Disusun rapi dalam <strong>box eksklusif</strong>, produk ini menjadi pilihan istimewa sebagai sajian keluarga, hadiah, maupun hampers premium untuk berbagai perayaan berharga Anda.
        </p>

        <h4 class="pdetail-sec-header">Informasi Produk</h4>
        <ul>
          <li><strong>Nama Produk:</strong> {{ $product->name }}</li>
          <li><strong>Isi:</strong> {{ $product->size ?: '1 Box Eksklusif' }}</li>
          <li><strong>Berat Bersih (Netto):</strong> {{ $product->size ?: '575 - 650 gram' }}</li>
          <li><strong>Kemasan:</strong> Vacuum Pack Higienis &amp; Exclusive Rigid Hardbox</li>
          <li><strong>Pengiriman:</strong> Aman dikirim ke seluruh Indonesia dengan garansi keamanan kemasan</li>
        </ul>

        <h4 class="pdetail-sec-header">Komposisi (Bahan-bahan)</h4>
        <ul>
          <li>Tepung Terigu Protein Sedang Pilihan</li>
          <li>Mentega Pure Wijsman Asli Impor</li>
          <li>Telur Ayam Omega Segar</li>
          <li>Selai Nanas Asli &amp; Rempah Alami Pilihan</li>
          <li>Bahan-bahan berkualitas lainnya sesuai standar artisanal {{ config('site.brand') }}</li>
        </ul>

        <h4 class="pdetail-sec-header">Informasi Penyimpanan</h4>
        <ul>
          <li>Produk dalam kondisi vacuum pack dapat disimpan hingga 14 hari di suhu ruang.</li>
          <li>Setelah kemasan dibuka, simpan di dalam lemari pendingin agar kualitas dan cita rasa tetap terjaga. Produk dapat bertahan hingga 30 hari dalam kondisi penyimpanan yang baik.</li>
          <li>Untuk mendapatkan rasa dan tekstur terbaik, disarankan untuk segera mengonsumsi produk setelah kemasan dibuka.</li>
        </ul>

        <h4 class="pdetail-sec-header">Layanan Pengemasan Kustom</h4>
        <p>
          {{ config('site.brand') }} menyediakan layanan custom packaging untuk berbagai kebutuhan Anda. Apabila menginginkan penambahan kartu ucapan personal, pita satin custom warna, atau sleeve logo perusahaan, silakan hubungi tim kami sebelum melakukan pemesanan agar dapat mendiskusikan kebutuhan Anda.
        </p>

        <h4 class="pdetail-sec-header">Jam Operasional</h4>
        <p>
          Tim {{ config('site.brand') }} melayani pelayanan dan pemrosesan pesanan pada:<br>
          <strong>Senin – Sabtu</strong><br>
          08.00 – 17.00 WIB<br>
          <small style="color: #64748b;">Pesanan yang dikonfirmasi di luar jam operasional akan diproses pada hari kerja berikutnya sesuai urutan antrean.</small>
        </p>

        <h4 class="pdetail-sec-header">Informasi Pengiriman</h4>
        <p>
          Setiap bingkisan dan kue dibuat dengan sistem <em>Bake-to-Order</em> yaitu dipanggang khusus setelah pesanan diterima. Proses teliti dan steril demi memastikan setiap produk yang sampai ke tangan pelanggan selalu dalam kondisi segar, berkualitas premium, dan memiliki cita rasa terbaik.
        </p>

        <div class="pdetail-signoff-box">
          Terima kasih telah mempercayakan pilihan hampers &amp; kuliner premium kepada {{ config('site.brand') }}.
        </div>
      </div>
    </div>

    <!-- Tab 2: Reviews Content -->
    <div id="tabContentReviews" style="display: none; max-width: 850px; margin: 0 auto;">
      <h3 style="font-family: 'Playfair Display', serif; font-size: 22px; color: #12030a; margin-bottom: 20px;">
        Ulasan Pelanggan ({{ $product->reviews->count() }})
      </h3>

      @forelse($product->reviews as $rev)
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 18px 20px; margin-bottom: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
            <div style="display: flex; align-items: center; gap: 10px;">
              <img src="{{ $rev->avatar_url ?? ('https://ui-avatars.com/api/?name=' . urlencode($rev->reviewer_name) . '&background=d97706&color=fff') }}" alt="{{ $rev->reviewer_name }}" style="width: 34px; height: 34px; border-radius: 50%; object-fit: cover;">
              <div>
                <strong style="color: #0f172a; font-size: 14px;">{{ $rev->reviewer_name }}</strong>
                <span style="font-size: 11px; color: #10b981; margin-left: 6px;">✓ Terverifikasi</span>
              </div>
            </div>
            <div style="color: #f59e0b; font-size: 14px;">
              {{ str_repeat('★', $rev->rating) }}{{ str_repeat('☆', 5 - $rev->rating) }}
            </div>
          </div>
          @if($rev->title)
            <div style="font-weight: 700; color: #1e293b; margin-bottom: 4px; font-size: 13.5px;">"{{ $rev->title }}"</div>
          @endif
          <p style="color: #475569; font-size: 13.5px; line-height: 1.6; margin: 0;">{{ $rev->body }}</p>
          <small style="color: #94a3b8; font-size: 11px; display: block; margin-top: 8px;">{{ $rev->created_at ? $rev->created_at->diffForHumans() : 'Baru saja' }}</small>
        </div>
      @empty
        <div style="text-align: center; padding: 40px; background: #ffffff; border-radius: 10px; border: 1px dashed #cbd5e1; color: #64748b;">
          Belum ada ulasan untuk produk ini. Jadilah yang pertama memberikan ulasan!
        </div>
      @endforelse

      <!-- Form Berikan Ulasan -->
      <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 26px; margin-top: 32px; box-shadow: 0 4px 15px rgba(0,0,0,0.04);">
        <h4 style="font-family: 'Playfair Display', serif; font-size: 18px; color: #12030a; margin: 0 0 16px 0;">
          Tulis Ulasan Anda
        </h4>

        <form action="{{ route('review.submit', $product->id) }}" method="POST">
          @csrf
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 14px;">
            <div>
              <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Nama Lengkap</label>
              <input type="text" name="reviewer_name" required placeholder="Nama Anda" style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box;">
            </div>
            <div>
              <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Rating Bintang</label>
              <select name="rating" style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box;">
                <option value="5">★★★★★ (5 Bintang - Sempurna)</option>
                <option value="4">★★★★☆ (4 Bintang - Sangat Baik)</option>
                <option value="3">★★★☆☆ (3 Bintang - Cukup)</option>
              </select>
            </div>
          </div>

          <div style="margin-bottom: 14px;">
            <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Judul Ulasan Singkat</label>
            <input type="text" name="title" placeholder="Contoh: Sangat Lezat & Kemasan Mewah!" style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box;">
          </div>

          <div style="margin-bottom: 18px;">
            <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 4px;">Isi Ulasan</label>
            <textarea name="body" rows="4" required placeholder="Ceritakan pengalaman Anda menikmati produk ini..." style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box;"></textarea>
          </div>

          <button type="submit" class="pdetail-add-cart-btn" style="padding: 11px 28px;">
            Kirim Ulasan
          </button>
        </form>
      </div>
    </div>

    <!-- "You also like" Section (Matching Reference Image 1) -->
    <div class="pdetail-you-like-sec">
      <h2 class="pdetail-you-like-title">Produk Pilihan Lainnya</h2>

      <div class="ref-white-grid">
        @foreach ($related as $r)
          @include('partials.card-white', ['item' => $r])
        @endforeach
      </div>
    </div>

  </div>
</div>

<script>
  function switchPdetailImage(url, btn) {
    const mainImg = document.getElementById('pdetailMainImg');
    if (mainImg) {
      mainImg.style.opacity = '0.4';
      setTimeout(() => {
        mainImg.src = url;
        mainImg.style.opacity = '1';
      }, 150);
    }
    document.querySelectorAll('.pdetail-thumb-item').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');
  }

  function adjustPdetailQty(delta) {
    const input = document.getElementById('pdetailQtyInput');
    if (input) {
      let val = parseInt(input.value, 10) || 1;
      val += delta;
      if (val < 1) val = 1;
      if (val > 99) val = 99;
      input.value = val;
    }
  }

  function switchTab(tab) {
    const btnDesc = document.getElementById('tabBtnDesc');
    const btnReviews = document.getElementById('tabBtnReviews');
    const contentDesc = document.getElementById('tabContentDesc');
    const contentReviews = document.getElementById('tabContentReviews');

    if (tab === 'desc') {
      btnDesc.classList.add('active');
      btnReviews.classList.remove('active');
      contentDesc.style.display = 'block';
      contentReviews.style.display = 'none';
    } else {
      btnDesc.classList.remove('active');
      btnReviews.classList.add('active');
      contentDesc.style.display = 'none';
      contentReviews.style.display = 'block';
    }
  }

  function shareCurrentProduct() {
    if (navigator.share) {
      navigator.share({
        title: '{{ $product->name }}',
        text: 'Lihat koleksi {{ $product->name }} di {{ config("site.brand") }}',
        url: window.location.href
      }).catch(() => {});
    } else {
      navigator.clipboard.writeText(window.location.href);
      alert('Tautan produk berhasil disalin ke clipboard!');
    }
  }
</script>
@endsection
