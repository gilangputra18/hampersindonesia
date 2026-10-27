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

  /* Hero Slider Section (Gambar Bergeser ke Kanan) */
  .hero-wine {
    position: relative;
    background: #16050e;
    min-height: 560px;
    overflow: hidden;
    padding: 0;
  }
  .hero-slider-track {
    display: flex;
    height: 100%;
    min-height: 560px;
    transition: transform 0.75s cubic-bezier(0.25, 1, 0.5, 1);
    will-change: transform;
  }
  .hero-slide {
    flex: 0 0 100%;
    width: 100%;
    min-height: 560px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 80px 20px 90px 20px;
    box-sizing: border-box;
    overflow: hidden;
  }
  .hero-slide-bg {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(0.62) contrast(1.12);
    transition: transform 6s ease;
  }
  .hero-slide.active .hero-slide-bg {
    transform: scale(1.04);
  }
  .hero-slide-overlay {
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at center, rgba(30, 8, 20, 0.45) 0%, rgba(18, 5, 14, 0.85) 100%);
    z-index: 2;
  }
  .hero-wine-overlay {
    position: relative;
    z-index: 5;
    max-width: 860px;
    margin: 0 auto;
    animation: heroFadeIn 0.8s ease-out;
  }
  @keyframes heroFadeIn {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
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
    background: rgba(0, 0, 0, 0.35);
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
  .hero-nav-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: rgba(18, 5, 14, 0.75);
    border: 1.5px solid #e6ca65;
    color: #fef08a;
    font-size: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    transition: all 0.25s ease;
    backdrop-filter: blur(4px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.5);
    user-select: none;
  }
  .hero-nav-arrow:hover {
    background: #e6ca65;
    color: #12030a;
    transform: translateY(-50%) scale(1.1);
  }
  .hero-nav-prev {
    left: 24px;
  }
  .hero-nav-next {
    right: 24px;
  }
  @media (max-width: 650px) {
    .hero-nav-arrow {
      width: 36px;
      height: 36px;
      font-size: 16px;
    }
    .hero-nav-prev { left: 10px; }
    .hero-nav-next { right: 10px; }
  }
  .hero-dots-container {
    position: absolute;
    bottom: 24px;
    left: 0;
    right: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    z-index: 10;
  }
  .hero-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.35);
    border: 1.5px solid rgba(230, 202, 101, 0.6);
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 0;
  }
  .hero-dot.active {
    width: 32px;
    border-radius: 12px;
    background: linear-gradient(135deg, #fff3c4, #e6ca65);
    border-color: #fff3c4;
    box-shadow: 0 0 10px rgba(230, 202, 101, 0.6);
  }

  /* Brand Story & Quality Certification Section (Royal Luxury Redesign) */
  .story-cert-section {
    background: linear-gradient(135deg, #17050f 0%, #230816 50%, #15040e 100%);
    padding: 70px 24px;
    color: #ffffff;
    position: relative;
    border-top: 1px solid rgba(230, 202, 101, 0.25);
    border-bottom: 1px solid rgba(230, 202, 101, 0.25);
    overflow: hidden;
  }
  .story-cert-section::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -20%;
    width: 60%;
    height: 140%;
    background: radial-gradient(circle, rgba(230, 202, 101, 0.08) 0%, transparent 70%);
    pointer-events: none;
  }
  .story-cert-inner {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1.15fr 0.85fr;
    gap: 48px;
    align-items: center;
    position: relative;
    z-index: 2;
  }
  .story-tag-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(230, 202, 101, 0.1);
    border: 1px solid rgba(230, 202, 101, 0.35);
    color: #fef08a;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    padding: 6px 18px;
    border-radius: 50px;
    margin-bottom: 16px;
    box-shadow: 0 0 14px rgba(230, 202, 101, 0.15);
  }
  .story-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: clamp(26px, 4vw, 38px);
    font-weight: 800;
    line-height: 1.25;
    margin: 0 0 16px 0;
    background: var(--gold-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    letter-spacing: 0.5px;
  }
  .story-divider-gold {
    width: 64px;
    height: 3px;
    background: var(--gold-gradient);
    border-radius: 2px;
    margin-bottom: 18px;
  }
  .story-desc {
    font-size: 14.5px;
    color: #e2e8f0;
    line-height: 1.8;
    margin: 0 0 24px 0;
    max-width: 620px;
  }
  .story-action-wrap {
    margin-bottom: 28px;
  }
  .btn-story-luxury {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: var(--gold-gradient);
    color: #12030a;
    padding: 13px 30px;
    border-radius: 50px;
    font-weight: 800;
    font-size: 12.5px;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    text-decoration: none;
    box-shadow: 0 6px 20px rgba(230, 202, 101, 0.35);
    transition: all 0.3s ease;
  }
  .btn-story-luxury:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(254, 240, 138, 0.5);
    color: #000;
  }
  .story-trust-badges {
    display: flex;
    align-items: center;
    gap: 24px;
    padding-top: 18px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
  }
  .trust-badge-item {
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .trust-icon {
    font-size: 22px;
  }
  .trust-badge-item strong {
    display: block;
    font-size: 14px;
    color: #fef08a;
    font-weight: 800;
    line-height: 1.2;
  }
  .trust-badge-item small {
    font-size: 11px;
    color: #94a3b8;
  }

  /* 3 Luxury Badges on the right */
  .story-right-cards {
    display: flex;
    flex-direction: column;
    gap: 14px;
  }
  .cert-card-luxury {
    display: flex;
    align-items: center;
    gap: 16px;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(230, 202, 101, 0.3);
    border-radius: 14px;
    padding: 16px 20px;
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s ease, border-color 0.3s ease, background 0.3s ease;
  }
  .cert-card-luxury:hover {
    transform: translateX(6px);
    border-color: #fef08a;
    background: rgba(230, 202, 101, 0.08);
  }
  .cert-gold-ring {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(230, 202, 101, 0.25) 0%, rgba(22, 5, 14, 0.6) 100%);
    border: 1.5px solid #e6ca65;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
    box-shadow: 0 0 12px rgba(230, 202, 101, 0.3);
  }
  .cert-luxury-label {
    margin: 0 0 2px 0;
    font-size: 15px;
    font-weight: 800;
    color: #ffffff;
    letter-spacing: 0.5px;
  }
  .cert-luxury-sub {
    margin: 0 0 6px 0;
    font-size: 12px;
    color: #cbd5e1;
  }
  .cert-mini-badge {
    display: inline-block;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
    color: #081711;
    background: linear-gradient(135deg, #fff3c4, #e6ca65);
    padding: 2px 8px;
    border-radius: 4px;
    text-transform: uppercase;
  }

  @media (max-width: 820px) {
    .story-cert-inner {
      grid-template-columns: 1fr;
      text-align: center;
      gap: 32px;
    }
    .story-divider-gold {
      margin-left: auto;
      margin-right: auto;
    }
    .story-desc {
      margin-left: auto;
      margin-right: auto;
    }
    .story-trust-badges {
      justify-content: center;
      flex-wrap: wrap;
      gap: 16px;
    }
    .story-right-cards {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 10px;
    }
    .cert-card-luxury {
      flex-direction: column;
      text-align: center;
      padding: 14px 8px;
      gap: 10px;
    }
    .cert-luxury-label {
      font-size: 12.5px;
    }
    .cert-luxury-sub {
      font-size: 10.5px;
      display: none;
    }
  }

  @media (max-width: 480px) {
    .story-cert-section {
      padding: 44px 14px;
    }
    .story-right-cards {
      grid-template-columns: repeat(3, 1fr);
      gap: 8px;
    }
    .cert-gold-ring {
      width: 38px;
      height: 38px;
      font-size: 18px;
    }
    .cert-card-luxury {
      padding: 12px 6px;
    }
    .cert-luxury-label {
      font-size: 11px;
    }
    .cert-mini-badge {
      font-size: 8px;
      padding: 1px 5px;
    }
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

  /* Product Showcase Rows (Matching Reference Image 1 & Centered Titles) */
  .showcase-row-section {
    padding: 60px 20px 50px 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
  }
  .showcase-header-ref {
    text-align: center;
    margin: 0 auto 30px auto;
    max-width: 800px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }
  .showcase-title-ref {
    font-family: 'Playfair Display', serif;
    font-size: clamp(26px, 4.5vw, 38px);
    font-weight: 800;
    margin: 0 0 10px 0;
    text-align: center;
    letter-spacing: 1.5px;
    background: var(--gold-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    display: block;
  }
  .showcase-sub-link-wrap {
    margin-top: 4px;
    text-align: center;
  }
  .showcase-center-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: #cbd5e1;
    text-decoration: none;
    padding: 6px 18px;
    border-radius: 50px;
    border: 1px solid rgba(230, 202, 101, 0.4);
    background: rgba(255, 255, 255, 0.05);
    transition: all 0.25s ease;
  }
  .showcase-center-pill:hover {
    background: var(--gold-gradient);
    color: #12030a;
    border-color: #fff3c4;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(230, 202, 101, 0.3);
  }

  /* White Product Card (Matching Reference Image 1) */
  .ref-white-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    max-width: 1200px;
    margin: 0 auto;
  }
  @media (max-width: 820px) {
    .ref-white-grid {
      display: flex !important;
      flex-wrap: nowrap !important;
      overflow-x: auto !important;
      scroll-snap-type: x mandatory !important;
      -webkit-overflow-scrolling: touch !important;
      gap: 14px !important;
      padding: 10px 16px 20px 16px !important;
      margin-left: -16px !important;
      margin-right: -16px !important;
      scrollbar-width: none !important;
      scroll-behavior: smooth !important;
    }
    .ref-white-grid::-webkit-scrollbar {
      display: none !important;
    }
    .ref-white-card {
      flex: 0 0 200px !important;
      max-width: 210px !important;
      min-width: 180px !important;
      scroll-snap-align: start !important;
    }
  }
  @media (max-width: 500px) {
    .ref-white-grid {
      gap: 12px !important;
      padding: 8px 14px 18px 14px !important;
      margin-left: -12px !important;
      margin-right: -12px !important;
    }
    .ref-white-card {
      flex: 0 0 170px !important;
      max-width: 180px !important;
      min-width: 155px !important;
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

  /* Mobile Android Typography & 2-Column Card Optimization */
  @media (max-width: 640px) {
    .hero-wine-title {
      font-size: clamp(22px, 6vw, 36px) !important;
      letter-spacing: 1px !important;
      margin-bottom: 10px !important;
    }
    .hero-signature-quote {
      font-size: clamp(16px, 4.2vw, 22px) !important;
      margin-bottom: 12px !important;
    }
    .hero-wine-desc {
      font-size: 13px !important;
      line-height: 1.6 !important;
      margin-bottom: 20px !important;
    }
    .btn-gold-luxury {
      padding: 11px 28px !important;
      font-size: 12px !important;
      letter-spacing: 1.5px !important;
    }
    .showcase-row-section {
      padding: 36px 12px 28px 12px !important;
    }
    .showcase-title-ref {
      font-size: clamp(18px, 5.2vw, 24px) !important;
      letter-spacing: 0.8px !important;
      margin-bottom: 6px !important;
    }
    .showcase-header-ref {
      margin-bottom: 18px !important;
    }
    .cat-title-ref {
      font-size: clamp(18px, 5.2vw, 24px) !important;
      margin-bottom: 14px !important;
    }
    .cat-eyebrow-ref {
      font-size: 16px !important;
    }
    .category-slider-section {
      padding: 40px 12px 30px 12px !important;
    }
    .story-cert-section {
      padding: 40px 14px !important;
    }
    .story-title {
      font-size: clamp(18px, 5vw, 24px) !important;
      margin-bottom: 10px !important;
    }
    .story-desc {
      font-size: 13px !important;
      line-height: 1.6 !important;
    }
    .ref-white-body {
      padding: 10px 8px 12px 8px !important;
    }
    .ref-white-title {
      font-size: 13px !important;
      min-height: 36px !important;
      line-height: 1.3 !important;
      margin-bottom: 4px !important;
    }
    .ref-white-price {
      font-size: 13px !important;
      margin-bottom: 8px !important;
    }
    .ref-white-cart-btn {
      font-size: 12px !important;
      padding: 4px 8px !important;
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

  /* Our Gallery Grid & Interactive Lightbox */
  .gallery-filter-tabs {
    display: flex;
    gap: 8px;
    margin-bottom: 16px;
    overflow-x: auto;
    padding-bottom: 6px;
    scrollbar-width: none;
    -webkit-overflow-scrolling: touch;
  }
  .gallery-filter-tabs::-webkit-scrollbar {
    display: none;
  }
  .gallery-tab-btn {
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(217, 119, 6, 0.25);
    color: #cbd5e1;
    padding: 6px 13px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-family: inherit;
  }
  .gallery-tab-btn:hover {
    background: rgba(217, 119, 6, 0.2);
    border-color: #f59e0b;
    color: #fff;
  }
  .gallery-tab-btn.active {
    background: linear-gradient(135deg, #d97706, #fbbf24);
    border-color: #fbbf24;
    color: #12030b;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.35);
  }

  .gallery-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
  }
  .gallery-item {
    border-radius: 10px;
    overflow: hidden;
    aspect-ratio: 1/1;
    position: relative;
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    background: #18060f;
    cursor: pointer;
    transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
  }
  .gallery-item:hover {
    transform: translateY(-3px);
    border-color: rgba(245, 158, 11, 0.6);
    box-shadow: 0 8px 24px rgba(217, 119, 6, 0.25);
  }
  .gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    display: block;
  }
  .gallery-item:hover img {
    transform: scale(1.09);
  }
  .gallery-item-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(17, 3, 11, 0.9) 0%, rgba(17, 3, 11, 0.3) 50%, rgba(0,0,0,0) 100%);
    opacity: 0;
    transition: opacity 0.25s ease;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 10px 12px;
  }
  .gallery-item:hover .gallery-item-overlay {
    opacity: 1;
  }
  .gallery-item-zoom-icon {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: rgba(217, 119, 6, 0.9);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.5);
  }
  .gallery-item-tag {
    font-size: 9px;
    color: #fbbf24;
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: 0.5px;
    margin-bottom: 2px;
  }
  .gallery-item-title {
    font-size: 11px;
    font-weight: 700;
    color: #fff;
    line-height: 1.25;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  /* Fullscreen Interactive Lightbox Modal */
  .gallery-lightbox-modal {
    position: fixed;
    inset: 0;
    z-index: 99999;
    background: rgba(8, 1, 5, 0.94);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    display: none;
    align-items: center;
    justify-content: center;
    padding: 16px;
    opacity: 0;
    transition: opacity 0.25s ease;
  }
  .gallery-lightbox-modal.active {
    display: flex;
    opacity: 1;
  }
  .lightbox-content-box {
    position: relative;
    max-width: 620px;
    width: 100%;
    max-height: 92vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    background: #180510;
    border: 1px solid rgba(217, 119, 6, 0.4);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.85), 0 0 40px rgba(217, 119, 6, 0.2);
    animation: zoomLightbox 0.25s cubic-bezier(0.16, 1, 0.3, 1);
  }
  @keyframes zoomLightbox {
    from { transform: scale(0.92); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
  }
  .lightbox-img-wrap {
    width: 100%;
    height: 380px;
    background: #0a0106;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
  }
  @media (max-width: 640px) {
    .lightbox-img-wrap {
      height: 270px;
    }
  }
  .lightbox-img-wrap img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    display: block;
    transition: opacity 0.2s ease;
  }
  .lightbox-info-bar {
    width: 100%;
    padding: 16px 20px;
    background: linear-gradient(180deg, #180510 0%, #10020a 100%);
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    box-sizing: border-box;
  }
  .lightbox-badge-row {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 4px;
  }
  .lightbox-cat-badge {
    background: rgba(245, 158, 11, 0.15);
    color: #fbbf24;
    border: 1px solid rgba(245, 158, 11, 0.35);
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .lightbox-counter-text {
    font-size: 11px;
    color: #94a3b8;
  }
  .lightbox-title {
    font-family: 'Playfair Display', serif;
    font-size: 17px;
    color: #fff;
    font-weight: 700;
    margin: 0 0 3px 0;
    line-height: 1.3;
  }
  .lightbox-desc {
    font-size: 12px;
    color: #cbd5e1;
    line-height: 1.45;
    margin: 0;
  }
  .lightbox-close-btn {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(0, 0, 0, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #fff;
    font-size: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    transition: all 0.2s ease;
  }
  .lightbox-close-btn:hover {
    background: #ef4444;
    border-color: #ef4444;
    transform: rotate(90deg);
  }
  .lightbox-nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: rgba(0, 0, 0, 0.7);
    border: 1px solid rgba(217, 119, 6, 0.45);
    color: #fbbf24;
    font-size: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    transition: all 0.2s ease;
    user-select: none;
  }
  .lightbox-nav-btn:hover {
    background: #d97706;
    color: #fff;
    transform: translateY(-50%) scale(1.08);
  }
  .lightbox-prev-btn { left: 10px; }
  .lightbox-next-btn { right: 10px; }

  @media (max-width: 640px) {
    .gallery-grid {
      grid-template-columns: repeat(3, 1fr) !important;
      gap: 8px !important;
    }
    .gallery-item-overlay {
      display: none !important;
    }
    .lightbox-info-bar {
      flex-direction: column !important;
      align-items: flex-start !important;
      padding: 12px 14px !important;
      gap: 10px !important;
    }
    .lightbox-info-bar a {
      width: 100% !important;
      text-align: center !important;
      justify-content: center !important;
    }
    .lightbox-nav-btn {
      width: 36px !important;
      height: 36px !important;
      font-size: 20px !important;
    }
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

<!-- Hero Slider Section (Gambar Bergeser ke Kanan - Custom Admin Enabled) -->
<section class="hero-wine" id="heroBannerSection" aria-label="Hero Banner Slider">
  <div class="hero-slider-track" id="heroSliderTrack">
    @foreach ($heroSlides as $idx => $slide)
      <div class="hero-slide {{ $idx === 0 ? 'active' : '' }}" data-slide-index="{{ $idx }}">
        <img src="{{ $slide['image'] }}" alt="{{ $slide['title'] }}" class="hero-slide-bg" loading="{{ $idx === 0 ? 'eager' : 'lazy' }}" onerror="this.src='{{ asset('images/hero-poster.jpg') }}';">
        <div class="hero-slide-overlay"></div>
        <div class="hero-wine-overlay">
          <span class="hero-brand-badge">{{ $slide['badge'] ?? '⚜️ PUSAT HAMPERS INDONESIA ⚜️' }}</span>
          <h1 class="hero-wine-title">{{ $slide['title'] ?? config('site.brand') }}</h1>
          <p class="hero-signature-quote">{!! $slide['quote'] ?? '"Have a bite, you can&#039;t stop!"' !!}</p>
          <p class="hero-wine-desc">{{ $slide['desc'] ?? 'Cita rasa premium Indonesia untuk setiap momen berharga, dibuat dengan bahan-bahan pilihan berkualitas tinggi berstandar artisanal internasional.' }}</p>
          <a href="{{ $slide['btn_url'] ?? route('shop') }}" class="btn-gold-luxury">{{ $slide['btn_text'] ?? 'JELAJAHI KOLEKSI' }}</a>
        </div>
      </div>
    @endforeach
  </div>

  @if(count($heroSlides) > 1)
    <!-- Navigation Buttons (Geser ke Kiri / Geser ke Kanan) -->
    <button type="button" class="hero-nav-arrow hero-nav-prev" id="heroPrevBtn" aria-label="Slide Sebelumnya">❮</button>
    <button type="button" class="hero-nav-arrow hero-nav-next" id="heroNextBtn" aria-label="Slide Berikutnya (Geser ke Kanan)">❯</button>

    <!-- Slide Indicator Dots -->
    <div class="hero-dots-container" id="heroDotsContainer">
      @foreach ($heroSlides as $idx => $slide)
        <button type="button" class="hero-dot {{ $idx === 0 ? 'active' : '' }}" data-slide-to="{{ $idx }}" aria-label="Ke Slide {{ $idx + 1 }}"></button>
      @endforeach
    </div>
  @endif
</section>

<!-- Brand Story & Quality Certification Section (Royal Luxury Redesign) -->
<section class="story-cert-section">
  <div class="story-cert-inner">
    <div class="story-left-content">
      <div class="story-tag-pill">
        <span>⚜️</span>
        <span>CERITA RASA KAMI</span>
        <span>⚜️</span>
      </div>
      <h2 class="story-title">Dedikasi Kemewahan &amp; Kelezatan Sejak Awal</h2>
      <div class="story-divider-gold"></div>
      <p class="story-desc">
        Setiap bingkisan diracik secara teliti menggunakan mentega impor murni, rempah istimewa, dan resep warisan artisanal tanpa pemanis buatan maupun pengawet. Kami mempersembahkan kehangatan hampers gourmet terbaik yang meninggalkan kenangan tak terlupakan bagi keluarga, sahabat, dan mitra bisnis terhormat Anda.
      </p>
      
      <div class="story-action-wrap">
        <a href="{{ route('shop') }}" class="btn-story-luxury">
          <span>Lihat Semua Pilihan Hampers</span>
          <span style="font-size: 13px;">❯</span>
        </a>
      </div>

      <!-- Trust highlights -->
      <div class="story-trust-badges">
        <div class="trust-badge-item">
          <span class="trust-icon">⭐</span>
          <div>
            <strong>4.9 / 5.0</strong>
            <small>Rating Kepuasan</small>
          </div>
        </div>
        <div class="trust-badge-item">
          <span class="trust-icon">📦</span>
          <div>
            <strong>15.000+</strong>
            <small>Hampers Terkirim</small>
          </div>
        </div>
        <div class="trust-badge-item">
          <span class="trust-icon">👑</span>
          <div>
            <strong>Artisanal</strong>
            <small>Standar VIP</small>
          </div>
        </div>
      </div>
    </div>

    <div class="story-right-cards">
      <div class="cert-card-luxury">
        <div class="cert-gold-ring">🌿</div>
        <div class="cert-card-text">
          <h4 class="cert-luxury-label">100% Halal</h4>
          <p class="cert-luxury-sub">LPPOM MUI &amp; BPOM Resmi</p>
          <span class="cert-mini-badge">TERJAMIN HIGIENIS</span>
        </div>
      </div>

      <div class="cert-card-luxury">
        <div class="cert-gold-ring">🧈</div>
        <div class="cert-card-text">
          <h4 class="cert-luxury-label">Pure Wijsman Butter</h4>
          <p class="cert-luxury-sub">Bahan Baku Impor Premium</p>
          <span class="cert-mini-badge">WANGI &amp; LEMBUT</span>
        </div>
      </div>

      <div class="cert-card-luxury">
        <div class="cert-gold-ring">🛡️</div>
        <div class="cert-card-text">
          <h4 class="cert-luxury-label">0% Pengawet</h4>
          <p class="cert-luxury-sub">Fresh Baked Every Day</p>
          <span class="cert-mini-badge">ALAMI &amp; SEHAT</span>
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

<!-- Product Showcase Row 1: Big Bites (Matching Reference Image 1 & Centered Title) -->
<section class="showcase-row-section" style="background: var(--wine-surface);">
  <div class="showcase-header-ref text-center">
    <h2 class="showcase-title-ref">Big Bites</h2>
    <div class="showcase-sub-link-wrap">
      <a href="{{ route('category', 'light-bites') }}" class="showcase-center-pill">Lihat Semua Big Bites ❯</a>
    </div>
  </div>

  <div class="ref-white-grid">
    @foreach ($bigBites->take(8) as $p)
      @include('partials.card-white', ['item' => $p, 'slug' => 'light-bites'])
    @endforeach
  </div>
</section>

<!-- Product Showcase Row 2: Loyang Mini (Matching Reference Image 1 & Centered Title) -->
<section class="showcase-row-section" style="background: var(--wine-dark);">
  <div class="showcase-header-ref text-center">
    <h2 class="showcase-title-ref">Loyang Mini</h2>
    <div class="showcase-sub-link-wrap">
      <a href="{{ route('category', 'cakes') }}" class="showcase-center-pill">Lihat Semua Loyang Mini ❯</a>
    </div>
  </div>

  <div class="ref-white-grid">
    @foreach ($loyangMini->take(8) as $p)
      @include('partials.card-white', ['item' => $p, 'slug' => 'cakes'])
    @endforeach
  </div>
</section>

<!-- Product Showcase Row 3: Other (Matching Reference Image 1 & Centered Title) -->
<section class="showcase-row-section" style="background: var(--wine-surface);">
  <div class="showcase-header-ref text-center">
    <h2 class="showcase-title-ref">Other</h2>
    <div class="showcase-sub-link-wrap">
      <a href="{{ route('shop') }}" class="showcase-center-pill">Lihat Semua Koleksi ❯</a>
    </div>
  </div>

  <div class="ref-white-grid">
    @foreach ($other->take(8) as $p)
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
      <h2 style="font-family: 'Playfair Display', serif; font-size: clamp(24px, 3.5vw, 32px); color: #fff; margin: 0 0 16px 0;">
        Our Gallery
      </h2>

      <!-- Filter Tabs: Macam-Macam Isi Galeri -->
      <div class="gallery-filter-tabs">
        <button type="button" class="gallery-tab-btn active" data-filter="all">✨ Semua (12)</button>
        <button type="button" class="gallery-tab-btn" data-filter="hampers">🎁 Luxury Hampers</button>
        <button type="button" class="gallery-tab-btn" data-filter="cookies">🍪 Artisan Cookies</button>
        <button type="button" class="gallery-tab-btn" data-filter="cakes">🎂 Cakes & Tarts</button>
        <button type="button" class="gallery-tab-btn" data-filter="special">🌙 Special Edition</button>
      </div>

      <div class="gallery-grid" id="mainGalleryGrid">
        <!-- 1. Hampers Rose Velvet Box -->
        <div class="gallery-item" data-category="hampers"
             data-img="{{ asset('images/rose.jpg') }}"
             data-badge="🎁 Luxury Hampers"
             data-title="Hampers Rose Velvet Box"
             data-desc="Trunk velvet box merah mewah berisi aneka cookies toples, macarons artisan, dan teh premium eksklusif.">
          <img src="{{ asset('images/rose.jpg') }}" alt="Hampers Rose Velvet Box" loading="lazy">
          <div class="gallery-item-overlay">
            <div class="gallery-item-zoom-icon">🔍</div>
            <div class="gallery-item-tag">🎁 Luxury Hampers</div>
            <div class="gallery-item-title">Rose Velvet Box</div>
          </div>
        </div>

        <!-- 2. Royal Heritage Hamper Basket -->
        <div class="gallery-item" data-category="hampers"
             data-img="{{ asset('images/feature.jpg') }}"
             data-badge="🎁 Luxury Hampers"
             data-title="Royal Heritage Hamper Basket"
             data-desc="Keranjang anyam gourmet rotan dengan pita satin, sparkling beverage, dan toples cookies wisman.">
          <img src="{{ asset('images/feature.jpg') }}" alt="Royal Heritage Hamper Basket" loading="lazy">
          <div class="gallery-item-overlay">
            <div class="gallery-item-zoom-icon">🔍</div>
            <div class="gallery-item-tag">🎁 Luxury Hampers</div>
            <div class="gallery-item-title">Royal Basket</div>
          </div>
        </div>

        <!-- 3. Hampers Calla Royal Set -->
        <div class="gallery-item" data-category="hampers"
             data-img="{{ asset('images/calla.jpg') }}"
             data-badge="🎁 Luxury Hampers"
             data-title="Hampers Calla Royal Set"
             data-desc="Gift box eksklusif dengan aksen pita emas mewah dan seleksi kue kering artisanal mentega murni.">
          <img src="{{ asset('images/calla.jpg') }}" alt="Hampers Calla Royal Set" loading="lazy">
          <div class="gallery-item-overlay">
            <div class="gallery-item-zoom-icon">🔍</div>
            <div class="gallery-item-tag">🎁 Luxury Hampers</div>
            <div class="gallery-item-title">Calla Royal Set</div>
          </div>
        </div>

        <!-- 4. Hampers Iris Luxury Trunk -->
        <div class="gallery-item" data-category="hampers"
             data-img="{{ asset('images/iris.jpg') }}"
             data-badge="🎁 Luxury Hampers"
             data-title="Hampers Iris Luxury Trunk"
             data-desc="Kotak hampers koper mewah berpita emas elegan cocok untuk hantaran korporat & perayaan keluarga.">
          <img src="{{ asset('images/iris.jpg') }}" alt="Hampers Iris Luxury Trunk" loading="lazy">
          <div class="gallery-item-overlay">
            <div class="gallery-item-zoom-icon">🔍</div>
            <div class="gallery-item-tag">🎁 Luxury Hampers</div>
            <div class="gallery-item-title">Iris Luxury Trunk</div>
          </div>
        </div>

        <!-- 5. Pure Wijsman Butter Cookies -->
        <div class="gallery-item" data-category="cookies"
             data-img="{{ asset('images/butter-cookies-jar.jpg') }}"
             data-badge="🍪 Artisan Cookies"
             data-title="Pure Wijsman Butter Cookies"
             data-desc="Kue semprit klasik mentega murni Wijsman Belanda dengan tekstur renyah dan lumer di mulut.">
          <img src="{{ asset('images/butter-cookies-jar.jpg') }}" alt="Pure Wijsman Butter Cookies" loading="lazy">
          <div class="gallery-item-overlay">
            <div class="gallery-item-zoom-icon">🔍</div>
            <div class="gallery-item-tag">🍪 Artisan Cookies</div>
            <div class="gallery-item-title">Butter Cookies Jar</div>
          </div>
        </div>

        <!-- 6. Royal Nastar Nanas Wijsman -->
        <div class="gallery-item" data-category="cookies"
             data-img="{{ asset('images/nastar-pineapple.jpg') }}"
             data-badge="🍪 Artisan Cookies"
             data-title="Royal Nastar Nanas Wijsman"
             data-desc="Kue nastar bulat emas beroles butter Wijsman dengan selai nanas segar caramel homemade.">
          <img src="{{ asset('images/nastar-pineapple.jpg') }}" alt="Royal Nastar Nanas Wijsman" loading="lazy">
          <div class="gallery-item-overlay">
            <div class="gallery-item-zoom-icon">🔍</div>
            <div class="gallery-item-tag">🍪 Artisan Cookies</div>
            <div class="gallery-item-title">Nastar Wijsman</div>
          </div>
        </div>

        <!-- 7. Premium Edam Kaastengel -->
        <div class="gallery-item" data-category="cookies"
             data-img="{{ asset('images/kaastengel.jpg') }}"
             data-badge="🍪 Artisan Cookies"
             data-title="Premium Edam Kaastengel"
             data-desc="Kue kering keju Edam tua Belanda asli berpadu taburan keju cheddar gurih renyah.">
          <img src="{{ asset('images/kaastengel.jpg') }}" alt="Premium Edam Kaastengel" loading="lazy">
          <div class="gallery-item-overlay">
            <div class="gallery-item-zoom-icon">🔍</div>
            <div class="gallery-item-tag">🍪 Artisan Cookies</div>
            <div class="gallery-item-title">Edam Kaastengel</div>
          </div>
        </div>

        <!-- 8. Basque Burnt Cheesecake -->
        <div class="gallery-item" data-category="cakes"
             data-img="{{ asset('images/basque-burnt-cheesecake.jpg') }}"
             data-badge="🎂 Cakes & Tarts"
             data-title="Basque Burnt Cheesecake"
             data-desc="Kue keju panggang ala Basque dengan bagian atas karamel harum dan tengah yang creamy lembut.">
          <img src="{{ asset('images/basque-burnt-cheesecake.jpg') }}" alt="Basque Burnt Cheesecake" loading="lazy">
          <div class="gallery-item-overlay">
            <div class="gallery-item-zoom-icon">🔍</div>
            <div class="gallery-item-tag">🎂 Cakes & Tarts</div>
            <div class="gallery-item-title">Burnt Cheesecake</div>
          </div>
        </div>

        <!-- 9. Fresh Artisanal Berry Tart -->
        <div class="gallery-item" data-category="cakes"
             data-img="{{ asset('images/berry-tart.jpg') }}"
             data-badge="🎂 Cakes & Tarts"
             data-title="Fresh Artisanal Berry Tart"
             data-desc="Tart pastry renyah butter Wijsman bermahkotakan aneka buah berry segar dan krim vanilla lembut.">
          <img src="{{ asset('images/berry-tart.jpg') }}" alt="Fresh Artisanal Berry Tart" loading="lazy">
          <div class="gallery-item-overlay">
            <div class="gallery-item-zoom-icon">🔍</div>
            <div class="gallery-item-tag">🎂 Cakes & Tarts</div>
            <div class="gallery-item-title">Fresh Berry Tart</div>
          </div>
        </div>

        <!-- 10. Classic Mascarpone Tiramisu -->
        <div class="gallery-item" data-category="cakes"
             data-img="{{ asset('images/tiramisu.jpg') }}"
             data-badge="🎂 Cakes & Tarts"
             data-title="Classic Mascarpone Tiramisu"
             data-desc="Dessert Italia autentik dengan ladyfinger beraroma kopi arabika dan krim mascarpone lembut.">
          <img src="{{ asset('images/tiramisu.jpg') }}" alt="Classic Mascarpone Tiramisu" loading="lazy">
          <div class="gallery-item-overlay">
            <div class="gallery-item-zoom-icon">🔍</div>
            <div class="gallery-item-tag">🎂 Cakes & Tarts</div>
            <div class="gallery-item-title">Classic Tiramisu</div>
          </div>
        </div>

        <!-- 11. Mooncake Hampers Celebration Box -->
        <div class="gallery-item" data-category="special"
             data-img="{{ asset('images/cat-mooncake.jpg') }}"
             data-badge="🌙 Special Edition"
             data-title="Mooncake Hampers Celebration Box"
             data-desc="Paket hampers spesial festival kue bulan dengan motif tradisional emas dan rasa lotus autentik.">
          <img src="{{ asset('images/cat-mooncake.jpg') }}" alt="Mooncake Hampers Celebration Box" loading="lazy">
          <div class="gallery-item-overlay">
            <div class="gallery-item-zoom-icon">🔍</div>
            <div class="gallery-item-tag">🌙 Special Edition</div>
            <div class="gallery-item-title">Mooncake Box</div>
          </div>
        </div>

        <!-- 12. Gourmet Bites Celebration Box of 6 -->
        <div class="gallery-item" data-category="special"
             data-img="{{ asset('images/box-of-6.jpg') }}"
             data-badge="🌙 Special Edition"
             data-title="Gourmet Bites Celebration Box of 6"
             data-desc="Koleksi 6 varian kue dan pastri signature dalam kemasan kotak kado berpita emas premium.">
          <img src="{{ asset('images/box-of-6.jpg') }}" alt="Gourmet Bites Celebration Box of 6" loading="lazy">
          <div class="gallery-item-overlay">
            <div class="gallery-item-zoom-icon">🔍</div>
            <div class="gallery-item-tag">🌙 Special Edition</div>
            <div class="gallery-item-title">Gourmet Box of 6</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Fullscreen Interactive Lightbox Modal -->
<div class="gallery-lightbox-modal" id="galleryLightboxModal" role="dialog" aria-modal="true" aria-label="Pratinjau Galeri">
  <div class="lightbox-content-box" id="lightboxContentBox">
    <button type="button" class="lightbox-close-btn" id="lightboxCloseBtn" title="Tutup (Esc)">&times;</button>
    <button type="button" class="lightbox-nav-btn lightbox-prev-btn" id="lightboxPrevBtn" title="Sebelumnya (←)">&#8249;</button>
    <button type="button" class="lightbox-nav-btn lightbox-next-btn" id="lightboxNextBtn" title="Berikutnya (→)">&#8250;</button>

    <div class="lightbox-img-wrap">
      <img id="lightboxActiveImg" src="" alt="Pratinjau Galeri Foto">
    </div>

    <div class="lightbox-info-bar">
      <div style="flex: 1; min-width: 0;">
        <div class="lightbox-badge-row">
          <span class="lightbox-cat-badge" id="lightboxCatBadge">🎁 LUXURY HAMPERS</span>
          <span class="lightbox-counter-text" id="lightboxCounter">1 / 12</span>
        </div>
        <h3 class="lightbox-title" id="lightboxTitle">Judul Foto</h3>
        <p class="lightbox-desc" id="lightboxDesc">Deskripsi isi galeri foto produk.</p>
      </div>
      <div>
        <a href="{{ route('shop') }}" class="btn-gold" style="padding: 8px 16px; font-size: 12px; border-radius: 6px; text-decoration: none;">
          <span>Belanja 🛍️</span>
        </a>
      </div>
    </div>
  </div>
</div>

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
    // 1. Kategori Slider Track (Scroll Horizontal)
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

    // 2. Hero Banner Slider (Gambar Bergeser ke Kanan Otomatis & Manual)
    const heroTrack = document.getElementById('heroSliderTrack');
    const heroPrevBtn = document.getElementById('heroPrevBtn');
    const heroNextBtn = document.getElementById('heroNextBtn');
    const heroDots = document.querySelectorAll('.hero-dot');
    const heroSlides = document.querySelectorAll('.hero-slide');

    if (heroTrack && heroSlides.length > 1) {
      let currentIndex = 0;
      const totalSlides = heroSlides.length;
      const autoplayInterval = {{ $heroSettings['interval'] ?? 4000 }};
      const isAutoplay = {{ ($heroSettings['autoplay'] ?? true) ? 'true' : 'false' }};
      let timer = null;

      function goToSlide(index) {
        if (index < 0) {
          currentIndex = totalSlides - 1;
        } else if (index >= totalSlides) {
          currentIndex = 0;
        } else {
          currentIndex = index;
        }

        heroTrack.style.transform = `translateX(-${currentIndex * 100}%)`;

        heroSlides.forEach((slide, i) => {
          if (i === currentIndex) {
            slide.classList.add('active');
          } else {
            slide.classList.remove('active');
          }
        });

        heroDots.forEach((dot, i) => {
          if (i === currentIndex) {
            dot.classList.add('active');
          } else {
            dot.classList.remove('active');
          }
        });
      }

      function nextSlide() {
        goToSlide(currentIndex + 1);
      }

      function prevSlide() {
        goToSlide(currentIndex - 1);
      }

      if (heroNextBtn) {
        heroNextBtn.addEventListener('click', function() {
          nextSlide();
          resetAutoplay();
        });
      }

      if (heroPrevBtn) {
        heroPrevBtn.addEventListener('click', function() {
          prevSlide();
          resetAutoplay();
        });
      }

      heroDots.forEach((dot) => {
        dot.addEventListener('click', function() {
          const target = parseInt(this.getAttribute('data-slide-to'), 10);
          goToSlide(target);
          resetAutoplay();
        });
      });

      function startAutoplay() {
        if (isAutoplay && !timer) {
          timer = setInterval(nextSlide, autoplayInterval);
        }
      }

      function stopAutoplay() {
        if (timer) {
          clearInterval(timer);
          timer = null;
        }
      }

      function resetAutoplay() {
        stopAutoplay();
        startAutoplay();
      }

      const heroSection = document.getElementById('heroBannerSection');
      if (heroSection) {
        heroSection.addEventListener('mouseenter', stopAutoplay);
        heroSection.addEventListener('mouseleave', startAutoplay);

        // Touch swipe support (Mobile)
        let touchStartX = 0;
        let touchEndX = 0;
        heroSection.addEventListener('touchstart', function(e) {
          touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        heroSection.addEventListener('touchend', function(e) {
          touchEndX = e.changedTouches[0].screenX;
          if (touchEndX < touchStartX - 40) {
            nextSlide(); // Geser ke kanan
            resetAutoplay();
          } else if (touchEndX > touchStartX + 40) {
            prevSlide(); // Geser ke kiri
            resetAutoplay();
          }
        }, { passive: true });
      }

      startAutoplay();
    }

    /* ========================================================
       Interactive Gallery: Filter Tabs & Fullscreen Lightbox
    ======================================================== */
    const galleryItems = Array.from(document.querySelectorAll('.gallery-item'));
    const filterTabs = document.querySelectorAll('.gallery-tab-btn');
    const lightboxModal = document.getElementById('galleryLightboxModal');
    const lightboxContentBox = document.getElementById('lightboxContentBox');
    const lightboxImg = document.getElementById('lightboxActiveImg');
    const lightboxTitle = document.getElementById('lightboxTitle');
    const lightboxCatBadge = document.getElementById('lightboxCatBadge');
    const lightboxDesc = document.getElementById('lightboxDesc');
    const lightboxCounter = document.getElementById('lightboxCounter');
    const lightboxCloseBtn = document.getElementById('lightboxCloseBtn');
    const lightboxPrevBtn = document.getElementById('lightboxPrevBtn');
    const lightboxNextBtn = document.getElementById('lightboxNextBtn');

    let visibleGalleryItems = [...galleryItems];
    let currentLightboxIdx = 0;

    // Filter Tabs Click Logic
    filterTabs.forEach(tab => {
      tab.addEventListener('click', function() {
        filterTabs.forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        const filterVal = this.getAttribute('data-filter');

        galleryItems.forEach(item => {
          const itemCat = item.getAttribute('data-category');
          if (filterVal === 'all' || itemCat === filterVal) {
            item.style.display = 'block';
          } else {
            item.style.display = 'none';
          }
        });

        visibleGalleryItems = galleryItems.filter(item => item.style.display !== 'none');
      });
    });

    function showLightboxItem(index) {
      if (!visibleGalleryItems.length) return;
      if (index < 0) index = visibleGalleryItems.length - 1;
      if (index >= visibleGalleryItems.length) index = 0;

      currentLightboxIdx = index;
      const targetItem = visibleGalleryItems[index];

      const imgSrc = targetItem.getAttribute('data-img');
      const title = targetItem.getAttribute('data-title');
      const badge = targetItem.getAttribute('data-badge');
      const desc = targetItem.getAttribute('data-desc');

      if (lightboxImg) {
        lightboxImg.style.opacity = '0.3';
        setTimeout(() => {
          lightboxImg.src = imgSrc;
          lightboxImg.alt = title;
          if (lightboxTitle) lightboxTitle.textContent = title;
          if (lightboxCatBadge) lightboxCatBadge.textContent = badge;
          if (lightboxDesc) lightboxDesc.textContent = desc;
          if (lightboxCounter) lightboxCounter.textContent = `${index + 1} / ${visibleGalleryItems.length}`;
          lightboxImg.style.opacity = '1';
        }, 80);
      }
    }

    galleryItems.forEach(item => {
      item.addEventListener('click', function() {
        const itemIdx = visibleGalleryItems.indexOf(this);
        if (itemIdx !== -1) {
          showLightboxItem(itemIdx);
          if (lightboxModal) {
            lightboxModal.classList.add('active');
            document.body.style.overflow = 'hidden';
          }
        }
      });
    });

    function closeLightbox() {
      if (lightboxModal) {
        lightboxModal.classList.remove('active');
        document.body.style.overflow = '';
      }
    }

    if (lightboxCloseBtn) lightboxCloseBtn.addEventListener('click', closeLightbox);
    if (lightboxPrevBtn) lightboxPrevBtn.addEventListener('click', () => showLightboxItem(currentLightboxIdx - 1));
    if (lightboxNextBtn) lightboxNextBtn.addEventListener('click', () => showLightboxItem(currentLightboxIdx + 1));

    if (lightboxModal) {
      lightboxModal.addEventListener('click', function(e) {
        if (lightboxContentBox && !lightboxContentBox.contains(e.target)) {
          closeLightbox();
        }
      });
    }

    // Keyboard navigation (Esc, Left, Right)
    document.addEventListener('keydown', function(e) {
      if (lightboxModal && lightboxModal.classList.contains('active')) {
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') showLightboxItem(currentLightboxIdx - 1);
        if (e.key === 'ArrowRight') showLightboxItem(currentLightboxIdx + 1);
      }
    });

    // Touch Swipe Support for Lightbox (Mobile)
    let lbTouchStartX = 0;
    let lbTouchEndX = 0;
    if (lightboxContentBox) {
      lightboxContentBox.addEventListener('touchstart', function(e) {
        lbTouchStartX = e.changedTouches[0].screenX;
      }, { passive: true });

      lightboxContentBox.addEventListener('touchend', function(e) {
        lbTouchEndX = e.changedTouches[0].screenX;
        if (lbTouchEndX < lbTouchStartX - 40) {
          showLightboxItem(currentLightboxIdx + 1); // Swipe left = next
        } else if (lbTouchEndX > lbTouchStartX + 40) {
          showLightboxItem(currentLightboxIdx - 1); // Swipe right = prev
        }
      }, { passive: true });
    }
  });
</script>
@endsection
