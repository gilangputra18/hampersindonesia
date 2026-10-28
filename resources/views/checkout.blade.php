@extends('layouts.app')

@section('title', 'Checkout Pesanan Hampers | ' . config('site.brand'))

@section('content')
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

          {{-- Status Akun --}}
          @if(auth()->check())
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px 16px; border-radius: 8px; margin-bottom: 22px; font-size: 13px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
              <div>
                <strong>👤 Akun Terhubung:</strong> {{ auth()->user()->name }} ({{ auth()->user()->email }})
              </div>
              <span style="background: #166534; color: #fff; padding: 2px 10px; border-radius: 12px; font-size: 11px; font-weight: 700;">VIP MEMBER</span>
            </div>
          @else
            <div style="background: #fffbe6; border: 1px solid #ffe58f; color: #873800; padding: 12px 16px; border-radius: 8px; margin-bottom: 22px; font-size: 12.5px;">
              ℹ️ Anda memesan sebagai <strong>Tamu (Pemesanan Tanpa Akun)</strong>. Ingin riwayat pesanan tersimpan rapi? <a href="{{ route('login') }}" style="color: #d97706; font-weight: 700; text-decoration: underline;">Masuk di sini</a> sebelum melanjutkan.
            </div>
          @endif

          {{-- DATA PEMESAN --}}
          <div class="form-group">
            <label for="customer_name">Nama Lengkap Pemesan <span style="color: #dc2626;">*</span></label>
            <input type="text" name="customer_name" id="customer_name" class="form-control" value="{{ old('customer_name', auth()->check() ? auth()->user()->name : '') }}" placeholder="Contoh: Budi Santoso" required>
          </div>

          <div class="checkout-input-grid">
            <div class="form-group">
              <label for="customer_phone">No. WhatsApp / HP Pemesan <span style="color: #dc2626;">*</span></label>
              <input type="tel" name="customer_phone" id="customer_phone" class="form-control" value="{{ old('customer_phone', auth()->check() ? (auth()->user()->phone ?? '') : '') }}" placeholder="081234567890" required>
            </div>

            <div class="form-group">
              <label for="customer_email">Alamat Email (Untuk Faktur Resmi)</label>
              <input type="email" name="customer_email" id="customer_email" class="form-control" value="{{ old('customer_email', auth()->check() ? auth()->user()->email : '') }}" placeholder="email@contoh.com">
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
            <div class="form-group">
              <label>Ekspedisi / Kurir Pengantar</label>
              <input type="hidden" name="courier_name" value="{{ old('courier_name', $courierName) }}">
              <div style="padding: 12px 16px; background: #f8faf9; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13.5px; color: #1e2d27; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                @php
                  $courierIcons = [
                    'Kurir Toko (Dedicated Bakery Delivery)' => '🥖',
                    'JNE Express (Reguler/YES)' => '📦',
                    'J&T Express' => '🚚',
                    'GoSend / GrabExpress (Instant)' => '⚡',
                    'Ambil Langsung di Butik Toko' => '🏪',
                  ];
                  $icon = $courierIcons[$courierName] ?? '🚚';
                @endphp
                <span>{{ $icon }}</span>
                <span>{{ old('courier_name', $courierName) }}</span>
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

            {{-- ALAMAT PENGIRIMAN LENGKAP --}}
            <div class="form-group">
              <label for="address">Alamat Pengiriman Lengkap <span style="color: #dc2626;">*</span></label>
              <textarea name="address" id="address" class="form-control" rows="3" placeholder="Nama Jalan, Nomor Rumah, RT/RW, Komplek / Gedung, Kelurahan, Kecamatan" required>{{ old('address') }}</textarea>
            </div>

            <div class="checkout-input-grid">
              <div class="form-group">
                <label for="city">Kota / Kabupaten</label>
                <input type="text" name="city" id="city" class="form-control" value="{{ old('city') }}" placeholder="Contoh: Jakarta Selatan">
              </div>

              <div class="form-group">
                <label for="postal_code">Kode Pos</label>
                <input type="text" name="postal_code" id="postal_code" class="form-control" value="{{ old('postal_code') }}" placeholder="Contoh: 12180">
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
              <label>Lokasi Pick Up Toko</label>
              <div style="padding: 14px 16px; background: #f1f5f9; border-radius: 8px; font-size: 13px; color: #475569; line-height: 1.5;">
                📍 <strong>PUSAT HAMPERS INDONESIA Boutique Store:</strong><br>
                Jl. Cikajang V No. 12, Kebayoran Baru, Jakarta Selatan.<br>
                <span style="font-size: 11.5px; color: #64748b; margin-top: 4px; display: block;">⏰ Jam operasional pengambilan: 08:00 - 20:00 WIB</span>
              </div>
              <input type="hidden" name="address" value="Store Pick up (Jl. Cikajang V No. 12, Kebayoran Baru, Jakarta Selatan)">
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
            <span style="color: #166534; font-weight: 700;">GRATIS (Rp 0)</span>
          @else
            <span style="font-weight: 600;">Rp {{ number_format($deliveryFee, 0, ',', '.') }}</span>
          @endif
        </div>

        <div class="summary-item total">
          <span>TOTAL PEMBAYARAN</span>
          <span style="color: #d97706; font-size: 19px;">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
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

  // Initial call on page load
  document.addEventListener('DOMContentLoaded', function() {
    updateCharCount();
  });
</script>
@endsection
