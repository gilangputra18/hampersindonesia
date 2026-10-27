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

  /* Best Seller Category Slider (Matching Reference Image 1) */
  .category-slider-section {
    background: var(--wine-dark);
    padding: 70px 20px 50px 20px;
    color: #fff;
    text-align: center;
    position: relative;
  }
  .cat-eyebrow-ref {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-style: italic;
    font-size: clamp(20px, 3.5vw, 26px);
    color: #e6ca65;
    letter-spacing: 1px;
    margin-bottom: 2px;
  }
  .cat-title-ref {
    font-family: 'Playfair Display', serif;
    font-size: clamp(26px, 4.5vw, 40px);
    font-weight: 800;
    background: var(--gold-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    text-transform: capitalize;
    margin: 0 0 28px 0;
    letter-spacing: 1px;
  }
  .cat-slider-outer {
    position: relative;
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .cat-slider-track {
    display: flex;
    gap: 20px;
    overflow-x: auto;
    scroll-behavior: smooth;
    scrollbar-width: none;
    -ms-overflow-style: none;
    padding: 10px 4px 20px 4px;
    width: 100%;
  }
  .cat-slider-track::-webkit-scrollbar {
    display: none;
  }
  .ref-cat-card {
    flex: 0 0 calc(25% - 15px);
    min-width: 230px;
    position: relative;
    border-radius: 14px;
    overflow: hidden;
    aspect-ratio: 1/1;
    display: block;
    text-decoration: none;
    border: 1.5px solid rgba(230, 202, 101, 0.3);
    box-shadow: 0 8px 24px rgba(0,0,0,0.5);
    background: #0d0208;
    transition: transform 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
  }
  @media (max-width: 768px) {
    .ref-cat-card {
      flex: 0 0 calc(50% - 10px);
      min-width: 160px;
    }
  }
  .ref-cat-card:hover {
    transform: translateY(-5px);
    border-color: #fef08a;
    box-shadow: 0 12px 30px rgba(230, 202, 101, 0.25);
  }
  .ref-cat-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
  }
  .ref-cat-card:hover img {
    transform: scale(1.08);
  }
  .ref-cat-pill {
    position: absolute;
    bottom: 14px;
    left: 12px;
    right: 12px;
    background: #ffffff;
    color: #12030a;
    font-family: 'Outfit', sans-serif;
    font-size: 12.5px;
    font-weight: 700;
    padding: 9px 12px;
    border-radius: 50px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.4);
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    transition: all 0.25s ease;
  }
  .ref-cat-card:hover .ref-cat-pill {
    background: #fff3c4;
    color: #b45309;
  }
  .cat-nav-btn {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(22, 5, 14, 0.9);
    border: 1.5px solid #e6ca65;
    color: #fef08a;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    cursor: pointer;
    flex-shrink: 0;
    transition: all 0.2s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.6);
    user-select: none;
    z-index: 5;
  }
  .cat-nav-btn:hover {
    background: #e6ca65;
    color: #16050e;
    transform: scale(1.1);
  }
  @media (max-width: 600px) {
    .cat-nav-btn {
      display: none;
    }
  }

  /* Product Showcase Rows (Matching Reference Image 1) */
  .showcase-row-section {
    padding: 60px 20px 50px 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
  }
  .showcase-header-ref {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 1200px;
    margin: 0 auto 24px auto;
  }
  .showcase-title-ref {
    font-family: 'Playfair Display', serif;
    font-size: clamp(24px, 4vw, 34px);
    font-weight: 700;
    color: #fef08a;
    margin: 0;
    letter-spacing: 0.5px;
  }
  .showcase-arrow-btn {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.08);
    border: 1.5px solid rgba(230, 202, 101, 0.6);
    color: #fef08a;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    text-decoration: none;
    transition: all 0.2s ease;
  }
  .showcase-arrow-btn:hover {
    background: #e6ca65;
    color: #16050e;
    transform: scale(1.08);
  }

  /* White Product Card (Matching Reference Image 1) */
  .ref-white-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    max-width: 1200px;
    margin: 0 auto;
  }
  @media (max-width: 1050px) {
    .ref-white-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: 16px;
    }
  }
  @media (max-width: 500px) {
    .ref-white-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: 10px;
    }
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
    width: 100%;
    height: 100%;
    object-fit: cover;
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
    font-size: 15.5px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 6px 0;
    line-height: 1.35;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    min-height: 42px;
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
    font-size: 13.5px;
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
    text-decoration: underline;
    transform: scale(1.04);
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

<!-- Best Seller / Kategori Produk Slider (Matching Reference Image 1) -->
<section class="category-slider-section">
  <div style="max-width: 1200px; margin: 0 auto;">
    <div class="cat-eyebrow-ref">Best Seller</div>
    <h2 class="cat-title-ref">Kategori Produk</h2>

    <div class="cat-slider-outer">
      <button type="button" class="cat-nav-btn prev-btn" id="catSliderPrev" aria-label="Kategori Sebelumnya">❮</button>
      
      <div class="cat-slider-track" id="catSliderTrack">
        @foreach ($categories as $c)
          @php
            $catImage = asset('images/cat-' . $c->slug . '.jpg');
          @endphp
          <a href="{{ route('category', $c->slug) }}" class="ref-cat-card">
            <img src="{{ $catImage }}" alt="{{ $c->title }}" loading="lazy" onerror="this.src='{{ asset('images/cat-hampers.jpg') }}';">
            <div class="ref-cat-pill">
              {{ $c->title }}
            </div>
          </a>
        @endforeach
      </div>

      <button type="button" class="cat-nav-btn next-btn" id="catSliderNext" aria-label="Kategori Berikutnya">❯</button>
    </div>
  </div>
</section>

<!-- Product Showcase Row 1: Big Bites (Matching Reference Image 1) -->
<section class="showcase-row-section" style="background: var(--wine-surface);">
  <div class="showcase-header-ref">
    <h2 class="showcase-title-ref">Big Bites</h2>
    <a href="{{ route('category', 'light-bites') }}" class="showcase-arrow-btn" title="Lihat Semua Big Bites">❯</a>
  </div>

  <div class="ref-white-grid">
    @foreach ($bigBites->take(4) as $p)
      @include('partials.card-white', ['item' => $p, 'slug' => 'light-bites'])
    @endforeach
  </div>
</section>

<!-- Product Showcase Row 2: Loyang Mini (Matching Reference Image 1) -->
<section class="showcase-row-section" style="background: var(--wine-dark);">
  <div class="showcase-header-ref">
    <h2 class="showcase-title-ref">Loyang Mini</h2>
    <a href="{{ route('category', 'cakes') }}" class="showcase-arrow-btn" title="Lihat Semua Loyang Mini">❯</a>
  </div>

  <div class="ref-white-grid">
    @foreach ($loyangMini->take(4) as $p)
      @include('partials.card-white', ['item' => $p, 'slug' => 'cakes'])
    @endforeach
  </div>
</section>

<!-- Product Showcase Row 3: Other (Matching Reference Image 1) -->
<section class="showcase-row-section" style="background: var(--wine-surface);">
  <div class="showcase-header-ref">
    <h2 class="showcase-title-ref">Other</h2>
    <a href="{{ route('shop') }}" class="showcase-arrow-btn" title="Lihat Semua Koleksi">❯</a>
  </div>

  <div class="ref-white-grid">
    @foreach ($other->take(4) as $p)
      @include('partials.card-white', ['item' => $p, 'slug' => 'hampers'])
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

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const track = document.getElementById('catSliderTrack');
    const prev = document.getElementById('catSliderPrev');
    const next = document.getElementById('catSliderNext');
    if (track && prev && next) {
      prev.addEventListener('click', function() {
        track.scrollBy({ left: -260, behavior: 'smooth' });
      });
      next.addEventListener('click', function() {
        track.scrollBy({ left: 260, behavior: 'smooth' });
      });
    }
  });
</script>
@endsection
