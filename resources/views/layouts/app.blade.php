<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', config('site.brand'))</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800&family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,400;1,600&family=Work+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/site.css') }}">
<style>
  .btn-gold-pill {
    display: inline-block;
    background: linear-gradient(135deg, #b89243 0%, #e6ca65 35%, #c59b27 70%, #997317 100%);
    color: #0c2217 !important;
    font-family: 'Outfit', sans-serif;
    font-weight: 800;
    font-size: 13px;
    letter-spacing: 2px;
    padding: 12px 30px;
    border-radius: 50px;
    text-decoration: none;
    box-shadow: 0 4px 15px rgba(197, 155, 39, 0.4), inset 0 1px 0 rgba(255,255,255,0.4);
    transition: all 0.3s ease;
    border: 1px solid #fef08a;
    text-transform: uppercase;
  }
  .btn-gold-pill:hover {
    transform: translateY(-2px) scale(1.03);
    box-shadow: 0 8px 24px rgba(230, 202, 101, 0.6);
    color: #000 !important;
  }

  /* Universal Image & Product Card Sizing Safety */
  img {
    max-width: 100%;
  }
  .ref-white-img-wrap {
    width: 100%;
    aspect-ratio: 1/1;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
  }
  .ref-white-img-wrap img {
    width: 100% !important;
    height: 100% !important;
    max-width: 100% !important;
    object-fit: cover !important;
    display: block !important;
  }

  /* Ultra-Luxurious Royal Burgundy & Gold Header Bar */
  .top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #6a0d24;
    padding: 10px 40px;
    font-size: 11px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.15);
    color: #ffffff;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    font-family: 'Outfit', sans-serif;
    flex-wrap: wrap;
    gap: 16px;
  }
  .top nav {
    display: flex;
    gap: 28px;
    align-items: center;
    flex-wrap: wrap;
  }
  .top nav a {
    color: #ffd1dc;
    text-decoration: none;
    transition: all 0.2s ease;
    font-weight: 700;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    letter-spacing: 2px;
  }
  .top nav a:hover {
    color: #ffffff;
    text-shadow: 0 0 8px rgba(255, 255, 255, 0.6);
  }
  .header-right-group {
    display: flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
  }
  .currency-pill {
    font-size: 10px;
    color: #e6ca65;
    font-weight: 700;
    padding: 3px 10px;
    background: rgba(230, 202, 101, 0.1);
    border: 1px solid rgba(230, 202, 101, 0.4);
    border-radius: 4px;
    white-space: nowrap;
    letter-spacing: 1px;
  }
  .user-action-group {
    display: flex;
    align-items: center;
    gap: 16px;
  }
  .top-text-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 800;
    color: #e2e8f0;
    text-decoration: none;
    letter-spacing: 1.5px;
    transition: all 0.2s ease;
    white-space: nowrap;
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 0;
  }
  .top-text-link:hover {
    color: #fef08a;
    text-shadow: 0 0 8px rgba(230, 202, 101, 0.4);
  }
  .header-icon-btn {
    background: transparent;
    border: none;
    cursor: pointer;
    font-size: 11px;
    color: #e2e8f0;
    position: relative;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    transition: all 0.2s ease;
    padding: 0;
    font-weight: 800;
    letter-spacing: 1.5px;
    white-space: nowrap;
  }
  .header-icon-btn:hover {
    color: #fef08a;
    text-shadow: 0 0 8px rgba(230, 202, 101, 0.4);
  }
  .cart-badge {
    position: absolute;
    top: -8px;
    right: -10px;
    background: linear-gradient(135deg, #b89243, #e6ca65);
    color: #081711;
    font-size: 10px;
    font-weight: 800;
    width: 17px;
    height: 17px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 8px rgba(13, 33, 22, 0.6);
  }

  /* 3-Dots Menu Button & Mobile Drawer Overlay Styling */
  .three-dots-btn {
    display: none;
    background: transparent;
    border: none;
    color: #f59e0b;
    font-size: 20px;
    font-weight: 700;
    line-height: 1;
    cursor: pointer;
    padding: 2px 6px;
    border-radius: 4px;
    transition: all 0.2s;
    margin-left: 4px;
  }
  .three-dots-btn:hover {
    color: #fef08a;
    background: rgba(255, 255, 255, 0.08);
  }

  /* Souvia-Style Dedicated Mobile Header Bar */
  .souvia-mobile-topbar {
    display: none;
  }
  

  /* Hamburger Menu Wrapper Button (Matching User Reference Image) */
  .header-menu-wrap-btn {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 8px 12px;
    border-radius: 6px;
    transition: all 0.2s ease;
    outline: none;
    text-decoration: none;
  }
  .header-menu-wrap-btn:hover {
    background: rgba(255, 255, 255, 0.08);
  }
  .hamburger-icon-bars {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    width: 22px;
    height: 15px;
  }
  .hamburger-icon-bars span {
    display: block;
    width: 100%;
    height: 2px;
    background: #e2e8f0;
    border-radius: 2px;
    transition: background 0.2s ease, transform 0.2s ease;
  }
  .header-menu-wrap-btn:hover .hamburger-icon-bars span {
    background: #fef08a;
  }
  .menu-btn-text {
    font-family: 'Cormorant Garamond', 'Playfair Display', serif;
    font-size: 22px;
    font-weight: 600;
    letter-spacing: 0.5px;
    color: #f1f5f9;
    line-height: 1;
    transition: color 0.2s ease;
  }
  .header-menu-wrap-btn:hover .menu-btn-text {
    color: #fef08a;
  }

  /* Unified Luxury Header Bar */
  .luxury-header-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 40px;
    background: #87102e;
    border-bottom: 2px solid #e6ca65;
    gap: 20px;
    position: relative;
    box-shadow: 0 6px 30px rgba(0, 0, 0, 0.25);
  }
  .header-left-col {
    display: flex;
    align-items: center;
    flex: 1 1 auto;
    min-width: 0;
  }
  .luxury-brand-stack {
    display: inline-flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
    text-decoration: none;
    text-align: left;
    padding: 2px 0;
    transition: transform 0.25s ease;
  }
  .luxury-brand-stack:hover {
    transform: scale(1.01);
  }
  .fleur-crest-top, .fleur-crest-bottom {
    display: none;
  }
  .logo-title-majestic {
    font-family: 'Cormorant Garamond', 'Cinzel', serif;
    font-size: 26px;
    letter-spacing: 0.22em;
    line-height: 1.15;
    background: linear-gradient(135deg, #fff3c4 0%, #e6ca65 40%, #c59b27 75%, #fef08a 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-weight: 700;
    text-transform: uppercase;
    display: block;
    white-space: nowrap;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.4);
  }
  .logo-subtext-clean {
    display: block;
    font-family: 'Outfit', sans-serif;
    font-size: 9px;
    letter-spacing: 0.24em;
    color: #cbd5e1;
    font-weight: 600;
    margin-top: 3px;
    text-transform: uppercase;
    -webkit-text-fill-color: #cbd5e1;
  }
  .header-right-col {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    flex: 0 0 auto;
    min-width: auto;
  }

  @media (max-width: 900px) {
    .luxury-header-bar {
      padding: 10px 18px;
    }
    .logo-title-majestic {
      font-size: clamp(14px, 3.8vw, 20px) !important;
      letter-spacing: 0.12em !important;
      white-space: nowrap !important;
    }
    .logo-subtext-clean {
      font-size: 8px;
      letter-spacing: 0.12em;
    }
  }

  @media (max-width: 768px) {
    body {
      padding-bottom: 60px; /* Space for fixed bottom mobile navigation */
    }
    .luxury-header-bar {
      padding: 8px 12px !important;
      min-height: 52px !important;
      max-width: 100vw !important;
      box-sizing: border-box !important;
    }
    .header-left-col {
      flex: 1 1 auto !important;
      min-width: 0 !important;
      overflow: hidden !important;
    }
    .luxury-brand-stack {
      padding: 0 !important;
      align-items: flex-start !important;
      text-align: left !important;
      max-width: 100% !important;
    }
    .fleur-crest-top, .fleur-crest-bottom {
      display: none !important;
    }
    .logo-title-majestic {
      font-size: clamp(13px, 4.2vw, 16.5px) !important;
      letter-spacing: 0.08em !important;
      white-space: nowrap !important;
      overflow: hidden !important;
      text-overflow: ellipsis !important;
      line-height: 1.2 !important;
      margin: 0 !important;
      padding: 0 !important;
    }
    .logo-subtext-clean {
      display: none !important;
    }
    .header-right-col {
      flex: 0 0 auto !important;
      min-width: auto !important;
      margin-left: 8px !important;
    }
    .header-menu-wrap-btn {
      padding: 6px 12px !important;
      gap: 7px !important;
    }
    .menu-btn-text {
      font-size: 13.5px !important;
    }
    .hamburger-icon-bars {
      width: 16px !important;
      height: 11px !important;
    }
  }

  @media (max-width: 380px) {
    .luxury-header-bar {
      padding: 6px 10px !important;
    }
    .logo-title-majestic {
      font-size: 12px !important;
      letter-spacing: 0.05em !important;
    }
    .header-menu-wrap-btn {
      padding: 5px 8px !important;
      gap: 5px !important;
    }
    .menu-btn-text {
      font-size: 12.5px !important;
    }
  }

  /* Mobile Bottom Navigation Bar (Matching Android Reference Image 1) */
  .mobile-bottom-nav-bar {
    display: none;
  }
  @media (max-width: 768px) {
    .mobile-bottom-nav-bar {
      display: flex;
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      height: 56px;
      background: #14050e;
      border-top: 1px solid rgba(230, 202, 101, 0.25);
      box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.55);
      z-index: 9998;
      align-items: center;
      justify-content: space-around;
      padding: 0 6px;
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
    }
    .mobile-bnav-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      color: #94a3b8;
      background: transparent;
      border: none;
      padding: 4px 8px;
      cursor: pointer;
      font-family: 'Outfit', sans-serif;
      transition: all 0.2s ease;
      flex: 1;
      max-width: 100px;
      outline: none;
    }
    .mobile-bnav-item.active,
    .mobile-bnav-item:hover {
      color: #fef08a;
    }
    .mobile-bnav-icon {
      font-size: 20px;
      line-height: 1;
      margin-bottom: 2px;
      position: relative;
      display: inline-block;
    }
    .mobile-bnav-text {
      font-size: 10.5px;
      font-weight: 700;
      letter-spacing: 0.4px;
    }
    .mobile-bnav-badge {
      position: absolute;
      top: -5px;
      right: -10px;
      background: linear-gradient(135deg, #b89243, #e6ca65);
      color: #081711;
      font-size: 9.5px;
      font-weight: 800;
      width: 17px;
      height: 17px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 0 6px rgba(0,0,0,0.6);
    }
  }

  /* Off-Canvas Slide-In Drawer (Wrapping the Navigation Menu - Slides from Right) */
  .mobile-drawer-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.7);
    backdrop-filter: blur(6px);
    z-index: 99999;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
    display: flex;
    justify-content: flex-end;
  }
  .mobile-drawer-overlay.open {
    opacity: 1;
    visibility: visible;
  }
  .drawer-panel-inner {
    width: 100%;
    max-width: 380px;
    height: 100%;
    background: #ffffff;
    display: flex;
    flex-direction: column;
    box-shadow: -10px 0 40px rgba(0, 0, 0, 0.4);
    transform: translateX(100%);
    transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    overflow-y: auto;
  }
  .mobile-drawer-overlay.open .drawer-panel-inner {
    transform: translateX(0);
  }
  .souvia-drawer-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 18px;
    background: linear-gradient(135deg, #18060f 0%, #2a0b1b 100%);
    gap: 10px;
    position: sticky;
    top: 0;
    z-index: 10;
    border-bottom: 1px solid rgba(255,255,255,0.1);
  }
  .souvia-drawer-brand {
    font-family: 'Cormorant Garamond', serif;
    font-size: 17px;
    letter-spacing: 1.5px;
    color: #fef08a;
    font-weight: 700;
    text-decoration: none;
    white-space: nowrap;
    text-transform: uppercase;
  }
  .mobile-drawer-close {
    background: none;
    border: none;
    color: #ffffff;
    font-size: 22px;
    cursor: pointer;
    line-height: 1;
    padding: 4px 8px;
  }

  /* Drawer Navigation & Menu Styles (Matching Reference Images 1 & 2) */
  .drawer-search-container {
    padding: 14px 16px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
  }
  .drawer-search-input-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 10px 14px;
    cursor: pointer;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
  }
  .drawer-search-input-wrap input {
    border: none;
    outline: none;
    font-size: 14px;
    font-family: 'Outfit', sans-serif;
    color: #334155;
    width: 100%;
    cursor: pointer;
    background: transparent;
  }
  .drawer-menu-list {
    display: flex;
    flex-direction: column;
    padding: 6px 16px;
    background: #ffffff;
  }
  .drawer-menu-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 8px;
    border-bottom: 1px solid #f1f5f9;
    background: none;
    border-top: none;
    border-left: none;
    border-right: none;
    color: #1e293b;
    font-family: 'Outfit', sans-serif;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    text-decoration: none;
    cursor: pointer;
    text-align: left;
    transition: color 0.2s ease, transform 0.2s ease;
  }
  .drawer-menu-link:hover {
    color: #b45309;
    transform: translateX(4px);
  }
  .drawer-arrow {
    font-size: 20px;
    color: #94a3b8;
    font-weight: 300;
  }
  .drawer-utility-section {
    padding: 18px 16px 36px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  .drawer-sub-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-family: 'Outfit', sans-serif;
    font-size: 13.5px;
    color: #475569;
    font-weight: 600;
    text-decoration: none;
    padding: 8px 6px;
    border-radius: 6px;
  }
  .drawer-sub-link:hover {
    color: #0f172a;
    background: #f1f5f9;
  }
  .drawer-logout-btn {
    width: 100%;
    padding: 11px;
    background: #ef4444;
    color: #ffffff;
    border: none;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1px;
    cursor: pointer;
    text-transform: uppercase;
    margin-top: 6px;
  }
  .cart-pill-badge {
    background: #d97706;
    color: #fff;
    font-size: 11px;
    padding: 2px 7px;
    border-radius: 12px;
    font-weight: 700;
  }

  /* Info Modals (Pembayaran & Pengiriman, Syarat & Ketentuan) */
  .info-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.75);
    backdrop-filter: blur(6px);
    z-index: 999999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
  }
  .info-modal-overlay.open {
    opacity: 1;
    visibility: visible;
  }
  .info-modal-box {
    background: #ffffff;
    border-radius: 14px;
    max-width: 650px;
    width: 100%;
    max-height: 85vh;
    overflow-y: auto;
    position: relative;
    box-shadow: 0 25px 60px rgba(0,0,0,0.35);
    border: 1px solid #e2e8f0;
    animation: modalScaleUp 0.25s ease-out;
  }
  @keyframes modalScaleUp {
    from { transform: scale(0.95); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
  }
  .info-modal-close {
    position: absolute;
    top: 14px;
    right: 16px;
    background: rgba(255, 255, 255, 0.2);
    border: none;
    font-size: 18px;
    color: #fff;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    z-index: 5;
  }
  .info-modal-close:hover {
    background: #ef4444;
    color: #fff;
  }
  .info-modal-header {
    background: linear-gradient(135deg, #18060f 0%, #2a0b1b 100%);
    color: #fff;
    padding: 26px 24px 20px;
    text-align: center;
    border-top-left-radius: 14px;
    border-top-right-radius: 14px;
  }
  .info-modal-crest {
    font-size: 26px;
    margin-bottom: 6px;
  }
  .info-modal-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 24px;
    letter-spacing: 2px;
    color: #fef08a;
    font-weight: 700;
    margin: 0;
    text-transform: uppercase;
  }
  .info-modal-subtitle {
    font-size: 12.5px;
    color: #cbd5e1;
    margin-top: 4px;
    letter-spacing: 0.5px;
  }
  .info-modal-body {
    padding: 24px;
    color: #334155;
    font-family: 'Outfit', sans-serif;
  }
  .info-modal-section {
    margin-bottom: 22px;
  }
  .info-section-title {
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
    padding-bottom: 4px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .info-modal-section p {
    font-size: 13.5px;
    line-height: 1.6;
    margin: 0 0 8px 0;
  }
  .info-check-list {
    margin: 0;
    padding-left: 20px;
    font-size: 13px;
    line-height: 1.65;
    color: #475569;
  }
  .info-check-list li {
    margin-bottom: 6px;
  }
  .souvia-accordion-menu .accordion-body a:hover {
    color: #ea580c;
  }

  @media (max-width: 768px) {
    html, body {
      overflow-x: clip !important;
      max-width: 100vw !important;
      width: 100% !important;
    }
    .three-dots-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 34px;
      height: 34px;
      min-width: 34px;
      padding: 0;
      background: rgba(245, 158, 11, 0.15);
      border: 1px solid rgba(245, 158, 11, 0.35);
      border-radius: 6px;
      font-size: 18px;
    }
    .top {
      padding: 8px 12px;
      justify-content: space-between;
      align-items: center;
      gap: 8px;
      width: 100%;
      max-width: 100vw;
    }
    .top nav {
      display: none !important; /* Hide redundant text links on mobile since they exist in 3-dots drawer */
    }
    .header-right-group {
      justify-content: flex-end;
      gap: 6px;
      width: 100%;
      margin-top: 0;
      flex-wrap: nowrap;
      align-items: center;
    }
    .currency-pill {
      display: none; /* Hide currency pill on small phones to give space */
    }
    .user-action-group {
      gap: 6px;
      display: flex;
      align-items: center;
    }
    .top-text-link, .header-icon-btn {
      font-size: 13px;
      padding: 6px 8px;
      background: rgba(255, 255, 255, 0.08);
      border-radius: 6px;
      border: 1px solid rgba(255, 255, 255, 0.12);
      min-width: 34px;
      height: 34px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0;
      letter-spacing: 0;
    }
    .nav-text-label {
      display: none !important; /* Hide text on small screens, keep icons beautiful & crisp */
    }

    .head {
      padding: 12px 10px 10px;
      max-width: 100vw;
      overflow: hidden;
    }
    .logo {
      font-size: clamp(16px, 4.5vw, 20px) !important;
      letter-spacing: 1px !important;
      padding: 0 !important;
      white-space: normal !important;
      display: inline-block !important;
      text-align: center !important;
      max-width: 100% !important;
      line-height: 1.25 !important;
      word-break: normal !important;
      overflow-wrap: normal !important;
    }
    .fleur-icon {
      font-size: 14px !important;
      margin: 0 4px !important;
      vertical-align: middle !important;
    }
    .logo small {
      font-size: 8.5px !important;
      letter-spacing: 0.5px !important;
      margin-top: 4px !important;
      line-height: 1.3 !important;
      white-space: normal !important;
      word-break: normal !important;
      overflow-wrap: normal !important;
    }

    .nav-main-wrapper {
      width: 100%;
      max-width: 100vw;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
    }
    .main {
      padding: 0 8px;
      gap: 2px;
    }
    .main-nav-link {
      padding: 10px 12px;
      font-size: 11px;
      letter-spacing: .08em;
    }
  }

  /* Sticky Site Header Wrapper - Dark Velvet Royal Emerald & Gold Theme */
  .site-header-sticky-wrapper {
    position: sticky;
    top: 0;
    z-index: 9990;
    background: #081711;
    box-shadow: 0 6px 30px rgba(0, 0, 0, 0.6);
    border-bottom: 2px solid #b89243;
  }

  /* Royal Brand Logo Bar */
  .head {
    position: relative;
    background: #081711;
    border-bottom: 1px solid rgba(184, 146, 67, 0.2);
    z-index: 500;
    text-align: center;
    padding: 22px 0 16px;
    width: 100%;
  }
  .logo {
    display: inline-block;
    font-family: 'Cormorant Garamond', serif;
    font-size: 42px;
    letter-spacing: .28em;
    line-height: 1.1;
    background: linear-gradient(135deg, #fff3c4 0%, #e6ca65 40%, #c59b27 75%, #fef08a 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-weight: 700;
    text-decoration: none;
    text-transform: uppercase;
    transition: all 0.3s ease;
    padding: 0 20px;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5));
  }
  .logo:hover {
    filter: drop-shadow(0 0 12px rgba(230, 202, 101, 0.6));
  }
  .fleur-icon {
    color: #e6ca65;
    font-size: 32px;
    vertical-align: middle;
    margin: 0 14px;
    display: inline-block;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.5));
  }
  .logo small {
    display: block;
    font-family: 'Outfit', sans-serif;
    font-size: 10.5px;
    letter-spacing: .38em;
    color: #cbd5e1;
    font-weight: 600;
    margin-top: 6px;
    text-shadow: none;
  }

  /* Royal Dark Emerald Navigation Bar */
  .nav-main-wrapper {
    position: relative;
    background: #050e0a;
    margin-top: 0;
    width: 100%;
    border-top: 1px solid rgba(184, 146, 67, 0.2);
  }
  .main {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 4px;
    padding: 0 20px;
    max-width: 1350px;
    margin: 0 auto;
    flex-wrap: wrap;
  }
  .main-nav-link {
    display: inline-block;
    padding: 14px 20px;
    font-family: 'Cormorant Garamond', serif;
    text-transform: uppercase;
    letter-spacing: .18em;
    font-weight: 700;
    font-size: 14px;
    color: #e2e8f0;
    text-decoration: none;
    transition: all 0.25s ease;
    border-bottom: 2px solid transparent;
    white-space: nowrap;
  }
  .main-nav-link:hover, .main-nav-link.active {
    color: #fef08a;
    background: rgba(230, 202, 101, 0.1);
    border-bottom-color: #e6ca65;
    text-shadow: 0 0 8px rgba(230, 202, 101, 0.4);
  }

  @media (max-width: 1100px) {
    .nav-main-wrapper {
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      scrollbar-width: none;
    }
    .nav-main-wrapper::-webkit-scrollbar {
      display: none;
    }
    .main {
      justify-content: flex-start;
      overflow-x: auto;
      flex-wrap: nowrap;
      -webkit-overflow-scrolling: touch;
      padding: 0 10px;
      width: max-content;
      min-width: 100%;
    }
    .main-nav-link {
      padding: 12px 14px;
      font-size: 12px;
      letter-spacing: .1em;
    }
  }

  /* Mega Menu Royal Dark Dropdown */
  .mega-menu-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: #0f1c16;
    border-bottom: 3px solid #d97706;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.5);
    opacity: 0;
    visibility: hidden;
    transform: translateY(12px);
    transition: opacity 0.3s ease, transform 0.3s ease, visibility 0.3s ease;
    z-index: 600;
    pointer-events: none;
  }
  .nav-item-has-mega:hover .mega-menu-dropdown,
  .mega-menu-dropdown:hover {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
    pointer-events: auto;
  }

  .mega-menu-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 36px 30px;
    display: grid;
    grid-template-columns: minmax(210px, 1fr) minmax(170px, 1fr) minmax(190px, 1fr) 1.3fr;
    gap: 32px;
    align-items: start;
    text-align: left;
  }
  .mega-column-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 15px;
    letter-spacing: 2px;
    font-weight: 600;
    text-transform: uppercase;
    color: #fef08a;
    margin-bottom: 16px;
    border-bottom: 1px solid rgba(217, 119, 6, 0.3);
    padding-bottom: 8px;
    white-space: normal;
    word-break: break-word;
  }
  .mega-link-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  .mega-link-list a {
    font-size: 13px;
    color: #a7bbb0;
    text-decoration: none;
    transition: all 0.2s ease;
    font-family: 'Outfit', sans-serif;
    white-space: normal;
    line-height: 1.4;
    word-break: break-word;
    overflow-wrap: break-word;
  }
  .mega-link-list a:hover {
    color: #fef08a;
    transform: translateX(4px);
  }

  .mega-featured-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
  }
  .mega-card {
    display: block;
    text-decoration: none;
    color: inherit;
    text-align: center;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(217, 119, 6, 0.2);
    border-radius: 6px;
    padding: 10px;
    transition: all 0.25s ease;
  }
  .mega-card:hover {
    border-color: #f59e0b;
    background: rgba(255, 255, 255, 0.06);
    transform: translateY(-3px);
  }
  .mega-card img {
    width: 100%;
    aspect-ratio: 1/1;
    object-fit: cover;
    border-radius: 4px;
    background: #1a2a22;
    margin-bottom: 8px;
    transition: transform 0.2s;
  }
  .mega-card-title {
    font-size: 12.5px;
    font-weight: 600;
    color: #cad8d1;
  }

  /* Live Search Modal Overlay */
  .search-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.85);
    backdrop-filter: blur(8px);
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
  }
  .search-modal-overlay.open {
    opacity: 1;
    visibility: visible;
  }
  .search-modal-box {
    width: 100%;
    max-width: 650px;
    background: #fff;
    border-radius: 14px;
    padding: 30px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
    position: relative;
    max-height: 85vh;
    display: flex;
    flex-direction: column;
    margin: 0 auto;
  }
  .search-modal-close {
    position: absolute;
    top: 16px;
    right: 20px;
    background: none;
    border: none;
    font-size: 24px;
    color: #64748b;
    cursor: pointer;
  }

  /* Ultra-Luxurious Royal Emerald & Gold Footer Styling */
  .luxury-footer {
    background: linear-gradient(180deg, #0e1a15 0%, #15241e 40%, #0b1410 100%);
    color: #cad8d1;
    font-family: 'Outfit', sans-serif;
    font-size: 13px;
    line-height: 1.85;
    position: relative;
    border-top: 3px solid transparent;
    border-image: linear-gradient(90deg, #854d0e, #d97706, #fbbf24, #d97706, #854d0e) 1;
    box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.4);
  }

  /* Royal Crest Header Bar */
  .footer-royal-crest {
    text-align: center;
    padding: 30px 20px 10px;
    border-bottom: 1px solid rgba(217, 119, 6, 0.15);
    background: rgba(0, 0, 0, 0.15);
  }
  .royal-crest-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 15px;
    letter-spacing: 5px;
    color: #f59e0b;
    text-transform: uppercase;
    font-weight: 600;
  }
  .royal-crest-tag {
    font-size: 11px;
    color: #8fa59b;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-top: 2px;
  }

  /* Ultra-Luxurious Minimalist Royal Footer */
  .luxury-footer {
    background: #54081c;
    color: #ffd1dc;
    border-top: 2px solid #e6ca65;
    font-family: 'Outfit', sans-serif;
  }
  .footer-royal-crest {
    text-align: center;
    padding: 36px 20px 24px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
  }
  .royal-crest-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 22px;
    letter-spacing: 4px;
    color: #fef08a;
    font-weight: 700;
    text-transform: uppercase;
  }
  .royal-crest-tag {
    font-size: 11px;
    letter-spacing: 2px;
    color: #854d0e;
    text-transform: uppercase;
    margin-top: 4px;
  }

  .footer-main-content {
    max-width: 900px;
    margin: 0 auto;
    padding: 36px 30px 28px;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 48px;
  }
  @media (max-width: 768px) {
    .footer-main-content {
      grid-template-columns: repeat(2, 1fr) !important;
      gap: 24px 18px !important;
      text-align: left !important;
      padding: 28px 16px 20px !important;
    }
    .footer-col h4 {
      font-size: 13.5px !important;
      letter-spacing: 1.5px !important;
      margin-bottom: 10px !important;
    }
    .footer-col p, .footer-col a {
      font-size: 11.5px !important;
      line-height: 1.6 !important;
      margin-bottom: 5px !important;
    }
  }

  .footer-col h4 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 15px;
    letter-spacing: 2.5px;
    font-weight: 700;
    text-transform: uppercase;
    color: #fef08a;
    margin-bottom: 14px;
    display: inline-block;
  }
  .footer-col p {
    color: #9cb3a8;
    margin-bottom: 8px;
    font-size: 12.5px;
    line-height: 1.7;
  }
  .footer-col a {
    color: #9cb3a8;
    text-decoration: none;
    transition: color 0.2s ease;
    display: block;
    margin-bottom: 6px;
    font-size: 12.5px;
  }
  .footer-col a:hover {
    color: #f59e0b;
  }

  .footer-bottom-bar {
    border-top: 1px solid rgba(255, 255, 255, 0.06);
    padding: 18px 24px;
    background: #060c09;
    font-size: 11px;
    color: #64748b;
  }
  .bottom-inner {
    max-width: 1100px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
  }
  @media (max-width: 768px) {
    .bottom-inner {
      justify-content: flex-start;
      text-align: left;
      flex-direction: column;
      align-items: flex-start;
      gap: 6px;
    }
  }
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
    font-size: 12px;
    color: #799185;
  }
  .payment-badges-row {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 11px;
    color: #9cb3a8;
    flex-wrap: wrap;
  }
  .payment-pill {
    padding: 3px 8px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 3px;
    font-size: 10px;
    letter-spacing: 0.5px;
    color: #cad8d1;
  }

  /* Interactive Luxury Product & Hampers Detail Modal */
  .product-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(13, 23, 19, 0.85);
    backdrop-filter: blur(8px);
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
  }
  .product-modal-overlay.open {
    opacity: 1;
    visibility: visible;
  }
  .product-modal-box {
    background: #ffffff;
    border-radius: 14px;
    max-width: 760px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
    border: 1px solid rgba(217, 119, 6, 0.3);
  }
  .product-modal-close {
    position: absolute;
    top: 14px;
    right: 16px;
    background: rgba(13, 23, 19, 0.1);
    border: none;
    font-size: 20px;
    color: #122019;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
  }
  .product-modal-close:hover {
    background: #d97706;
    color: #fff;
  }
  .product-modal-content {
    display: grid;
    grid-template-columns: 1fr 1.2fr;
    gap: 24px;
    padding: 28px;
  }
  @media (max-width: 700px) {
    .product-modal-content {
      grid-template-columns: 1fr;
      padding: 20px 16px;
      gap: 16px;
    }
  }
  .product-modal-img-wrap {
    width: 100%;
    aspect-ratio: 1/1;
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
  }
  .product-modal-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .product-modal-category {
    font-size: 11px;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    color: #b45309;
    font-weight: 700;
    display: block;
    margin-bottom: 4px;
  }
  .product-modal-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 26px;
    letter-spacing: 1px;
    color: #122019;
    font-weight: 700;
    margin: 0 0 8px 0;
    line-height: 1.25;
  }
  .product-modal-price {
    font-size: 20px;
    color: #b45309;
    font-weight: 800;
    margin-bottom: 6px;
  }
  .product-modal-stock {
    display: inline-block;
    font-size: 10.5px;
    letter-spacing: 1px;
    padding: 3px 8px;
    background: #f0fdf4;
    color: #15803d;
    border: 1px solid #bbf7d0;
    border-radius: 4px;
    font-weight: 600;
    margin-bottom: 16px;
  }
  .product-modal-items-section {
    background: #f8faf9;
    padding: 14px 16px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    margin-bottom: 16px;
  }
  .product-modal-items-header {
    font-size: 11px;
    letter-spacing: 2px;
    text-transform: uppercase;
    font-weight: 700;
    color: #122019;
    margin-bottom: 8px;
    border-bottom: 1px solid rgba(217, 119, 6, 0.2);
    padding-bottom: 6px;
  }
  .product-modal-items-list {
    list-style: none;
    padding: 0;
    margin: 0;
  }
  .product-modal-items-list li {
    font-size: 12.5px;
    color: #2c3e35;
    padding: 4px 0;
    display: flex;
    align-items: center;
    gap: 6px;
    line-height: 1.5;
  }
  .product-modal-meta-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
    font-size: 11.5px;
    margin-bottom: 20px;
  }
  .pm-meta-label {
    color: #64756d;
    font-weight: 600;
  }
  .pm-meta-val {
    color: #122019;
    font-weight: 700;
  }
  .product-modal-actions {
    display: flex;
    gap: 10px;
    flex-direction: column;
  }
  .pm-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    padding: 12px 16px;
    border-radius: 6px;
    font-size: 11px;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s;
  }
  .pm-btn-cart {
    background: linear-gradient(135deg, #111f18 0%, #1c3026 100%);
    color: #fef08a;
    border: 1px solid rgba(217, 119, 6, 0.3);
  }
  .pm-btn-cart:hover {
    background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
    color: #0d1713;
  }
  .pm-btn-wa {
    background: #25d366;
    color: #fff;
    border: none;
    box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
  }
  .pm-btn-wa:hover {
    background: #128c7e;
  }

  /* ================= SHOPPING CART DRAWER (MATCHING REFERENCE IMAGE 2) ================= */
  .cart-drawer-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.65);
    backdrop-filter: blur(4px);
    z-index: 10000;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.35s ease, visibility 0.35s ease;
  }
  .cart-drawer-overlay.open {
    opacity: 1;
    visibility: visible;
  }
  .cart-drawer-panel {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    max-width: 420px;
    background: #fffdf9;
    color: #1e293b;
    z-index: 10001;
    box-shadow: -10px 0 35px rgba(0, 0, 0, 0.4);
    display: flex;
    flex-direction: column;
    transform: translateX(100%);
    transition: transform 0.35s cubic-bezier(0.25, 1, 0.5, 1);
  }
  .cart-drawer-overlay.open .cart-drawer-panel {
    transform: translateX(0);
  }
  .cart-drawer-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 22px 24px;
    border-bottom: 1px solid #f1f5f9;
    background: #ffffff;
  }
  .cart-drawer-title {
    font-family: 'Playfair Display', serif;
    font-size: 18px;
    font-weight: 800;
    letter-spacing: 1px;
    color: #12030a;
    margin: 0;
    text-transform: uppercase;
  }
  .cart-drawer-close {
    background: none;
    border: none;
    font-size: 20px;
    color: #64748b;
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 4px;
    transition: color 0.2s;
  }
  .cart-drawer-close:hover {
    color: #0f172a;
  }
  .cart-drawer-body {
    flex: 1;
    overflow-y: auto;
    padding: 20px 24px;
    display: flex;
    flex-direction: column;
    gap: 18px;
  }
  .cart-drawer-item {
    display: flex;
    gap: 14px;
    align-items: center;
    position: relative;
    padding-bottom: 16px;
    border-bottom: 1px solid #f1f5f9;
  }
  .cart-drawer-remove-btn {
    position: absolute;
    top: 0;
    left: -4px;
    background: #ef4444;
    color: #fff;
    border: none;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    font-size: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(239, 68, 68, 0.4);
    z-index: 2;
  }
  .cart-drawer-thumb {
    width: 70px;
    height: 70px;
    border-radius: 8px;
    overflow: hidden;
    background: #0d0208;
    flex-shrink: 0;
  }
  .cart-drawer-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .cart-drawer-item-info {
    flex: 1;
    min-width: 0;
  }
  .cart-drawer-item-name {
    font-family: 'Playfair Display', serif;
    font-size: 14.5px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 6px 0;
    line-height: 1.3;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .cart-drawer-item-qty-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
  }
  .cart-drawer-mini-qty {
    display: inline-flex;
    align-items: center;
    border: 1px solid #cbd5e1;
    border-radius: 4px;
    background: #ffffff;
  }
  .cart-drawer-mini-btn {
    width: 24px;
    height: 24px;
    background: #f8fafc;
    border: none;
    font-size: 13px;
    font-weight: 700;
    color: #334155;
    cursor: pointer;
  }
  .cart-drawer-mini-val {
    width: 28px;
    text-align: center;
    font-size: 12px;
    font-weight: 700;
    color: #0f172a;
  }
  .cart-drawer-item-price {
    font-family: 'Outfit', sans-serif;
    font-size: 13.5px;
    font-weight: 600;
    color: #475569;
  }
  .cart-drawer-footer {
    padding: 20px 24px 28px 24px;
    border-top: 1px solid #f1f5f9;
    background: #ffffff;
  }
  .cart-drawer-subtotal-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
  }
  .cart-drawer-subtotal-label {
    font-family: 'Outfit', sans-serif;
    font-size: 16px;
    font-weight: 700;
    color: #1e293b;
  }
  .cart-drawer-subtotal-val {
    font-family: 'Outfit', sans-serif;
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
  }
  .cart-drawer-checkout-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 14px 20px;
    background: #9b3820;
    color: #ffffff;
    font-family: 'Outfit', sans-serif;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    text-decoration: none;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(155, 56, 32, 0.4);
    transition: all 0.25s ease;
    border: none;
    cursor: pointer;
    box-sizing: border-box;
  }
  .cart-drawer-checkout-btn:hover {
    background: #b94427;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(155, 56, 32, 0.5);
  }
  .cart-drawer-view-cart {
    display: block;
    text-align: center;
    margin-top: 10px;
    font-size: 12.5px;
    color: #64748b;
    text-decoration: underline;
  }
  .cart-drawer-empty {
    text-align: center;
    padding: 60px 20px;
    color: #64748b;
  }

  /* Global Page Back Bar (Sticky Subheader for non-home pages) */
  .global-page-back-bar {
    background: #ffffff;
    border-bottom: 1px solid #ebd9b8;
    padding: 8px 16px;
    position: sticky;
    top: 56px;
    z-index: 850;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;
  }
  .global-page-back-inner {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .btn-global-nav-back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #fdf6ea;
    border: 1px solid #ebd9b8;
    color: #9f183c;
    font-size: 13px;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
  }
  .btn-global-nav-back:hover {
    background: #9f183c;
    border-color: #9f183c;
    color: #ffffff;
    transform: translateX(-2px);
    box-shadow: 0 3px 10px rgba(159, 24, 60, 0.25);
  }
  .global-back-nav-links {
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .btn-global-home-link, .btn-global-shop-link {
    font-size: 12px;
    font-weight: 600;
    color: #614a30;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: color 0.2s;
  }
  .btn-global-home-link:hover, .btn-global-shop-link:hover {
    color: #9f183c;
    text-decoration: underline;
  }

  @media (max-width: 768px) {
    .global-page-back-bar {
      top: 50px;
      padding: 6px 12px;
    }
  }
  @media print {
    .global-page-back-bar {
      display: none !important;
    }
  }
</style>
</head>
<body class="theme-burgundy-white">
@php
  $cartQty = array_sum(array_column(session('cart', []), 'quantity'));
@endphp

<!-- Header Main Royal Logo & Navigation (Sticky Wrapper) -->
<header class="site-header-sticky-wrapper">
  <!-- Unified Ultra-Luxurious Royal Header Bar (Brand on Left, Menu on Right) -->
  <div class="luxury-header-bar">
    <!-- Left: Brand Logo Stacked Crest (Left Aligned) -->
    <div class="header-left-col">
      <a href="{{ route('home') }}" class="luxury-brand-stack" title="{{ config('site.brand') }}">
        <span class="logo-title-majestic">{{ config('site.brand') }}</span>
        <span class="logo-subtext-clean">PUSAT HAMPERS, GIFT BOX &amp; PARCEL GOURMET • INDONESIA</span>
      </a>
    </div>

    <!-- Right: Menu Button (User requested menu on the right) -->
    <div class="header-right-col">
      <button type="button" class="header-menu-wrap-btn" id="open-menu-drawer-btn" title="Buka Menu Navigasi">
        <span class="menu-btn-text">Menu</span>
        <span class="hamburger-icon-bars">
          <span></span>
          <span></span>
          <span></span>
        </span>
      </button>
    </div>
  </div>
</header>

@if (!request()->is('/') && !request()->routeIs('home'))
  <!-- Global Single Sub-Header Back Navigation Bar -->
  <div class="global-page-back-bar">
    <div class="global-page-back-inner">
      <button type="button" class="btn-global-nav-back" onclick="window.history.length > 1 ? window.history.back() : window.location.href='{{ route('home') }}'" title="Kembali ke halaman sebelumnya">
        <span class="back-icon-chevron">❮</span>
        <span class="back-label-text">Kembali</span>
      </button>

      <div class="global-back-nav-links">
        <a href="{{ route('home') }}" class="btn-global-home-link" title="Kembali ke Beranda Utama">
          <span>🏠</span> <span>Beranda</span>
        </a>
        <a href="{{ route('shop') }}" class="btn-global-shop-link" title="Buka Katalog Produk">
          <span>🛍️</span> <span>Katalog</span>
        </a>
      </div>
    </div>
  </div>
@endif

<main>@yield('content')</main>

<!-- Live Search Modal Overlay -->
<div class="search-modal-overlay" id="search-modal">
  <div class="search-modal-box">
    <button type="button" class="search-modal-close" id="close-search-btn">✕</button>
    
    <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 22px; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 16px; color: #1e2d27;">CARI PRODUK HAMPERS &amp; KUE</h3>
    
    <input type="text" id="public-search-input" placeholder="Ketik nama produk hampers / kue (contoh: Nastar, Kastengel, Lapis Legit)..." style="width: 100%; padding: 14px 18px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; font-family: inherit; color: #0f172a;" autocomplete="off" autofocus>

    <div id="public-search-results" style="margin-top: 16px; overflow-y: auto; max-height: 350px;"></div>
  </div>
</div>

<!-- Slide-In Navigation Drawer Modal (Wrapping the Navigation Menu) -->
<div class="mobile-drawer-overlay" id="mobile-drawer-modal">
  <div class="drawer-panel-inner">
    <div class="souvia-drawer-topbar">
      <a href="{{ route('home') }}" class="souvia-drawer-brand">
        ⚜️ {{ config('site.brand') }}
      </a>
      <button type="button" class="mobile-drawer-close" id="close-three-dots-btn" title="Tutup Menu">✕</button>
    </div>

    <div class="drawer-search-container">
      <div class="drawer-search-input-wrap trigger-live-search">
        <span>🔍</span>
        <input type="text" placeholder="Cari produk..." readonly>
      </div>
    </div>

    <div class="drawer-menu-list">
      <a href="{{ route('shop') }}" class="drawer-menu-link">
        <span>PRODUK</span>
        <span class="drawer-arrow">›</span>
      </a>
      <a href="{{ route('pricelist') }}" class="drawer-menu-link">
        <span>DAFTAR HARGA</span>
        <span class="drawer-arrow">›</span>
      </a>
      <a href="{{ route('gallery') }}" class="drawer-menu-link">
        <span>GALERI</span>
        <span class="drawer-arrow">›</span>
      </a>
      <a href="{{ route('payment.shipping') }}" class="drawer-menu-link">
        <span>PEMBAYARAN & PENGIRIMAN</span>
        <span class="drawer-arrow">›</span>
      </a>
      <a href="{{ route('terms') }}" class="drawer-menu-link">
        <span>SYARAT & KETENTUAN</span>
        <span class="drawer-arrow">›</span>
      </a>
    </div>

    <div class="drawer-utility-section">
      <a href="{{ route('track') }}" class="drawer-sub-link">
        <span>📦 Lacak Pesanan Real-Time</span>
        <span class="drawer-arrow">›</span>
      </a>
      <a href="{{ route('cart.index') }}" class="drawer-sub-link">
        <span>🛒 Keranjang Belanja</span>
        @if ($cartQty > 0)
          <span class="cart-pill-badge">{{ $cartQty }}</span>
        @endif
      </a>

      @auth
        <a href="{{ route('my.orders') }}" class="drawer-sub-link">
          <span>📋 Riwayat Pesanan Saya</span>
        </a>
        @if(Auth::user()->is_admin)
          <a href="{{ route('admin.dashboard') }}" class="drawer-sub-link" style="color: #d97706; font-weight: 700;">
            <span>🛡️ Dashboard Admin</span>
          </a>
        @endif
        <form action="{{ route('logout') }}" method="POST" style="margin-top: 6px;">
          @csrf
          <button type="submit" class="drawer-logout-btn">KELUAR DARI AKUN</button>
        </form>
      @else
        <a href="{{ route('login') }}" class="drawer-sub-link" style="color: #9f183c; font-weight: 700;">
          <span>👤 Masuk / Daftar Akun</span>
        </a>
      @endauth
    </div>
  </div>
</div>

<!-- Shopping Cart Off-canvas Drawer (Matching Reference Image 2) -->
<div class="cart-drawer-overlay" id="shopping-cart-drawer">
  <div class="cart-drawer-panel">
    <div class="cart-drawer-header">
      <h3 class="cart-drawer-title">KERANJANG BELANJA</h3>
      <button type="button" class="cart-drawer-close" id="close-cart-drawer-btn" title="Tutup Keranjang">✕</button>
    </div>

    <div class="cart-drawer-body" id="cartDrawerBody">
      @php
        $drawerCart = session('cart', []);
        $drawerSubtotal = 0;
        foreach ($drawerCart as $dItem) {
          $drawerSubtotal += ($dItem['price'] * $dItem['quantity']);
        }
      @endphp

      @if(count($drawerCart) > 0)
        @foreach($drawerCart as $pId => $item)
          <div class="cart-drawer-item" data-cart-item-id="{{ $pId }}">
            <button type="button" class="cart-drawer-remove-btn" onclick="removeDrawerCartItem({{ $pId }})" title="Hapus dari keranjang">✕</button>
            <div class="cart-drawer-thumb">
              <img src="{{ $item['image_url'] ?? asset('images/cat-hampers.jpg') }}" alt="{{ $item['name'] }}" onerror="this.src='{{ asset('images/cat-hampers.jpg') }}';">
            </div>
            <div class="cart-drawer-item-info">
              <h4 class="cart-drawer-item-name" title="{{ $item['name'] }}">{{ $item['name'] }}</h4>
              <div class="cart-drawer-item-qty-row">
                <div class="cart-drawer-mini-qty">
                  <button type="button" class="cart-drawer-mini-btn" onclick="updateDrawerCartItem({{ $pId }}, 'decrease')">-</button>
                  <span class="cart-drawer-mini-val" id="drawerQty-{{ $pId }}">{{ $item['quantity'] }}</span>
                  <button type="button" class="cart-drawer-mini-btn" onclick="updateDrawerCartItem({{ $pId }}, 'increase')">+</button>
                </div>
                <div class="cart-drawer-item-price">
                  <span id="drawerPrice-{{ $pId }}">{{ $item['quantity'] }} x Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                </div>
              </div>
              <div style="margin-top: 4px;">
                <button type="button" class="cart-drawer-del-link" onclick="removeDrawerCartItem({{ $pId }})" style="background: none; border: none; padding: 0; color: #dc2626; font-size: 11px; font-weight: 600; cursor: pointer; text-decoration: underline; display: inline-flex; align-items: center; gap: 3px;" title="Hapus item ini dari keranjang">
                  <span>🗑️ Hapus</span>
                </button>
              </div>
            </div>
          </div>
        @endforeach
      @else
        <div class="cart-drawer-empty">
          <div style="font-size: 38px; margin-bottom: 12px;">🛒</div>
          <p style="margin: 0; font-size: 14px;">Keranjang belanja Anda masih kosong.</p>
        </div>
      @endif
    </div>

    <div class="cart-drawer-footer" id="cartDrawerFooter" style="{{ count($drawerCart) > 0 ? '' : 'display: none;' }}">
      <div class="cart-drawer-subtotal-row">
        <span class="cart-drawer-subtotal-label">Subtotal:</span>
        <span class="cart-drawer-subtotal-val" id="drawerSubtotalVal">Rp {{ number_format($drawerSubtotal, 0, ',', '.') }}</span>
      </div>
      <a href="{{ route('checkout.show') }}" class="cart-drawer-checkout-btn">
        Lanjut ke Pembayaran ➔
      </a>
      <a href="{{ route('cart.index') }}" class="cart-drawer-view-cart">
        Lihat Keranjang Lengkap
      </a>
    </div>
  </div>
</div>

<!-- Modal Pembayaran & Pengiriman Popup -->
<div class="info-modal-overlay" id="modal-payment-shipping">
  <div class="info-modal-box">
    <button type="button" class="info-modal-close close-info-modal" title="Tutup Modal">✕</button>
    <div class="info-modal-header">
      <div class="info-modal-crest">💳 🚚</div>
      <h3 class="info-modal-title">PEMBAYARAN & PENGIRIMAN</h3>
      <p class="info-modal-subtitle">Informasi Transaksi Aman & Layanan Pengiriman Terpercaya</p>
    </div>
    <div class="info-modal-body">
      <div class="info-modal-section">
        <div class="info-section-title">💳 1. METODE PEMBAYARAN</div>
        <p>Kami menyediakan berbagai metode pembayaran yang fleksibel, aman, dan terverifikasi otomatis:</p>
        <ul class="info-check-list">
          <li><strong>Virtual Account Bank:</strong> BCA, Mandiri, BRI, BNI, dan Permata (verifikasi otomatis 24/7).</li>
          <li><strong>QRIS Instan:</strong> GoPay, OVO, ShopeePay, Dana, LinkAja, dan BCA Mobile via scan kode QR.</li>
          <li><strong>Kartu Kredit & Debit:</strong> Visa, MasterCard, dan JCB dengan proteksi keamanan 3D Secure.</li>
          <li><strong>Transfer Bank Manual:</strong> Upload bukti transfer dapat dilakukan langsung pada halaman pesanan.</li>
        </ul>
      </div>

      <div class="info-modal-section">
        <div class="info-section-title">🚚 2. METODE & JANGKAUAN PENGIRIMAN</div>
        <p>Setiap pesanan dipacking secara eksklusif dan dikirim langsung dari 2 Hub Butik Utama kami di <strong>Kota Surabaya</strong> dan <strong>Kota Bandung</strong>, serta melayani pengiriman ke seluruh Indonesia:</p>
        <ul class="info-check-list">
          <li><strong>Instant Courier (1-3 Jam):</strong> Khusus produk kue basah, tart, dan hantaran segar area Surabaya Raya & Bandung Raya.</li>
          <li><strong>Same Day Courier:</strong> Pengiriman di hari yang sama dengan kurir khusus handling makanan dan hampers.</li>
          <li><strong>Dedicated Hampers Courier:</strong> Pengiriman armada butik ber-AC khusus pesanan parcel & hampers VIP.</li>
          <li><strong>Ekspedisi 1-2 Hari (Nasional):</strong> Pengiriman ke seluruh kota di Indonesia dengan box kayu, bubble wrap tebal, dan asuransi.</li>
        </ul>
      </div>

      <div class="info-modal-section">
        <div class="info-section-title">🛡️ 3. JAMINAN KUALITAS & KEAMANAN</div>
        <p>Semua pesanan dilindungi dengan <strong>Garansi 100% Penggantian Baru</strong> bila produk diterima dalam kondisi rusak selama proses pengiriman. Cukup lampirkan rekaman video unboxing saat paket pertama kali dibuka.</p>
      </div>
    </div>
  </div>
</div>

<!-- Modal Syarat & Ketentuan Popup -->
<div class="info-modal-overlay" id="modal-terms">
  <div class="info-modal-box">
    <button type="button" class="info-modal-close close-info-modal" title="Tutup Modal">✕</button>
    <div class="info-modal-header">
      <div class="info-modal-crest">📜</div>
      <h3 class="info-modal-title">SYARAT & KETENTUAN</h3>
      <p class="info-modal-subtitle">Panduan & Ketentuan Pemesanan di {{ config('site.brand') }}</p>
    </div>
    <div class="info-modal-body">
      <div class="info-modal-section">
        <div class="info-section-title">📌 1. KETENTUAN PEMESANAN</div>
        <ul class="info-check-list">
          <li>Pemesanan produk ready stock diproses dan dikirim pada hari yang sama untuk konfirmasi pembayaran sebelum pukul 15.00 WIB.</li>
          <li>Pemesanan Pre-Order (Custom Hampers / Kue Tart Spesial) disarankan dilakukan minimal H-2 sebelum tanggal pengiriman.</li>
          <li>Pastikan data alamat pengiriman, nomor telepon penerima, dan tanggal pengiriman telah diisi dengan lengkap dan benar.</li>
        </ul>
      </div>

      <div class="info-modal-section">
        <div class="info-section-title">💌 2. KARTU UCAPAN & PERSONALISASI</div>
        <ul class="info-check-list">
          <li>Setiap paket hampers mendapatkan <strong>Gratis Kartu Ucapan Eksklusif</strong>.</li>
          <li>Pesan ucapan dapat dicantumkan pada kolom catatan pesanan saat proses checkout.</li>
          <li>Untuk pesanan korporat (B2B / Custom Branding Logo), silakan konsultasikan melalui form kontak kami.</li>
        </ul>
      </div>

      <div class="info-modal-section">
        <div class="info-section-title">🔄 3. KEBIJAKAN PENGEMBALIAN & RETUR</div>
        <ul class="info-check-list">
          <li>Penggantian produk rusak dilayani maksimal 1x24 jam sejak paket diterima oleh penerima.</li>
          <li>Wajib melampirkan foto resi pengiriman dan rekaman video unboxing paket saat dibuka.</li>
          <li>Produk yang sudah dikonsumsi atau salah penyimpanan oleh penerima tidak dapat dikembalikan.</li>
        </ul>
      </div>

      <div class="info-modal-section">
        <div class="info-section-title">✨ 4. STANDAR KUALITAS & SERTIFIKASI</div>
        <p>Seluruh produk kami diproduksi menggunakan bahan bermutu tinggi, 100% Halal berstandar BPOM & MUI, dan higienis tanpa menggunakan pengawet kimia berbahaya.</p>
      </div>
    </div>
  </div>
</div>

<!-- Interactive Luxury Product & Hampers Detail Modal -->
<div class="product-modal-overlay" id="product-detail-modal">
  <div class="product-modal-box">
    <button type="button" class="product-modal-close" id="close-product-modal-btn">✕</button>
    
    <div class="product-modal-content">
      <div class="product-modal-image-col">
        <div class="product-modal-img-wrap">
          <img id="pm-image" src="" alt="Product Image">
        </div>
        <div class="product-modal-gallery-thumbs" id="pm-gallery-thumbs" style="display: flex; gap: 8px; margin-top: 12px; overflow-x: auto; padding-bottom: 4px;"></div>
      </div>
      
      <div class="product-modal-info-col">
        <span class="product-modal-category" id="pm-category">HAMPERS MEWAH</span>
        <h2 class="product-modal-title" id="pm-title">Nama Hampers</h2>
        <div class="product-modal-price" id="pm-price">Rp 0</div>
        <div class="product-modal-stock" id="pm-availability">Stok Tersedia</div>
        
        <!-- Breakdown Rincian Isi Paket Hampers / Produk -->
        <div class="product-modal-items-section">
          <div class="product-modal-items-header">🎁 RINCIAN ISI PAKET HAMPERS</div>
          <ul class="product-modal-items-list" id="pm-items-list">
            <!-- Dynamically populated -->
          </ul>
        </div>
        
        <div class="product-modal-meta-grid">
          <div><span class="pm-meta-label">VARIAN RASA:</span> <span id="pm-flavor" class="pm-meta-val">-</span></div>
          <div><span class="pm-meta-label">UKURAN / PORSI:</span> <span id="pm-size" class="pm-meta-val">-</span></div>
        </div>

        <!-- Section Review & Ulasan Pelanggan -->
        <div class="product-modal-reviews-section" style="margin-top: 16px; padding-top: 14px; border-top: 1px solid #e2e8f0;">
          <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
            <div style="font-size: 12.5px; font-weight: 700; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px;">
              ⭐ ULASAN PELANGGAN (<span id="pm-review-count">2</span>)
            </div>
            <div style="font-size: 11.5px; color: #d97706; font-weight: 700;">★ 5.0 / 5.0</div>
          </div>
          
          <div id="pm-reviews-list" style="display: flex; flex-direction: column; gap: 8px; max-height: 180px; overflow-y: auto; padding-right: 4px;">
            <!-- Dynamically populated via JavaScript -->
          </div>
        </div>
        
        <div class="product-modal-actions">
          <form action="{{ route('cart.add') }}" method="POST" id="pm-cart-form" style="width: 100%;">
            @csrf
            <input type="hidden" name="product_id" id="pm-product-id" value="0">
            <button type="submit" class="pm-btn pm-btn-cart">🛒 + TAMBAH KE KERANJANG</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Ultra-Luxurious Royal Emerald & Gold Footer -->
<footer class="luxury-footer">
  <!-- Royal Crest Header Bar -->
  <div class="footer-royal-crest">
    <div class="royal-crest-title">⚜️ PUSAT HAMPERS INDONESIA ⚜️</div>
    <div class="royal-crest-tag">Pusat Hampers, Gift Box & Parcel Gourmet Terlengkap</div>
  </div>

  <!-- Main Footer Columns (Clean 2-Column Grid) -->
  <div class="footer-main-content">
    <!-- Col 1: Butik & Jam Operasional -->
    <div class="footer-col">
      <h4>BUTIK & LOKASI</h4>
      <p style="margin-bottom: 6px;">🏬 <strong>Hub Surabaya:</strong> Jl. Raya Darmo No. 45, Wonokromo, Kota Surabaya</p>
      <p style="margin-bottom: 6px;">🏬 <strong>Hub Bandung:</strong> Jl. LLRE Martadinata (Riau) No. 68, Kota Bandung</p>
      <p>⏰ {{ config('site.hours') }}</p>
    </div>

    <!-- Col 2: Akses Cepat & Koleksi -->
    <div class="footer-col">
      <h4>MENU UTAMA & LAYANAN</h4>
      <a href="{{ route('shop') }}">🛍️ Produk & Koleksi Lengkap</a>
      <a href="{{ route('pricelist') }}">📄 Daftar Harga & Katalog Menu</a>
      <a href="{{ route('gallery') }}">📸 Galeri Hampers & Karya</a>
      <a href="{{ route('payment.shipping') }}">💳 Pembayaran & Pengiriman</a>
      <a href="{{ route('terms') }}">📜 Syarat & Ketentuan</a>
      <a href="{{ route('track') }}">📦 Lacak Pesanan Real-Time</a>
    </div>
  </div>

  <!-- Bottom Bar -->
  <div class="footer-bottom-bar">
    <div class="bottom-inner">
      <div>
        © {{ date('Y') }} {{ config('site.brand') }} • HAK CIPTA DILINDUNGI.
      </div>
      <div style="font-size: 10.5px; color: #94a3b8;">
        PEMBAYARAN: BCA • MANDIRI • BRI • QRIS • VISA • MASTERCARD
      </div>
    </div>
  </div>
</footer>

<!-- Mobile Bottom Navigation Bar (Matching Android Reference Image 1) -->
<nav class="mobile-bottom-nav-bar" aria-label="Navigasi Android Mobile">
  <a href="{{ route('shop') }}" class="mobile-bnav-item {{ request()->routeIs('shop') || request()->routeIs('category') || request()->routeIs('home') ? 'active' : '' }}">
    <span class="mobile-bnav-icon">🏪</span>
    <span class="mobile-bnav-text">Belanja</span>
  </a>
  <a href="@auth {{ route('my.orders') }} @else {{ route('login') }} @endauth" class="mobile-bnav-item {{ request()->routeIs('login') || request()->routeIs('register') || request()->routeIs('my.orders') ? 'active' : '' }}">
    <span class="mobile-bnav-icon">👤</span>
    <span class="mobile-bnav-text">Akun</span>
  </a>
  <button type="button" class="mobile-bnav-item" onclick="if(window.openShoppingCartDrawer){window.openShoppingCartDrawer();}else{window.location.href='{{ route('cart.index') }}';}">
    <span class="mobile-bnav-icon">
      🛒
      @php $cartQty = array_sum(array_column(session('cart', []), 'quantity')); @endphp
      <span class="mobile-bnav-badge" id="bottomNavCartBadge" style="{{ $cartQty > 0 ? '' : 'display:none;' }}">{{ $cartQty }}</span>
    </span>
    <span class="mobile-bnav-text">Keranjang</span>
  </button>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function () {
  // Hamburger "☰ Menu" Wrap Drawer Toggle Logic (Matching User's Request)
  const openMenuDrawerBtn = document.getElementById('open-menu-drawer-btn');
  const closeThreeDotsBtn = document.getElementById('close-three-dots-btn');
  const mobileDrawerModal = document.getElementById('mobile-drawer-modal');

  if (openMenuDrawerBtn && mobileDrawerModal) {
    openMenuDrawerBtn.addEventListener('click', function (e) {
      e.preventDefault();
      mobileDrawerModal.classList.add('open');
      document.body.style.overflow = 'hidden';
    });
  }

  document.querySelectorAll('.three-dots-btn').forEach(btn => {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      if (mobileDrawerModal) {
        mobileDrawerModal.classList.add('open');
        document.body.style.overflow = 'hidden';
      }
    });
  });

  if (closeThreeDotsBtn && mobileDrawerModal) {
    closeThreeDotsBtn.addEventListener('click', function () {
      mobileDrawerModal.classList.remove('open');
      document.body.style.overflow = '';
    });
  }

  // Close drawer when clicking outside the panel on the backdrop overlay
  if (mobileDrawerModal) {
    mobileDrawerModal.addEventListener('click', function (e) {
      if (e.target === mobileDrawerModal) {
        mobileDrawerModal.classList.remove('open');
        document.body.style.overflow = '';
      }
    });
  }

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && mobileDrawerModal && mobileDrawerModal.classList.contains('open')) {
      mobileDrawerModal.classList.remove('open');
      document.body.style.overflow = '';
    }
  });

  // Info Modals (Pembayaran & Pengiriman, Syarat & Ketentuan)
  const paymentModal = document.getElementById('modal-payment-shipping');
  const termsModal = document.getElementById('modal-terms');

  document.querySelectorAll('.open-payment-modal-btn').forEach(btn => {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      if (mobileDrawerModal) mobileDrawerModal.classList.remove('open');
      if (paymentModal) {
        paymentModal.classList.add('open');
        document.body.style.overflow = 'hidden';
      }
    });
  });

  document.querySelectorAll('.open-terms-modal-btn').forEach(btn => {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      if (mobileDrawerModal) mobileDrawerModal.classList.remove('open');
      if (termsModal) {
        termsModal.classList.add('open');
        document.body.style.overflow = 'hidden';
      }
    });
  });

  document.querySelectorAll('.close-info-modal').forEach(btn => {
    btn.addEventListener('click', function () {
      if (paymentModal) paymentModal.classList.remove('open');
      if (termsModal) termsModal.classList.remove('open');
      document.body.style.overflow = '';
    });
  });

  [paymentModal, termsModal].forEach(modal => {
    if (modal) {
      modal.addEventListener('click', function (e) {
        if (e.target === this) {
          this.classList.remove('open');
          document.body.style.overflow = '';
        }
      });
    }
  });

  const openSearchBtn = document.getElementById('open-search-btn');
  const closeSearchBtn = document.getElementById('close-search-btn');
  const searchModal = document.getElementById('search-modal');
  const publicSearchInput = document.getElementById('public-search-input');
  const publicSearchResults = document.getElementById('public-search-results');
  let searchTimer = null;

  if (openSearchBtn) {
    openSearchBtn.addEventListener('click', function () {
      searchModal.classList.add('open');
      setTimeout(() => publicSearchInput.focus(), 100);
    });
  }

  if (closeSearchBtn) {
    closeSearchBtn.addEventListener('click', function () {
      searchModal.classList.remove('open');
    });
  }

  if (searchModal) {
    searchModal.addEventListener('click', function (e) {
      if (e.target === searchModal) {
        searchModal.classList.remove('open');
      }
    });
  }

  if (publicSearchInput) {
    publicSearchInput.addEventListener('input', function () {
      clearTimeout(searchTimer);
      const query = this.value.trim();

      if (query.length === 0) {
        publicSearchResults.innerHTML = '';
        return;
      }

      searchTimer = setTimeout(() => {
        fetch(`{{ route('public.search') }}?q=${encodeURIComponent(query)}`, {
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        })
        .then(res => res.json())
        .then(data => {
          if (!data || data.length === 0) {
            publicSearchResults.innerHTML = `
              <div style="padding: 20px; text-align: center; color: #64748b; font-size: 13px;">
                Tidak ada produk bakery yang sesuai dengan "${query}"
              </div>`;
            return;
          }

          let html = '';
          data.forEach(p => {
            html += `
              <a href="${p.edit_url ? '{{ url('/shop') }}/' + p.category_title.toLowerCase() : '#'}" style="display: flex; align-items: center; gap: 14px; padding: 12px; border-bottom: 1px solid #f1f5f9; text-decoration: none; color: inherit; transition: background 0.15s;" onmouseover="this.style.background='#f8faf9'" onmouseout="this.style.background='transparent'">
                <img src="${p.thumbnail_url || p.image_url}" alt="${p.name}" loading="lazy" decoding="async" onerror="this.onerror=null; this.src='{{ asset('images/cat-hampers.jpg') }}';" style="width: 48px; height: 48px; object-fit: cover; border-radius: 6px; background: #e2e8f0;">
                <div style="flex: 1;">
                  <div style="font-weight: 600; font-size: 14px; color: #1e2d27;">${p.name}</div>
                  <div style="font-size: 12px; color: #64756d;">Kategori: ${p.category_title}</div>
                </div>
                <div style="font-weight: 700; color: #b45309; font-size: 13px;">${p.price_formatted}</div>
              </a>`;
          });

          publicSearchResults.innerHTML = html;
        });
      }, 150);
    });
  }

  // Automatic Preloader for Product Cards & Gallery Images
  const preloadedImageUrls = new Set();
  function preloadImageUrl(url) {
    if (!url || preloadedImageUrls.has(url)) return;
    preloadedImageUrls.add(url);
    const i = new Image();
    i.src = url;
  }

  document.querySelectorAll('.product-detail-trigger').forEach(card => {
    const mainImg = card.getAttribute('data-image');
    if (mainImg) preloadImageUrl(mainImg);

    card.addEventListener('mouseenter', function() {
      if (mainImg) preloadImageUrl(mainImg);
      try {
        const gal = JSON.parse(card.getAttribute('data-gallery') || '[]');
        gal.forEach(preloadImageUrl);
      } catch(e) {}
    }, { passive: true });
  });

  // Product Detail Modal Event Handlers
  const productModal = document.getElementById('product-detail-modal');
  const closeProductModalBtn = document.getElementById('close-product-modal-btn');

  function openProductModal(data) {
    if (!productModal) return;

    const pmImg = document.getElementById('pm-image');
    if (pmImg) {
      pmImg.decoding = 'async';
      pmImg.style.opacity = '0.3';
      pmImg.style.transition = 'opacity 0.2s ease';
      pmImg.onerror = function() {
        this.onerror = null;
        this.src = '{{ asset("images/cat-hampers.jpg") }}';
        this.style.opacity = '1';
      };
      pmImg.onload = function() { pmImg.style.opacity = '1'; };
      pmImg.src = data.image || '{{ asset("images/cat-hampers.jpg") }}';
      if (pmImg.complete) { pmImg.style.opacity = '1'; }
    }

    document.getElementById('pm-title').textContent = data.name || '';
    document.getElementById('pm-category').textContent = data.category || 'HAMPERS MEWAH';
    document.getElementById('pm-price').textContent = data.price || '';
    document.getElementById('pm-availability').textContent = data.availability || 'Ready Stock (Tersedia)';
    document.getElementById('pm-flavor').textContent = data.flavor || 'Original Gourmet';
    document.getElementById('pm-size').textContent = data.size || 'Standar Porsi';
    document.getElementById('pm-product-id').value = data.id || 0;

    // Populate multi-image gallery thumbnails
    const thumbsContainer = document.getElementById('pm-gallery-thumbs');
    if (thumbsContainer) {
      thumbsContainer.innerHTML = '';
      let gallery = [];
      try {
        gallery = typeof data.gallery === 'string' ? JSON.parse(data.gallery) : (data.gallery || []);
      } catch(e) {
        gallery = [];
      }

      if (!gallery || gallery.length === 0) {
        if (data.image) gallery = [data.image];
      }

      if (gallery.length > 1) {
        gallery.forEach((imgUrl, index) => {
          if (!imgUrl) return;
          preloadImageUrl(imgUrl);
          const thumb = document.createElement('img');
          thumb.src = imgUrl;
          thumb.alt = 'Thumbnail ' + (index + 1);
          thumb.decoding = 'async';
          thumb.onerror = function() {
            this.onerror = null;
            this.src = '{{ asset("images/cat-hampers.jpg") }}';
          };
          thumb.style.width = '56px';
          thumb.style.height = '56px';
          thumb.style.objectFit = 'cover';
          thumb.style.borderRadius = '6px';
          thumb.style.cursor = 'pointer';
          thumb.style.border = (index === 0 || imgUrl === data.image) ? '2px solid #f59e0b' : '1px solid #cbd5e1';
          thumb.style.transition = 'all 0.2s ease';
          
          thumb.addEventListener('click', function() {
            if (pmImg) {
              pmImg.style.opacity = '0.3';
              pmImg.onload = function() { pmImg.style.opacity = '1'; };
              pmImg.src = imgUrl;
              if (pmImg.complete) { pmImg.style.opacity = '1'; }
            }
            thumbsContainer.querySelectorAll('img').forEach(t => {
              t.style.border = '1px solid #cbd5e1';
            });
            thumb.style.border = '2px solid #f59e0b';
          });

          thumbsContainer.appendChild(thumb);
        });
        thumbsContainer.style.display = 'flex';
      } else {
        thumbsContainer.style.display = 'none';
      }
    }

    // Build included items list
    const itemsList = document.getElementById('pm-items-list');
    itemsList.innerHTML = '';

    let items = [];
    try {
      items = typeof data.items === 'string' ? JSON.parse(data.items) : (data.items || []);
    } catch(e) {
      items = [];
    }

    if (!items || items.length === 0) {
      items = [
        '✨ Dibuat segar (freshly baked) secara artisanal dari bahan impor pilihan',
        '📦 Dikemas secara higienis & mewah cocok untuk santapan maupun bingkisan',
        '🌿 Bebas bahan pengawet kimia buatan'
      ];
    }

    items.forEach(itemText => {
      const li = document.createElement('li');
      li.textContent = itemText;
      itemsList.appendChild(li);
    });

    // Build Product Reviews
    const reviewsList = document.getElementById('pm-reviews-list');
    const reviewCountSpan = document.getElementById('pm-review-count');
    if (reviewsList) {
      reviewsList.innerHTML = '';
      let reviews = [];
      try {
        reviews = typeof data.reviews === 'string' ? JSON.parse(data.reviews) : (data.reviews || []);
      } catch(e) {
        reviews = [];
      }

      if (!reviews || reviews.length === 0) {
        reviews = [
          {
            name: 'Adeline Wijaya',
            avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80',
            rating: 5,
            title: 'Rasa Sangat Lezat & Kemasan Mewah VIP!',
            body: 'Bahan-bahannya terasa sangat segar dan butter impornya begitu harum. Packaging hampers eksklusif cocok buat bingkisan.',
            date: '2 hari lalu'
          },
          {
            name: 'Clarissa Putri',
            avatar: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=150&auto=format&fit=crop&q=80',
            rating: 5,
            title: 'Pengiriman Cepat & Fast Response',
            body: 'Teksturnya lembut dan rasanya pas di lidah, pengiriman dedicated courier sangat aman tanpa cacat.',
            date: '4 hari lalu'
          }
        ];
      }

      if (reviewCountSpan) reviewCountSpan.textContent = reviews.length;

      reviews.forEach(rev => {
        const revCard = document.createElement('div');
        revCard.style.cssText = 'background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 12px; font-size: 12px;';
        
        const starsHtml = '★'.repeat(rev.rating || 5) + '☆'.repeat(5 - (rev.rating || 5));
        
        revCard.innerHTML = `
          <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px;">
            <div style="display: flex; align-items: center; gap: 8px;">
              <img src="${rev.avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(rev.name || 'Pelanggan')}" alt="${rev.name}" style="width: 26px; height: 26px; border-radius: 50%; object-fit: cover;">
              <span style="font-weight: 700; color: #1e293b; font-size: 12px;">${rev.name || 'Pelanggan VIP'}</span>
            </div>
            <span style="color: #f59e0b; font-weight: 700; font-size: 11px;">${starsHtml}</span>
          </div>
          ${rev.title ? `<div style="font-weight: 700; color: #0f172a; margin-bottom: 3px; font-size: 11.5px;">"${rev.title}"</div>` : ''}
          <div style="color: #475569; line-height: 1.4; font-size: 11.5px;">${rev.body || ''}</div>
          ${rev.date ? `<div style="font-size: 10px; color: #94a3b8; margin-top: 4px; text-align: right;">${rev.date}</div>` : ''}
        `;
        reviewsList.appendChild(revCard);
      });
    }

    productModal.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  document.addEventListener('click', function(e) {
    const card = e.target.closest('.product-detail-trigger');
    if (card && !e.target.closest('form')) {
      const data = {
        id: card.getAttribute('data-id'),
        name: card.getAttribute('data-name'),
        price: card.getAttribute('data-price'),
        image: card.getAttribute('data-image'),
        gallery: card.getAttribute('data-gallery'),
        reviews: card.getAttribute('data-reviews'),
        category: card.getAttribute('data-category'),
        flavor: card.getAttribute('data-flavor'),
        size: card.getAttribute('data-size'),
        type: card.getAttribute('data-type'),
        availability: card.getAttribute('data-availability'),
        items: card.getAttribute('data-items')
      };
      openProductModal(data);
    }
  });

  if (closeProductModalBtn && productModal) {
    closeProductModalBtn.addEventListener('click', function() {
      productModal.classList.remove('open');
      document.body.style.overflow = '';
    });
    productModal.addEventListener('click', function(e) {
      if (e.target === productModal) {
        productModal.classList.remove('open');
        document.body.style.overflow = '';
      }
    });
  }

  // Accordion Drawer Toggle Logic (Reference Image 2)
  document.querySelectorAll('.souvia-accordion-menu .accordion-header').forEach(header => {
    header.addEventListener('click', function() {
      const item = this.closest('.accordion-item');
      if (!item) return;
      const isOpen = item.classList.contains('active');
      
      // Close other drawer accordion items
      document.querySelectorAll('.souvia-accordion-menu .accordion-item').forEach(i => i.classList.remove('active'));
      
      // Toggle current
      if (!isOpen) {
        item.classList.add('active');
      }
    });
  });

  // Mobile Header Search Triggers
  document.querySelectorAll('.trigger-live-search').forEach(input => {
    input.addEventListener('click', function() {
      const drawer = document.getElementById('mobile-drawer-modal');
      if (drawer) drawer.classList.remove('open');
      if (searchModal) {
        searchModal.classList.add('open');
        setTimeout(() => publicSearchInput.focus(), 100);
      }
    });
  });

  // ================= SHOPPING CART DRAWER (MATCHING REFERENCE IMAGE 2) =================
  const openCartDrawerBtn = document.getElementById('open-cart-drawer-btn');
  const cartDrawerModal = document.getElementById('shopping-cart-drawer');
  const closeCartDrawerBtn = document.getElementById('close-cart-drawer-btn');

  if (openCartDrawerBtn && cartDrawerModal) {
    openCartDrawerBtn.addEventListener('click', function(e) {
      e.preventDefault();
      cartDrawerModal.classList.add('open');
    });
  }
  if (closeCartDrawerBtn && cartDrawerModal) {
    closeCartDrawerBtn.addEventListener('click', function() {
      cartDrawerModal.classList.remove('open');
    });
    cartDrawerModal.addEventListener('click', function(e) {
      if (e.target === cartDrawerModal) {
        cartDrawerModal.classList.remove('open');
      }
    });
  }

  window.openShoppingCartDrawer = function() {
    if (cartDrawerModal) cartDrawerModal.classList.add('open');
  };

  window.closeShoppingCartDrawer = function() {
    if (cartDrawerModal) cartDrawerModal.classList.remove('open');
  };

  window.renderCartDrawer = function(cart, subtotal, subtotalFormatted, cartCount) {
    const body = document.getElementById('cartDrawerBody');
    const footer = document.getElementById('cartDrawerFooter');
    const badge = document.getElementById('headerCartBadge');
    const bottomNavBadge = document.getElementById('bottomNavCartBadge');
    const subtotalEl = document.getElementById('drawerSubtotalVal');

    if (badge) {
      badge.textContent = cartCount;
      badge.style.display = cartCount > 0 ? 'flex' : 'none';
    }
    if (bottomNavBadge) {
      bottomNavBadge.textContent = cartCount;
      bottomNavBadge.style.display = cartCount > 0 ? 'flex' : 'none';
    }

    if (subtotalEl) {
      subtotalEl.textContent = subtotalFormatted || ('Rp ' + Number(subtotal).toLocaleString('id-ID'));
    }

    if (!body) return;

    const items = Object.entries(cart || {});
    if (items.length === 0) {
      body.innerHTML = `
        <div class="cart-drawer-empty">
          <div style="font-size: 38px; margin-bottom: 12px;">🛒</div>
          <p style="margin: 0; font-size: 14px;">Keranjang belanja Anda masih kosong.</p>
        </div>
      `;
      if (footer) footer.style.display = 'none';
      return;
    }

    if (footer) footer.style.display = 'block';

    let html = '';
    items.forEach(([pId, item]) => {
      const priceFormatted = 'Rp ' + Number(item.price).toLocaleString('id-ID');
      html += `
        <div class="cart-drawer-item" data-cart-item-id="${pId}">
          <button type="button" class="cart-drawer-remove-btn" onclick="removeDrawerCartItem(${pId})" title="Hapus dari keranjang">✕</button>
          <div class="cart-drawer-thumb">
            <img src="${item.image_url || '{{ asset("images/cat-hampers.jpg") }}'}" alt="${item.name}" onerror="this.src='{{ asset("images/cat-hampers.jpg") }}';">
          </div>
          <div class="cart-drawer-item-info">
            <h4 class="cart-drawer-item-name" title="${item.name}">${item.name}</h4>
            <div class="cart-drawer-item-qty-row">
              <div class="cart-drawer-mini-qty">
                <button type="button" class="cart-drawer-mini-btn" onclick="updateDrawerCartItem(${pId}, 'decrease')">-</button>
                <span class="cart-drawer-mini-val" id="drawerQty-${pId}">${item.quantity}</span>
                <button type="button" class="cart-drawer-mini-btn" onclick="updateDrawerCartItem(${pId}, 'increase')">+</button>
              </div>
              <div class="cart-drawer-item-price">
                <span id="drawerPrice-${pId}">${item.quantity} x ${priceFormatted}</span>
              </div>
            </div>
            <div style="margin-top: 4px;">
              <button type="button" class="cart-drawer-del-link" onclick="removeDrawerCartItem(${pId})" style="background: none; border: none; padding: 0; color: #dc2626; font-size: 11px; font-weight: 600; cursor: pointer; text-decoration: underline; display: inline-flex; align-items: center; gap: 3px;" title="Hapus item ini dari keranjang">
                <span>🗑️ Hapus</span>
              </button>
            </div>
          </div>
        </div>
      `;
    });
    body.innerHTML = html;
  };

  window.updateDrawerCartItem = function(productId, action) {
    fetch('{{ route("cart.update") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      },
      body: JSON.stringify({ product_id: productId, action: action })
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        window.renderCartDrawer(data.cart, data.subtotal, data.subtotal_formatted, data.cart_count);
      }
    });
  };

  window.removeDrawerCartItem = function(productId) {
    window.updateDrawerCartItem(productId, 'remove');
  };

  // Intercept all .ajax-add-cart-form submissions
  document.addEventListener('submit', function(e) {
    const form = e.target.closest('.ajax-add-cart-form');
    if (!form) return;

    e.preventDefault();
    const btn = form.querySelector('button[type="submit"]');
    const originalText = btn ? btn.innerHTML : '';
    if (btn) {
      btn.disabled = true;
      btn.innerHTML = '⏳ Menambahkan...';
    }

    const formData = new FormData(form);
    fetch('{{ route("cart.add") }}', {
      method: 'POST',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      },
      body: formData
    })
    .then(r => r.json())
    .then(data => {
      if (btn) {
        btn.disabled = false;
        btn.innerHTML = originalText;
      }
      if (data.success) {
        window.renderCartDrawer(data.cart, data.subtotal, data.subtotal_formatted, data.cart_count);
        window.openShoppingCartDrawer();
      }
    })
    .catch(() => {
      form.submit();
    });
  });
});
</script>


<script src="{{ asset('js/site.js') }}" defer></script>
</body>
</html>
