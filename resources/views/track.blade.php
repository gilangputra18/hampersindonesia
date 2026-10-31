@extends('layouts.app')

@section('title', 'Lacak Pesanan Real-Time | ' . config('site.brand'))

@section('content')
<style>
  /* ================= LUXURY BURGUNDY, GOLD & WARM IVORY THEME ================= */
  .track-page {
    background-color: #faf7f4;
    background-image: 
      radial-gradient(circle at 10% 15%, rgba(114, 47, 55, 0.03) 0%, transparent 40%),
      radial-gradient(circle at 90% 85%, rgba(230, 202, 101, 0.05) 0%, transparent 40%);
    color: #1e293b;
    padding: 40px 20px 90px;
    min-height: 85vh;
    font-family: 'Outfit', sans-serif;
  }
  .track-container {
    max-width: 920px;
    margin: 0 auto;
  }

  /* 1. Header Hero Card */
  .track-hero-card {
    background: linear-gradient(135deg, #380a17 0%, #1a0207 60%, #4a0d1f 100%);
    border: 1px solid rgba(230, 202, 101, 0.45);
    border-radius: 20px;
    padding: 38px 28px;
    text-align: center;
    margin-bottom: 28px;
    box-shadow: 0 15px 40px rgba(56, 10, 23, 0.18), inset 0 1px 0 rgba(255, 255, 255, 0.15);
    position: relative;
    overflow: hidden;
  }
  .track-hero-card::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle at 50% 25%, rgba(230, 202, 101, 0.14) 0%, transparent 60%);
    pointer-events: none;
  }
  .hero-crown-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(230, 202, 101, 0.15);
    border: 1px solid rgba(230, 202, 101, 0.4);
    color: #fef08a;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    padding: 5px 16px;
    border-radius: 30px;
    margin-bottom: 14px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
  }
  .track-title {
    font-family: 'Playfair Display', 'Cinzel', serif;
    font-size: 34px;
    font-weight: 700;
    letter-spacing: 2px;
    color: #ffffff;
    margin: 0 0 10px;
  }
  .track-title .gold-accent {
    background: linear-gradient(135deg, #ffffff 0%, #fef08a 45%, #e6ca65 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }
  .track-sub {
    font-size: 13.5px;
    color: #f2d6df;
    letter-spacing: 1px;
    max-width: 640px;
    margin: 0 auto;
    line-height: 1.5;
  }

  /* 2. Floating Luxury Search Card */
  .track-search-card {
    background: #ffffff;
    border: 1.5px solid #ecd5dc;
    border-radius: 16px;
    padding: 28px 32px;
    box-shadow: 0 10px 30px rgba(114, 47, 55, 0.06);
    margin-bottom: 32px;
    transition: all 0.25s ease;
  }
  .track-search-card:hover {
    border-color: #722F37;
    box-shadow: 0 14px 38px rgba(114, 47, 55, 0.1);
  }
  .search-label {
    font-size: 12.5px;
    font-weight: 700;
    color: #722F37;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 12px;
    text-transform: uppercase;
  }
  .track-input-group {
    display: flex;
    gap: 12px;
  }
  .track-input {
    flex: 1;
    padding: 14px 18px;
    background: #faf7f8;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    color: #1e293b;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.2s ease;
  }
  .track-input:focus {
    outline: none;
    background: #ffffff;
    border-color: #722F37;
    box-shadow: 0 0 0 4px rgba(114, 47, 55, 0.1);
  }
  .track-input::placeholder {
    color: #94a3b8;
    font-weight: 400;
  }
  .track-btn-submit {
    padding: 14px 30px;
    background: linear-gradient(135deg, #722F37 0%, #4a0d1f 100%);
    color: #ffffff;
    font-family: 'Outfit', sans-serif;
    font-weight: 800;
    font-size: 13px;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    border: 1px solid rgba(230, 202, 101, 0.4);
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.25s ease;
    box-shadow: 0 6px 20px rgba(114, 47, 55, 0.22);
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }
  .track-btn-submit:hover {
    transform: translateY(-2px);
    background: linear-gradient(135deg, #873843 0%, #591226 100%);
    box-shadow: 0 10px 26px rgba(114, 47, 55, 0.32);
  }

  /* Sample Pills */
  .sample-pills {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 16px;
    flex-wrap: wrap;
    font-size: 12px;
    color: #64748b;
    padding-top: 14px;
    border-top: 1px dashed #e2e8f0;
  }
  .sample-pill {
    background: #fdf2f4;
    border: 1px solid #f3d2d9;
    color: #722F37;
    padding: 4px 12px;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    font-weight: 600;
    font-size: 11.5px;
  }
  .sample-pill:hover {
    background: #722F37;
    color: #ffffff;
    border-color: #722F37;
    transform: translateY(-1px);
  }

  /* 3. Order Result Luxury Card */
  .order-result-card {
    background: #ffffff;
    border: 1.5px solid #ecd5dc;
    border-radius: 18px;
    padding: 36px;
    box-shadow: 0 12px 35px rgba(114, 47, 55, 0.07);
    margin-bottom: 40px;
  }
  .order-meta-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 16px;
    padding-bottom: 22px;
    border-bottom: 1.5px solid #f1f5f9;
    margin-bottom: 28px;
  }
  .inv-subtitle {
    font-size: 11.5px;
    color: #722F37;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 4px;
  }
  .inv-number {
    font-family: 'Playfair Display', serif;
    font-size: 26px;
    color: #1e293b;
    font-weight: 800;
    letter-spacing: 0.5px;
  }
  .resi-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #fefce8;
    border: 1px solid #fef08a;
    color: #854d0e;
    font-size: 13px;
    font-family: monospace;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 6px;
    margin-top: 6px;
  }
  .status-badge-group {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
  }
  .badge-status {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 800;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
    gap: 5px;
  }
  .status-completed { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }
  .status-processing { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
  .status-shipping { background: #e0f2fe; color: #0369a1; border: 1px solid #7dd3fc; }
  .status-pending { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
  .status-cancelled { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }

  /* 4. 4-Step Stepper Progress Bar */
  .tracker-stepper {
    display: flex;
    justify-content: space-between;
    position: relative;
    margin: 36px 0 44px;
  }
  .tracker-stepper::before {
    content: '';
    position: absolute;
    top: 22px;
    left: 40px;
    right: 40px;
    height: 3px;
    background: #e2e8f0;
    z-index: 1;
  }
  .tracker-progress-line {
    position: absolute;
    top: 22px;
    left: 40px;
    height: 3px;
    background: linear-gradient(90deg, #722F37 0%, #d97706 100%);
    z-index: 1;
    transition: width 0.6s ease;
  }
  .step-node {
    position: relative;
    z-index: 2;
    text-align: center;
    flex: 1;
  }
  .step-icon {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    background: #ffffff;
    border: 2px solid #cbd5e1;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    font-size: 18px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 6px rgba(0,0,0,0.04);
  }
  .step-node.active .step-icon {
    background: linear-gradient(135deg, #722F37 0%, #4a0d1f 100%);
    border-color: #e6ca65;
    color: #ffffff;
    box-shadow: 0 0 16px rgba(114, 47, 55, 0.3);
    transform: scale(1.12);
  }
  .step-node.done .step-icon {
    background: linear-gradient(135deg, #15803d 0%, #166534 100%);
    border-color: #86efac;
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(22, 128, 61, 0.2);
  }
  .step-label {
    font-size: 12.5px;
    font-weight: 600;
    color: #64748b;
    line-height: 1.35;
  }
  .step-node.active .step-label {
    color: #722F37;
    font-weight: 700;
  }
  .step-node.done .step-label {
    color: #166534;
    font-weight: 600;
  }

  /* 5. In-Transit Courier Active Card */
  .shipping-active-banner {
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border: 2px solid #38bdf8;
    border-radius: 14px;
    padding: 24px;
    margin-bottom: 30px;
    box-shadow: 0 8px 25px rgba(2, 132, 199, 0.12);
  }

  /* 6. Info Customer & Delivery Grid */
  .info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    background: #faf7f8;
    padding: 22px;
    border-radius: 12px;
    border: 1px solid #ebdbe0;
    margin-bottom: 30px;
  }
  .info-item label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #722F37;
    font-weight: 700;
    display: block;
    margin-bottom: 4px;
  }
  .info-item span {
    font-size: 13.5px;
    color: #1e293b;
    font-weight: 600;
  }

  /* 7. Items Table & Totals */
  .items-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 28px;
  }
  .items-table th {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #722F37;
    background: #fdf2f4;
    padding: 12px 14px;
    font-weight: 700;
    border-bottom: 1px solid #ecd5dc;
  }
  .items-table td {
    padding: 14px;
    text-align: left;
    border-bottom: 1px solid #f1f5f9;
    font-size: 13.5px;
    color: #334155;
  }
  .item-thumb {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    object-fit: cover;
    border: 1px solid #ecd5dc;
  }
  .totals-box {
    margin-left: auto;
    max-width: 340px;
    background: #fdfafb;
    padding: 20px 22px;
    border-radius: 12px;
    border: 1.5px solid #ecd5dc;
  }
  .total-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
    font-size: 13px;
    color: #64748b;
  }
  .total-row.grand {
    font-size: 17px;
    font-weight: 800;
    color: #722F37;
    border-top: 1.5px solid #ecd5dc;
    padding-top: 12px;
    margin-top: 10px;
    margin-bottom: 0;
  }

  /* 8. Action Buttons */
  .track-action-btn {
    padding: 12px 22px;
    border-radius: 8px;
    font-size: 12.5px;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
  }
  .btn-wa-live {
    background: #15803d;
    color: #ffffff !important;
    border: 1px solid #22c55e;
    box-shadow: 0 4px 12px rgba(21, 128, 61, 0.25);
  }
  .btn-wa-live:hover {
    background: #166534;
    transform: translateY(-1px);
  }
  .btn-print-receipt {
    background: #ffffff;
    color: #722F37;
    border: 1.5px solid #ecd5dc;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
  }
  .btn-print-receipt:hover {
    background: #fdf2f4;
    border-color: #722F37;
  }

  /* Responsive Adjustments */
  @media (max-width: 640px) {
    .track-page { padding: 20px 12px 60px; }
    .track-hero-card { padding: 26px 18px; border-radius: 14px; margin-bottom: 20px; }
    .track-title { font-size: 23px; letter-spacing: 1px; }
    .track-sub { font-size: 12px; }
    .track-search-card { padding: 20px 16px; border-radius: 12px; margin-bottom: 20px; }
    .track-input-group { flex-direction: column; }
    .track-btn-submit { width: 100%; justify-content: center; }
    .order-result-card { padding: 20px 14px; border-radius: 14px; }
    .inv-number { font-size: 20px; }
    .tracker-stepper { margin: 24px 0 32px; }
    .tracker-stepper::before, .tracker-progress-line { top: 18px; left: 20px; right: 20px; }
    .step-icon { width: 36px; height: 36px; font-size: 14px; }
    .step-label { font-size: 10px; }
    .info-grid { grid-template-columns: 1fr; padding: 16px 14px; }
    .totals-box { max-width: 100%; }
  }
</style>

<div class="track-page">
  <div class="track-container">
    
    <!-- 1. Royal Burgundy & Champagne Gold Hero Card -->
    <div class="track-hero-card">
      <div class="hero-crown-badge">
        <span>👑</span> <span>SISTEM PELACAKAN RESMI</span>
      </div>
      <h1 class="track-title">
        <span>📦</span> <span class="gold-accent">LACAK PESANAN REAL-TIME</span>
      </h1>
      <p class="track-sub">
        Pantau setiap tahapan pemanggangan, pengemasan hampers butik, hingga paket tiba di tangan penerima dengan aman &amp; presisi.
      </p>
    </div>

    <!-- 2. Clean Luxury Search Form Card -->
    <div class="track-search-card">
      <form action="{{ route('track') }}" method="GET">
        <label for="invInput" class="search-label">
          <span>🔍</span> <span>Masukkan Nomor Invoice atau Resi Pembelian Anda:</span>
        </label>
        <div class="track-input-group">
          <input type="text" id="invInput" name="invoice" class="track-input" placeholder="contoh: INV-20260922-RYEB atau KUR-20260929-8812" value="{{ $invoice }}" required>
          <button type="submit" class="track-btn-submit">
            <span>🚀 Lacak Pesanan</span>
          </button>
        </div>
      </form>

      @if(!empty($latestSampleOrders) && $latestSampleOrders->count() > 0)
        <div class="sample-pills">
          <span style="font-weight: 700; color: #722F37;">💡 Contoh Lacak Cepat:</span>
          @foreach($latestSampleOrders as $sample)
            <a href="{{ route('track') }}?invoice={{ $sample->invoice_number }}" class="sample-pill">
              {{ $sample->invoice_number }} ({{ $sample->customer_name }})
            </a>
          @endforeach
        </div>
      @endif
    </div>

    <!-- 3. Order Tracking Result Section -->
    @if($order)
      @php
        // Calculate Progress Step (1: Pending, 2: Processing, 3: Shipping, 4: Completed)
        $step = 1;
        $progressWidth = '0%';
        if ($order->order_status == 'pending') {
            $step = 1;
            $progressWidth = '15%';
        } elseif ($order->order_status == 'processing') {
            $step = 2;
            $progressWidth = '45%';
        } elseif ($order->order_status == 'shipping') {
            $step = 3;
            $progressWidth = '75%';
        } elseif ($order->order_status == 'completed') {
            $step = 4;
            $progressWidth = '100%';
        } elseif ($order->order_status == 'cancelled') {
            $step = 0;
            $progressWidth = '0%';
        }
      @endphp

      <div class="order-result-card">
        <div class="order-meta-header">
          <div>
            <div class="inv-subtitle">Nomor Invoice &amp; Resi Pengiriman:</div>
            <div class="inv-number">#{{ $order->invoice_number }}</div>
            @if($order->tracking_number)
              <div class="resi-badge">
                <span>🏷️ RESI:</span> <span>{{ $order->tracking_number }}</span>
              </div>
            @endif
            <div style="font-size: 12px; color: #64748b; margin-top: 6px;">
              📅 Tanggal Reservasi: <strong>{{ $order->created_at->format('d M Y, H:i') }} WIB</strong>
            </div>
          </div>

          <div class="status-badge-group">
            <span class="badge-status status-{{ $order->order_status }}">
              📌 Status: {{ $order->order_status === 'shipping' ? 'DIKIRIM KURIR' : strtoupper($order->order_status) }}
            </span>
            <span class="badge-status {{ $order->payment_status == 'paid' ? 'status-completed' : 'status-processing' }}">
              💳 {{ $order->payment_status == 'paid' ? 'LUNAS' : strtoupper($order->payment_status) }}
            </span>
          </div>
        </div>

        @if($order->order_status != 'cancelled')
          <!-- 4-Step Live Tracking Progress Stepper -->
          <div style="margin-bottom: 8px; font-size: 13px; font-weight: 700; color: #722F37; text-transform: uppercase; letter-spacing: 0.5px;">
            🚦 Progres Pelacakan Pesanan:
          </div>

          <div class="tracker-stepper">
            <div class="tracker-progress-line" style="width: {{ $progressWidth }};"></div>

            <div class="step-node {{ $step >= 1 ? ($step == 1 ? 'active' : 'done') : '' }}">
              <div class="step-icon">{{ $step > 1 ? '✓' : '📝' }}</div>
              <div class="step-label">Pesanan Diterima<br><small style="font-size: 10px; opacity: 0.85;">Tercatat di Sistem</small></div>
            </div>

            <div class="step-node {{ $step >= 2 ? ($step == 2 ? 'active' : 'done') : '' }}">
              <div class="step-icon">{{ $step > 2 ? '✓' : '🥖' }}</div>
              <div class="step-label">Proses Pembuatan Artisan<br><small style="font-size: 10px; opacity: 0.85;">Dapur Pâtisserie</small></div>
            </div>

            <div class="step-node {{ $step >= 3 ? ($step == 3 ? 'active' : 'done') : '' }}">
              <div class="step-icon">{{ $step > 3 ? '✓' : '🚚' }}</div>
              <div class="step-label">Dalam Pengiriman<br><small style="font-size: 10px; opacity: 0.85;">Kurir / Ekspedisi</small></div>
            </div>

            <div class="step-node {{ $step == 4 ? 'active done' : '' }}">
              <div class="step-icon">🎉</div>
              <div class="step-label">Selesai Diterima<br><small style="font-size: 10px; opacity: 0.85;">Pesanan Tiba</small></div>
            </div>
          </div>

          <!-- Highlight Box Saat Sedang Dibawa Kurir -->
          @if($order->order_status == 'shipping')
            <div class="shipping-active-banner">
              <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 14px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                  <span style="font-size: 32px;">🚚</span>
                  <div>
                    <h3 style="margin: 0; font-size: 17px; font-weight: 800; color: #0284c7; letter-spacing: 0.5px;">Paket Sedang Dibawa Kurir Ekspedisi</h3>
                    <span style="font-size: 12.5px; color: #475569;">Paket hampers Anda dalam perjalanan menuju alamat tujuan</span>
                  </div>
                </div>
                <span style="background: #0284c7; color: #fff; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; letter-spacing: 1px;">ON DELIVERY / IN TRANSIT</span>
              </div>

              <div style="background: #ffffff; border: 1px solid #bae6fd; border-radius: 10px; padding: 14px 18px; margin-bottom: 16px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; font-size: 13px;">
                  <div>
                    <span style="color: #64748b; font-size: 11px; text-transform: uppercase; display: block; font-weight: 600;">Ekspedisi Pengantar:</span>
                    <strong style="color: #0f172a; font-size: 14px;">{{ $order->courier_name ?: 'Kurir Toko Dedicated' }}</strong>
                  </div>
                  <div>
                    <span style="color: #64748b; font-size: 11px; text-transform: uppercase; display: block; font-weight: 600;">Nomor Resi / AWB:</span>
                    <strong style="color: #0284c7; font-family: monospace; font-size: 15px; letter-spacing: 0.5px;">{{ $order->tracking_number }}</strong>
                  </div>
                  <div>
                    <span style="color: #64748b; font-size: 11px; text-transform: uppercase; display: block; font-weight: 600;">Jadwal Pengantaran:</span>
                    <strong style="color: #0f172a;">📅 {{ date('d F Y', strtotime($order->delivery_date)) }}</strong>
                  </div>
                </div>
              </div>

              <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                @if($order->courier_tracking_url)
                  <a href="{{ $order->courier_tracking_url }}" target="_blank" style="padding: 10px 18px; background: #0284c7; color: #fff; text-decoration: none; border-radius: 8px; font-size: 12.5px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.35);">
                    🔍 Cek Resi Resmi di {{ str_contains(strtolower($order->courier_name ?? ''), 'jne') ? 'JNE Express' : ($order->courier_name ?: 'Ekspedisi') }} ↗
                  </a>
                @endif

                <form action="{{ route('order.confirm_received', $order->invoice_number) }}" method="POST" style="margin: 0; display: inline;" onsubmit="return confirm('Konfirmasi bahwa paket hampers telah Anda terima dengan baik?');">
                  @csrf
                  <button type="submit" style="padding: 10px 18px; background: #15803d; color: #fff; border: 1px solid #22c55e; border-radius: 8px; font-size: 12.5px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(22, 128, 61, 0.35);">
                    ✅ Konfirmasi Paket Sudah Diterima
                  </button>
                </form>

                @if($order->google_maps_url)
                  <a href="{{ $order->google_maps_url }}" target="_blank" style="padding: 10px 14px; background: #ffffff; color: #047857; text-decoration: none; border: 1px solid #a7f3d0; border-radius: 8px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                    📍 Rute Google Maps Kurir ↗
                  </a>
                @endif
              </div>
            </div>
          @elseif($order->order_status == 'completed')
            <div style="background: #f0fdf4; border: 1.5px solid #86efac; border-radius: 12px; padding: 20px 24px; margin-bottom: 28px; text-align: center;">
              <div style="font-size: 32px; margin-bottom: 4px;">🎉</div>
              <h3 style="color: #15803d; margin: 0 0 4px 0; font-size: 17px; font-weight: 800;">PESANAN TELAH SELESAI &amp; DITERIMA</h3>
              <p style="font-size: 13px; color: #475569; margin: 0;">Terima kasih atas kepercayaan Anda memesan di Pusat Hampers Indonesia. Selamat menikmati sajian mewah kami!</p>
            </div>
          @endif
        @else
          <div style="background: #fef2f2; border: 1px solid #fecdd3; color: #9f1239; padding: 16px 20px; border-radius: 12px; margin-bottom: 28px;">
            ⚠️ Pesanan ini telah <strong>DIBATALKAN</strong>. Jika Anda memerlukan bantuan lebih lanjut, silakan hubungi layanan Concierge kami.
          </div>
        @endif

        <!-- 4. Rincian Pemesan & Pengiriman -->
        <div class="info-grid">
          <div class="info-item">
            <label>👤 Akun &amp; Pemesan:</label>
            <span>
              {{ $order->customer_name }}
              @if($order->user_id)
                <small style="color: #15803d; font-size: 11px; display: block; font-weight: 700;">🟢 Akun Member Terverifikasi</small>
              @else
                <small style="color: #64748b; font-size: 11px; display: block;">⚪ Pemesan Tamu</small>
              @endif
            </span>
          </div>
          <div class="info-item">
            <label>📞 No. Telepon / WhatsApp:</label>
            <span>
              @php
                $isOwnerOrAdmin = auth()->check() && (auth()->id() === $order->user_id || auth()->user()->is_admin);
                $rawPhone = $order->customer_phone ?? '';
                if ($isOwnerOrAdmin || strlen($rawPhone) < 6) {
                    $maskedPhone = $rawPhone;
                } else {
                    $maskedPhone = substr($rawPhone, 0, 4) . '••••' . substr($rawPhone, -3);
                }
              @endphp
              {{ $maskedPhone }}
              @if(!$isOwnerOrAdmin)
                <small style="color: #94a3b8; font-size: 10px; display: block;">🔒 Disamarkan demi privasi</small>
              @endif
            </span>
          </div>
          <div class="info-item">
            <label>🚚 Ekspedisi / Kurir:</label>
            <span>{{ $order->courier_name ?: 'Kurir Toko (Dedicated Delivery)' }}</span>
          </div>
          <div class="info-item">
            <label>🏷️ Resi Pembelian / Tracking:</label>
            <span style="color: #b45309; font-family: monospace; font-weight: 700;">{{ $order->tracking_number ?: 'Menunggu Penerbitan' }}</span>
          </div>
          <div class="info-item">
            <label>💳 Metode Pembayaran:</label>
            <span>{{ $order->payment_method ?: 'Transfer Bank BCA' }}</span>
          </div>
          <div class="info-item">
            <label>📅 Tanggal Pengiriman / Pick-Up:</label>
            <span>{{ $order->delivery_date ? $order->delivery_date->format('d M Y') : 'Hari ini' }}</span>
          </div>
          <div class="info-item" style="grid-column: 1 / -1;">
            <label>📍 Alamat Pengiriman / Catatan:</label>
            <span>
              @php
                $rawAddress = $order->address ?: 'Pick up di Store Pusat Hampers Indonesia';
                if ($isOwnerOrAdmin || $order->delivery_option === 'pickup' || strlen($rawAddress) < 20) {
                    $displayAddress = $rawAddress;
                } else {
                    $displayAddress = substr($rawAddress, 0, 18) . '... [Alamat lengkap disamarkan demi privasi data]';
                }
              @endphp
              {{ $displayAddress }}
            </span>
          </div>
        </div>

        <!-- 5. Rincian Item Produk -->
        <h4 style="font-size: 14.5px; font-weight: 700; color: #722F37; margin-bottom: 14px; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 8px;">
          <span>🛒</span> <span>Rincian Produk Pesanan:</span>
        </h4>

        <div style="overflow-x: auto;">
          <table class="items-table">
            <thead>
              <tr>
                <th style="width: 60px;">Foto</th>
                <th>Produk</th>
                <th>Harga Unit</th>
                <th>Jumlah</th>
                <th style="text-align: right;">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              @foreach($order->items as $item)
                <tr>
                  <td>
                    <img src="{{ $item->product ? $item->product->thumbnail_url : asset('images/cat-cakes.jpg') }}" alt="{{ $item->product_name }}" class="item-thumb" loading="lazy" decoding="async" onerror="this.src='{{ asset('images/cat-cakes.jpg') }}'">
                  </td>
                  <td>
                    <div style="font-weight: 700; color: #1e293b;">{{ $item->product_name }}</div>
                  </td>
                  <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                  <td><strong>x{{ $item->quantity }}</strong></td>
                  <td style="text-align: right; font-weight: 700; color: #722F37;">
                    Rp {{ number_format($item->subtotal ?: ($item->price * $item->quantity), 0, ',', '.') }}
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <!-- 6. Totals Box -->
        <div class="totals-box">
          <div class="total-row">
            <span>Subtotal Produk:</span>
            <span style="font-weight: 600; color: #1e293b;">Rp {{ number_format($order->subtotal ?: ($order->total_amount - $order->delivery_fee), 0, ',', '.') }}</span>
          </div>
          <div class="total-row">
            <span>Biaya Pengiriman:</span>
            <span style="font-weight: 600; color: #1e293b;">Rp {{ number_format($order->delivery_fee ?? 0, 0, ',', '.') }}</span>
          </div>
          <div class="total-row grand">
            <span>TOTAL PEMBAYARAN:</span>
            <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
          </div>
        </div>

        <!-- 7. Action Links -->
        <div style="margin-top: 32px; padding-top: 22px; border-top: 1.5px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
          <a href="https://wa.me/62811152282?text=Halo%20Pusat%20Hampers%20Indonesia,%20saya%20ingin%20menanyakan%20status%20pesanan%20nomor%20{{ $order->invoice_number }}" target="_blank" class="track-action-btn btn-wa-live">
            <span>💬</span> <span>Bantuan Live Concierge (WhatsApp)</span>
          </a>

          <button onclick="window.print()" class="track-action-btn btn-print-receipt">
            <span>🖨️</span> <span>Cetak Bukti Pelacakan</span>
          </button>
        </div>
      </div>
    @elseif($searched)
      <div style="background: #fff1f2; border: 1.5px solid #fecdd3; color: #9f1239; padding: 28px 24px; border-radius: 16px; text-align: center; box-shadow: 0 6px 20px rgba(159, 18, 57, 0.06);">
        <div style="font-size: 32px; margin-bottom: 8px;">⚠️</div>
        <h3 style="font-size: 18px; margin: 0 0 8px; font-weight: 800;">Pesanan Tidak Ditemukan</h3>
        <p style="font-size: 14px; color: #881337; max-width: 600px; margin: 0 auto 14px; line-height: 1.5;">
          Tidak ditemukan pesanan dengan Nomor Invoice <strong>"{{ $invoice }}"</strong>. Mohon periksa kembali nomor invoice pada email konfirmasi atau bukti transaksi Anda.
        </p>
        <p style="font-size: 12px; color: #9f1239; margin: 0;">Contoh format invoice yang benar: <code>INV-20260922-RYEB</code> atau Resi <code>JNE-20261031-1234</code></p>
      </div>
    @endif

  </div>
</div>
@endsection
