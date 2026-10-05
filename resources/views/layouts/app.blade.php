<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', config('site.brand'))</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&family=Work+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/site.css') }}">
<style>
  /* Ultra-Luxurious Royal Emerald & Gold Header Bar */
  .top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #0d1713;
    padding: 12px 40px;
    font-size: 11px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    color: #cad8d1;
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
    color: #a7bbb0;
    text-decoration: none;
    transition: all 0.2s ease;
    font-weight: 500;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    letter-spacing: 2px;
  }
  .top nav a:hover {
    color: #f59e0b;
    text-shadow: 0 0 10px rgba(245, 158, 11, 0.4);
  }
  .header-right-group {
    display: flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
  }
  .currency-pill {
    font-size: 10px;
    color: #8fa59b;
    font-weight: 600;
    padding: 3px 10px;
    background: transparent;
    border: 1px solid rgba(217, 119, 6, 0.3);
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
    font-weight: 700;
    color: #d97706;
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
    text-shadow: 0 0 8px rgba(254, 240, 138, 0.4);
  }
  .header-icon-btn {
    background: transparent;
    border: none;
    cursor: pointer;
    font-size: 11px;
    color: #d97706;
    position: relative;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    transition: all 0.2s ease;
    padding: 0;
    font-weight: 700;
    letter-spacing: 1.5px;
    white-space: nowrap;
  }
  .header-icon-btn:hover {
    color: #fef08a;
    text-shadow: 0 0 8px rgba(254, 240, 138, 0.4);
  }
  .cart-badge {
    position: absolute;
    top: -8px;
    right: -10px;
    background: linear-gradient(135deg, #d97706, #f59e0b);
    color: #0d1713;
    font-size: 10px;
    font-weight: 800;
    width: 17px;
    height: 17px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 8px rgba(245, 158, 11, 0.6);
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

  .mobile-drawer-overlay {
    position: fixed;
    inset: 0;
    background: rgba(13, 23, 19, 0.96);
    backdrop-filter: blur(12px);
    z-index: 9999;
    display: flex;
    flex-direction: column;
    padding: 24px 20px 40px;
    transform: translateY(-100%);
    opacity: 0;
    visibility: hidden;
    transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.35s ease, visibility 0.35s ease;
    overflow-y: auto;
  }
  .mobile-drawer-overlay.open {
    transform: translateY(0);
    opacity: 1;
    visibility: visible;
  }
  .mobile-drawer-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(217, 119, 6, 0.3);
    padding-bottom: 16px;
    margin-bottom: 20px;
  }
  .mobile-drawer-brand {
    font-family: 'Cormorant Garamond', serif;
    font-size: 20px;
    letter-spacing: 2px;
    color: #fef08a;
    font-weight: 700;
  }
  .mobile-drawer-close {
    background: none;
    border: none;
    color: #94a3b8;
    font-size: 24px;
    cursor: pointer;
    line-height: 1;
    padding: 4px;
  }
  .mobile-drawer-section-title {
    font-size: 10px;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    color: #f59e0b;
    font-weight: 700;
    margin-bottom: 10px;
  }
  .mobile-drawer-links {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }
  .mobile-drawer-link {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 12.5px;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: #cad8d1;
    text-decoration: none;
    font-weight: 600;
    padding: 12px 14px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(217, 119, 6, 0.2);
    border-radius: 6px;
    transition: all 0.2s;
  }
  .mobile-drawer-link:hover, .mobile-drawer-link.active {
    background: rgba(217, 119, 6, 0.15);
    border-color: #f59e0b;
    color: #fef08a;
  }

  @media (max-width: 768px) {
    html, body {
      overflow-x: hidden !important;
      max-width: 100vw !important;
      width: 100% !important;
    }
    .three-dots-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }
    .top {
      padding: 8px 12px;
      justify-content: space-between;
      align-items: center;
      gap: 6px;
      width: 100%;
      max-width: 100vw;
    }
    .top nav {
      display: none !important; /* Hide redundant text links on mobile since they exist in 3-dots drawer */
    }
    .header-right-group {
      justify-content: space-between;
      gap: 6px;
      width: 100%;
      margin-top: 0;
      flex-wrap: nowrap;
    }
    .currency-pill {
      font-size: 9px;
      padding: 2px 6px;
      letter-spacing: 0.5px;
    }
    .top-text-link, .header-icon-btn {
      font-size: 10px;
      letter-spacing: 0.5px;
      gap: 4px;
    }

    .head {
      padding: 14px 10px 12px;
      max-width: 100vw;
      overflow: hidden;
    }
    .logo {
      font-size: clamp(16px, 4.5vw, 20px) !important;
      letter-spacing: .08em !important;
      padding: 0 !important;
      white-space: normal !important;
      display: inline-block !important;
      text-align: center !important;
      max-width: 100% !important;
      line-height: 1.25 !important;
      word-break: break-word !important;
      overflow-wrap: break-word !important;
    }
    .fleur-icon {
      font-size: 14px !important;
      margin: 0 4px !important;
      vertical-align: middle !important;
    }
    .logo small {
      font-size: 8px !important;
      letter-spacing: .1em !important;
      margin-top: 4px !important;
      line-height: 1.3 !important;
      white-space: normal !important;
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

  /* Royal Brand Logo Bar */
  .head {
    position: relative;
    background: #ffffff;
    z-index: 500;
    text-align: center;
    padding: 28px 0 20px;
    width: 100%;
  }
  .logo {
    display: inline-block;
    font-family: 'Cormorant Garamond', serif;
    font-size: 42px;
    letter-spacing: .28em;
    line-height: 1.1;
    color: #122019;
    font-weight: 700;
    text-decoration: none;
    text-transform: uppercase;
    transition: color 0.2s;
    padding: 0 20px;
  }
  .logo:hover {
    color: #b45309;
  }
  .fleur-icon {
    color: #d97706;
    font-size: 32px;
    vertical-align: middle;
    margin: 0 14px;
    display: inline-block;
    filter: drop-shadow(0 2px 4px rgba(217, 119, 6, 0.3));
  }
  .logo small {
    display: block;
    font-family: 'Outfit', sans-serif;
    font-size: 10.5px;
    letter-spacing: .38em;
    color: #b45309;
    font-weight: 600;
    margin-top: 6px;
  }

  /* Royal Dark Emerald Navigation Bar */
  .nav-main-wrapper {
    position: relative;
    background: #0d1713;
    margin-top: 0;
    width: 100%;
    border-top: 1px solid rgba(0, 0, 0, 0.08);
    border-bottom: 2px solid #b45309;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
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
    padding: 16px 20px;
    font-family: 'Cormorant Garamond', serif;
    text-transform: uppercase;
    letter-spacing: .18em;
    font-weight: 600;
    font-size: 13.5px;
    color: #cad8d1;
    text-decoration: none;
    transition: all 0.25s ease;
    border-bottom: 2px solid transparent;
    white-space: nowrap;
  }
  .main-nav-link:hover, .main-nav-link.active {
    color: #fef08a;
    background: rgba(255, 255, 255, 0.03);
    border-bottom-color: #f59e0b;
    text-shadow: 0 0 10px rgba(254, 240, 138, 0.3);
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
    background: rgba(15, 23, 42, 0.75);
    backdrop-filter: blur(8px);
    z-index: 2000;
    display: flex;
    justify-content: center;
    padding-top: 100px;
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
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
    position: relative;
    max-height: 80vh;
    display: flex;
    flex-direction: column;
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
    background: #09120e;
    color: #cad8d1;
    border-top: 2px solid #b45309;
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
  .footer-newsletter-compact {
    max-width: 480px;
    margin: 20px auto 0;
    text-align: center;
  }
  .footer-newsletter-compact p {
    font-size: 12px;
    color: #9cb3a8;
    margin-bottom: 10px;
    letter-spacing: 0.5px;
  }
  .newsletter-form-simple {
    display: flex;
    gap: 8px;
    max-width: 420px;
    margin: 0 auto;
  }
  .newsletter-form-simple input {
    flex: 1;
    padding: 10px 14px;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(217, 119, 6, 0.3);
    border-radius: 4px;
    color: #fff;
    font-size: 12px;
    font-family: inherit;
  }
  .newsletter-form-simple input:focus {
    outline: none;
    border-color: #f59e0b;
    background: rgba(255, 255, 255, 0.08);
  }
  .newsletter-form-simple button {
    padding: 10px 18px;
    background: linear-gradient(135deg, #b45309, #d97706);
    color: #fff;
    border: none;
    border-radius: 4px;
    font-size: 10px;
    letter-spacing: 1.5px;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.2s ease;
  }
  .newsletter-form-simple button:hover {
    background: linear-gradient(135deg, #d97706, #f59e0b);
    color: #0d1713;
  }

  .footer-main-content {
    max-width: 1100px;
    margin: 0 auto;
    padding: 36px 30px 28px;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 36px;
  }
  @media (max-width: 768px) {
    .footer-main-content {
      grid-template-columns: repeat(2, 1fr) !important;
      gap: 24px 14px !important;
      text-align: left !important;
      padding: 28px 16px 20px !important;
    }
    .footer-col:last-child {
      grid-column: 1 / -1 !important;
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

  .social-icons-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 12px;
  }
  @media (max-width: 768px) {
    .social-icons-row {
      justify-content: flex-start;
    }
  }
  .social-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(217, 119, 6, 0.3);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #f59e0b;
    transition: all 0.2s ease;
    text-decoration: none !important;
  }
  .social-icon svg {
    width: 16px;
    height: 16px;
  }
  .social-icon:hover {
    background: #d97706;
    color: #0d1713;
    border-color: #f59e0b;
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
</style>
</head>
<body>

<!-- Ultra-Luxurious Royal Header Top Bar -->
<div class="top">
  <nav>
    <a href="{{ route('reservations') }}">🍷 RESERVASI & ACARA</a>
    <a href="{{ route('contact') }}">💬 KONTAK & LAYANAN</a>
    <a href="{{ route('track') }}">📦 LACAK PESANAN REAL-TIME</a>
  </nav>

  <!-- Right Header Controls: User Actions, Search, Cart -->
  <div class="header-right-group">

    @auth
      <div class="user-action-group">
        <a href="{{ route('my.orders') }}" class="top-text-link" title="Riwayat Pesanan Saya">
          📦 <span>PESANAN SAYA</span>
        </a>

        @if(Auth::user()->is_admin)
          <a href="{{ route('admin.dashboard') }}" class="top-text-link" style="color: #fef08a;" title="Dashboard Admin">
            🛡️ <span>ADMIN PANEL</span>
          </a>
        @endif

        <form action="{{ route('logout') }}" method="POST" style="display: inline; margin: 0;">
          @csrf
          <button type="submit" class="top-text-link" style="color: #f87171;" title="Keluar (Logout)">
            🚪 <span>KELUAR</span>
          </button>
        </form>
      </div>
    @else
      <a href="{{ route('login') }}" class="header-icon-btn" title="Akun Saya / Login">
        <span style="color: #a855f7;">👤</span> <span>MASUK</span>
      </a>
    @endauth

    <button type="button" class="header-icon-btn" id="open-search-btn" title="Cari Produk (Live Search)">
      <span style="color: #38bdf8;">🔍</span> <span>CARI</span>
    </button>

    <a href="{{ route('cart.index') }}" class="header-icon-btn" title="Keranjang Belanja">
      <span>🛒</span> <span>KERANJANG</span>
      @php $cartQty = array_sum(array_column(session('cart', []), 'quantity')); @endphp
      @if ($cartQty > 0)
        <span class="cart-badge">{{ $cartQty }}</span>
      @endif
    </a>

    <!-- 3-Dots Mobile Menu Trigger Button -->
    <button type="button" class="three-dots-btn" id="open-three-dots-btn" title="Menu Lengkap (Titik 3)">
      ⋮
    </button>
  </div>
</div>

<!-- Mobile 3-Dots Menu Drawer Overlay -->
<div class="mobile-drawer-overlay" id="mobile-drawer-modal">
  <div class="mobile-drawer-header">
    <div class="mobile-drawer-brand">
      ⚜️ PUSAT HAMPERS INDONESIA
    </div>
    <button type="button" class="mobile-drawer-close" id="close-three-dots-btn">✕</button>
  </div>

  <div class="mobile-drawer-section-title">AKSES CEPAT & LAYANAN</div>
  <div class="mobile-drawer-links">
    <a href="{{ route('reservations') }}" class="mobile-drawer-link">
      <span>🍷</span> <span>RESERVASI & ACARA</span>
    </a>
    <a href="{{ route('contact') }}" class="mobile-drawer-link">
      <span>💬</span> <span>KONTAK & LAYANAN CONCIERGE</span>
    </a>
    <a href="{{ route('track') }}" class="mobile-drawer-link">
      <span>📦</span> <span>LACAK PESANAN REAL-TIME</span>
    </a>
    <a href="{{ route('cart.index') }}" class="mobile-drawer-link">
      <span>🛒</span> <span>KERANJANG BELANJA ({{ $cartQty ?? 0 }})</span>
    </a>
  </div>

  <div class="mobile-drawer-section-title" style="margin-top: 24px;">AKUN PELANGGAN</div>
  <div class="mobile-drawer-links">
    @auth
      <a href="{{ route('my.orders') }}" class="mobile-drawer-link">
        <span>📦</span> <span>RIWAYAT PESANAN SAYA</span>
      </a>
      @if(Auth::user()->is_admin)
        <a href="{{ route('admin.dashboard') }}" class="mobile-drawer-link" style="color: #fef08a; border-color: #d97706;">
          <span>🛡️</span> <span>DASHBOARD ADMIN PANEL</span>
        </a>
      @endif
      <form action="{{ route('logout') }}" method="POST" style="width: 100%; margin: 0;">
        @csrf
        <button type="submit" class="mobile-drawer-link" style="width: 100%; color: #f87171; text-align: left; cursor: pointer;">
          <span>🚪</span> <span>KELUAR (LOGOUT)</span>
        </button>
      </form>
    @else
      <a href="{{ route('login') }}" class="mobile-drawer-link">
        <span>👤</span> <span>MASUK / DAFTAR AKUN</span>
      </a>
    @endauth
    <a href="{{ route('menu.pdf') }}" target="_blank" class="mobile-drawer-link">
      <span>📜</span> <span>DOWNLOAD MENU PDF RESTORAN</span>
    </a>
  </div>

  <div class="mobile-drawer-section-title" style="margin-top: 24px;">KATEGORI TOKO ROTI & PASTRI</div>
  <div class="mobile-drawer-links">
    @foreach (config('site.categories') as $slug => $c)
      <a href="{{ route('category', $slug) }}" class="mobile-drawer-link">
        <span>🥐</span> <span>{{ $c['title'] }}</span>
      </a>
    @endforeach
  </div>
</div>

<!-- Header Main Royal Logo & Navigation -->
<header class="head">
  <a href="{{ route('home') }}" class="logo">
    <span class="fleur-icon">⚜️</span> PUSAT HAMPERS INDONESIA <span class="fleur-icon">⚜️</span>
    <small>PUSAT HAMPERS, GIFT BOX & PARCEL GOURMET • INDONESIA • DIDIRIKAN {{ config('site.since') }}</small>
  </a>

  <div class="nav-main-wrapper">
    <nav class="main">
      @foreach (config('site.categories') as $slug => $c)
        <div class="nav-item-has-mega" style="display: inline-block;">
          <a href="{{ route('category', $slug) }}" class="main-nav-link {{ request()->is('shop/'.$slug) ? 'active' : '' }}">
            {{ $c['title'] }}
          </a>

          <!-- Mega Menu Dropdown Panel -->
          <div class="mega-menu-dropdown">
            <div class="mega-menu-inner">
              <!-- Column 1: Category -->
              <div>
                <div class="mega-column-title">{{ strtoupper($c['title']) }}</div>
                <div class="mega-link-list">
                  <a href="{{ route('category', $slug) }}" style="font-weight: 600; color: #fef08a;">Lihat Semua {{ $c['title'] }} →</a>
                  @foreach (array_slice($c['items'], 0, 4) as $item)
                    <a href="{{ route('category', $slug) }}">{{ $item[0] }}</a>
                  @endforeach
                </div>
              </div>

              <!-- Column 2: Flavors -->
              <div>
                <div class="mega-column-title">VARIAN RASA</div>
                <div class="mega-link-list">
                  <a href="{{ route('category', $slug) }}?flavors[]=Chocolate">Cokelat</a>
                  <a href="{{ route('category', $slug) }}?flavors[]=Cheese">Keju</a>
                  <a href="{{ route('category', $slug) }}?flavors[]=Coffee">Kopi</a>
                  <a href="{{ route('category', $slug) }}?flavors[]=Pandan">Pandan</a>
                  <a href="{{ route('category', $slug) }}?flavors[]=Berry">Buah Beri</a>
                </div>
              </div>

              <!-- Column 3: Types -->
              <div>
                <div class="mega-column-title">KATEGORI BENTUK</div>
                <div class="mega-link-list">
                  <a href="{{ route('category', $slug) }}?types[]=Round">Kue Bulat</a>
                  <a href="{{ route('category', $slug) }}?types[]=Square">Kue Kotak</a>
                  <a href="{{ route('category', $slug) }}?types[]=Whole+Cake">Kue Utuh (Whole)</a>
                  <a href="{{ route('category', $slug) }}?types[]=Gift+Box">Kotak Hadiah</a>
                  <a href="{{ route('category', $slug) }}?types[]=The+Classics">Varian Klasik</a>
                </div>
              </div>

              <!-- Column 4: 2 Featured Products -->
              <div>
                <div class="mega-column-title">PRODUK UNGGULAN</div>
                <div class="mega-featured-grid">
                  @php
                    $feat1 = $c['items'][0] ?? ['Tiramisu', 525000];
                    $feat2 = $c['items'][1] ?? ['Berry Tart', 295000];
                    $slug1 = \Illuminate\Support\Str::slug($feat1[0]);
                    $slug2 = \Illuminate\Support\Str::slug($feat2[0]);
                  @endphp
                  <a href="{{ route('category', $slug) }}" class="mega-card">
                    <img src="{{ asset('images/' . $slug1 . '.jpg') }}" alt="{{ $feat1[0] }}" onerror="this.src='{{ asset('images/cat-cakes.jpg') }}'">
                    <div class="mega-card-title">{{ $feat1[0] }}</div>
                  </a>
                  <a href="{{ route('category', $slug) }}" class="mega-card">
                    <img src="{{ asset('images/' . $slug2 . '.jpg') }}" alt="{{ $feat2[0] }}" onerror="this.src='{{ asset('images/cat-cakes.jpg') }}'">
                    <div class="mega-card-title">{{ $feat2[0] }}</div>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
      <a href="{{ route('menu.pdf') }}" target="_blank" class="main-nav-link">Menu Restoran PDF 📄</a>
    </nav>
  </div>
</header>

<main>@yield('content')</main>

<!-- Live Search Modal Overlay -->
<div class="search-modal-overlay" id="search-modal">
  <div class="search-modal-box">
    <button type="button" class="search-modal-close" id="close-search-btn">✕</button>
    
    <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 22px; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 16px; color: #1e2d27;">CARI PRODUK TOKO ROTI</h3>
    
    <input type="text" id="public-search-input" placeholder="Ketik nama produk toko roti (contoh: Cheesecake, Croissant, Tart)..." style="width: 100%; padding: 14px 18px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; font-family: inherit; color: #0f172a;" autocomplete="off" autofocus>

    <div id="public-search-results" style="margin-top: 16px; overflow-y: auto; max-height: 350px;"></div>
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
      </div>
      
      <div class="product-modal-info-col">
        <span class="product-modal-category" id="pm-category">HAMPERS MEWAH</span>
        <h2 class="product-modal-title" id="pm-title">Nama Hampers</h2>
        <div class="product-modal-price" id="pm-price">Rp 0</div>
        <div class="product-modal-stock" id="pm-availability">Ready Stock (Tersedia)</div>
        
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
        
        <div class="product-modal-actions">
          <form action="{{ route('cart.add') }}" method="POST" id="pm-cart-form" style="width: 100%;">
            @csrf
            <input type="hidden" name="product_id" id="pm-product-id" value="0">
            <button type="submit" class="pm-btn pm-btn-cart">🛒 + TAMBAH KE KERANJANG</button>
          </form>
          <a href="#" target="_blank" id="pm-wa-btn" class="pm-btn pm-btn-wa">💬 PESAN VIA WHATSAPP</a>
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

    <div class="footer-newsletter-compact">
      <p>Berlangganan Penawaran Eksklusif & Katalog Hampers Terbaru</p>
      <form action="{{ route('contact.send') }}" method="POST" class="newsletter-form-simple">
        @csrf
        <input type="hidden" name="subject" value="Pendaftaran Klub Privilese VIP">
        <input type="email" name="email" placeholder="Alamat Email Anda..." required>
        <button type="submit">GABUNG VIP</button>
      </form>
    </div>
  </div>

  <!-- Main Footer Columns (Clean 3-Column Grid) -->
  <div class="footer-main-content">
    <!-- Col 1: Butik & Jam Operasional -->
    <div class="footer-col">
      <h4>BUTIK & LOKASI</h4>
      <p>📍 {{ config('site.address') }}</p>
      <p>⏰ {{ config('site.hours') }}</p>
      <p>📱 WA VIP: <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', config('site.wa')) }}" style="display:inline; color:#fef08a;">{{ config('site.wa') }}</a></p>
    </div>

    <!-- Col 2: Akses Cepat & Koleksi -->
    <div class="footer-col">
      <h4>KOLEKSI & LAYANAN</h4>
      <a href="{{ route('category', 'hampers') }}">🎁 Hampers & Gift Box Mewah</a>
      <a href="{{ route('category', 'mooncake') }}">🥮 Paket Hadiah Mooncake</a>
      <a href="{{ route('category', 'cakes') }}">🎂 Kue & Tart Artisanal</a>
      <a href="{{ route('reservations') }}">🍷 Reservasi Meja & Acara</a>
      <a href="{{ route('track') }}">📦 Lacak Pesanan Real-Time</a>
    </div>

    <!-- Col 3: Hubungi Kami & Sosial Media -->
    <div class="footer-col">
      <h4>HUBUNGI KAMI</h4>
      <p>✉️ <a href="mailto:{{ config('site.email') }}" style="display:inline; color:#fef08a;">{{ config('site.email') }}</a></p>
      <p>📞 Telp: {{ config('site.phone') }}</p>

      <div class="social-icons-row">
        <a href="https://instagram.com" target="_blank" class="social-icon" title="Instagram">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path></svg>
        </a>
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', config('site.wa')) }}" target="_blank" class="social-icon" title="WhatsApp VIP">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
        </a>
        <a href="https://facebook.com" target="_blank" class="social-icon" title="Facebook">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
        </a>
      </div>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
  // 3-Dots Mobile Drawer Toggle Logic
  const openThreeDotsBtn = document.getElementById('open-three-dots-btn');
  const closeThreeDotsBtn = document.getElementById('close-three-dots-btn');
  const mobileDrawerModal = document.getElementById('mobile-drawer-modal');

  if (openThreeDotsBtn && mobileDrawerModal) {
    openThreeDotsBtn.addEventListener('click', function () {
      mobileDrawerModal.classList.add('open');
      document.body.style.overflow = 'hidden';
    });
  }

  if (closeThreeDotsBtn && mobileDrawerModal) {
    closeThreeDotsBtn.addEventListener('click', function () {
      mobileDrawerModal.classList.remove('open');
      document.body.style.overflow = '';
    });
  }

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
                <img src="${p.image_url}" alt="${p.name}" style="width: 48px; height: 48px; object-fit: cover; border-radius: 6px; background: #e2e8f0;">
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

  // Product Detail Modal Event Handlers
  const productModal = document.getElementById('product-detail-modal');
  const closeProductModalBtn = document.getElementById('close-product-modal-btn');

  function openProductModal(data) {
    if (!productModal) return;

    document.getElementById('pm-image').src = data.image || '';
    document.getElementById('pm-title').textContent = data.name || '';
    document.getElementById('pm-category').textContent = data.category || 'HAMPERS MEWAH';
    document.getElementById('pm-price').textContent = data.price || '';
    document.getElementById('pm-availability').textContent = data.availability || 'Ready Stock (Tersedia)';
    document.getElementById('pm-flavor').textContent = data.flavor || 'Original Gourmet';
    document.getElementById('pm-size').textContent = data.size || 'Standar Porsi';
    document.getElementById('pm-product-id').value = data.id || 0;

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

    // Setup WhatsApp direct order link
    const waBtn = document.getElementById('pm-wa-btn');
    if (waBtn) {
      const waMsg = encodeURIComponent(`Halo Pusat Hampers Indonesia, saya tertarik dengan ${data.name} (${data.price}). Boleh minta informasi ketersediaan & cara pemesanan?`);
      waBtn.href = `https://wa.me/{{ preg_replace('/[^0-9]/', '', config('site.wa')) }}?text=${waMsg}`;
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
});
</script>
<script src="{{ asset('js/site.js') }}" defer></script>
</body>
</html>
