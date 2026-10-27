@extends('layouts.app')

@section('title', config('site.brand') . ' | Pusat Hampers & Hadiah Mewah Indonesia')

@section('content')
<style>
  /* ===================== THE GOLDEN BITES LUXURY THEME (MATCHING IMAGE 5) ===================== */
  :root {
    --wine-dark: #16050e;
    --wine-surface: #200814;
    --wine-card: #280b1a;
    --gold-bright: #fef08a;
    --gold-accent: #e6ca65;
    --gold-deep: #b45309;
    --gold-gradient: linear-gradient(135deg, #fff3c4 0%, #e6ca65 35%, #c59b27 70%, #997317 100%);
  }

  /* Hero Section */
  .hero-wine {
    position: relative;
    background: radial-gradient(circle at center, #2e0d1f 0%, #16050e 100%);
    min-height: 520px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    padding: 70px 20px;
    text-align: center;
  }
  .hero-wine video {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.28;
    filter: brightness(0.7) contrast(1.1);
    pointer-events: none;
  }
  .hero-wine-overlay {
    position: relative;
    z-index: 5;
    max-width: 850px;
    margin: 0 auto;
  }
  .hero-brand-badge {
    display: inline-block;
    font-size: 11px;
    letter-spacing: 4px;
    text-transform: uppercase;
    color: var(--gold-bright);
    border: 1px solid rgba(254, 240, 138, 0.4);
    padding: 6px 18px;
    border-radius: 50px;
    margin-bottom: 20px;
    background: rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(4px);
  }
  .hero-wine-title {
    font-family: 'Playfair Display', 'Cormorant Garamond', serif;
    font-size: clamp(32px, 6vw, 62px);
    font-weight: 800;
    letter-spacing: 2px;
    background: var(--gold-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    line-height: 1.15;
    margin: 0 0 16px 0;
    text-transform: uppercase;
  }
  .hero-signature-quote {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(22px, 3.5vw, 34px);
    font-style: italic;
    color: #fde047;
    margin: 0 0 18px 0;
    letter-spacing: 1px;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.7);
  }
  .hero-wine-desc {
    font-size: clamp(14px, 2vw, 17px);
    color: #f1f5f9;
    letter-spacing: 0.5px;
    line-height: 1.7;
    max-width: 680px;
    margin: 0 auto 30px auto;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.9);
  }
  .btn-gold-luxury {
    display: inline-block;
    background: var(--gold-gradient);
    color: #12030a;
    font-family: 'Outfit', sans-serif;
    font-weight: 800;
    font-size: 13.5px;
    letter-spacing: 2.5px;
    padding: 15px 40px;
    border-radius: 50px;
    text-decoration: none;
    box-shadow: 0 8px 25px rgba(230, 202, 101, 0.35);
    transition: all 0.3s ease;
    border: 1px solid #fff3bd;
    text-transform: uppercase;
  }
  .btn-gold-luxury:hover {
    transform: translateY(-3px) scale(1.03);
    box-shadow: 0 12px 32px rgba(254, 240, 138, 0.55);
    color: #000;
  }

  /* Story & Certification Section */
  .story-cert-section {
    background: #ffffff;
    padding: 60px 20px;
    color: #0f172a;
  }
  .story-cert-inner {
    max-width: 1100px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 40px;
    align-items: center;
  }
  @media (max-width: 820px) {
    .story-cert-inner {
      grid-template-columns: 1fr;
      text-align: center;
      gap: 30px;
    }
  }
  .story-tag {
    color: #b45309;
    font-size: 11px;
    letter-spacing: 3px;
    text-transform: uppercase;
    font-weight: 700;
    margin-bottom: 8px;
  }
  .story-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(24px, 4vw, 36px);
    font-weight: 800;
    color: #12030a;
    line-height: 1.25;
    margin: 0 0 16px 0;
  }
  .story-desc {
    font-size: 14.5px;
    color: #475569;
    line-height: 1.75;
    margin: 0 0 20px 0;
  }
  .cert-badges-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    background: #fdfbf7;
    padding: 24px;
    border-radius: 12px;
    border: 1px solid rgba(217, 119, 6, 0.2);
    box-shadow: 0 4px 15px rgba(0,0,0,0.02);
  }
  @media (max-width: 500px) {
    .cert-badges-grid {
      grid-template-columns: 1fr;
      gap: 12px;
    }
  }
  .cert-item {
    text-align: center;
  }
  .cert-icon {
    font-size: 28px;
    margin-bottom: 6px;
    display: block;
  }
  .cert-label {
    font-size: 12px;
    font-weight: 800;
    color: #12030a;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .cert-sub {
    font-size: 11px;
    color: #64748b;
    margin-top: 2px;
  }

  /* Best Seller Category Slider (Matching Reference Image 4 & 5) */
  .category-slider-section {
    background: var(--wine-dark);
    padding: 70px 20px;
    color: #fff;
    text-align: center;
  }
  .sec-heading-gold {
    font-family: 'Playfair Display', serif;
    font-size: clamp(24px, 4vw, 36px);
    font-weight: 800;
    letter-spacing: 2px;
    background: var(--gold-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    text-transform: uppercase;
    margin: 0 0 8px 0;
  }
  .sec-subtitle-gold {
    font-size: 13px;
    color: #cbd5e1;
    letter-spacing: 1px;
    margin: 0 auto 36px auto;
    max-width: 600px;
  }
  .home-category-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    max-width: 1200px;
    margin: 0 auto;
  }
  @media (max-width: 650px) {
    .home-category-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
    }
  }
  .home-cat-card {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    aspect-ratio: 1/1;
    display: block;
    text-decoration: none;
    border: 1px solid rgba(217, 119, 6, 0.3);
    box-shadow: 0 8px 25px rgba(0,0,0,0.4);
    background: #0d0208;
    transition: transform 0.3s ease, border-color 0.3s ease;
  }
  .home-cat-card:hover {
    transform: translateY(-4px);
    border-color: #f59e0b;
  }
  .home-cat-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
  }
  .home-cat-card:hover img {
    transform: scale(1.08);
  }
  .home-cat-badge {
    position: absolute;
    bottom: 12px;
    left: 10px;
    right: 10px;
    background: rgba(255, 255, 255, 0.94);
    backdrop-filter: blur(6px);
    color: #12030a;
    font-size: 12px;
    font-weight: 700;
    padding: 9px 12px;
    border-radius: 50px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    transition: all 0.2s;
  }
  .home-cat-card:hover .home-cat-badge {
    background: #ffffff;
    color: #b45309;
  }

  /* Product Showcase Rows */
  .product-showcase-section {
    background: var(--wine-surface);
    padding: 70px 20px;
    color: #fff;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
  }
  .showcase-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 1200px;
    margin: 0 auto 30px auto;
    flex-wrap: wrap;
    gap: 12px;
  }
  .showcase-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(20px, 3.5vw, 30px);
    font-weight: 800;
    color: var(--gold-bright);
    margin: 0;
  }
  .showcase-link {
    color: #cbd5e1;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1px;
    text-decoration: none;
    text-transform: uppercase;
    transition: color 0.2s;
  }
  .showcase-link:hover {
    color: var(--gold-bright);
  }
  .product-showcase-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    max-width: 1200px;
    margin: 0 auto;
  }
  @media (max-width: 1050px) {
    .product-showcase-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: 16px;
    }
  }
  @media (max-width: 500px) {
    .product-showcase-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: 10px;
    }
  }

  /* Split Reviews & Gallery Section (Matching Reference Image 5) */
  .split-reviews-gallery {
    background: #11030b;
    padding: 80px 20px;
    color: #fff;
    border-top: 1px solid rgba(217, 119, 6, 0.2);
  }
  .split-container {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1.2fr;
    gap: 45px;
    align-items: start;
  }
  @media (max-width: 900px) {
    .split-container {
      grid-template-columns: 1fr;
      gap: 50px;
    }
  }

  /* Customer Reviews Card */
  .review-box-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(217, 119, 6, 0.3);
    border-radius: 14px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.4);
    position: relative;
  }
  .review-stars {
    color: #fbbf24;
    font-size: 18px;
    margin-bottom: 12px;
  }
  .review-quote {
    font-size: 15px;
    line-height: 1.7;
    color: #e2e8f0;
    font-style: italic;
    margin-bottom: 20px;
  }
  .review-author {
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .review-author img {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #d97706;
  }
  .review-author-name {
    font-weight: 700;
    font-size: 14px;
    color: #fff;
  }
  .review-author-badge {
    font-size: 11px;
    color: #10b981;
    font-weight: 600;
  }

  /* Our Gallery Grid */
  .gallery-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
  }
  .gallery-item {
    border-radius: 10px;
    overflow: hidden;
    aspect-ratio: 1/1;
    position: relative;
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    background: #18060f;
  }
  .gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
  }
  .gallery-item:hover img {
    transform: scale(1.1);
  }

  /* 4-Pillar Value Proposition Bar (Sage Green Pastel - Strictly NO WhatsApp!) */
  .value-pillars-bar {
    background: #eaf3ee;
    padding: 36px 20px;
    border-top: 1px solid #d1e5db;
    border-bottom: 1px solid #d1e5db;
    color: #143325;
  }
  .value-pillars-inner {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
  }
  @media (max-width: 880px) {
    .value-pillars-inner {
      grid-template-columns: repeat(2, 1fr);
      gap: 24px;
    }
  }
  @media (max-width: 500px) {
    .value-pillars-inner {
      grid-template-columns: 1fr;
      gap: 16px;
      text-align: center;
    }
  }
  .pillar-item {
    display: flex;
    align-items: center;
    gap: 14px;
  }
  @media (max-width: 500px) {
    .pillar-item {
      flex-direction: column;
      gap: 6px;
    }
  }
  .pillar-icon {
    font-size: 28px;
    color: #15803d;
    flex-shrink: 0;
  }
  .pillar-text h4 {
    margin: 0 0 3px 0;
    font-size: 13.5px;
    font-weight: 800;
    color: #0f2c1f;
    letter-spacing: 0.5px;
    text-transform: uppercase;
  }
  .pillar-text p {
    margin: 0;
    font-size: 12px;
    color: #3e5a4d;
    line-height: 1.4;
  }
</style>

<!-- Hero Section (Matching Reference Image 5) -->
<section class="hero-wine">
  <video autoplay muted loop playsinline poster="{{ asset('images/hero-poster.jpg') }}">
    <source src="https://assets.mixkit.co/videos/preview/mixkit-pastry-chef-making-a-cake-41223-large.mp4" type="video/mp4">
    <source src="https://cdn.coverr.co/videos/coverr-baking-fresh-bread-5764/1080p.mp4" type="video/mp4">
  </video>

  <div class="hero-wine-overlay">
    <span class="hero-brand-badge">⚜️ PUSAT HAMPERS INDONESIA ⚜️</span>
    <h1 class="hero-wine-title">{{ config('site.brand') }}</h1>
    <p class="hero-signature-quote">"Have a bite, you can't stop!"</p>
    <p class="hero-wine-desc">
      Cita rasa premium Indonesia untuk setiap momen berharga, dibuat dengan bahan-bahan pilihan berkualitas tinggi berstandar artisanal internasional.
    </p>
    <a href="{{ route('shop') }}" class="btn-gold-luxury">JELAJAHI KOLEKSI</a>
  </div>
</section>

<!-- Brand Story & Quality Certification Section -->
<section class="story-cert-section">
  <div class="story-cert-inner">
    <div>
      <div class="story-tag">⚜️ CERITA RASA KAMI ⚜️</div>
      <h2 class="story-title">Dedikasi Kemewahan & Kelezatan Sejak Awal</h2>
      <p class="story-desc">
        Setiap produk diracik secara teliti menggunakan mentega impor murni, rempah pilihan, dan bahan alami terbaik tanpa pemanis buatan maupun pengawet berbahaya. Kami mempersembahkan kehangatan bingkisan premium yang meninggalkan kenangan tak terlupakan bagi keluarga, kerabat, dan mitra bisnis Anda.
      </p>
      <a href="{{ route('shop') }}" style="color: #b45309; font-weight: 700; font-size: 13px; text-decoration: none; letter-spacing: 1px;">
        LIHAT SEMUA PILIHAN HAMPERS →
      </a>
    </div>

    <div>
      <div class="cert-badges-grid">
        <div class="cert-item">
          <span class="cert-icon">🌿</span>
          <div class="cert-label">100% Halal</div>
          <div class="cert-sub">LPPOM MUI & BPOM</div>
        </div>
        <div class="cert-item">
          <span class="cert-icon">🧈</span>
          <div class="cert-label">Pure Butter</div>
          <div class="cert-sub">Bahan Baku Premium</div>
        </div>
        <div class="cert-item">
          <span class="cert-icon">🛡️</span>
          <div class="cert-label">0% Pengawet</div>
          <div class="cert-sub">Higienis & Segar</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Best Seller / Kategori Produk Slider (Matching Reference Image 4 & 5) -->
<section class="category-slider-section">
  <div style="max-width: 1200px; margin: 0 auto;">
    <h2 class="sec-heading-gold">KATEGORI PRODUK UNGGULAN</h2>
    <p class="sec-subtitle-gold">Pilihan bingkisan mewah dan kuliner artisanal favorit untuk merayakan hari istimewa.</p>

    <div class="home-category-grid">
      @foreach ($categories as $c)
        @php
          $catImage = asset('images/cat-' . $c->slug . '.jpg');
        @endphp
        <a href="{{ route('category', $c->slug) }}" class="home-cat-card">
          <img src="{{ $catImage }}" alt="{{ $c->title }}" loading="lazy" onerror="this.src='{{ asset('images/cat-hampers.jpg') }}';">
          <div class="home-cat-badge">
            {{ $c->title }} ({{ $c->products_count }} Produk)
          </div>
        </a>
      @endforeach
    </div>
  </div>
</section>

<!-- Product Showcase Row 1: Big Bites & Koleksi Favorit -->
<section class="product-showcase-section">
  <div class="showcase-header">
    <h2 class="showcase-title">Pilihan Favorit & Big Bites</h2>
    <a href="{{ route('shop') }}" class="showcase-link">Lihat Semua →</a>
  </div>

  <div class="product-showcase-grid">
    @foreach ($best->take(4) as $p)
      @include('partials.card', ['item' => $p, 'from' => true])
    @endforeach
  </div>
</section>

<!-- Product Showcase Row 2: Kue Tart & Artisanal Bakery -->
<section class="product-showcase-section" style="background: var(--wine-dark);">
  <div class="showcase-header">
    <h2 class="showcase-title">Kue & Tart Artisanal</h2>
    <a href="{{ route('category', 'cakes') }}" class="showcase-link">Lihat Semua Kue →</a>
  </div>

  <div class="product-showcase-grid">
    @foreach ($cakes->take(4) as $p)
      @include('partials.card', ['item' => $p, 'slug' => 'cakes', 'from' => true])
    @endforeach
  </div>
</section>

<!-- Product Showcase Row 3: Parsel & Hampers Mewah -->
<section class="product-showcase-section">
  <div class="showcase-header">
    <h2 class="showcase-title">Hampers & Paket Bingkisan VIP</h2>
    <a href="{{ route('category', 'hampers') }}" class="showcase-link">Lihat Semua Hampers →</a>
  </div>

  <div class="product-showcase-grid">
    @foreach ($hampers->take(4) as $p)
      @include('partials.card', ['item' => $p, 'slug' => 'hampers', 'from' => true])
    @endforeach
  </div>
</section>

<!-- Split Section: What Our Customers Say & Our Gallery (Matching Reference Image 5) -->
<section class="split-reviews-gallery" id="gallery">
  <div class="split-container">
    <!-- Left Column: What Our Customers Say -->
    <div>
      <div style="color: #fbbf24; font-size: 11px; letter-spacing: 2.5px; text-transform: uppercase; font-weight: 700; margin-bottom: 8px;">
        ⭐ TESTIMONI PELANGGAN VIP ⭐
      </div>
      <h2 style="font-family: 'Playfair Display', serif; font-size: clamp(24px, 3.5vw, 32px); color: #fff; margin: 0 0 20px 0;">
        What Our Customers Say
      </h2>

      @if(isset($reviews) && $reviews->isNotEmpty())
        @php $featuredRev = $reviews->first(); @endphp
        <div class="review-box-card">
          <div class="review-stars">★★★★★</div>
          <div class="review-quote">
            "{{ $featuredRev->body }}"
          </div>
          <div class="review-author">
            <img src="{{ $featuredRev->avatar_url }}" alt="{{ $featuredRev->reviewer_name }}" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($featuredRev->reviewer_name) }}&background=b45309&color=fff';">
            <div>
              <div class="review-author-name">{{ $featuredRev->reviewer_name }}</div>
              <div class="review-author-badge">✓ Terverifikasi Google Reviews</div>
            </div>
          </div>
        </div>
      @else
        <div class="review-box-card">
          <div class="review-stars">★★★★★</div>
          <div class="review-quote">
            "Rasa kuenya sangat lezat, butter impornya harum sekali dan kemasan hampersnya benar-benar mewah. Sangat puas untuk hadiah rekan bisnis dan keluarga."
          </div>
          <div class="review-author">
            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80" alt="Pelanggan VIP">
            <div>
              <div class="review-author-name">Adeline Wijaya</div>
              <div class="review-author-badge">✓ Terverifikasi Google Reviews</div>
            </div>
          </div>
        </div>
      @endif
    </div>

    <!-- Right Column: Our Gallery -->
    <div>
      <div style="color: #fbbf24; font-size: 11px; letter-spacing: 2.5px; text-transform: uppercase; font-weight: 700; margin-bottom: 8px;">
        📸 GALERI FOTO ARTISANAL
      </div>
      <h2 style="font-family: 'Playfair Display', serif; font-size: clamp(24px, 3.5vw, 32px); color: #fff; margin: 0 0 20px 0;">
        Our Gallery
      </h2>

      <div class="gallery-grid">
        <div class="gallery-item">
          <img src="{{ asset('images/feature.jpg') }}" alt="Hampers Creation" loading="lazy">
        </div>
        <div class="gallery-item">
          <img src="{{ asset('images/cat-hampers.jpg') }}" alt="Luxury Box" loading="lazy">
        </div>
        <div class="gallery-item">
          <img src="{{ asset('images/cat-cakes.jpg') }}" alt="Artisanal Tart" loading="lazy">
        </div>
        <div class="gallery-item">
          <img src="{{ asset('images/rose.jpg') }}" alt="Celebration Parcel" loading="lazy">
        </div>
        <div class="gallery-item">
          <img src="{{ asset('images/cat-cookies.jpg') }}" alt="Gourmet Cookies" loading="lazy">
        </div>
        <div class="gallery-item">
          <img src="{{ asset('images/events.jpg') }}" alt="Festive Gift Set" loading="lazy">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 4-Pillar Value Proposition Bar (Matching Reference Image 5 - Strictly NO WhatsApp!) -->
<section class="value-pillars-bar">
  <div class="value-pillars-inner">
    <div class="pillar-item">
      <span class="pillar-icon">⭐</span>
      <div class="pillar-text">
        <h4>Produk Berkualitas</h4>
        <p>100% bahan premium tanpa bahan pengawet berbahaya</p>
      </div>
    </div>

    <div class="pillar-item">
      <span class="pillar-icon">🚚</span>
      <div class="pillar-text">
        <h4>Pengiriman Cepat</h4>
        <p>Pengemasan aman & kurir terpercaya tiba tepat waktu</p>
      </div>
    </div>

    <div class="pillar-item">
      <span class="pillar-icon">💳</span>
      <div class="pillar-text">
        <h4>Pembayaran Fleksibel</h4>
        <p>Mendukung Virtual Account, QRIS, & Kartu Kredit</p>
      </div>
    </div>

    <div class="pillar-item">
      <span class="pillar-icon">🛡️</span>
      <div class="pillar-text">
        <h4>Jaminan Kualitas</h4>
        <p>Garansi penggantian bila paket rusak saat pengiriman</p>
      </div>
    </div>
  </div>
</section>
@endsection
