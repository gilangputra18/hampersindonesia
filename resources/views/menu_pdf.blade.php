@extends('layouts.app')

@section('title', 'Pricelist & Katalog Produk | ' . config('site.brand'))

@section('content')
<style>
  /* Pricelist Page Shell */
  .pricelist-wrapper {
    background-color: #fbf9f5;
    min-height: 100vh;
    padding: 30px 16px 80px;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
  }
  
  /* Breadcrumb & Main Header */
  .pricelist-header-section {
    text-align: center;
    max-width: 680px;
    margin: 0 auto 36px;
  }
  .pricelist-breadcrumb {
    font-size: 13px;
    color: #8c7355;
    margin-bottom: 12px;
    letter-spacing: 0.5px;
  }
  .pricelist-breadcrumb a {
    color: #8c7355;
    text-decoration: none;
    transition: color 0.2s;
  }
  .pricelist-breadcrumb a:hover {
    color: #9f183c;
    text-decoration: underline;
  }
  .pricelist-breadcrumb span {
    color: #caa436;
    font-weight: 600;
  }
  .pricelist-page-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 38px;
    font-weight: 500;
    color: #caa436;
    letter-spacing: 1px;
    margin: 0;
    line-height: 1.2;
  }

  /* Main Flyer Container */
  .pricelist-stack-container {
    max-width: 620px;
    margin: 0 auto;
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 0px;
  }

  /* Right-side Indicator Dots */
  .pricelist-dots-nav {
    position: fixed;
    right: 24px;
    top: 50%;
    transform: translateY(-50%);
    display: flex;
    flex-direction: column;
    gap: 14px;
    z-index: 100;
  }
  .pricelist-dot {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: rgba(160, 160, 160, 0.45);
    border: 2px solid #ffffff;
    cursor: pointer;
    transition: all 0.25s ease;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    text-decoration: none;
    display: block;
  }
  .pricelist-dot:hover, .pricelist-dot.active {
    background: #caa436;
    transform: scale(1.3);
    box-shadow: 0 2px 10px rgba(202, 164, 54, 0.5);
  }
  @media (max-width: 1024px) {
    .pricelist-dots-nav {
      display: none;
    }
  }

  /* Flyer Card 1 & 2: Luxury Light Gold Parchment */
  .flyer-card-gold {
    background: #fbf5e7;
    background: linear-gradient(180deg, #fcf8ee 0%, #f6ecd6 100%);
    border: 1px solid #ebd9b8;
    box-shadow: 0 10px 30px rgba(80, 50, 10, 0.08);
    padding: 34px 28px 26px;
    position: relative;
    overflow: hidden;
  }

  .flyer-top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding-bottom: 8px;
  }
  .flyer-brand-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
  }
  .flyer-logo-icon {
    font-size: 26px;
    line-height: 1;
    color: #b3822a;
  }
  .flyer-logo-meta {
    text-align: left;
  }
  .flyer-logo-meta h4 {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 1.5px;
    color: #3b2812;
    text-transform: uppercase;
    margin: 0;
  }
  .flyer-logo-meta span {
    font-size: 9px;
    letter-spacing: 1px;
    color: #8c7355;
    text-transform: uppercase;
    display: block;
  }

  /* Halal Indonesia Logo Badge */
  .halal-badge-wrap {
    display: flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 255, 255, 0.7);
    border: 1px solid #dfcfb2;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    color: #4a3419;
    letter-spacing: 0.5px;
  }
  .halal-badge-wrap svg {
    width: 18px;
    height: 18px;
    fill: #15803d;
  }

  /* Flyer Headings */
  .flyer-main-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 32px;
    font-weight: 700;
    font-style: italic;
    color: #1f1406;
    text-align: center;
    margin: 0 0 2px;
    letter-spacing: 0.5px;
  }
  .flyer-sub-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 20px;
    font-weight: 400;
    color: #422d14;
    text-align: center;
    margin: 0 0 28px;
    letter-spacing: 0.5px;
  }

  /* Card 1: Dotted Menu Table */
  .dotted-menu-list {
    display: flex;
    flex-direction: column;
    gap: 9px;
    margin-bottom: 30px;
  }
  .dotted-menu-row {
    display: flex;
    align-items: baseline;
    font-size: 15px;
    line-height: 1.4;
  }
  .dotted-item-name {
    font-family: 'Playfair Display', Georgia, serif;
    font-style: italic;
    font-weight: 600;
    color: #1a1207;
    white-space: nowrap;
    letter-spacing: 0.2px;
  }
  .dotted-item-size {
    font-family: 'Playfair Display', Georgia, serif;
    font-style: italic;
    font-size: 12.5px;
    font-weight: 400;
    color: #614a30;
    margin-left: 4px;
    white-space: nowrap;
  }
  .dotted-filler {
    flex: 1;
    margin: 0 8px;
    border-bottom: 1.5px dotted #c5ad88;
    height: 1px;
    opacity: 0.7;
  }
  .dotted-item-price {
    font-family: 'Playfair Display', Georgia, serif;
    font-weight: 700;
    color: #1a1207;
    white-space: nowrap;
    letter-spacing: 0.5px;
    font-size: 15px;
  }

  /* Card Footer Info Bar */
  .flyer-footer-bar {
    border-top: 1px solid #ebd9b8;
    padding-top: 14px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    font-size: 11px;
    color: #4a3419;
    font-weight: 600;
  }
  .flyer-footer-contact {
    display: flex;
    gap: 14px;
    align-items: center;
    flex-wrap: wrap;
  }
  .flyer-tagline {
    text-align: center;
    font-family: 'Playfair Display', Georgia, serif;
    font-style: italic;
    font-size: 13.5px;
    color: #422d14;
    margin-top: 14px;
    letter-spacing: 0.5px;
  }

  /* Photo Ribbon Divider (4 Photos in a Row) */
  .flyer-photo-ribbon {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 4px;
    background: #2b1704;
    padding: 4px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.25);
  }
  .ribbon-photo-item {
    aspect-ratio: 1 / 1;
    overflow: hidden;
    position: relative;
    background: #190902;
  }
  .ribbon-photo-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
  }
  .ribbon-photo-item:hover img {
    transform: scale(1.08);
  }

  /* Card 2: Split Thumbnail Grid & Pricelist */
  .split-flyer-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 26px;
    align-items: start;
  }
  .thumbs-column-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
  }
  .mini-thumb-card {
    background: rgba(255, 255, 255, 0.6);
    border: 1px solid #e2cfad;
    border-radius: 4px;
    padding: 4px;
    text-align: center;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .mini-thumb-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  }
  .mini-thumb-img {
    width: 100%;
    aspect-ratio: 1 / 1;
    object-fit: cover;
    border-radius: 2px;
    display: block;
  }
  .mini-thumb-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-style: italic;
    font-size: 10.5px;
    line-height: 1.25;
    color: #2b1d0c;
    margin-top: 5px;
    display: block;
    font-weight: 600;
  }

  .prices-column-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
    justify-content: center;
  }
  .price-col-row {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    font-size: 13.5px;
    line-height: 1.35;
  }
  .price-col-name {
    font-family: 'Playfair Display', Georgia, serif;
    font-style: italic;
    font-weight: 600;
    color: #1a1207;
  }
  .price-col-sub {
    font-size: 10.5px;
    color: #614a30;
    display: block;
  }
  .price-col-val {
    font-family: 'Playfair Display', Georgia, serif;
    font-weight: 700;
    color: #1a1207;
    white-space: nowrap;
    margin-left: 8px;
  }

  /* Card 3: Specialty Savory / Risoles Flyer (Caramel Golden-Brown Paper) */
  .flyer-card-brown {
    background: #b68754;
    background: linear-gradient(180deg, #be8f5a 0%, #a47442 100%);
    border: 1px solid #916130;
    box-shadow: 0 10px 30px rgba(50, 30, 5, 0.15);
    padding: 34px 28px 0px;
    position: relative;
    overflow: hidden;
    color: #1a0f03;
  }
  .risoles-main-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 46px;
    font-style: italic;
    font-weight: 700;
    color: #241404;
    margin: 0;
    line-height: 1.1;
  }
  .risoles-sub-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 22px;
    font-style: normal;
    color: #241404;
    margin: 0 0 20px;
  }
  .risoles-item-row {
    display: flex;
    align-items: baseline;
    font-size: 17px;
    margin-bottom: 10px;
    font-family: 'Playfair Display', Georgia, serif;
    font-weight: 600;
  }
  .risoles-item-name {
    color: #1f1103;
    white-space: nowrap;
  }
  .risoles-filler {
    flex: 1;
    margin: 0 8px;
    border-bottom: 1.5px dotted #6b451c;
    height: 1px;
  }
  .risoles-item-price {
    color: #1f1103;
    font-weight: 700;
    white-space: nowrap;
  }
  .risoles-tag-box {
    margin: 22px 0 14px;
    text-align: center;
  }
  .risoles-tag-headline {
    font-size: 18px;
    font-weight: 700;
    color: #221203;
    font-family: 'Playfair Display', Georgia, serif;
  }
  .risoles-tag-phone {
    font-size: 18px;
    font-weight: 800;
    color: #221203;
    margin-top: 4px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-family: 'Outfit', sans-serif;
  }
  .risoles-hero-photo-wrap {
    margin: 16px -28px 0;
    overflow: hidden;
    max-height: 280px;
  }
  .risoles-hero-photo {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    mask-image: linear-gradient(to bottom, rgba(0,0,0,1) 75%, rgba(0,0,0,0) 100%);
    -webkit-mask-image: linear-gradient(to bottom, rgba(0,0,0,1) 75%, rgba(0,0,0,0) 100%);
  }

  /* Bottom Sticky Action Buttons */
  .pricelist-cta-section {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 14px;
    margin-top: 32px;
    flex-wrap: wrap;
  }
  .btn-cta-tokopedia {
    background: #03ac0e;
    color: #ffffff;
    font-size: 13.5px;
    font-weight: 700;
    padding: 12px 26px;
    border-radius: 6px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 14px rgba(3, 172, 14, 0.35);
    transition: all 0.2s ease;
  }
  .btn-cta-tokopedia:hover {
    background: #02940c;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(3, 172, 14, 0.45);
    color: #ffffff;
  }
  .btn-cta-website {
    background: #25050d;
    background: linear-gradient(135deg, #320712 0%, #190207 100%);
    border: 1px solid #e6ca65;
    color: #fef08a;
    font-size: 13.5px;
    font-weight: 700;
    padding: 12px 26px;
    border-radius: 6px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 14px rgba(50, 7, 18, 0.4);
    transition: all 0.2s ease;
  }
  .btn-cta-website:hover {
    background: linear-gradient(135deg, #780d26 0%, #9f183c 100%);
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(159, 24, 60, 0.5);
  }
  .btn-cta-print {
    background: #ffffff;
    border: 1.5px solid #d4c5a3;
    color: #614a30;
    font-size: 13.5px;
    font-weight: 600;
    padding: 12px 22px;
    border-radius: 6px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
  }
  .btn-cta-print:hover {
    background: #fcf9f2;
    border-color: #caa436;
    color: #caa436;
  }

  /* Responsive Adjustments */
  @media (max-width: 640px) {
    .pricelist-wrapper {
      padding: 20px 8px 60px;
    }
    .flyer-card-gold, .flyer-card-brown {
      padding: 24px 16px 20px;
    }
    .flyer-main-title {
      font-size: 26px;
    }
    .dotted-item-name, .dotted-item-price {
      font-size: 13.5px;
    }
    .split-flyer-grid {
      grid-template-columns: 1fr;
      gap: 16px;
    }
    .risoles-main-title {
      font-size: 36px;
    }
    .risoles-hero-photo-wrap {
      margin: 14px -16px 0;
      max-height: 220px;
    }
  }

  /* Clean High-Resolution Print Styles */
  @media print {
    body {
      background: #ffffff !important;
      padding: 0 !important;
    }
    .site-header, .top, .luxury-footer, .pricelist-dots-nav, .pricelist-cta-section, .pricelist-breadcrumb {
      display: none !important;
    }
    .pricelist-wrapper {
      background: #ffffff !important;
      padding: 0 !important;
    }
    .pricelist-stack-container {
      max-width: 100% !important;
    }
    .flyer-card-gold, .flyer-card-brown {
      box-shadow: none !important;
      page-break-after: always;
      border: 1px solid #ccc !important;
      margin-bottom: 20px !important;
    }
  }
</style>

<div class="pricelist-wrapper">
  <!-- 0. Header & Breadcrumb -->
  <div class="pricelist-header-section">
    <div class="pricelist-breadcrumb">
      <a href="{{ route('home') }}">Beranda</a> &gt; <span>Daftar Harga (Pricelist)</span>
    </div>
    <h1 class="pricelist-page-title">Daftar Harga (Pricelist)</h1>
  </div>

  <!-- Main Canvas Stacker -->
  <div class="pricelist-stack-container">
    
    <!-- Navigation Scroll Indicator Dots -->
    <div class="pricelist-dots-nav">
      <a href="#flyer-page-1" class="pricelist-dot active" title="Halaman 1: Katalog Pilihan Utama" onclick="setActiveDot(this)"></a>
      <a href="#flyer-page-2" class="pricelist-dot" title="Halaman 2: Aneka Lapis & Kue Premium" onclick="setActiveDot(this)"></a>
      <a href="#flyer-page-3" class="pricelist-dot" title="Halaman 3: Risoles & Pastry Gurih" onclick="setActiveDot(this)"></a>
    </div>

    <!-- ==========================================
         CARD 1: FULL MENU PRICELIST FLYER
         ========================================== -->
    <div class="flyer-card-gold" id="flyer-page-1">
      <!-- Top Bar: Brand Crest & Halal Indonesia Badge -->
      <div class="flyer-top-bar">
        <a href="{{ route('home') }}" class="flyer-brand-logo">
          <span class="flyer-logo-icon">⚜️</span>
          <div class="flyer-logo-meta">
            <h4>PUSAT HAMPERS INDONESIA</h4>
            <span>Official Luxury Bakery &amp; Hampers</span>
          </div>
        </a>
        <div class="halal-badge-wrap" title="Tersertifikasi Halal Indonesia">
          <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
          <span>HALAL INDONESIA</span>
        </div>
      </div>

      <!-- Main Flyer Titles -->
      <h2 class="flyer-main-title">Pusat Hampers Indonesia</h2>
      <div class="flyer-sub-title">Daftar Harga • Pricelist</div>

      <!-- Dotted Menu Item List (Matching Reference Concept 1) -->
      <div class="dotted-menu-list">
        <div class="dotted-menu-row">
          <span class="dotted-item-name">Kaasstengels Toples</span>
          <span class="dotted-filler"></span>
          <span class="dotted-item-price">160K</span>
        </div>

        <div class="dotted-menu-row">
          <span class="dotted-item-name">Nastar Toples</span>
          <span class="dotted-filler"></span>
          <span class="dotted-item-price">180K</span>
        </div>

        <div class="dotted-menu-row">
          <span class="dotted-item-name">Lapis Loyang Mini Original</span>
          <span class="dotted-item-size">(10x20x4cm)</span>
          <span class="dotted-filler"></span>
          <span class="dotted-item-price">260K</span>
        </div>

        <div class="dotted-menu-row">
          <span class="dotted-item-name">Lapis Loyang Mini Cempedak</span>
          <span class="dotted-item-size">(10x20x4cm)</span>
          <span class="dotted-filler"></span>
          <span class="dotted-item-price">260K</span>
        </div>

        <div class="dotted-menu-row">
          <span class="dotted-item-name">Lapis Long Loyang</span>
          <span class="dotted-filler"></span>
          <span class="dotted-item-price">350K</span>
        </div>

        <div class="dotted-menu-row">
          <span class="dotted-item-name">Lapis Loyang Plum</span>
          <span class="dotted-item-size">(22x22x4cm)</span>
          <span class="dotted-filler"></span>
          <span class="dotted-item-price">550K</span>
        </div>

        <div class="dotted-menu-row">
          <span class="dotted-item-name">Lapis Loyang Original</span>
          <span class="dotted-item-size">(22x22x4cm)</span>
          <span class="dotted-filler"></span>
          <span class="dotted-item-price">800K</span>
        </div>

        <div class="dotted-menu-row">
          <span class="dotted-item-name">Lapis Loyang Cempedak</span>
          <span class="dotted-item-size">(22x22x4cm)</span>
          <span class="dotted-filler"></span>
          <span class="dotted-item-price">860K</span>
        </div>

        <div class="dotted-menu-row">
          <span class="dotted-item-name">Choco Bites Big</span>
          <span class="dotted-item-size">(36 bites)</span>
          <span class="dotted-filler"></span>
          <span class="dotted-item-price">230K</span>
        </div>

        <div class="dotted-menu-row">
          <span class="dotted-item-name">Choco Bites Small</span>
          <span class="dotted-item-size">(12 bites)</span>
          <span class="dotted-filler"></span>
          <span class="dotted-item-price">85K</span>
        </div>

        <div class="dotted-menu-row">
          <span class="dotted-item-name">Tropical Layer Cake</span>
          <span class="dotted-filler"></span>
          <span class="dotted-item-price">280K</span>
        </div>

        <div class="dotted-menu-row">
          <span class="dotted-item-name">Lapis Legit Slice</span>
          <span class="dotted-filler"></span>
          <span class="dotted-item-price">28K</span>
        </div>

        <div class="dotted-menu-row">
          <span class="dotted-item-name">Risol Mayo &amp; Ragout</span>
          <span class="dotted-filler"></span>
          <span class="dotted-item-price">16K</span>
        </div>
      </div>

      <!-- Footer Bar -->
      <div class="flyer-footer-bar">
        <div class="flyer-footer-contact">
          <span>🌐 pusathampersindonesia.com</span>
          <span>📸 @pusathampersindonesia</span>
        </div>
        <div>
          <span>📞 {{ config('site.phone') }}</span>
        </div>
      </div>
      <div class="flyer-tagline">Once You Bite, You Can't Stop!</div>
    </div>

    <!-- ==========================================
         PHOTO RIBBON STRIP (4 Photos in a Row)
         ========================================== -->
    <div class="flyer-photo-ribbon">
      <div class="ribbon-photo-item" title="Kue Kering & Hampers Nastar">
        <img src="{{ asset('images/kaastengel.jpg') }}" alt="Kaastengel & Cookies" onerror="this.src='{{ asset('images/cat-cookies.jpg') }}'">
      </div>
      <div class="ribbon-photo-item" title="Nastar Toples Premium">
        <img src="{{ asset('images/nastar-pineapple.jpg') }}" alt="Nastar Toples" onerror="this.src='{{ asset('images/butter-cookies-jar.jpg') }}'">
      </div>
      <div class="ribbon-photo-item" title="Exclusive Hampers Gift Box">
        <img src="{{ asset('images/box-of-4.jpg') }}" alt="Exclusive Gift Box" onerror="this.src='{{ asset('images/cat-hampers.jpg') }}'">
      </div>
      <div class="ribbon-photo-item" title="Fresh Baked Pastry & Treats">
        <img src="{{ asset('images/croissant.jpg') }}" alt="Fresh Baked Pastry" onerror="this.src='{{ asset('images/cat-cakes.jpg') }}'">
      </div>
    </div>

    <!-- ==========================================
         CARD 2: SPLIT THUMBNAIL GRID & PRICELIST
         ========================================== -->
    <div class="flyer-card-gold" id="flyer-page-2">
      <!-- Top Bar -->
      <div class="flyer-top-bar">
        <div class="flyer-brand-logo">
          <span class="flyer-logo-icon">⚜️</span>
          <div class="flyer-logo-meta">
            <h4>PUSAT HAMPERS INDONESIA</h4>
            <span>Gourmet Bites &amp; Treats</span>
          </div>
        </div>
        <div class="halal-badge-wrap">
          <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
          <span>HALAL INDONESIA</span>
        </div>
      </div>

      <h2 class="flyer-main-title">Pusat Hampers Indonesia</h2>
      <div class="flyer-sub-title">Daftar Harga • Pricelist</div>

      <!-- Split 2-Column: Left Thumbnails + Right Prices -->
      <div class="split-flyer-grid">
        
        <!-- Left: 2 Columns of Thumbnails (10 Items) -->
        <div class="thumbs-column-grid">
          <div class="mini-thumb-card">
            <img src="{{ asset('images/cat-cakes.jpg') }}" class="mini-thumb-img" alt="Lapis Plum Big">
            <span class="mini-thumb-title">Lapis Plum Big</span>
          </div>
          <div class="mini-thumb-card">
            <img src="{{ asset('images/black-forest.jpg') }}" class="mini-thumb-img" alt="Lapis Plum Small">
            <span class="mini-thumb-title">Lapis Plum Small</span>
          </div>
          <div class="mini-thumb-card">
            <img src="{{ asset('images/tiramisu.jpg') }}" class="mini-thumb-img" alt="Lapis Original Big">
            <span class="mini-thumb-title">Lapis Original Big</span>
          </div>
          <div class="mini-thumb-card">
            <img src="{{ asset('images/basque-burnt-cheesecake.jpg') }}" class="mini-thumb-img" alt="Lapis Original Small">
            <span class="mini-thumb-title">Lapis Original Small</span>
          </div>
          <div class="mini-thumb-card">
            <img src="{{ asset('images/chocolate-hazelnut.jpg') }}" class="mini-thumb-img" alt="Mubang Big">
            <span class="mini-thumb-title">Mubang Big</span>
          </div>
          <div class="mini-thumb-card">
            <img src="{{ asset('images/cat-light-bites.jpg') }}" class="mini-thumb-img" alt="Mubang Small">
            <span class="mini-thumb-title">Mubang Small</span>
          </div>
          <div class="mini-thumb-card">
            <img src="{{ asset('images/butter-cookies-jar.jpg') }}" class="mini-thumb-img" alt="Marble Big">
            <span class="mini-thumb-title">Marble Big</span>
          </div>
          <div class="mini-thumb-card">
            <img src="{{ asset('images/cat-cookies.jpg') }}" class="mini-thumb-img" alt="Marble Small">
            <span class="mini-thumb-title">Marble Small</span>
          </div>
          <div class="mini-thumb-card">
            <img src="{{ asset('images/box-of-6.jpg') }}" class="mini-thumb-img" alt="Mix Big (4 Rasa)">
            <span class="mini-thumb-title">Mix Big (4 Rasa)</span>
          </div>
          <div class="mini-thumb-card">
            <img src="{{ asset('images/box-of-4.jpg') }}" class="mini-thumb-img" alt="Mix Small (4 Rasa)">
            <span class="mini-thumb-title">Mix Small</span>
          </div>
        </div>

        <!-- Right: Corresponding Price List -->
        <div class="prices-column-list">
          <div class="price-col-row">
            <span class="price-col-name">Lapis Plum Big</span>
            <span class="price-col-val">410K</span>
          </div>
          <div class="price-col-row">
            <span class="price-col-name">Lapis Plum Small</span>
            <span class="price-col-val">139K</span>
          </div>
          <div class="price-col-row">
            <span class="price-col-name">Lapis Original Big</span>
            <span class="price-col-val">410K</span>
          </div>
          <div class="price-col-row">
            <span class="price-col-name">Lapis Original Small</span>
            <span class="price-col-val">150K</span>
          </div>
          <div class="price-col-row">
            <span class="price-col-name">Mubang Big</span>
            <span class="price-col-val">250K</span>
          </div>
          <div class="price-col-row">
            <span class="price-col-name">Mubang Small</span>
            <span class="price-col-val">125K</span>
          </div>
          <div class="price-col-row">
            <span class="price-col-name">Marble Big</span>
            <span class="price-col-val">180K</span>
          </div>
          <div class="price-col-row">
            <span class="price-col-name">Marble Small</span>
            <span class="price-col-val">95K</span>
          </div>
          <div class="price-col-row">
            <span class="price-col-name">Mix Big ( 4 Rasa )</span>
            <span class="price-col-val">330K</span>
          </div>
          <div class="price-col-row">
            <span class="price-col-name">Mix Small ( 4 Rasa )</span>
            <span class="price-col-val">139K</span>
          </div>
          <div class="price-col-row">
            <div>
              <span class="price-col-name">Mix Lapis Big</span>
              <span class="price-col-sub">( 1/2 Lapis Plum, 1/2 Lapis Ori )</span>
            </div>
            <span class="price-col-val">410K</span>
          </div>
          <div class="price-col-row">
            <span class="price-col-name">Mix Lapis Small</span>
            <span class="price-col-val">175K</span>
          </div>
          <div class="price-col-row">
            <span class="price-col-name">Mix Harum Big</span>
            <span class="price-col-val">410K</span>
          </div>
          <div class="price-col-row">
            <span class="price-col-name">Mix Harum Small</span>
            <span class="price-col-val">175K</span>
          </div>
        </div>

      </div>

      <!-- Footer Bar -->
      <div class="flyer-footer-bar">
        <div class="flyer-footer-contact">
          <span>🌐 pusathampersindonesia.com</span>
          <span>📸 @pusathampersindonesia</span>
        </div>
        <div>
          <span>📞 {{ config('site.phone') }}</span>
        </div>
      </div>
      <div class="flyer-tagline">Once You Bite, You Can't Stop!</div>
    </div>

    <!-- ==========================================
         CARD 3: RISOLES / SAVORY SPECIALTY FLYER
         ========================================== -->
    <div class="flyer-card-brown" id="flyer-page-3">
      <!-- Top Bar -->
      <div class="flyer-top-bar" style="border-bottom: 1px solid rgba(0,0,0,0.15);">
        <div class="flyer-brand-logo">
          <span class="flyer-logo-icon" style="color: #381f08;">⚜️</span>
          <div class="flyer-logo-meta">
            <h4 style="color: #241404;">PUSAT HAMPERS INDONESIA</h4>
            <span style="color: #3b2208;">Resep Artisan Autentik</span>
          </div>
        </div>
        <div class="halal-badge-wrap" style="background: rgba(255,255,255,0.85); border-color: #8c5a28;">
          <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
          <span style="color: #1a0f03;">HALAL INDONESIA</span>
        </div>
      </div>

      <!-- Risoles Headings -->
      <h2 class="risoles-main-title">Risoles</h2>
      <div class="risoles-sub-title">Daftar Harga • Pricelist</div>

      <!-- Dotted Risoles Price Rows -->
      <div style="margin-bottom: 22px;">
        <div class="risoles-item-row">
          <span class="risoles-item-name">Risoles Beef Mayo</span>
          <span class="risoles-filler"></span>
          <span class="risoles-item-price">IDR 15.000/ Pcs</span>
        </div>
        <div class="risoles-item-row">
          <span class="risoles-item-name">Risoles Ragout &amp; Cheese</span>
          <span class="risoles-filler"></span>
          <span class="risoles-item-price">IDR 15.000/ Pcs</span>
        </div>
      </div>

      <!-- Availability & Contact Tag -->
      <div class="risoles-tag-box">
        <div class="risoles-tag-headline">Tersedia Frozen / Siap Saji di Butik</div>
        <div class="risoles-tag-phone">
          <span>📞</span> <span>{{ config('site.phone') }}</span>
        </div>
      </div>

      <!-- Hero Pastry Photography (Golden Risoles) -->
      <div class="risoles-hero-photo-wrap">
        <img src="{{ asset('images/bitterballen.jpg') }}" alt="Risoles &amp; Gourmet Bites" class="risoles-hero-photo" onerror="this.src='{{ asset('images/focaccia-chicken-pesto-sandwich.jpg') }}'">
      </div>
    </div>

    <!-- ==========================================
         ACTION BUTTONS (Tokopedia & Website Order)
         ========================================== -->
    <div class="pricelist-cta-section">
      <a href="{{ config('site.tokopedia_url', 'https://www.tokopedia.com') }}" target="_blank" rel="noopener noreferrer" class="btn-cta-tokopedia" title="Beli di Tokopedia Resmi">
        <span>🛍️</span>
        <span>Pesan di Tokopedia</span>
      </a>

      <a href="{{ route('shop') }}" class="btn-cta-website" title="Pesan Langsung Lewat Website">
        <span>✨</span>
        <span>Pesan di Website</span>
      </a>

      <button type="button" onclick="window.print()" class="btn-cta-print" title="Cetak atau Simpan sebagai PDF">
        <span>🖨️</span>
        <span>Cetak / Unduh PDF</span>
      </button>
    </div>

  </div>
</div>

<script>
  function setActiveDot(elem) {
    document.querySelectorAll('.pricelist-dot').forEach(d => d.classList.remove('active'));
    elem.classList.add('active');
  }

  // Smooth scroll spy for dots
  document.addEventListener('scroll', function() {
    const p1 = document.getElementById('flyer-page-1');
    const p2 = document.getElementById('flyer-page-2');
    const p3 = document.getElementById('flyer-page-3');
    const dots = document.querySelectorAll('.pricelist-dot');

    if (!p1 || !p2 || !p3 || dots.length < 3) return;

    const scrollPos = window.scrollY + 250;
    if (scrollPos >= p3.offsetTop) {
      dots.forEach(d => d.classList.remove('active'));
      dots[2].classList.add('active');
    } else if (scrollPos >= p2.offsetTop) {
      dots.forEach(d => d.classList.remove('active'));
      dots[1].classList.add('active');
    } else {
      dots.forEach(d => d.classList.remove('active'));
      dots[0].classList.add('active');
    }
  });
</script>
@endsection
