@extends('layouts.app')

@section('title', 'Checkout Pesanan Hampers | ' . config('site.brand'))

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<style>
  .checkout-page {
    background-color: #f7f9f8;
    min-height: 100vh;
    padding: 36px 20px 90px 20px;
    color: #2c3e35;
    font-family: 'Outfit', sans-serif;
  }
  .checkout-top-bar {
    max-width: 1020px;
    margin: 0 auto 24px auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
  }
  .back-to-cart-link {
    color: #4b6357;
    text-decoration: none;
    font-size: 13.5px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: color 0.2s;
  }
  .back-to-cart-link:hover {
    color: #1e2d27;
  }
  .checkout-steps-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: #64748b;
    font-weight: 600;
  }
  .checkout-steps-badge span.active {
    color: #1e2d27;
    background: #e2e8f0;
    padding: 3px 10px;
    border-radius: 12px;
  }

  .checkout-container {
    max-width: 1020px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 32px;
    align-items: start;
  }

  .checkout-input-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 16px;
    width: 100%;
    box-sizing: border-box;
  }

  @media (max-width: 900px) {
    .checkout-container {
      display: flex !important;
      flex-direction: column !important;
      gap: 24px !important;
    }
    .checkout-right-col {
      position: static !important;
      top: auto !important;
      order: 2 !important;
      width: 100% !important;
      max-width: 100% !important;
    }
  }

  @media (max-width: 768px) {
    .checkout-page {
      padding: 18px 12px 60px 12px;
    }
    .checkout-box {
      padding: 20px 14px !important;
      margin-bottom: 20px !important;
    }
    .checkout-input-grid {
      display: flex !important;
      flex-direction: column !important;
      gap: 14px !important;
      margin-bottom: 14px !important;
      width: 100% !important;
    }
    .checkout-input-grid > * {
      width: 100% !important;
      box-sizing: border-box !important;
    }
    .payment-grid {
      grid-template-columns: 1fr !important;
    }
    .greeting-card-panel {
      padding: 16px 14px !important;
      margin-bottom: 20px !important;
    }
  }

  .checkout-box {
    background: #fff;
    border: 1px solid #d2dcd7;
    border-radius: 10px;
    padding: 30px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
    margin-bottom: 24px;
  }

  .box-title {
    font-family: 'Playfair Display', serif;
    font-size: 20px;
    letter-spacing: 1.5px;
    color: #1e2d27;
    margin-bottom: 20px;
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .section-subtitle {
    font-size: 13px;
    color: #64748b;
    margin-top: -14px;
    margin-bottom: 22px;
    line-height: 1.5;
  }

  .form-group {
    margin-bottom: 18px;
    clear: both;
    position: relative;
  }
  .form-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #334155;
    margin-bottom: 6px;
  }
  .form-control {
    width: 100%;
    max-width: 100%;
    padding: 11px 14px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 13.5px;
    font-family: inherit;
    color: #0f172a;
    background: #ffffff;
    box-sizing: border-box;
    display: block;
    position: relative;
    transition: border-color 0.2s, box-shadow 0.2s;
  }
  textarea.form-control {
    min-height: 85px;
    line-height: 1.5;
    resize: vertical;
  }
  .form-control:focus {
    outline: none;
    border-color: #1e2d27;
    box-shadow: 0 0 0 3px rgba(30, 45, 39, 0.1);
  }

  /* Luxury Special Gift & Greeting Box */
  .greeting-card-panel {
    background: linear-gradient(135deg, #fdfcf7 0%, #faf6eb 100%);
    border: 1px solid #e6dcbd;
    border-radius: 10px;
    padding: 22px;
    margin-bottom: 24px;
    position: relative;
    box-shadow: 0 2px 10px rgba(217, 119, 6, 0.05);
    clear: both;
    overflow: visible;
  }
  .greeting-card-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #d97706;
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    margin-bottom: 10px;
  }
  .greeting-card-title {
    font-size: 16px;
    font-weight: 700;
    color: #78350f;
    margin: 0 0 4px 0;
  }
  .greeting-card-subtitle {
    font-size: 12.5px;
    color: #92400e;
    margin-bottom: 16px;
    line-height: 1.5;
  }

  /* Template Chips */
  .template-chips-wrap {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 14px;
  }
  .template-chip {
    background: #fff;
    border: 1px solid #d4c5a0;
    color: #78350f;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    user-select: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
  }
  .template-chip:hover {
    background: #fef3c7;
    border-color: #d97706;
    color: #92400e;
    transform: translateY(-1px);
  }
  .template-chip.clear-btn {
    border-color: #fca5a5;
    color: #dc2626;
    background: #fff;
  }
  .template-chip.clear-btn:hover {
    background: #fee2e2;
  }

  /* Different Recipient Box */
  .gift-toggle-card {
    background: #f8faf9;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 20px;
  }
  .gift-toggle-label {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    cursor: pointer;
    font-size: 13.5px;
    font-weight: 600;
    color: #1e2d27;
  }
  .gift-toggle-label input[type="checkbox"] {
    margin-top: 3px;
    width: 17px;
    height: 17px;
    accent-color: #1e2d27;
    cursor: pointer;
  }

  /* Visual Payment Cards */
  .payment-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin-bottom: 20px;
  }
  .payment-card-option {
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    padding: 14px;
    cursor: pointer;
    background: #fff;
    transition: all 0.2s;
    position: relative;
    display: flex;
    align-items: flex-start;
    gap: 12px;
  }
  .payment-card-option:hover {
    border-color: #94a3b8;
    background: #fafafa;
  }
  .payment-card-option.active {
    border-color: #1e2d27;
    background: #f0fdf4;
    box-shadow: 0 2px 8px rgba(30, 45, 39, 0.08);
  }
  .payment-card-option input[type="radio"] {
    margin-top: 3px;
    accent-color: #1e2d27;
    cursor: pointer;
  }
  .payment-card-info {
    flex: 1;
  }
  .payment-card-title {
    font-size: 13.5px;
    font-weight: 700;
    color: #1e2d27;
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 3px;
  }
  .payment-card-desc {
    font-size: 11.5px;
    color: #64748b;
    line-height: 1.4;
  }
  .payment-card-badge {
    font-size: 10px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 4px;
    text-transform: uppercase;
  }
  .badge-pop {
    background: #fef3c7;
    color: #b45309;
  }
  .badge-instant {
    background: #dcfce7;
    color: #15803d;
  }
  .badge-auto {
    background: #dbeafe;
    color: #1e40af;
  }

  /* Interactive Courier Selection Cards */
  .courier-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin-top: 8px;
    margin-bottom: 8px;
  }
  @media (max-width: 640px) {
    .courier-grid {
      grid-template-columns: 1fr;
    }
  }
  .courier-card-option {
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    padding: 12px 14px;
    cursor: pointer;
    background: #ffffff;
    transition: all 0.2s ease;
    position: relative;
    display: flex;
    align-items: flex-start;
    gap: 12px;
  }
  .courier-card-option:hover {
    border-color: #722F37;
    background: #fffcfc;
  }
  .courier-card-option.active {
    border-color: #722F37;
    background: #fdf2f4;
    box-shadow: 0 2px 10px rgba(114, 47, 55, 0.12);
  }
  .courier-card-option input[type="radio"] {
    margin-top: 3px;
    accent-color: #722F37;
    cursor: pointer;
  }
  .courier-card-info {
    flex: 1;
    min-width: 0;
  }
  .courier-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 6px;
    margin-bottom: 3px;
  }
  .courier-card-title {
    font-size: 13px;
    font-weight: 700;
    color: #1e2d27;
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .courier-card-rate {
    font-size: 13px;
    font-weight: 700;
    color: #722F37;
    white-space: nowrap;
  }
  .courier-card-rate.rate-free {
    color: #15803d;
  }
  .courier-card-desc {
    font-size: 11.5px;
    color: #64748b;
    line-height: 1.35;
  }
  .courier-card-badge {
    font-size: 10px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 4px;
    text-transform: uppercase;
  }

  /* Summary Styling */
  .checkout-right-col {
    position: sticky;
    top: 24px;
  }
  .summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f1f5f9;
    font-size: 13px;
  }
  .summary-item.total {
    font-size: 17px;
    font-weight: 700;
    color: #1e2d27;
    border-top: 2px solid #e2e8f0;
    border-bottom: none;
    margin-top: 14px;
    padding-top: 16px;
  }

  /* Coupon Box in Checkout */
  .checkout-coupon-box {
    background: #f8fafc;
    border: 1px dashed #cbd5e1;
    border-radius: 8px;
    padding: 14px;
    margin: 18px 0;
  }
  .coupon-input-group {
    display: flex;
    gap: 8px;
  }
  .coupon-input-group input {
    flex: 1;
    padding: 9px 12px;
    font-size: 12.5px;
    border: 1px solid #cbd5e1;
    border-radius: 5px;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
  }
  .coupon-input-group input:focus {
    outline: none;
    border-color: #1e2d27;
  }
  .coupon-btn {
    padding: 9px 16px;
    background: #1e2d27;
    color: #fff;
    border: none;
    border-radius: 5px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.2s;
    white-space: nowrap;
  }
  .coupon-btn:hover {
    background: #334e43;
  }
  .coupon-feedback {
    font-size: 11.5px;
    margin-top: 8px;
    display: none;
    line-height: 1.4;
  }
  .coupon-feedback.success {
    color: #15803d;
  }
  .coupon-feedback.error {
    color: #dc2626;
  }
  .coupon-feedback.info {
    color: #2563eb;
  }

  /* Submit Order Button & Loading */
  .btn-submit-order {
    width: 100%;
    padding: 16px 20px;
    background: #1e2d27;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 13.5px;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    font-weight: 700;
    cursor: pointer;
    margin-top: 24px;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 4px 14px rgba(30, 45, 39, 0.2);
  }
  .btn-submit-order:hover {
    background: #2d453b;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(30, 45, 39, 0.25);
  }
  .checkout-spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255,255,255,0.3);
    border-radius: 50%;
    border-top-color: #fff;
    animation: spin 0.8s linear infinite;
  }
  @keyframes spin {
    to { transform: rotate(360deg); }
  }

  /* Trust Badges */
  .trust-badges-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px dashed #cbd5e1;
  }
  @media (max-width: 480px) {
    .trust-badges-grid {
      grid-template-columns: 1fr;
    }
  }
  .trust-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 12px;
    color: #475569;
  }
  .trust-icon {
    font-size: 20px;
    flex-shrink: 0;
  }

  /* Luxury Delivery Map & Geolocation Card */
  .map-picker-card {
    background: #fdfcf7;
    border: 1px solid #e7dfc6;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 22px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
    clear: both;
    position: relative;
    box-sizing: border-box;
  }
  .map-picker-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 6px;
  }
  .map-picker-title {
    font-family: 'Playfair Display', serif;
    font-size: 16.5px;
    font-weight: 700;
    color: #1e2d27;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin: 0;
  }
  .badge-precision {
    background: #ecfdf5;
    color: #065f46;
    border: 1px solid #a7f3d0;
    padding: 3px 9px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    font-family: 'Outfit', sans-serif;
    letter-spacing: 0.5px;
  }
  .map-picker-desc {
    font-size: 12.5px;
    color: #64748b;
    line-height: 1.5;
    margin-bottom: 16px;
  }
  /* 2 Focus Hubs (Surabaya & Bandung) */
  .focus-hubs-bar {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px 14px;
    margin-bottom: 14px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.02);
  }
  .focus-hubs-label {
    display: block;
    font-size: 11px;
    font-weight: 800;
    color: #722F37;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 8px;
  }
  .focus-hubs-buttons {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
  }
  @media (max-width: 540px) {
    .focus-hubs-buttons {
      grid-template-columns: 1fr;
    }
  }
  .btn-hub-pill {
    background: #f8fafc;
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    padding: 10px 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    transition: all 0.2s ease;
    text-align: left;
  }
  .btn-hub-pill:hover {
    background: #fdfcf7;
    border-color: #d97706;
  }
  .btn-hub-pill.active {
    background: linear-gradient(135deg, #722F37 0%, #4a151b 100%);
    border-color: #fef08a;
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(114, 47, 55, 0.35);
  }
  .btn-hub-pill .hub-title {
    font-weight: 700;
    font-size: 13px;
    display: block;
    color: inherit;
  }
  .btn-hub-pill .hub-sub {
    font-size: 11px;
    opacity: 0.85;
    display: block;
    color: inherit;
    margin-top: 1px;
  }
  .btn-hub-pill.active .hub-sub {
    color: #fef08a;
  }
  .btn-hub-pill .hub-tag {
    font-size: 10px;
    font-weight: 800;
    padding: 2px 7px;
    border-radius: 12px;
    background: #e2e8f0;
    color: #334155;
    flex-shrink: 0;
  }
  .btn-hub-pill.active .hub-tag {
    background: #fef08a;
    color: #4a151b;
  }

  .pickup-store-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 12px;
  }
  @media (max-width: 600px) {
    .pickup-store-grid {
      grid-template-columns: 1fr;
    }
  }
  .pickup-store-option {
    border: 2px solid #e2e8f0;
    background: #ffffff;
    border-radius: 8px;
    padding: 14px;
    cursor: pointer;
    display: block;
    position: relative;
    transition: all 0.2s ease;
  }
  .pickup-store-option:hover {
    border-color: #d97706;
  }
  .pickup-store-option.active {
    border-color: #722F37;
    background: #fffdfa;
    box-shadow: 0 4px 12px rgba(114, 47, 55, 0.08);
  }

  .map-toolbar-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 12px;
    box-sizing: border-box;
  }
  @media (max-width: 640px) {
    .map-toolbar-grid {
      grid-template-columns: 1fr;
    }
  }
  .btn-gps-locate {
    width: 100%;
    background: linear-gradient(135deg, #1e2d27 0%, #2c3e35 100%);
    color: #fef08a;
    border: 1px solid #ca8a04;
    padding: 10px 14px;
    border-radius: 6px;
    font-size: 12.5px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    transition: all 0.2s ease;
    box-sizing: border-box;
  }
  .btn-gps-locate:hover {
    background: #0f1713;
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(202, 138, 4, 0.25);
  }
  .select-quick-region {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 12.5px;
    font-family: inherit;
    color: #1e293b;
    background: #ffffff;
    box-sizing: border-box;
  }
  .map-search-row {
    display: flex;
    gap: 8px;
    position: relative;
    margin-bottom: 12px;
    box-sizing: border-box;
    z-index: 99999;
  }
  @media (max-width: 480px) {
    .map-search-row {
      flex-direction: column;
    }
  }
  .map-search-input-wrap {
    flex: 1;
    position: relative;
    min-width: 0;
  }
  .map-search-input {
    width: 100%;
    padding: 11px 14px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 13px;
    color: #0f172a;
    box-sizing: border-box;
    background: #ffffff;
    transition: border-color 0.2s, box-shadow 0.2s;
  }
  .map-search-input:focus {
    outline: none;
    border-color: #722F37;
    box-shadow: 0 0 0 3px rgba(114, 47, 55, 0.12);
  }
  .btn-search-pin {
    background: #722F37;
    color: #ffffff;
    border: none;
    padding: 10px 18px;
    border-radius: 6px;
    font-size: 12.5px;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
    transition: background 0.2s;
    box-sizing: border-box;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }
  .btn-search-pin:hover {
    background: #531e24;
  }
  .map-dropdown-results {
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    right: 0;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    box-shadow: 0 16px 36px rgba(0, 0, 0, 0.18);
    z-index: 999999 !important;
    max-height: 290px;
    overflow-y: auto;
  }
  .map-dropdown-header {
    padding: 8px 12px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .map-quick-pills-row {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    padding: 10px 12px;
    background: #fdfcf7;
    border-bottom: 1px solid #f1f5f9;
  }
  .map-quick-pill {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    color: #1e2d27;
    font-size: 11.5px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.15s ease;
    display: inline-flex;
    align-items: center;
    gap: 4px;
  }
  .map-quick-pill:hover {
    background: #722F37;
    color: #ffffff;
    border-color: #722F37;
  }
  .map-result-item {
    padding: 10px 14px;
    border-bottom: 1px solid #f1f5f9;
    cursor: pointer;
    font-size: 12.5px;
    line-height: 1.4;
    color: #334155;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: background 0.15s;
  }
  .map-result-item:hover, .map-result-item.keyboard-active {
    background: #fefce8;
    color: #722F37;
  }
  .map-result-item:last-child {
    border-bottom: none;
  }
  .map-result-icon {
    font-size: 16px;
    flex-shrink: 0;
  }
  .map-result-content {
    flex: 1;
    min-width: 0;
  }
  .map-result-name {
    font-weight: 700;
    color: #1e2d27;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 6px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
  .map-result-detail {
    font-size: 11px;
    color: #64748b;
    margin-top: 1px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
  .map-match-highlight {
    background: #fef08a;
    color: #854d0e;
    font-weight: 800;
    padding: 0 1px;
    border-radius: 2px;
  }
  .map-result-badge {
    font-size: 10px;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 4px;
    background: #f1f5f9;
    color: #475569;
    flex-shrink: 0;
    white-space: nowrap;
  }
  .map-smart-notice {
    background: #fefce8;
    border: 1px solid #fef08a;
    color: #854d0e;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 12px;
    line-height: 1.45;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .map-canvas-container {
    width: 100%;
    height: 330px;
    border-radius: 8px;
    border: 1px solid #cbd5e1;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    position: relative;
    z-index: 5;
    box-sizing: border-box;
  }
  .coords-info-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 12px;
    padding: 10px 14px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 12.5px;
    color: #475569;
  }
  .btn-gmaps-view {
    background: #059669;
    color: #ffffff !important;
    text-decoration: none;
    padding: 5px 12px;
    border-radius: 4px;
    font-size: 11.5px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: background 0.2s;
  }
  .btn-gmaps-view:hover {
    background: #047857;
  }
  .detected-addr-box {
    margin-top: 10px;
    padding: 10px 14px;
    background: #ecfdf5;
    border: 1px solid #a7f3d0;
    border-radius: 6px;
    font-size: 12px;
    color: #065f46;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 8px;
  }
</style>

<div class="checkout-page">
  <div class="checkout-top-bar">
    <a href="{{ route('cart.index') }}" class="back-to-cart-link">
      <span>←</span>
      <span>Kembali ke Keranjang Belanja</span>
    </a>
    <div class="checkout-steps-badge">
      <span>1. Keranjang</span>
      <span>›</span>
      <span class="active">2. Informasi &amp; Pembayaran</span>
      <span>›</span>
      <span>3. Selesai</span>
    </div>
  </div>

  <div class="checkout-container">
    <!-- KOLOM KIRI: FORM CHECKOUT LENGKAP -->
    <div>
      <div class="checkout-box">
        <h2 class="box-title">
          <span>DETAIL PEMESAN &amp; PENGIRIMAN</span>
          <span style="font-size: 13px; font-family: 'Outfit', sans-serif; font-weight: 500; color: #15803d; letter-spacing: 0;">🔒 Transaksi Aman</span>
        </h2>
        <p class="section-subtitle">
          Lengkapi data penerima dan pesan kartu ucapan untuk memastikan bingkisan hampers tiba dengan sempurna.
        </p>

        @if ($errors->any())
          <div style="background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; padding: 14px 18px; border-radius: 8px; margin-bottom: 24px; font-size: 13px;">
            <strong>⚠️ Mohon lengkapi atau perbaiki kolom berikut:</strong>
            <ul style="margin-left: 20px; margin-top: 6px;">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm" onsubmit="return handleCheckoutSubmit(event)">
          @csrf

          <input type="hidden" name="delivery_option" value="{{ $deliveryOption }}">
          <input type="hidden" name="delivery_date" value="{{ $deliveryDate }}">
          <input type="hidden" name="order_note" value="{{ $orderNote }}">

          {{-- Status Akun Pelanggan --}}
          <div style="background: #f0fdf4; border: 1.5px solid #86efac; color: #166534; padding: 12px 16px; border-radius: 8px; margin-bottom: 22px; font-size: 13px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
            <div>
              <strong>👤 Akun Terhubung:</strong> {{ auth()->user()->name }} ({{ auth()->user()->email }})
            </div>
            <span style="background: #166534; color: #fff; padding: 3px 12px; border-radius: 12px; font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">✓ MEMBER AKTIF</span>
          </div>

          {{-- DATA PEMESAN --}}
          <div class="form-group">
            <label for="customer_name">Nama Lengkap Pemesan <span style="color: #dc2626;">*</span></label>
            <input type="text" name="customer_name" id="customer_name" class="form-control" value="{{ old('customer_name', auth()->user()->name) }}" placeholder="Contoh: Budi Santoso" required>
          </div>

          <div class="checkout-input-grid">
            <div class="form-group">
              <label for="customer_phone">No. WhatsApp / HP Pemesan <span style="color: #dc2626;">*</span></label>
              <input type="tel" name="customer_phone" id="customer_phone" class="form-control" value="{{ old('customer_phone', auth()->user()->phone ?? '') }}" placeholder="081234567890" required>
            </div>

            <div class="form-group">
              <label for="customer_email">Alamat Email (Untuk Faktur Resmi)</label>
              <input type="email" name="customer_email" id="customer_email" class="form-control" value="{{ old('customer_email', auth()->user()->email) }}" placeholder="email@contoh.com">
            </div>
          </div>

          {{-- OPSI PENGIRIMAN & KURIR --}}
          <div class="form-group">
            <label>Metode &amp; Tanggal Pengiriman Terpilih</label>
            <div style="padding: 14px 16px; background: #f8faf9; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; line-height: 1.6;">
              <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
                <div>
                  <strong>Opsi:</strong> {{ strtoupper($deliveryOption) }} ({{ $deliveryOption === 'pickup' ? 'Ambil Langsung di Butik Toko' : 'Pengiriman Kurir Lokal Khusus' }})<br>
                  <strong>Jadwal Pengiriman:</strong> 📅 {{ date('d F Y', strtotime($deliveryDate)) }}
                </div>
                <a href="{{ route('cart.index') }}" style="font-size: 11.5px; color: #d97706; text-decoration: underline; font-weight: 600;">Ubah Jadwal di Keranjang</a>
              </div>
            </div>
          </div>

          @if ($deliveryOption === 'delivery')
            <div class="form-group" style="margin-top: 18px;">
              <label style="font-size: 14px; font-weight: 700; color: #1e2d27; margin-bottom: 4px;">Pilih Ekspedisi / Kurir Pengantar <span style="color: #dc2626;">*</span></label>
              <div class="section-subtitle" style="margin-top: 0; margin-bottom: 12px;">Pilih layanan pengantaran sesuai preferensi kecepatan dan kenyamanan Anda.</div>

              <div class="courier-grid">
                @php
                  $selectedCourier = old('courier_name', $courierName);
                  $freeMin = $shipSettings['free_shipping_min'] ?? 500000;
                  $isFree = ($subtotal >= $freeMin);

                  $courierOptions = [
                    [
                      'name' => 'Kurir Toko (Dedicated Bakery Delivery)',
                      'icon' => '🥖',
                      'title' => 'Kurir Toko Dedicated',
                      'badge' => 'Armada Khusus',
                      'badge_cls' => 'badge-pop',
                      'rate' => $shipSettings['store_courier_rate'] ?? 20000,
                      'desc' => 'Armada berpendingin khusus cake &amp; hampers, aman tanpa risiko guncangan.',
                    ],
                    [
                      'name' => 'JNE Express (Reguler/YES)',
                      'icon' => '📦',
                      'title' => 'JNE Express',
                      'badge' => 'Ekspedisi Nasional',
                      'badge_cls' => 'badge-auto',
                      'rate' => $shipSettings['jne_rate'] ?? 15000,
                      'desc' => 'Layanan ekspedisi terpercaya se-Surabaya &amp; Bandung dengan resi otomatis.',
                    ],
                    [
                      'name' => 'J&T Express',
                      'icon' => '🚚',
                      'title' => 'J&T Express',
                      'badge' => 'Cepat Harian',
                      'badge_cls' => 'badge-instant',
                      'rate' => $shipSettings['jnt_rate'] ?? 16000,
                      'desc' => 'Pengantaran kilat 365 hari tanpa libur dengan nomor resi terintegrasi live.',
                    ],
                    [
                      'name' => 'SiCepat Express',
                      'icon' => '⚡',
                      'title' => 'SiCepat Express',
                      'badge' => 'Kilat Aman',
                      'badge_cls' => 'badge-instant',
                      'rate' => $shipSettings['sicepat_rate'] ?? 15000,
                      'desc' => 'Layanan ekspedisi cepat &amp; amanah khusus paket bingkisan hampers butik.',
                    ],
                    [
                      'name' => 'GoSend / GrabExpress (Instant)',
                      'icon' => '🛵',
                      'title' => 'GoSend / GrabExpress',
                      'badge' => 'Instan 2-3 Jam',
                      'badge_cls' => 'badge-pop',
                      'rate' => $shipSettings['gosend_rate'] ?? 25000,
                      'desc' => 'Driver motor point-to-point langsung antar ke alamat pada tanggal terpilih.',
                    ],
                  ];
                @endphp

                @foreach ($courierOptions as $opt)
                  @php
                    $optRate = $isFree ? 0 : $opt['rate'];
                    $isActive = ($selectedCourier === $opt['name'] || (empty($selectedCourier) && $loop->first));
                  @endphp
                  <label class="courier-card-option {{ $isActive ? 'active' : '' }}" onclick="selectCourierOption(this, '{{ $opt['name'] }}', {{ $optRate }})">
                    <input type="radio" name="courier_name" value="{{ $opt['name'] }}" {{ $isActive ? 'checked' : '' }} required>
                    <div class="courier-card-info">
                      <div class="courier-card-header">
                        <div class="courier-card-title">
                          <span>{{ $opt['icon'] }}</span>
                          <span>{{ $opt['title'] }}</span>
                        </div>
                        <span class="courier-card-badge {{ $opt['badge_cls'] }}">{{ $opt['badge'] }}</span>
                      </div>
                      <div class="courier-card-desc">{{ $opt['desc'] }}</div>
                      <div style="margin-top: 6px; display: flex; align-items: center; justify-content: space-between;">
                        <span style="font-size: 11px; color: #64748b;">Tarif Pengiriman:</span>
                        <span class="courier-card-rate {{ $optRate == 0 ? 'rate-free' : '' }}">
                          {{ $optRate == 0 ? 'GRATIS (Rp 0)' : 'Rp ' . number_format($optRate, 0, ',', '.') }}
                        </span>
                      </div>
                    </div>
                  </label>
                @endforeach
              </div>
            </div>

            {{-- TOGGLE KIRIM SEBAGAI HADIAH (PENERIMA BERBEDA) --}}
            <div class="gift-toggle-card">
              <label class="gift-toggle-label">
                <input type="checkbox" name="is_gift" id="is_gift_checkbox" value="1" {{ old('is_gift') ? 'checked' : '' }} onchange="toggleRecipientFields()">
                <div>
                  <span style="display: block; font-size: 13.5px; color: #1e2d27; font-weight: 700;">🎁 Kirim sebagai Hadiah / Kado (Penerima Berbeda)</span>
                  <span style="display: block; font-size: 12px; color: #64748b; font-weight: 400; margin-top: 2px;">
                    Aktifkan jika pesanan dikirim langsung ke kerabat atau mitra Anda. Nota harga fisik <strong>tidak akan</strong> disertakan di dalam paket.
                  </span>
                </div>
              </label>

              <div id="different_recipient_container" style="display: {{ old('is_gift') ? 'block' : 'none' }}; margin-top: 16px; padding-top: 14px; border-top: 1px dashed #cbd5e1;">
                <div class="checkout-input-grid">
                  <div class="form-group">
                    <label for="recipient_name">Nama Penerima Hadiah <span style="color: #dc2626;">*</span></label>
                    <input type="text" name="recipient_name" id="recipient_name" class="form-control" value="{{ old('recipient_name') }}" placeholder="Contoh: Ibu Rina Suryani">
                  </div>
                  <div class="form-group">
                    <label for="recipient_phone">No. WhatsApp / HP Penerima <span style="color: #dc2626;">*</span></label>
                    <input type="tel" name="recipient_phone" id="recipient_phone" class="form-control" value="{{ old('recipient_phone') }}" placeholder="081987654321">
                  </div>
                </div>
              </div>
            </div>

            {{-- PETA LOKASI & KOORDINAT GPS (INDONESIA) --}}
            <div class="map-picker-card">
              <div class="map-picker-header">
                <h3 class="map-picker-title">
                  <span>📍 Titik Lokasi Peta &amp; Koordinat GPS Presisi</span>
                </h3>
                <span class="badge-precision">Super Presisi</span>
              </div>
              <p class="map-picker-desc">
                Gunakan lokasi saat ini (GPS) atau cari nama jalan, kelurahan, dan kecamatan di area Kota Surabaya dan Kota Bandung. Anda juga dapat menggeser pin merah pada peta agar kurir tiba tepat di depan pintu tujuan.
              </p>

              {{-- PILIHAN KOTA PENGIRIMAN: SURABAYA & BANDUNG --}}
              <div class="focus-hubs-bar">
                <span class="focus-hubs-label">⭐ PILIH KOTA PENGIRIMAN / BUTIK:</span>
                <div class="focus-hubs-buttons">
                  <button type="button" id="btn_hub_surabaya" class="btn-hub-pill active" onclick="jumpToHub('surabaya')">
                    <div>
                      <span class="hub-title">🏬 KOTA SURABAYA</span>
                      <span class="hub-sub">Darmo, Gubeng, Pakuwon, Rungkut, Sidoarjo &amp; Gresik</span>
                    </div>
                    <span class="hub-tag">Jawa Timur</span>
                  </button>
                  <button type="button" id="btn_hub_bandung" class="btn-hub-pill" onclick="jumpToHub('bandung')">
                    <div>
                      <span class="hub-title">🏬 KOTA BANDUNG</span>
                      <span class="hub-sub">Jl. Riau, Dago, Sukajadi, Buahbatu, Cimahi &amp; Lembang</span>
                    </div>
                    <span class="hub-tag">Jawa Barat</span>
                  </button>
                </div>
              </div>

              {{-- TOOLBAR: GPS & QUICK SELECT WILAYAH/KOTA --}}
              <div class="map-toolbar-grid">
                <div>
                  <button type="button" id="btn_get_current_location" class="btn-gps-locate">
                    <span id="gps_icon">📍</span> <span id="gps_label">Gunakan Lokasi Saat Ini (GPS)</span>
                  </button>
                </div>
                <div>
                  <select id="select_province_quick" class="select-quick-region" aria-label="Pilih Wilayah Surabaya atau Bandung">
                    <option value="">🏛️ Pilih Wilayah Cepat di Surabaya atau Bandung...</option>
                    <optgroup label="📍 KOTA SURABAYA &amp; SEKITARNYA (JAWA TIMUR)">
                      <option value="-7.2575,112.7521|Kota Surabaya" selected>Kota Surabaya (Pusat / Darmo / Gubeng / Basuki Rahmat)</option>
                      <option value="-7.2886,112.6756|Surabaya Barat (Pakuwon / Citraland)">Surabaya Barat (Pakuwon Mall / Citraland / HR Muhammad)</option>
                      <option value="-7.3185,112.7758|Surabaya Timur (Rungkut / Sukolilo)">Surabaya Timur (Rungkut / Mulyorejo / Sukolilo)</option>
                      <option value="-7.2892,112.7342|Surabaya Selatan (Wonokromo / Gayungan)">Surabaya Selatan (Wonokromo / Gayungan / A. Yani)</option>
                      <option value="-7.2285,112.7512|Surabaya Utara (Kenjeran / Perak)">Surabaya Utara (Kenjeran / Perak)</option>
                      <option value="-7.4478,112.7183|Kabupaten Sidoarjo">Kab. Sidoarjo (Waru / Gedangan / Kota)</option>
                      <option value="-7.1566,112.6555|Kabupaten Gresik">Kab. Gresik (Kebomas / Manyar / Kota)</option>
                    </optgroup>
                    <optgroup label="📍 KOTA BANDUNG &amp; SEKITARNYA (JAWA BARAT)">
                      <option value="-6.9175,107.6191|Kota Bandung">Kota Bandung (Pusat / Jl. Riau / Asia Afrika)</option>
                      <option value="-6.8833,107.6167|Bandung Dago (Coblong)">Bandung Utara (Dago / Dipatiukur / ITB)</option>
                      <option value="-6.8924,107.5982|Bandung Sukajadi (Pasteur)">Bandung Sukajadi (Pasteur / PVJ / Setiabudi)</option>
                      <option value="-6.9532,107.6358|Bandung Buahbatu (Batununggal)">Bandung Selatan (Buahbatu / Batununggal / Lengkong)</option>
                      <option value="-6.9167,107.6667|Bandung Antapani (Arcamanik)">Bandung Timur (Antapani / Arcamanik)</option>
                      <option value="-6.8167,107.6167|Lembang Bandung Barat">Lembang &amp; Bandung Barat</option>
                      <option value="-6.8722,107.5422|Kota Cimahi">Kota Cimahi</option>
                    </optgroup>
                  </select>
                </div>
              </div>

              {{-- PENCARIAN ALAMAT SE-SURABAYA & BANDUNG DENGAN REKOMENDASI LIVE --}}
              <div class="map-search-row">
                <div class="map-search-input-wrap">
                  <input type="text" id="map_search_input" class="form-control map-search-input" placeholder="🔍 Cari jalan, kelurahan, atau kecamatan (Contoh: Darmo, Dago, Rungkut, Sukajadi)..." autocomplete="off">
                  <div id="map_search_results" class="map-dropdown-results" style="display: none;"></div>
                </div>
                <button type="button" id="btn_map_search" class="btn-search-pin">
                  <span>Cari Titik</span>
                </button>
              </div>

              {{-- NOTIFIKASI PENCARIAN CERDAS (SMART FALLBACK) --}}
              <div id="map_smart_notice" class="map-smart-notice" style="display: none;">
                <span>💡</span> <span id="map_smart_notice_text"></span>
              </div>

              {{-- CANVAS PETA LEAFLET --}}
              <div id="checkout_leaflet_map" class="map-canvas-container"></div>

              {{-- STATUS KOORDINAT & LINK GOOGLE MAPS --}}
              <div class="coords-info-bar">
                <div>
                  <strong>📍 Titik Koordinat:</strong>
                  <span id="display_coords_val" style="font-family: monospace; font-weight: 700; color: #1e2d27;">{{ old('latitude', '-7.2574719') }}, {{ old('longitude', '112.7520883') }}</span>
                </div>
                <div>
                  <a id="btn_open_gmaps" href="https://www.google.com/maps?q={{ old('latitude', '-7.2574719') }},{{ old('longitude', '112.7520883') }}" target="_blank" class="btn-gmaps-view">
                    <span>🗺️ Buka di Google Maps ↗</span>
                  </a>
                </div>
              </div>

              {{-- REKOMENDASI ALAMAT TERDETEKSI --}}
              <div id="box_detected_address" class="detected-addr-box">
                <div style="flex: 1; min-width: 200px;">
                  <strong>✨ Titik Alamat Peta:</strong> <span id="detected_address_text">Kota Surabaya (Darmo)</span>
                </div>
                <button type="button" id="btn_sync_address" style="background: #059669; color: #fff; border: none; padding: 5px 12px; border-radius: 4px; font-size: 11.5px; font-weight: 700; cursor: pointer; white-space: nowrap;">
                  📋 Salin ke Formulir Alamat
                </button>
              </div>

              {{-- INPUT HIDDEN UNTUK FORM DATABASE --}}
              <input type="hidden" name="latitude" id="input_latitude" value="{{ old('latitude', '-7.2574719') }}">
              <input type="hidden" name="longitude" id="input_longitude" value="{{ old('longitude', '112.7520883') }}">
            </div>

            {{-- ALAMAT PENGIRIMAN LENGKAP --}}
            <div class="form-group">
              <label for="address">Alamat Pengiriman Lengkap <span style="color: #dc2626;">*</span></label>
              <textarea name="address" id="address" class="form-control" rows="3" placeholder="Nama Jalan, Nomor Rumah, RT/RW, Komplek / Gedung, Kelurahan, Kecamatan" required>{{ old('address') }}</textarea>
            </div>

            <div class="checkout-input-grid">
              <div class="form-group">
                <label for="city">Kota / Kabupaten</label>
                <input type="text" name="city" id="city" class="form-control" value="{{ old('city') }}" placeholder="Contoh: Kota Surabaya atau Kota Bandung">
              </div>

              <div class="form-group">
                <label for="postal_code">Kode Pos</label>
                <input type="text" name="postal_code" id="postal_code" class="form-control" value="{{ old('postal_code') }}" placeholder="Contoh: 60241 atau 40115">
              </div>
            </div>

            <div class="form-group">
              <label for="address_notes">Patokan Lokasi / Catatan Pengantaran Kurir (Opsional)</label>
              <input type="text" name="address_notes" id="address_notes" class="form-control" value="{{ old('address_notes') }}" placeholder="Contoh: Pagar hitam samping kantor pos, titip di resepsionis lantai 2 jika tidak di tempat">
            </div>

            <div class="form-group">
              <label for="custom_order_note">Catatan / Keterangan Khusus Pesanan (Opsional)</label>
              <textarea name="custom_order_note" id="custom_order_note" class="form-control" rows="2" placeholder="Tuliskan catatan khusus untuk pesanan, warna pita, instruksi packaging, atau pesan khusus...">{{ old('custom_order_note', $orderNote) }}</textarea>
            </div>
          @else
            <input type="hidden" name="courier_name" value="Ambil Sendiri di Boutique Store">
            <div class="form-group">
              <label style="font-weight: 700; font-size: 13.5px; color: #1e2d27; margin-bottom: 8px;">Pilih Lokasi Butik Pengambilan (Pick Up) <span style="color: #dc2626;">*</span></label>
              <div class="pickup-store-grid">
                <label id="pickup_card_surabaya" class="pickup-store-option active">
                  <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4px;">
                    <div style="font-weight: 800; font-size: 13px; color: #722F37;">🏬 BUTIK SURABAYA</div>
                    <input type="radio" name="pickup_branch" value="surabaya" checked onchange="selectPickupBranch('surabaya')">
                  </div>
                  <div style="font-size: 12px; color: #334155; line-height: 1.4;">Jl. Raya Darmo No. 45, Wonokromo, Kota Surabaya, Jawa Timur</div>
                  <div style="font-size: 11px; color: #64748b; margin-top: 6px;">⏰ Buka: 08:00 - 20:00 WIB • Ready Stock &amp; Custom Hampers</div>
                </label>

                <label id="pickup_card_bandung" class="pickup-store-option">
                  <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4px;">
                    <div style="font-weight: 800; font-size: 13px; color: #722F37;">🏬 BUTIK BANDUNG</div>
                    <input type="radio" name="pickup_branch" value="bandung" onchange="selectPickupBranch('bandung')">
                  </div>
                  <div style="font-size: 12px; color: #334155; line-height: 1.4;">Jl. LLRE Martadinata (Riau) No. 68, Citarum, Kota Bandung, Jawa Barat</div>
                  <div style="font-size: 11px; color: #64748b; margin-top: 6px;">⏰ Buka: 08:00 - 20:00 WIB • Ready Stock &amp; Custom Hampers</div>
                </label>
              </div>
              <input type="hidden" name="address" id="pickup_store_address" value="Store Pick Up: Butik Surabaya (Jl. Raya Darmo No. 45, Wonokromo, Kota Surabaya)">
            </div>

            <div class="form-group">
              <label for="custom_order_note">Catatan / Keterangan Khusus Pesanan (Opsional)</label>
              <textarea name="custom_order_note" id="custom_order_note" class="form-control" rows="2" placeholder="Tuliskan catatan khusus untuk pesanan atau jam perkiraan pengambilan...">{{ old('custom_order_note', $orderNote) }}</textarea>
            </div>
          @endif

          {{-- KARTU UCAPAN HANTARAN (GRATIS) --}}
          @php
            $activeCards = $greetingTemplates ?? \App\Http\Controllers\Admin\AdminGreetingCardController::getActiveTemplates();
          @endphp
          <div class="greeting-card-panel">
            <div class="greeting-card-badge">✨ GRATIS EXCLUSIVE CARD</div>
            <h3 class="greeting-card-title">🎁 Kartu Ucapan Spesial Hantaran</h3>
            <p class="greeting-card-subtitle">
              Setiap bingkisan hampers dilengkapi kartu ucapan mewah. Klik tema ucapan siap pakai di bawah ini atau tuliskan ucapan personal Anda:
            </p>

            <div class="template-chips-wrap">
              @foreach($activeCards as $tpl)
                <button type="button" class="template-chip" onclick="applyTemplate('{{ $tpl['id'] }}')">
                  {{ $tpl['title'] }}
                </button>
              @endforeach
              <button type="button" class="template-chip clear-btn" onclick="applyTemplate('clear')">
                🗑️ Hapus Teks
              </button>
            </div>

            <div class="checkout-input-grid" style="margin-bottom: 14px;">
              <div class="form-group" style="margin-bottom: 0;">
                <label for="card_to" style="font-size: 12px; font-weight: 600; color: #78350f; display: block; margin-bottom: 4px;">Kepada (To):</label>
                <input type="text" name="card_to" id="card_to" class="form-control" value="{{ old('card_to') }}" placeholder="Contoh: Keluarga Ibu Maya &amp; Bapak Edwin">
              </div>
              <div class="form-group" style="margin-bottom: 0;">
                <label for="card_from" style="font-size: 12px; font-weight: 600; color: #78350f; display: block; margin-bottom: 4px;">Dari (From):</label>
                <input type="text" name="card_from" id="card_from" class="form-control" value="{{ old('card_from') }}" placeholder="Contoh: Keluarga Besar Budi Santoso">
              </div>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
              <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                <label for="card_message" style="font-size: 12px; font-weight: 600; color: #78350f;">Pesan Ucapan Hantaran:</label>
                <span style="font-size: 11px; color: #92400e;"><span id="card_char_count">0</span>/300 karakter</span>
              </div>
              <textarea name="card_message" id="card_message" class="form-control" rows="3" maxlength="300" placeholder="Tuliskan ucapan penuh makna untuk penerima hantaran di sini..." oninput="updateCharCount()">{{ old('card_message') }}</textarea>
            </div>
          </div>

          {{-- METODE PEMBAYARAN INTERAKTIF --}}
          <div class="form-group" style="margin-top: 24px;">
            <label style="font-size: 14px; font-weight: 700; color: #1e2d27; margin-bottom: 8px;">Pilih Metode Pembayaran <span style="color: #dc2626;">*</span></label>
            <div class="section-subtitle" style="margin-top: 0; margin-bottom: 14px;">Semua transaksi terenkripsi dengan aman melalui verifikasi otomatis dan manual resmi perbankan.</div>

            <div class="payment-grid">
              @php
                $selectedPayment = old('payment_method', 'Transfer Bank BCA');
              @endphp

              {{-- BCA --}}
              <label class="payment-card-option {{ $selectedPayment == 'Transfer Bank BCA' ? 'active' : '' }}" onclick="selectPaymentMethod(this)">
                <input type="radio" name="payment_method" value="Transfer Bank BCA" {{ $selectedPayment == 'Transfer Bank BCA' ? 'checked' : '' }} required>
                <div class="payment-card-info">
                  <div class="payment-card-title">
                    <span>💳 Transfer Bank BCA</span>
                    <span class="payment-card-badge badge-pop">Populer</span>
                  </div>
                  <div class="payment-card-desc">BCA Virtual Account / Transfer Manual via BCA Mobile &amp; ATM.</div>
                </div>
              </label>

              {{-- Mandiri --}}
              <label class="payment-card-option {{ $selectedPayment == 'Transfer Bank Mandiri' ? 'active' : '' }}" onclick="selectPaymentMethod(this)">
                <input type="radio" name="payment_method" value="Transfer Bank Mandiri" {{ $selectedPayment == 'Transfer Bank Mandiri' ? 'checked' : '' }}>
                <div class="payment-card-info">
                  <div class="payment-card-title">
                    <span>🏦 Transfer Bank Mandiri</span>
                  </div>
                  <div class="payment-card-desc">Transfer via Livin' by Mandiri, ATM, atau Internet Banking.</div>
                </div>
              </label>

              {{-- BRI --}}
              <label class="payment-card-option {{ $selectedPayment == 'Transfer Bank BRI' ? 'active' : '' }}" onclick="selectPaymentMethod(this)">
                <input type="radio" name="payment_method" value="Transfer Bank BRI" {{ $selectedPayment == 'Transfer Bank BRI' ? 'checked' : '' }}>
                <div class="payment-card-info">
                  <div class="payment-card-title">
                    <span>🏛️ Transfer Bank BRI</span>
                  </div>
                  <div class="payment-card-desc">Transfer melalui aplikasi BRIMO, ATM BRI, &amp; Agen BRILink.</div>
                </div>
              </label>

              {{-- QRIS --}}
              <label class="payment-card-option {{ $selectedPayment == 'QRIS Instant Payment' ? 'active' : '' }}" onclick="selectPaymentMethod(this)">
                <input type="radio" name="payment_method" value="QRIS Instant Payment" {{ $selectedPayment == 'QRIS Instant Payment' ? 'checked' : '' }}>
                <div class="payment-card-info">
                  <div class="payment-card-title">
                    <span>📱 QRIS Instant Payment</span>
                    <span class="payment-card-badge badge-instant">Instan</span>
                  </div>
                  <div class="payment-card-desc">Scan QRIS via GoPay, OVO, ShopeePay, DANA, BCA, Livin, dll.</div>
                </div>
              </label>

              {{-- Midtrans Gateway --}}
              <label class="payment-card-option {{ $selectedPayment == 'Midtrans Payment Gateway (Verifikasi Otomatis)' ? 'active' : '' }}" onclick="selectPaymentMethod(this)">
                <input type="radio" name="payment_method" value="Midtrans Payment Gateway (Verifikasi Otomatis)" {{ $selectedPayment == 'Midtrans Payment Gateway (Verifikasi Otomatis)' ? 'checked' : '' }}>
                <div class="payment-card-info">
                  <div class="payment-card-title">
                    <span>⚡ Midtrans Auto Gateway</span>
                    <span class="payment-card-badge badge-auto">Otomatis</span>
                  </div>
                  <div class="payment-card-desc">Kartu Kredit, GoPay, Multi VA dengan verifikasi instan 24 jam.</div>
                </div>
              </label>

              {{-- COD --}}
              <label class="payment-card-option {{ $selectedPayment == 'COD (Cash on Delivery)' ? 'active' : '' }}" onclick="selectPaymentMethod(this)">
                <input type="radio" name="payment_method" value="COD (Cash on Delivery)" {{ $selectedPayment == 'COD (Cash on Delivery)' ? 'checked' : '' }}>
                <div class="payment-card-info">
                  <div class="payment-card-title">
                    <span>💵 COD (Bayar di Tempat)</span>
                  </div>
                  <div class="payment-card-desc">Bayar tunai kepada kurir toko saat bingkisan hampers tiba.</div>
                </div>
              </label>
            </div>
          </div>

          {{-- CATATAN TAMBAHAN PESANAN --}}
          <div class="form-group">
            <label for="custom_order_note">Catatan Tambahan untuk Toko (Opsional)</label>
            <input type="text" name="custom_order_note" id="custom_order_note" class="form-control" value="{{ old('custom_order_note', $orderNote) }}" placeholder="Contoh: Mohon ikat pita warna emas, kirim sebelum jam 15:00 WIB">
          </div>

          {{-- TERMS & SUBMIT --}}
          <div style="margin-top: 24px;">
            <label style="display: flex; align-items: flex-start; gap: 10px; font-size: 13px; cursor: pointer; color: #475569; line-height: 1.5;">
              <input type="checkbox" name="terms" value="1" checked required style="margin-top: 3px; accent-color: #1e2d27;">
              <span>Saya menyetujui <a href="{{ route('terms') }}" target="_blank" style="color: inherit; text-decoration: underline;"><strong>Syarat &amp; Ketentuan Pemesanan</strong></a> serta menjamin data penerima hantaran sudah diisi dengan benar.</span>
            </label>
          </div>

          <button type="submit" class="btn-submit-order" id="btn_submit_order">
            <span>🔒 SELESAIKAN PEMESANAN &amp; BAYAR →</span>
          </button>
        </form>

        {{-- TRUST & SECURITY BADGES --}}
        <div class="trust-badges-grid">
          <div class="trust-item">
            <div class="trust-icon">🔒</div>
            <div>
              <strong style="color: #1e2d27; display: block;">Enkripsi SSL 256-Bit</strong>
              <span>Transaksi &amp; data Anda dijamin 100% aman terlindungi.</span>
            </div>
          </div>
          <div class="trust-item">
            <div class="trust-icon">🛡️</div>
            <div>
              <strong style="color: #1e2d27; display: block;">Garansi Kemasan Aman</strong>
              <span>Dilindungi double bubble wrap dan hardbox tebal elegan.</span>
            </div>
          </div>
          <div class="trust-item">
            <div class="trust-icon">🚚</div>
            <div>
              <strong style="color: #1e2d27; display: block;">Kurir Terjadwal Rapi</strong>
              <span>Tiba tepat waktu sesuai tanggal reservasi pilihan Anda.</span>
            </div>
          </div>
          <div class="trust-item">
            <div class="trust-icon">✨</div>
            <div>
              <strong style="color: #1e2d27; display: block;">Kualitas Pastry Premium</strong>
              <span>Diproduksi segar (freshly baked) dari bahan bersertifikasi halal.</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- KOLOM KANAN: RINGKASAN PESANAN & KUPON PROMO -->
    <div class="checkout-right-col">
      <div class="checkout-box">
        <h3 class="box-title" style="font-size: 16px; margin-bottom: 16px;">RINGKASAN ITEM</h3>

        <div style="max-height: 280px; overflow-y: auto; padding-right: 4px; margin-bottom: 12px;">
          @foreach ($cart as $item)
            <div class="summary-item">
              <div style="display: flex; gap: 12px; align-items: center;">
                <img src="{{ $item['thumbnail_url'] ?? ($item['image_url'] ?? asset('images/logo.png')) }}" alt="{{ $item['name'] }}" loading="lazy" decoding="async" style="width: 46px; height: 46px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0;">
                <div>
                  <div style="font-weight: 600; font-size: 13.5px; color: #1e2d27;">{{ $item['name'] }}</div>
                  <div style="font-size: 11.5px; color: #64756d;">Qty: {{ $item['quantity'] }} × Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                </div>
              </div>
              <div style="font-weight: 700; color: #1e2d27; font-size: 13.5px;">
                Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
              </div>
            </div>
          @endforeach
        </div>

        {{-- PROMO KUPON LANGSUNG DI CHECKOUT --}}
        <div class="checkout-coupon-box">
          <div style="font-size: 12px; font-weight: 700; color: #1e2d27; margin-bottom: 8px; display: flex; align-items: center; justify-content: space-between;">
            <span>🎟️ Punya Kode Kupon Promo?</span>
            @if(!empty($appliedCoupon['code']))
              <span style="color: #15803d; font-weight: 700; font-size: 11px;">Kupon Aktif</span>
            @endif
          </div>

          @if(!empty($appliedCoupon['code']))
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 8px 12px; border-radius: 6px; display: flex; align-items: center; justify-content: space-between;">
              <div style="font-size: 12px; color: #166534;">
                <strong>{{ $appliedCoupon['code'] }}</strong>: Diskon Rp {{ number_format($discountAmount, 0, ',', '.') }}
              </div>
              <button type="button" onclick="removeCheckoutCoupon()" style="background: #ef4444; color: #fff; border: none; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: 700; cursor: pointer;">
                Hapus
              </button>
            </div>
          @else
            <div class="coupon-input-group">
              <input type="text" id="checkout_coupon_input" placeholder="Ketik kode kupon..." value="">
              <button type="button" class="coupon-btn" id="btn_apply_coupon" onclick="applyCheckoutCoupon()">Terapkan</button>
            </div>
          @endif
          <div id="checkout_coupon_msg" class="coupon-feedback"></div>
        </div>

        @php
          $freeMin = $shipSettings['free_shipping_min'] ?? 500000;
          $isFreeShipping = ($deliveryFee == 0 && $deliveryOption !== 'pickup');
          $neededForFree = $freeMin - $subtotal;
        @endphp

        @if($isFreeShipping)
          <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; padding: 10px 14px; border-radius: 6px; margin-bottom: 14px; font-size: 12px; font-weight: 600;">
            🎉 Selamat! Pesanan Anda memenuhi syarat <strong>GRATIS ONGKIR</strong> (Min. Rp {{ number_format($freeMin, 0, ',', '.') }}).
          </div>
        @elseif($deliveryOption !== 'pickup' && $neededForFree > 0)
          <div style="background: #fffbe6; border: 1px solid #ffe58f; color: #873800; padding: 10px 14px; border-radius: 6px; margin-bottom: 14px; font-size: 12px;">
            💡 Tambah pesanan senilai <strong>Rp {{ number_format($neededForFree, 0, ',', '.') }}</strong> lagi untuk mendapatkan <strong>GRATIS ONGKIR</strong>!
          </div>
        @endif

        {{-- RINCIAN BIAYA --}}
        <div class="summary-item">
          <span style="color: #64748b;">Subtotal Item</span>
          <span style="font-weight: 600;">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
        </div>

        @if(($discountAmount ?? 0) > 0)
          <div class="summary-item">
            <span style="color: #166534; font-weight: 600;">Diskon Kupon ({{ $appliedCoupon['code'] ?? '' }})</span>
            <span style="color: #166534; font-weight: 700;">-Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
          </div>
        @endif

        <div class="summary-item">
          <span style="color: #64748b;">Ongkos Kirim ({{ strtoupper($deliveryOption) }})</span>
          @if($deliveryFee == 0)
            <span style="color: #166534; font-weight: 700;" id="summary_delivery_fee">GRATIS (Rp 0)</span>
          @else
            <span style="font-weight: 600;" id="summary_delivery_fee">Rp {{ number_format($deliveryFee, 0, ',', '.') }}</span>
          @endif
        </div>

        <div class="summary-item total">
          <span>TOTAL PEMBAYARAN</span>
          <span style="color: #d97706; font-size: 19px;" id="summary_total_amount">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
        </div>

        <div style="margin-top: 18px; padding-top: 14px; border-top: 1px solid #f1f5f9; font-size: 11.5px; color: #64748b; line-height: 1.5; text-align: center;">
          🛡️ Pelayanan pesanan hantaran korporasi &amp; keluarga didukung tim packaging profesional.
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // 1. Template Ucapan Siap Pakai (Dinamis dari Admin / Default)
  @php
    $jsCards = $activeCards ?? ($greetingTemplates ?? \App\Http\Controllers\Admin\AdminGreetingCardController::getActiveTemplates());
  @endphp
  const greetingTemplates = @json(collect($jsCards)->pluck('message', 'id'));

  function applyTemplate(key) {
    const textarea = document.getElementById('card_message');
    if (!textarea) return;

    if (key === 'clear') {
      textarea.value = '';
    } else if (greetingTemplates && greetingTemplates[key]) {
      textarea.value = greetingTemplates[key];
    }
    updateCharCount();
    textarea.focus();
  }

  function updateCharCount() {
    const textarea = document.getElementById('card_message');
    const counter = document.getElementById('card_char_count');
    if (textarea && counter) {
      counter.textContent = textarea.value.length;
    }
  }

  // 2. Toggle Penerima Berbeda
  function toggleRecipientFields() {
    const checkbox = document.getElementById('is_gift_checkbox');
    const container = document.getElementById('different_recipient_container');
    if (!checkbox || !container) return;

    if (checkbox.checked) {
      container.style.display = 'block';
    } else {
      container.style.display = 'none';
    }
  }

  // 3. Visual Payment Card Selection
  function selectPaymentMethod(labelElement) {
    document.querySelectorAll('.payment-card-option').forEach(card => {
      card.classList.remove('active');
    });
    labelElement.classList.add('active');
    const radio = labelElement.querySelector('input[type="radio"]');
    if (radio) {
      radio.checked = true;
    }
  }

  // 3b. Interactive Courier Selection & Live Fee Calculation
  const checkoutSubtotal = {{ $subtotal }};
  const checkoutDiscount = {{ $discountAmount ?? 0 }};
  const isFreeShippingSubtotal = {{ ($subtotal >= ($shipSettings['free_shipping_min'] ?? 500000)) ? 'true' : 'false' }};

  function selectCourierOption(element, courierName, courierRate) {
    document.querySelectorAll('.courier-card-option').forEach(card => card.classList.remove('active'));
    element.classList.add('active');

    const radio = element.querySelector('input[type="radio"]');
    if (radio) radio.checked = true;

    const actualRate = isFreeShippingSubtotal ? 0 : courierRate;
    const feeElem = document.getElementById('summary_delivery_fee');
    if (feeElem) {
      if (actualRate === 0) {
        feeElem.innerHTML = '<span style="color: #166534; font-weight: 700;">GRATIS (Rp 0)</span>';
      } else {
        feeElem.innerHTML = 'Rp ' + actualRate.toLocaleString('id-ID');
      }
    }

    const totalElem = document.getElementById('summary_total_amount');
    if (totalElem) {
      const grandTotal = Math.max(0, checkoutSubtotal + actualRate - checkoutDiscount);
      totalElem.innerHTML = 'Rp ' + grandTotal.toLocaleString('id-ID');
    }
  }

  // 4. Anti Double-Submit & Loading State
  function handleCheckoutSubmit(e) {
    const btn = document.getElementById('btn_submit_order');
    if (btn) {
      btn.disabled = true;
      btn.style.opacity = '0.75';
      btn.style.cursor = 'not-allowed';
      btn.innerHTML = '<span class="checkout-spinner"></span> MEMPROSES PESANAN ANDA...';
    }
    return true;
  }

  // 5. Apply Coupon AJAX
  function applyCheckoutCoupon() {
    const input = document.getElementById('checkout_coupon_input');
    const msg = document.getElementById('checkout_coupon_msg');
    const btn = document.getElementById('btn_apply_coupon');
    if (!input || !msg) return;

    const code = input.value.trim();
    if (!code) {
      msg.style.display = 'block';
      msg.className = 'coupon-feedback error';
      msg.textContent = 'Silakan masukkan kode kupon terlebih dahulu.';
      return;
    }

    if (btn) {
      btn.disabled = true;
      btn.textContent = '...';
    }
    msg.style.display = 'block';
    msg.className = 'coupon-feedback info';
    msg.textContent = 'Memeriksa kupon...';

    fetch('{{ route("coupon.apply") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ code: code })
    })
    .then(res => res.json())
    .then(data => {
      if (btn) {
        btn.disabled = false;
        btn.textContent = 'Terapkan';
      }
      if (data.success) {
        msg.className = 'coupon-feedback success';
        msg.textContent = data.message;
        setTimeout(() => location.reload(), 600);
      } else {
        msg.className = 'coupon-feedback error';
        msg.textContent = data.message;
      }
    })
    .catch(() => {
      if (btn) {
        btn.disabled = false;
        btn.textContent = 'Terapkan';
      }
      msg.className = 'coupon-feedback error';
      msg.textContent = 'Terjadi kendala saat menerapkan kupon.';
    });
  }

  // 6. Remove Coupon AJAX
  function removeCheckoutCoupon() {
    const msg = document.getElementById('checkout_coupon_msg');
    if (msg) {
      msg.style.display = 'block';
      msg.className = 'coupon-feedback info';
      msg.textContent = 'Menghapus kupon...';
    }

    fetch('{{ route("coupon.remove") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      }
    })
    .then(res => res.json())
    .then(() => {
      location.reload();
    })
    .catch(() => {
      location.reload();
    });
  }

  // 7. Interactive Delivery Map & Geolocation System
  let checkoutMap = null;
  let checkoutMarker = null;
  let mapSearchDebounce = null;

  // Dual Focus Hub Quick Navigator (Surabaya & Bandung)
  function jumpToHub(hubKey) {
    const btnSby = document.getElementById('btn_hub_surabaya');
    const btnBdg = document.getElementById('btn_hub_bandung');

    let lat, lon, title, sub, cityName;
    if (hubKey === 'bandung') {
      lat = -6.9174639;
      lon = 107.6191228;
      title = '🏬 KOTA BANDUNG';
      sub = 'Jl. LLRE Martadinata (Riau) No. 68, Citarum, Kota Bandung';
      cityName = 'Kota Bandung';
      if (btnBdg) btnBdg.classList.add('active');
      if (btnSby) btnSby.classList.remove('active');
    } else {
      // Default Surabaya
      lat = -7.2574719;
      lon = 112.7520883;
      title = '🏬 KOTA SURABAYA';
      sub = 'Jl. Raya Darmo No. 45, Wonokromo, Kota Surabaya';
      cityName = 'Kota Surabaya';
      if (btnSby) btnSby.classList.add('active');
      if (btnBdg) btnBdg.classList.remove('active');
    }

    if (checkoutMap && checkoutMarker) {
      checkoutMap.flyTo([lat, lon], 14);
      checkoutMarker.setLatLng([lat, lon]);
      checkoutMarker.bindPopup('<b>' + title + '</b><br><small style="color: #64748b;">' + sub + '</small>').openPopup();
    }

    setPinCoordinates(lat, lon, true);

    const cityInput = document.getElementById('city');
    if (cityInput && (!cityInput.value.trim() || cityInput.dataset.autoFilled === '1')) {
      cityInput.value = cityName;
      cityInput.dataset.autoFilled = '1';
    }
  }

  // Pick Up Branch Selector
  function selectPickupBranch(branch) {
    const cardSby = document.getElementById('pickup_card_surabaya');
    const cardBdg = document.getElementById('pickup_card_bandung');
    const hiddenAddr = document.getElementById('pickup_store_address');

    if (branch === 'bandung') {
      if (cardBdg) cardBdg.classList.add('active');
      if (cardSby) cardSby.classList.remove('active');
      if (hiddenAddr) hiddenAddr.value = 'Store Pick Up: Butik Bandung (Jl. LLRE Martadinata No. 68, Citarum, Kota Bandung)';
    } else {
      if (cardSby) cardSby.classList.add('active');
      if (cardBdg) cardBdg.classList.remove('active');
      if (hiddenAddr) hiddenAddr.value = 'Store Pick Up: Butik Surabaya (Jl. Raya Darmo No. 45, Wonokromo, Kota Surabaya)';
    }
  }

  function initCheckoutMap() {
    const mapElement = document.getElementById('checkout_leaflet_map');
    if (!mapElement || typeof L === 'undefined') return;

    let initLat = parseFloat(document.getElementById('input_latitude').value) || -7.2574719;
    let initLon = parseFloat(document.getElementById('input_longitude').value) || 112.7520883;

    try {
      checkoutMap = L.map('checkout_leaflet_map', {
        scrollWheelZoom: false,
      }).setView([initLat, initLon], 14);

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
      }).addTo(checkoutMap);

      checkoutMarker = L.marker([initLat, initLon], {
        draggable: true
      }).addTo(checkoutMap);

      checkoutMarker.bindPopup('<b>📍 Titik Pengiriman</b><br><small style="color: #64748b;">Geser pin ini tepat ke depan rumah atau kantor Anda</small>');

      checkoutMarker.on('dragend', function() {
        const pos = checkoutMarker.getLatLng();
        setPinCoordinates(pos.lat, pos.lng, true);
      });

      checkoutMap.on('click', function(e) {
        checkoutMarker.setLatLng(e.latlng);
        setPinCoordinates(e.latlng.lat, e.latlng.lng, true);
      });

      // Update UI coords
      setPinCoordinates(initLat, initLon, false);

      setTimeout(function() {
        checkoutMap.invalidateSize();
      }, 500);
    } catch (e) {
      console.warn('Leaflet map init notice:', e);
    }
  }

  function setPinCoordinates(lat, lon, fetchAddress = true) {
    lat = parseFloat(lat);
    lon = parseFloat(lon);

    const inLat = document.getElementById('input_latitude');
    const inLon = document.getElementById('input_longitude');
    if (inLat) inLat.value = lat.toFixed(7);
    if (inLon) inLon.value = lon.toFixed(7);

    const coordsDisplay = document.getElementById('display_coords_val');
    if (coordsDisplay) {
      coordsDisplay.textContent = lat.toFixed(6) + ', ' + lon.toFixed(6);
    }

    const gmapsBtn = document.getElementById('btn_open_gmaps');
    if (gmapsBtn) {
      gmapsBtn.href = 'https://www.google.com/maps?q=' + lat + ',' + lon;
    }

    if (fetchAddress) {
      fetchReverseGeocode(lat, lon);
    }
  }

  function fetchReverseGeocode(lat, lon) {
    const addrText = document.getElementById('detected_address_text');
    if (addrText) addrText.textContent = 'Mendeteksi alamat dari titik peta...';

    fetch('{{ route("geocode.reverse") }}?lat=' + lat + '&lon=' + lon)
      .then(res => res.json())
      .then(data => {
        if (data && (data.status === 'success' || data.status === 'partial')) {
          const display = data.display_name || data.address_line || (lat.toFixed(6) + ', ' + lon.toFixed(6));
          if (addrText) addrText.textContent = display;

          const cityInput = document.getElementById('city');
          if (cityInput && (!cityInput.value.trim() || cityInput.dataset.autoFilled === '1')) {
            if (data.city) {
              cityInput.value = data.city;
              cityInput.dataset.autoFilled = '1';
            }
          }

          const postalInput = document.getElementById('postal_code');
          if (postalInput && (!postalInput.value.trim() || postalInput.dataset.autoFilled === '1')) {
            if (data.postal_code) {
              postalInput.value = data.postal_code;
              postalInput.dataset.autoFilled = '1';
            }
          }

          const syncBtn = document.getElementById('btn_sync_address');
          if (syncBtn) {
            syncBtn.onclick = function() {
              const addressTextarea = document.getElementById('address');
              if (addressTextarea) {
                const parts = [];
                if (data.address_line) parts.push(data.address_line);
                if (data.city && !data.address_line.includes(data.city)) parts.push(data.city);
                if (data.province && !data.address_line.includes(data.province)) parts.push(data.province);
                addressTextarea.value = parts.length > 0 ? parts.join(', ') : display;
                addressTextarea.focus();
              }
            };
          }

          // Auto-fill address if textarea is currently empty
          const addressArea = document.getElementById('address');
          if (addressArea && !addressArea.value.trim()) {
            const parts = [];
            if (data.address_line) parts.push(data.address_line);
            if (data.city && !data.address_line.includes(data.city)) parts.push(data.city);
            if (data.province && !data.address_line.includes(data.province)) parts.push(data.province);
            addressArea.value = parts.length > 0 ? parts.join(', ') : display;
          }
        }
      })
      .catch(() => {
        if (addrText) addrText.textContent = 'Titik Koordinat: ' + lat.toFixed(6) + ', ' + lon.toFixed(6);
      });
  }

  // Setup Map Event Listeners
  function setupMapControls() {
    // 1. Tombol GPS Lokasi Saat Ini
    const gpsBtn = document.getElementById('btn_get_current_location');
    if (gpsBtn) {
      gpsBtn.addEventListener('click', function() {
        if (!navigator.geolocation) {
          alert('Perangkat atau browser Anda tidak mendukung fitur Geolocation GPS.');
          return;
        }

        const gpsLabel = document.getElementById('gps_label');
        const originalText = gpsLabel ? gpsLabel.textContent : 'Gunakan Lokasi Saat Ini (GPS)';
        if (gpsLabel) gpsLabel.textContent = 'Mencari Koordinat GPS...';
        gpsBtn.disabled = true;

        navigator.geolocation.getCurrentPosition(
          function(pos) {
            const uLat = pos.coords.latitude;
            const uLon = pos.coords.longitude;
            if (gpsLabel) gpsLabel.textContent = '✅ Lokasi Anda Ditemukan!';

            if (checkoutMap && checkoutMarker) {
              checkoutMap.flyTo([uLat, uLon], 17);
              checkoutMarker.setLatLng([uLat, uLon]);
              checkoutMarker.openPopup();
            }
            setPinCoordinates(uLat, uLon, true);

            setTimeout(function() {
              if (gpsLabel) gpsLabel.textContent = originalText;
              gpsBtn.disabled = false;
            }, 2500);
          },
          function(err) {
            if (gpsLabel) gpsLabel.textContent = originalText;
            gpsBtn.disabled = false;
            let errMsg = 'Gagal mendeteksi lokasi GPS.';
            if (err.code === 1) {
              errMsg = 'Izin lokasi belum diberikan di browser Anda. Silakan izinkan akses lokasi atau gunakan kotak pencarian alamat/pilihan kota.';
            } else if (err.code === 2) {
              errMsg = 'Posisi GPS tidak dapat diperoleh saat ini. Silakan cari nama alamat atau pilih kota pada dropdown.';
            } else if (err.code === 3) {
              errMsg = 'Waktu permintaan lokasi GPS habis. Silakan coba kembali.';
            }
            alert(errMsg);
          },
          { enableHighAccuracy: true, timeout: 12000, maximumAge: 0 }
        );
      });
    }

    // 2. Pemilih Cepat Provinsi & Kota Utama
    const quickSelect = document.getElementById('select_province_quick');
    if (quickSelect) {
      quickSelect.addEventListener('change', function() {
        const val = this.value;
        if (!val) return;
        const parts = val.split('|');
        const coords = parts[0].split(',');
        const lat = parseFloat(coords[0]);
        const lon = parseFloat(coords[1]);
        const cityName = parts[1] || '';

        if (checkoutMap && checkoutMarker) {
          checkoutMap.flyTo([lat, lon], 14);
          checkoutMarker.setLatLng([lat, lon]);
          checkoutMarker.openPopup();
        }
        setPinCoordinates(lat, lon, true);

        const cityInput = document.getElementById('city');
        if (cityInput && cityName) {
          cityInput.value = cityName.split('/')[0].trim();
        }
      });
    }

    // 3. Pencarian Alamat Se-Indonesia dengan Rekomendasi Live Sesuai Kata yang Diketik
    const searchInput = document.getElementById('map_search_input');
    const searchBtn = document.getElementById('btn_map_search');
    const resultsDiv = document.getElementById('map_search_results');
    const noticeDiv = document.getElementById('map_smart_notice');
    const noticeText = document.getElementById('map_smart_notice_text');

    const INDONESIA_RECOMMENDATIONS = [
      // KOTA SURABAYA & SEKITARNYA (JAWA TIMUR)
      { name: "Kota Surabaya", sub: "Pusat Kota (Darmo / Gubeng / Basuki Rahmat), Jawa Timur", lat: -7.2575, lon: 112.7521, type: "Surabaya Pusat", keywords: ["surabaya", "kota surabaya", "darmo", "gubeng", "basuki rahmat", "jatim"] },
      { name: "Surabaya Barat", sub: "Pakuwon Mall / Citraland / HR Muhammad, Surabaya", lat: -7.2886, lon: 112.6756, type: "Surabaya Barat", keywords: ["pakuwon", "citraland", "hr muhammad", "mayjend sungkono", "surabaya barat"] },
      { name: "Surabaya Timur", sub: "Rungkut / Mulyorejo / Sukolilo / Merr, Surabaya", lat: -7.3185, lon: 112.7758, type: "Surabaya Timur", keywords: ["rungkut", "mulyorejo", "sukolilo", "merr", "surabaya timur", "galaxy mall"] },
      { name: "Surabaya Selatan", sub: "Wonokromo / Gayungan / A. Yani, Surabaya", lat: -7.2892, lon: 112.7342, type: "Surabaya Selatan", keywords: ["wonokromo", "gayungan", "a yani", "jambangan", "surabaya selatan"] },
      { name: "Surabaya Utara", sub: "Perak / Kenjeran / Ampel, Surabaya", lat: -7.2285, lon: 112.7512, type: "Surabaya Utara", keywords: ["kenjeran", "perak", "ampel", "surabaya utara"] },
      { name: "Kabupaten Sidoarjo", sub: "Waru / Gedangan / Alun-alun Kota Sidoarjo", lat: -7.4478, lon: 112.7183, type: "Sidoarjo", keywords: ["sidoarjo", "waru", "gedangan"] },
      { name: "Kabupaten Gresik", sub: "Kebomas / Manyar / Kota Gresik", lat: -7.1566, lon: 112.6555, type: "Gresik", keywords: ["gresik", "kebomas", "manyar"] },

      // KOTA BANDUNG & SEKITARNYA (JAWA BARAT)
      { name: "Kota Bandung", sub: "Pusat Kota (Jl. Riau / Asia Afrika / Braga), Jawa Barat", lat: -6.9175, lon: 107.6191, type: "Bandung Pusat", keywords: ["bandung", "kota bandung", "riau", "martadinata", "asia afrika", "braga", "jabar"] },
      { name: "Bandung Dago", sub: "Coblong / Dipatiukur / ITB, Kota Bandung", lat: -6.8833, lon: 107.6167, type: "Bandung Utara", keywords: ["dago", "coblong", "dipatiukur", "itb", "bandung utara"] },
      { name: "Bandung Sukajadi", sub: "Pasteur / PVJ / Setiabudi, Kota Bandung", lat: -6.8924, lon: 107.5982, type: "Bandung Barat", keywords: ["sukajadi", "pasteur", "pvj", "setiabudi", "paris van java"] },
      { name: "Bandung Buahbatu", sub: "Batununggal / Lengkong, Kota Bandung", lat: -6.9532, lon: 107.6358, type: "Bandung Selatan", keywords: ["buahbatu", "buah batu", "batununggal", "lengkong", "bandung selatan"] },
      { name: "Bandung Antapani", sub: "Arcamanik / Cisaranten, Kota Bandung", lat: -6.9167, lon: 107.6667, type: "Bandung Timur", keywords: ["antapani", "arcamanik", "cisaranten", "bandung timur"] },
      { name: "Lembang", sub: "Bandung Barat, Jawa Barat", lat: -6.8167, lon: 107.6167, type: "Lembang", keywords: ["lembang", "bandung barat"] },
      { name: "Kota Cimahi", sub: "Jawa Barat", lat: -6.8722, lon: 107.5422, type: "Cimahi", keywords: ["cimahi", "kota cimahi"] },
    ];

    function highlightMatch(text, query) {
      if (!query || !text) return text || '';
      const cleanQ = query.trim().replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
      const words = cleanQ.split(/\s+/).filter(w => w.length > 0);
      if (words.length === 0) return text;
      const regex = new RegExp('(' + words.join('|') + ')', 'gi');
      return text.replace(regex, '<span class="map-match-highlight">$1</span>');
    }

    function selectLocationItem(item) {
      if (!item) return;

      if (checkoutMap && checkoutMarker) {
        checkoutMap.flyTo([item.lat, item.lon], 16);
        checkoutMarker.setLatLng([item.lat, item.lon]);
        checkoutMarker.openPopup();
      }

      setPinCoordinates(item.lat, item.lon, true);

      if (searchInput) {
        searchInput.value = item.name || (item.display_name ? item.display_name.split(',')[0] : '');
      }

      if (resultsDiv) {
        resultsDiv.style.display = 'none';
      }

      const cityInput = document.getElementById('city');
      if (cityInput && item.name) {
        cityInput.value = item.name.split('/')[0].trim();
      }
    }

    function renderDefaultPopularPills() {
      if (!resultsDiv) return;

      resultsDiv.innerHTML = '';
      resultsDiv.style.display = 'block';

      const header = document.createElement('div');
      header.className = 'map-dropdown-header';
      header.innerHTML = '<span>✨ REKOMENDASI POPULER (KLIK LANGSUNG):</span>';
      resultsDiv.appendChild(header);

      const pillsRow = document.createElement('div');
      pillsRow.className = 'map-quick-pills-row';

      const popularItems = [
        { name: "🏬 Surabaya (Darmo)", lat: -7.2575, lon: 112.7521 },
        { name: "🏬 Bandung (Riau)", lat: -6.9175, lon: 107.6191 },
        { name: "📍 Pakuwon Sby", lat: -7.2886, lon: 112.6756 },
        { name: "📍 Dago Bandung", lat: -6.8833, lon: 107.6167 },
        { name: "📍 Rungkut Sby", lat: -7.3185, lon: 112.7758 },
        { name: "📍 Sukajadi Bdg", lat: -6.8924, lon: 107.5982 },
        { name: "📍 Sidoarjo", lat: -7.4478, lon: 112.7183 },
        { name: "📍 Lembang", lat: -6.8167, lon: 107.6167 },
        { name: "📍 Buahbatu Bdg", lat: -6.9532, lon: 107.6358 },
        { name: "📍 Kota Cimahi", lat: -6.8722, lon: 107.5422 },
      ];

      popularItems.forEach(function(pop) {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'map-quick-pill';
        btn.innerHTML = '<span>📍</span> <span>' + pop.name + '</span>';
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          e.stopPropagation();
          selectLocationItem({ name: pop.name, lat: pop.lat, lon: pop.lon });
        });
        pillsRow.appendChild(btn);
      });

      resultsDiv.appendChild(pillsRow);
    }

    function showRecommendationsForQuery(q) {
      if (!resultsDiv) return;

      const cleanQ = (q || '').trim().toLowerCase();
      if (!cleanQ) {
        renderDefaultPopularPills();
        return;
      }

      resultsDiv.innerHTML = '';
      resultsDiv.style.display = 'block';

      // 1. Filter rekomendasi lokal instan (0ms latency)
      const qTokens = cleanQ.split(/\s+/).filter(t => t.length > 0);
      const matchedLocal = INDONESIA_RECOMMENDATIONS.filter(function(place) {
        const fullString = (place.name + ' ' + place.sub + ' ' + (place.keywords || []).join(' ')).toLowerCase();
        return qTokens.every(token => fullString.includes(token)) ||
               place.name.toLowerCase().includes(cleanQ);
      });

      const header = document.createElement('div');
      header.className = 'map-dropdown-header';
      header.innerHTML = `<span>📍 REKOMENDASI LOKASI ("${q}"):</span><span id="recom_status_text">${matchedLocal.length} Ditemukan</span>`;
      resultsDiv.appendChild(header);

      const itemsContainer = document.createElement('div');
      itemsContainer.id = 'recom_items_list';

      matchedLocal.slice(0, 8).forEach(function(item) {
        const itemDiv = document.createElement('div');
        itemDiv.className = 'map-result-item';
        itemDiv.innerHTML = `
          <div class="map-result-icon">📍</div>
          <div class="map-result-content">
            <div class="map-result-name">${highlightMatch(item.name, q)}</div>
            <div class="map-result-detail">${highlightMatch(item.sub, q)}</div>
          </div>
          <div class="map-result-badge">${item.type || 'Wilayah'}</div>
        `;

        itemDiv.addEventListener('click', function(e) {
          e.preventDefault();
          e.stopPropagation();
          selectLocationItem(item);
        });

        itemsContainer.appendChild(itemDiv);
      });

      resultsDiv.appendChild(itemsContainer);

      // Loading indicator for background geocode API
      const loadingFooter = document.createElement('div');
      loadingFooter.id = 'recom_loading_footer';
      loadingFooter.style.padding = '8px 14px';
      loadingFooter.style.fontSize = '11px';
      loadingFooter.style.color = '#64748b';
      loadingFooter.style.background = '#f8fafc';
      loadingFooter.style.borderTop = '1px solid #f1f5f9';
      loadingFooter.style.display = 'flex';
      loadingFooter.style.alignItems = 'center';
      loadingFooter.style.gap = '6px';
      loadingFooter.innerHTML = '<span>⏳</span> <span>Mencari titik alamat di Surabaya &amp; Bandung...</span>';
      resultsDiv.appendChild(loadingFooter);

      // 2. Fetch live data from OpenStreetMap Nominatim proxy API
      fetch('{{ route("geocode.search") }}?q=' + encodeURIComponent(q))
        .then(res => res.json())
        .then(data => {
          if (loadingFooter) loadingFooter.remove();

          if (data && data.notice && noticeDiv && noticeText) {
            noticeText.textContent = data.notice;
            noticeDiv.style.display = 'flex';
          }

          if (data && data.results && data.results.length > 0) {
            data.results.forEach(function(apiItem) {
              // Avoid duplicate if coordinates already in local
              const isDupe = matchedLocal.some(loc => Math.abs(loc.lat - apiItem.lat) < 0.001 && Math.abs(loc.lon - apiItem.lon) < 0.001);
              if (!isDupe) {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'map-result-item';
                const mainName = apiItem.name || apiItem.display_name.split(',')[0];
                itemDiv.innerHTML = `
                  <div class="map-result-icon">🗺️</div>
                  <div class="map-result-content">
                    <div class="map-result-name">${highlightMatch(mainName, q)}</div>
                    <div class="map-result-detail">${highlightMatch(apiItem.display_name, q)}</div>
                  </div>
                  <div class="map-result-badge">${apiItem.type || 'Peta'}</div>
                `;

                itemDiv.addEventListener('click', function(e) {
                  e.preventDefault();
                  e.stopPropagation();
                  selectLocationItem({
                    name: mainName,
                    lat: apiItem.lat,
                    lon: apiItem.lon,
                    display_name: apiItem.display_name
                  });
                });

                itemsContainer.appendChild(itemDiv);
              }
            });
          }
        })
        .catch(() => {
          if (loadingFooter) loadingFooter.remove();
        });
    }

    if (searchBtn) {
      searchBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const q = searchInput ? searchInput.value.trim() : '';
        if (q) {
          showRecommendationsForQuery(q);
        }
      });
    }

    if (searchInput) {
      // Focus: Tampilkan rekomendasi populer jika kosong atau rekomendasi sesuai kata
      searchInput.addEventListener('focus', function() {
        const q = this.value.trim();
        showRecommendationsForQuery(q);
      });

      // Input: Tampilkan rekomendasi langsung sesuai kata yang diketik (responsif instan)
      searchInput.addEventListener('input', function() {
        clearTimeout(mapSearchDebounce);
        const q = this.value;
        mapSearchDebounce = setTimeout(function() {
          showRecommendationsForQuery(q);
        }, 180);
      });

      // Keydown: Navigasi keyboard & Enter
      searchInput.addEventListener('keydown', function(e) {
        if (!resultsDiv || resultsDiv.style.display === 'none') return;

        const items = resultsDiv.querySelectorAll('.map-result-item');
        let activeIndex = -1;
        items.forEach((it, idx) => {
          if (it.classList.contains('keyboard-active')) activeIndex = idx;
        });

        if (e.key === 'ArrowDown') {
          e.preventDefault();
          if (items.length > 0) {
            if (activeIndex >= 0) items[activeIndex].classList.remove('keyboard-active');
            activeIndex = (activeIndex + 1) % items.length;
            items[activeIndex].classList.add('keyboard-active');
            items[activeIndex].scrollIntoView({ block: 'nearest' });
          }
        } else if (e.key === 'ArrowUp') {
          e.preventDefault();
          if (items.length > 0) {
            if (activeIndex >= 0) items[activeIndex].classList.remove('keyboard-active');
            activeIndex = (activeIndex - 1 + items.length) % items.length;
            items[activeIndex].classList.add('keyboard-active');
            items[activeIndex].scrollIntoView({ block: 'nearest' });
          }
        } else if (e.key === 'Enter') {
          if (activeIndex >= 0 && items[activeIndex]) {
            e.preventDefault();
            items[activeIndex].click();
          } else {
            e.preventDefault();
            const firstItem = items[0];
            if (firstItem) firstItem.click();
          }
        } else if (e.key === 'Escape') {
          resultsDiv.style.display = 'none';
        }
      });
    }

    // Klik di luar dropdown untuk menutup daftar rekomendasi
    document.addEventListener('click', function(e) {
      if (resultsDiv && !resultsDiv.contains(e.target) && e.target !== searchInput && e.target !== searchBtn) {
        resultsDiv.style.display = 'none';
      }
    });
  }

  // Initial call on page load
  document.addEventListener('DOMContentLoaded', function() {
    updateCharCount();
    initCheckoutMap();
    setupMapControls();
  });
</script>
@endsection
