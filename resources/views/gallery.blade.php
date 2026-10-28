@extends('layouts.app')

@section('title', 'Gallery Produk & Karya | ' . config('site.brand'))

@section('content')
<style>
  /* Gallery Page Shell */
  .gallery-page-wrapper {
    background-color: #fbf9f5;
    background: linear-gradient(180deg, #fdfbf7 0%, #f7f3ea 100%);
    min-height: 100vh;
    padding: 36px 16px 90px;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
  }

  /* Header & Breadcrumb */
  .gallery-header-section {
    text-align: center;
    max-width: 900px;
    margin: 0 auto 32px;
  }
  .gallery-breadcrumb {
    font-size: 13.5px;
    color: #8c7355;
    margin-bottom: 12px;
    letter-spacing: 0.5px;
  }
  .gallery-breadcrumb a {
    color: #8c7355;
    text-decoration: none;
    transition: color 0.2s;
  }
  .gallery-breadcrumb a:hover {
    color: #9f183c;
    text-decoration: underline;
  }
  .gallery-breadcrumb span {
    color: #caa436;
    font-weight: 600;
  }
  .gallery-main-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 46px;
    font-weight: 600;
    color: #caa436;
    letter-spacing: 1px;
    margin: 0 0 10px;
    line-height: 1.15;
  }
  .gallery-subtitle {
    font-size: 14.5px;
    color: #614a30;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
  }

  /* Filter Tabs */
  .gallery-tabs-wrap {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-bottom: 30px;
    flex-wrap: wrap;
  }
  .gallery-filter-btn {
    background: #ffffff;
    border: 1px solid #dfcfb2;
    color: #524233;
    padding: 8px 18px;
    border-radius: 25px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s ease;
    box-shadow: 0 2px 6px rgba(0,0,0,0.03);
  }
  .gallery-filter-btn:hover {
    border-color: #caa436;
    color: #9f183c;
    transform: translateY(-1px);
  }
  .gallery-filter-btn.active {
    background: #320712;
    border-color: #d4af37;
    color: #fef08a;
    box-shadow: 0 4px 12px rgba(50, 7, 18, 0.25);
  }

  /* 3-Column Luxury Photo Grid (Matching Reference Image) */
  .gallery-cards-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
    max-width: 1040px;
    margin: 0 auto;
  }

  /* Single Gallery Card (Dark Luxury Staging) */
  .gallery-card-item {
    position: relative;
    aspect-ratio: 1 / 1;
    overflow: hidden;
    background: #0f0a0d;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(0,0,0,0.15);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  .gallery-card-item:hover {
    transform: scale(1.015);
    box-shadow: 0 8px 24px rgba(0,0,0,0.3);
    z-index: 2;
  }

  /* Card Image */
  .gallery-card-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.5s ease;
  }
  .gallery-card-item:hover img {
    transform: scale(1.06);
  }

  /* Top-Left Brand Logo Crest Watermark */
  .gallery-card-top-logo {
    position: absolute;
    top: 14px;
    left: 14px;
    display: flex;
    align-items: center;
    gap: 6px;
    z-index: 2;
    pointer-events: none;
    text-shadow: 0 2px 4px rgba(0,0,0,0.8);
  }
  .gallery-top-crest {
    font-size: 16px;
    line-height: 1;
    color: #e6ca65;
  }
  .gallery-top-name {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 1.2px;
    color: #fef08a;
    text-transform: uppercase;
    line-height: 1.1;
  }

  /* Bottom-Left Halal Indonesia Watermark Badge */
  .gallery-card-halal {
    position: absolute;
    bottom: 12px;
    left: 12px;
    display: flex;
    align-items: center;
    gap: 4px;
    background: rgba(10, 5, 5, 0.65);
    backdrop-filter: blur(4px);
    border: 1px solid rgba(230, 202, 101, 0.35);
    padding: 3px 8px;
    border-radius: 12px;
    z-index: 2;
    pointer-events: none;
  }
  .gallery-halal-svg {
    width: 13px;
    height: 13px;
    fill: #22c55e;
  }
  .gallery-halal-text {
    font-size: 8.5px;
    font-weight: 700;
    color: #f8fafc;
    letter-spacing: 0.5px;
  }

  /* Bottom-Right Luxury Tagline / Name */
  .gallery-card-bottom-tag {
    position: absolute;
    bottom: 12px;
    right: 12px;
    text-align: right;
    z-index: 2;
    pointer-events: none;
    text-shadow: 0 2px 6px rgba(0,0,0,0.9);
  }
  .gallery-item-name {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 12.5px;
    font-weight: 700;
    font-style: italic;
    color: #fef08a;
    display: block;
    line-height: 1.2;
  }
  .gallery-item-tagline {
    font-size: 8.5px;
    letter-spacing: 0.5px;
    color: rgba(255, 255, 255, 0.85);
    font-style: italic;
    display: block;
    margin-top: 2px;
  }

  /* Hover Zoom Glass Overlay */
  .gallery-hover-overlay {
    position: absolute;
    inset: 0;
    background: rgba(15, 5, 10, 0.3);
    opacity: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: opacity 0.3s ease;
    z-index: 1;
  }
  .gallery-card-item:hover .gallery-hover-overlay {
    opacity: 1;
  }
  .gallery-zoom-pill {
    background: rgba(255, 255, 255, 0.9);
    color: #1a080c;
    font-size: 11.5px;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    transform: translateY(8px);
    transition: transform 0.25s ease;
  }
  .gallery-card-item:hover .gallery-zoom-pill {
    transform: translateY(0);
  }

  /* Responsive Grid */
  @media (max-width: 820px) {
    .gallery-cards-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: 6px;
    }
    .gallery-main-title {
      font-size: 36px;
    }
  }
  @media (max-width: 520px) {
    .gallery-cards-grid {
      grid-template-columns: 1fr;
      gap: 10px;
    }
    .gallery-page-wrapper {
      padding: 20px 10px 60px;
    }
    .gallery-main-title {
      font-size: 32px;
    }
  }

  /* Fullscreen Interactive Lightbox Modal */
  .gallery-lightbox-modal {
    position: fixed;
    inset: 0;
    background: rgba(10, 2, 5, 0.94);
    backdrop-filter: blur(8px);
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    transition: opacity 0.25s ease;
  }
  .gallery-lightbox-modal.active {
    display: flex;
    opacity: 1;
  }
  .lightbox-box {
    position: relative;
    max-width: 760px;
    width: 100%;
    text-align: center;
  }
  .lightbox-image-wrap {
    max-height: 70vh;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0,0,0,0.8);
    border: 1.5px solid #d4af37;
    background: #000;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .lightbox-image-wrap img {
    max-width: 100%;
    max-height: 70vh;
    object-fit: contain;
    display: block;
  }
  .lightbox-caption-bar {
    margin-top: 14px;
    color: #ffffff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
  }
  .lightbox-meta h3 {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 18px;
    color: #fef08a;
    margin: 0 0 4px;
    text-align: left;
  }
  .lightbox-meta p {
    font-size: 12px;
    color: #cbd5e1;
    margin: 0;
    text-align: left;
  }
  .lightbox-close-btn {
    position: absolute;
    top: -46px;
    right: 0;
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: #ffffff;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    font-size: 18px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
  }
  .lightbox-close-btn:hover {
    background: #9f183c;
    border-color: #9f183c;
  }
  .lightbox-nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: #ffffff;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    font-size: 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
  }
  .lightbox-nav-btn:hover {
    background: #d4af37;
    color: #1a080c;
  }
  .lightbox-prev-btn { left: -60px; }
  .lightbox-next-btn { right: -60px; }
  @media (max-width: 860px) {
    .lightbox-prev-btn { left: 10px; }
    .lightbox-next-btn { right: 10px; }
  }
</style>

<div class="gallery-page-wrapper">
  <!-- Header & Breadcrumb -->
  <div class="gallery-header-section">
    <div class="gallery-breadcrumb">
      <button type="button" onclick="window.history.length > 1 ? window.history.back() : window.location.href='{{ route('home') }}'" class="btn-crumb-back" title="Kembali ke halaman sebelumnya">
        <span>❮</span> <span>Kembali</span>
      </button>
      <a href="{{ route('home') }}">Home</a> &raquo; <span>Gallery</span>
    </div>
    <h1 class="gallery-main-title">Gallery</h1>
    <p class="gallery-subtitle">
      Koleksi karya seni hampers premium, kue lapis legit otentik, dan aneka suguhan artisan istimewa dari {{ config('site.brand') }}.
    </p>
  </div>

  <!-- Filter Tabs -->
  <div class="gallery-tabs-wrap">
    <button type="button" class="gallery-filter-btn active" data-filter="all">✨ Semua Karya</button>
    <button type="button" class="gallery-filter-btn" data-filter="lapis">🥮 Lapis &amp; Cakes</button>
    <button type="button" class="gallery-filter-btn" data-filter="bites">🍫 Choco &amp; Bites</button>
    <button type="button" class="gallery-filter-btn" data-filter="hampers">🎁 Luxury Hampers</button>
    <button type="button" class="gallery-filter-btn" data-filter="cookies">🍪 Artisan Cookies</button>
  </div>

  <!-- 3-Column Gallery Grid (Matching Reference Image) -->
  <div class="gallery-cards-grid" id="galleryGrid">

    <!-- Item 1: Lapis Legit Original Slices -->
    <div class="gallery-card-item" data-category="lapis"
         data-img="{{ asset('images/cat-cakes.jpg') }}"
         data-title="Lapis Legit Original Slices"
         data-desc="Lapisan butter Wijsman otentik dipanggang perlapis dengan kelembutan sempurna.">
      <img src="{{ asset('images/cat-cakes.jpg') }}" alt="Lapis Legit Original Slices" loading="lazy">
      <div class="gallery-card-top-logo">
        <span class="gallery-top-crest">⚜️</span>
        <span class="gallery-top-name">{{ config('site.brand') }}</span>
      </div>
      <div class="gallery-card-halal">
        <svg class="gallery-halal-svg" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
        <span class="gallery-halal-text">HALAL INDONESIA</span>
      </div>
      <div class="gallery-card-bottom-tag">
        <span class="gallery-item-name">Lapis Legit Original</span>
        <span class="gallery-item-tagline">Once You Bite, You Can't Stop!</span>
      </div>
      <div class="gallery-hover-overlay">
        <span class="gallery-zoom-pill">🔍 Perbesar Foto</span>
      </div>
    </div>

    <!-- Item 2: Lapis Legit Mini Bites Platter -->
    <div class="gallery-card-item" data-category="lapis"
         data-img="{{ asset('images/black-forest.jpg') }}"
         data-title="Lapis Legit Mini Bites"
         data-desc="Potongan gigitan lapis legit mewah yang disajikan di atas piring saji eksklusif.">
      <img src="{{ asset('images/black-forest.jpg') }}" alt="Lapis Legit Mini Bites" loading="lazy">
      <div class="gallery-card-top-logo">
        <span class="gallery-top-crest">⚜️</span>
        <span class="gallery-top-name">{{ config('site.brand') }}</span>
      </div>
      <div class="gallery-card-halal">
        <svg class="gallery-halal-svg" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
        <span class="gallery-halal-text">HALAL INDONESIA</span>
      </div>
      <div class="gallery-card-bottom-tag">
        <span class="gallery-item-name">Mini Bites Platter</span>
        <span class="gallery-item-tagline">Premium Quality Cakes</span>
      </div>
      <div class="gallery-hover-overlay">
        <span class="gallery-zoom-pill">🔍 Perbesar Foto</span>
      </div>
    </div>

    <!-- Item 3: Mix Small 4 Rasa (12 Bites Box) -->
    <div class="gallery-card-item" data-category="bites"
         data-img="{{ asset('images/box-of-4.jpg') }}"
         data-title="Mix Small 4 Rasa (12 Bites)"
         data-desc="Kombinasi 4 varian rasa lapis legit pilihan dalam kemasan gift box elegan.">
      <img src="{{ asset('images/box-of-4.jpg') }}" alt="Mix Small 4 Rasa (12 Bites)" loading="lazy">
      <div class="gallery-card-top-logo">
        <span class="gallery-top-crest">⚜️</span>
        <span class="gallery-top-name">{{ config('site.brand') }}</span>
      </div>
      <div class="gallery-card-halal">
        <svg class="gallery-halal-svg" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
        <span class="gallery-halal-text">HALAL INDONESIA</span>
      </div>
      <div class="gallery-card-bottom-tag">
        <span class="gallery-item-name">Mix Small 4 Rasa</span>
        <span class="gallery-item-tagline">12 Bites Gift Box</span>
      </div>
      <div class="gallery-hover-overlay">
        <span class="gallery-zoom-pill">🔍 Perbesar Foto</span>
      </div>
    </div>

    <!-- Item 4: Lapis Loyang Mini Cempedak / Plum -->
    <div class="gallery-card-item" data-category="lapis"
         data-img="{{ asset('images/tiramisu.jpg') }}"
         data-title="Lapis Loyang Mini Plum &amp; Cempedak"
         data-desc="Sentuhan buah plum &amp; cempedak manis alami pada adonan lapis legit butter.">
      <img src="{{ asset('images/tiramisu.jpg') }}" alt="Lapis Loyang Mini Plum &amp; Cempedak" loading="lazy">
      <div class="gallery-card-top-logo">
        <span class="gallery-top-crest">⚜️</span>
        <span class="gallery-top-name">{{ config('site.brand') }}</span>
      </div>
      <div class="gallery-card-halal">
        <svg class="gallery-halal-svg" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
        <span class="gallery-halal-text">HALAL INDONESIA</span>
      </div>
      <div class="gallery-card-bottom-tag">
        <span class="gallery-item-name">Lapis Plum Square</span>
        <span class="gallery-item-tagline">Once You Bite, You Can't Stop!</span>
      </div>
      <div class="gallery-hover-overlay">
        <span class="gallery-zoom-pill">🔍 Perbesar Foto</span>
      </div>
    </div>

    <!-- Item 5: Marble Cake Small (12 pcs) -->
    <div class="gallery-card-item" data-category="lapis"
         data-img="{{ asset('images/butter-cookies-jar.jpg') }}"
         data-title="Marble Cake Small (12 pcs)"
         data-desc="Bolu marmer klasik dengan tekstur lembut wangi butter dan swirl cokelat premium.">
      <img src="{{ asset('images/butter-cookies-jar.jpg') }}" alt="Marble Cake Small (12 pcs)" loading="lazy">
      <div class="gallery-card-top-logo">
        <span class="gallery-top-crest">⚜️</span>
        <span class="gallery-top-name">{{ config('site.brand') }}</span>
      </div>
      <div class="gallery-card-halal">
        <svg class="gallery-halal-svg" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
        <span class="gallery-halal-text">HALAL INDONESIA</span>
      </div>
      <div class="gallery-card-bottom-tag">
        <span class="gallery-item-name">Marble Cake Small</span>
        <span class="gallery-item-tagline">12 Pcs Slide Box</span>
      </div>
      <div class="gallery-hover-overlay">
        <span class="gallery-zoom-pill">🔍 Perbesar Foto</span>
      </div>
    </div>

    <!-- Item 6: Lapis Pattern Harum / Spekkoek -->
    <div class="gallery-card-item" data-category="lapis"
         data-img="{{ asset('images/basque-burnt-cheesecake.jpg') }}"
         data-title="Lapis Legit Pattern Harum"
         data-desc="Corak pattern atas elegan dengan lapisan bumbu rempah spekkoek wangi semerbak.">
      <img src="{{ asset('images/basque-burnt-cheesecake.jpg') }}" alt="Lapis Legit Pattern Harum" loading="lazy">
      <div class="gallery-card-top-logo">
        <span class="gallery-top-crest">⚜️</span>
        <span class="gallery-top-name">{{ config('site.brand') }}</span>
      </div>
      <div class="gallery-card-halal">
        <svg class="gallery-halal-svg" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
        <span class="gallery-halal-text">HALAL INDONESIA</span>
      </div>
      <div class="gallery-card-bottom-tag">
        <span class="gallery-item-name">Lapis Pattern Harum</span>
        <span class="gallery-item-tagline">Once You Bite, You Can't Stop!</span>
      </div>
      <div class="gallery-hover-overlay">
        <span class="gallery-zoom-pill">🔍 Perbesar Foto</span>
      </div>
    </div>

    <!-- Item 7: Tropical Layer Set Exclusive -->
    <div class="gallery-card-item" data-category="lapis"
         data-img="{{ asset('images/berry-tart.jpg') }}"
         data-title="Tropical Layer Set"
         data-desc="Edisi koleksi lapis legit tropikal dengan packaging box botanikal mewah.">
      <img src="{{ asset('images/berry-tart.jpg') }}" alt="Tropical Layer Set" loading="lazy">
      <div class="gallery-card-top-logo">
        <span class="gallery-top-crest">⚜️</span>
        <span class="gallery-top-name">{{ config('site.brand') }}</span>
      </div>
      <div class="gallery-card-halal">
        <svg class="gallery-halal-svg" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
        <span class="gallery-halal-text">HALAL INDONESIA</span>
      </div>
      <div class="gallery-card-bottom-tag">
        <span class="gallery-item-name">Tropical Layer Set</span>
        <span class="gallery-item-tagline">Premium Quality Cakes</span>
      </div>
      <div class="gallery-hover-overlay">
        <span class="gallery-zoom-pill">🔍 Perbesar Foto</span>
      </div>
    </div>

    <!-- Item 8: Mix Big 4 Rasa (36 Bites Tray) -->
    <div class="gallery-card-item" data-category="bites"
         data-img="{{ asset('images/box-of-6.jpg') }}"
         data-title="Mix Big 4 Rasa (36 Bites)"
         data-desc="Nampan perjamuan eksklusif berisi 36 potong gigitan lapis legit dari 4 varian unggulan.">
      <img src="{{ asset('images/box-of-6.jpg') }}" alt="Mix Big 4 Rasa (36 Bites)" loading="lazy">
      <div class="gallery-card-top-logo">
        <span class="gallery-top-crest">⚜️</span>
        <span class="gallery-top-name">{{ config('site.brand') }}</span>
      </div>
      <div class="gallery-card-halal">
        <svg class="gallery-halal-svg" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
        <span class="gallery-halal-text">HALAL INDONESIA</span>
      </div>
      <div class="gallery-card-bottom-tag">
        <span class="gallery-item-name">Mix Big 4 Rasa</span>
        <span class="gallery-item-tagline">36 Bites Party Tray</span>
      </div>
      <div class="gallery-hover-overlay">
        <span class="gallery-zoom-pill">🔍 Perbesar Foto</span>
      </div>
    </div>

    <!-- Item 9: Choco Bites Gourmet -->
    <div class="gallery-card-item" data-category="bites"
         data-img="{{ asset('images/chocolate-hazelnut.jpg') }}"
         data-title="Choco Bites Gourmet"
         data-desc="Truffle cokelat hitam lumer bertabur gold dust mewah, perpaduan manis gurih tak terlupakan.">
      <img src="{{ asset('images/chocolate-hazelnut.jpg') }}" alt="Choco Bites Gourmet" loading="lazy">
      <div class="gallery-card-top-logo">
        <span class="gallery-top-crest">⚜️</span>
        <span class="gallery-top-name">{{ config('site.brand') }}</span>
      </div>
      <div class="gallery-card-halal">
        <svg class="gallery-halal-svg" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
        <span class="gallery-halal-text">HALAL INDONESIA</span>
      </div>
      <div class="gallery-card-bottom-tag">
        <span class="gallery-item-name">Choco Bites</span>
        <span class="gallery-item-tagline">Once You Bite, You Can't Stop!</span>
      </div>
      <div class="gallery-hover-overlay">
        <span class="gallery-zoom-pill">🔍 Perbesar Foto</span>
      </div>
    </div>

    <!-- Item 10: Exclusive Hampers Gift Box -->
    <div class="gallery-card-item" data-category="hampers"
         data-img="{{ asset('images/rose.jpg') }}"
         data-title="Rose Velvet Hampers Gift Box"
         data-desc="Hampers elegan balutan beludru merah maroon berisikan aneka cookies butter Wijsman &amp; lapis legit.">
      <img src="{{ asset('images/rose.jpg') }}" alt="Rose Velvet Hampers Gift Box" loading="lazy">
      <div class="gallery-card-top-logo">
        <span class="gallery-top-crest">⚜️</span>
        <span class="gallery-top-name">{{ config('site.brand') }}</span>
      </div>
      <div class="gallery-card-halal">
        <svg class="gallery-halal-svg" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
        <span class="gallery-halal-text">HALAL INDONESIA</span>
      </div>
      <div class="gallery-card-bottom-tag">
        <span class="gallery-item-name">Rose Velvet Box</span>
        <span class="gallery-item-tagline">Exclusive Gift Hampers</span>
      </div>
      <div class="gallery-hover-overlay">
        <span class="gallery-zoom-pill">🔍 Perbesar Foto</span>
      </div>
    </div>

    <!-- Item 11: Nastar Pineapple Butter Wijsman -->
    <div class="gallery-card-item" data-category="cookies"
         data-img="{{ asset('images/nastar-pineapple.jpg') }}"
         data-title="Nastar Pineapple Wijsman"
         data-desc="Kue kering nastar nanas lumer di mulut dengan selai nanas segar resep tradisional turun-temurun.">
      <img src="{{ asset('images/nastar-pineapple.jpg') }}" alt="Nastar Pineapple Wijsman" loading="lazy">
      <div class="gallery-card-top-logo">
        <span class="gallery-top-crest">⚜️</span>
        <span class="gallery-top-name">{{ config('site.brand') }}</span>
      </div>
      <div class="gallery-card-halal">
        <svg class="gallery-halal-svg" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
        <span class="gallery-halal-text">HALAL INDONESIA</span>
      </div>
      <div class="gallery-card-bottom-tag">
        <span class="gallery-item-name">Nastar Wijsman</span>
        <span class="gallery-item-tagline">Artisan Recipe Cookies</span>
      </div>
      <div class="gallery-hover-overlay">
        <span class="gallery-zoom-pill">🔍 Perbesar Foto</span>
      </div>
    </div>

    <!-- Item 12: Kaastengels Edam Cheese Toples -->
    <div class="gallery-card-item" data-category="cookies"
         data-img="{{ asset('images/kaastengel.jpg') }}"
         data-title="Edam Cheese Kaastengels"
         data-desc="Keju tua Edam Belanda melimpah dengan kerenyahan gurih otentik kualitas premium.">
      <img src="{{ asset('images/kaastengel.jpg') }}" alt="Edam Cheese Kaastengels" loading="lazy">
      <div class="gallery-card-top-logo">
        <span class="gallery-top-crest">⚜️</span>
        <span class="gallery-top-name">{{ config('site.brand') }}</span>
      </div>
      <div class="gallery-card-halal">
        <svg class="gallery-halal-svg" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
        <span class="gallery-halal-text">HALAL INDONESIA</span>
      </div>
      <div class="gallery-card-bottom-tag">
        <span class="gallery-item-name">Edam Kaastengels</span>
        <span class="gallery-item-tagline">Pure Dutch Cheese</span>
      </div>
      <div class="gallery-hover-overlay">
        <span class="gallery-zoom-pill">🔍 Perbesar Foto</span>
      </div>
    </div>

  </div>

  <!-- Bottom CTA Section -->
  <div style="text-align: center; margin-top: 48px;">
    <a href="{{ route('shop') }}" style="display: inline-flex; align-items: center; gap: 8px; background: #320712; background: linear-gradient(135deg, #3b0816 0%, #1e0208 100%); border: 1.5px solid #d4af37; color: #fef08a; padding: 13px 30px; border-radius: 6px; font-weight: 700; font-size: 14px; text-decoration: none; box-shadow: 0 4px 15px rgba(50, 7, 18, 0.4); transition: all 0.2s ease;">
      <span>🛍️</span>
      <span>Pesan Produk di Katalog Online</span>
    </a>
  </div>
</div>

<!-- Fullscreen Interactive Lightbox Modal -->
<div class="gallery-lightbox-modal" id="lightboxModal" role="dialog" aria-modal="true" aria-label="Pratinjau Foto Galeri">
  <div class="lightbox-box">
    <button type="button" class="lightbox-close-btn" id="lightboxClose" aria-label="Tutup Pratinjau">✕</button>
    <button type="button" class="lightbox-nav-btn lightbox-prev-btn" id="lightboxPrev" aria-label="Foto Sebelumnya">❮</button>
    
    <div class="lightbox-image-wrap">
      <img src="" alt="Pratinjau Foto Galeri" id="lightboxImg">
    </div>

    <div class="lightbox-caption-bar">
      <div class="lightbox-meta">
        <h3 id="lightboxTitle">Judul Foto</h3>
        <p id="lightboxDesc">Deskripsi detail produk.</p>
      </div>
      <a href="{{ route('shop') }}" style="background: #caa436; color: #1a080c; padding: 7px 16px; border-radius: 20px; font-size: 12.5px; font-weight: 700; text-decoration: none;">
        Beli Produk Ini ❯
      </a>
    </div>

    <button type="button" class="lightbox-nav-btn lightbox-next-btn" id="lightboxNext" aria-label="Foto Berikutnya">❯</button>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.gallery-filter-btn');
    const items = Array.from(document.querySelectorAll('.gallery-card-item'));
    const modal = document.getElementById('lightboxModal');
    const modalImg = document.getElementById('lightboxImg');
    const modalTitle = document.getElementById('lightboxTitle');
    const modalDesc = document.getElementById('lightboxDesc');
    const btnClose = document.getElementById('lightboxClose');
    const btnPrev = document.getElementById('lightboxPrev');
    const btnNext = document.getElementById('lightboxNext');

    let currentVisibleItems = [...items];
    let currentIndex = 0;

    // Filter functionality
    filterBtns.forEach(btn => {
      btn.addEventListener('click', function() {
        filterBtns.forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const filter = this.getAttribute('data-filter');

        items.forEach(item => {
          if (filter === 'all' || item.getAttribute('data-category') === filter) {
            item.style.display = 'block';
          } else {
            item.style.display = 'none';
          }
        });

        currentVisibleItems = items.filter(it => it.style.display !== 'none');
      });
    });

    // Lightbox open
    function openLightbox(index) {
      if (!currentVisibleItems.length) return;
      if (index < 0) index = currentVisibleItems.length - 1;
      if (index >= currentVisibleItems.length) index = 0;
      currentIndex = index;

      const target = currentVisibleItems[currentIndex];
      modalImg.src = target.getAttribute('data-img');
      modalTitle.textContent = target.getAttribute('data-title');
      modalDesc.textContent = target.getAttribute('data-desc');

      modal.classList.add('active');
      document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
      modal.classList.remove('active');
      document.body.style.overflow = '';
    }

    items.forEach(item => {
      item.addEventListener('click', function() {
        const idx = currentVisibleItems.indexOf(this);
        if (idx !== -1) {
          openLightbox(idx);
        }
      });
    });

    if (btnClose) btnClose.addEventListener('click', closeLightbox);
    if (btnPrev) btnPrev.addEventListener('click', () => openLightbox(currentIndex - 1));
    if (btnNext) btnNext.addEventListener('click', () => openLightbox(currentIndex + 1));

    if (modal) {
      modal.addEventListener('click', function(e) {
        if (e.target === modal) closeLightbox();
      });
    }

    document.addEventListener('keydown', function(e) {
      if (!modal.classList.contains('active')) return;
      if (e.key === 'Escape') closeLightbox();
      if (e.key === 'ArrowLeft') openLightbox(currentIndex - 1);
      if (e.key === 'ArrowRight') openLightbox(currentIndex + 1);
    });
  });
</script>
@endsection
