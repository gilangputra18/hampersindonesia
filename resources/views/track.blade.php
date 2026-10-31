@extends('layouts.app')

@section('title', 'Lacak Pesanan Real-Time | ' . config('site.brand'))

@section('content')
<style>
  .track-page {
    background: #0d1713;
    color: #f1f5f9;
    padding: 60px 20px 100px;
    min-height: 80vh;
    font-family: 'Outfit', sans-serif;
  }
  .track-container {
    max-width: 900px;
    margin: 0 auto;
  }
  .track-header {
    text-align: center;
    margin-bottom: 40px;
  }
  .track-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 38px;
    letter-spacing: 4px;
    font-weight: 700;
    color: #fef08a;
    text-transform: uppercase;
    margin-bottom: 6px;
  }
  .track-sub {
    font-size: 13px;
    letter-spacing: 2px;
    color: #9cb3a8;
    text-transform: uppercase;
  }

  /* Search Box Card */
  .track-search-card {
    background: linear-gradient(135deg, #12211c 0%, #1a2e26 100%);
    border: 1px solid rgba(217, 119, 6, 0.3);
    border-radius: 16px;
    padding: 32px;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
    margin-bottom: 40px;
  }
  .track-input-group {
    display: flex;
    gap: 12px;
    margin-top: 16px;
  }
  @media (max-width: 600px) {
    .track-input-group { flex-direction: column; }
  }
  .track-input {
    flex: 1;
    padding: 14px 20px;
    background: rgba(13, 23, 19, 0.9);
    border: 1px solid rgba(245, 158, 11, 0.35);
    border-radius: 8px;
    color: #fff;
    font-size: 15px;
    letter-spacing: 1px;
    transition: all 0.25s ease;
  }
  .track-input:focus {
    outline: none;
    border-color: #f59e0b;
    box-shadow: 0 0 15px rgba(245, 158, 11, 0.3);
  }
  .track-btn {
    padding: 14px 28px;
    background: linear-gradient(135deg, #b45309 0%, #d97706 50%, #f59e0b 100%);
    color: #0d1713;
    font-weight: 800;
    font-size: 12px;
    letter-spacing: 2px;
    text-transform: uppercase;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.25s ease;
    white-space: nowrap;
  }
  .track-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.45);
  }

  /* Sample Pills */
  .sample-pills {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 16px;
    flex-wrap: wrap;
    font-size: 12px;
    color: #8fa59b;
  }
  .sample-pill {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(245, 158, 11, 0.2);
    color: #fef08a;
    padding: 4px 12px;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
  }
  .sample-pill:hover {
    background: #d97706;
    color: #0d1713;
  }

  /* Order Found Result Section */
  .order-result-card {
    background: #12211c;
    border: 1px solid rgba(217, 119, 6, 0.35);
    border-radius: 16px;
    padding: 36px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
  }
  .order-meta-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 20px;
    padding-bottom: 24px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    margin-bottom: 32px;
  }
  .inv-number {
    font-family: 'Cormorant Garamond', serif;
    font-size: 28px;
    color: #fef08a;
    font-weight: 700;
  }
  .status-badge-group {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
  }
  .badge-status {
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
  }
  .status-completed { background: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3); }
  .status-processing { background: rgba(234, 179, 8, 0.15); color: #fde047; border: 1px solid rgba(234, 179, 8, 0.3); }
  .status-shipping { background: rgba(14, 165, 233, 0.2); color: #38bdf8; border: 1px solid rgba(14, 165, 233, 0.4); }
  .status-pending { background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); }
  .status-cancelled { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }

  /* 4-Step Live Tracking Progress Bar */
  .tracker-stepper {
    display: flex;
    justify-content: space-between;
    position: relative;
    margin: 40px 0 50px;
  }
  .tracker-stepper::before {
    content: '';
    position: absolute;
    top: 24px;
    left: 40px;
    right: 40px;
    height: 3px;
    background: rgba(255, 255, 255, 0.1);
    z-index: 1;
  }
  .tracker-progress-line {
    position: absolute;
    top: 24px;
    left: 40px;
    height: 3px;
    background: linear-gradient(90deg, #d97706, #f59e0b);
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
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #0d1713;
    border: 2px solid rgba(255, 255, 255, 0.2);
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 12px;
    font-size: 20px;
    transition: all 0.4s ease;
  }
  .step-node.active .step-icon {
    background: linear-gradient(135deg, #d97706, #f59e0b);
    border-color: #fef08a;
    color: #0d1713;
    box-shadow: 0 0 20px rgba(245, 158, 11, 0.6);
    transform: scale(1.15);
  }
  .step-node.done .step-icon {
    background: rgba(34, 197, 94, 0.2);
    border-color: #4ade80;
    color: #4ade80;
  }
  .step-label {
    font-size: 13px;
    font-weight: 600;
    color: #94a3b8;
    line-height: 1.4;
  }
  .step-node.active .step-label {
    color: #fef08a;
    font-weight: 700;
  }
  .step-node.done .step-label {
    color: #cbd5e1;
  }

  @media (max-width: 600px) {
    .track-page { padding: 30px 12px 60px; }
    .track-title { font-size: 26px; letter-spacing: 2px; }
    .track-search-card { padding: 20px 16px; }
    .order-result-card { padding: 20px 16px; }
    .step-icon { width: 36px; height: 36px; font-size: 14px; }
    .step-label { font-size: 10px; }
    .tracker-stepper::before, .tracker-progress-line { top: 18px; }
  }

  /* Customer Info Grid */
  .info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    background: rgba(13, 23, 19, 0.6);
    padding: 24px;
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.05);
    margin-bottom: 32px;
  }
  @media (max-width: 600px) {
    .info-grid { grid-template-columns: 1fr; }
  }
  .info-item label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #8fa59b;
    display: block;
    margin-bottom: 4px;
  }
  .info-item span {
    font-size: 14px;
    color: #f1f5f9;
    font-weight: 600;
  }

  /* Order Items Table */
  .items-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 32px;
  }
  .items-table th, .items-table td {
    padding: 14px;
    text-align: left;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
  }
  .items-table th {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #8fa59b;
    background: rgba(0, 0, 0, 0.2);
  }
  .item-thumb {
    width: 48px;
    height: 48px;
    border-radius: 6px;
    object-fit: cover;
    border: 1px solid rgba(217, 119, 6, 0.3);
  }

  .totals-box {
    margin-left: auto;
    max-width: 320px;
    background: rgba(13, 23, 19, 0.8);
    padding: 20px;
    border-radius: 10px;
    border: 1px solid rgba(217, 119, 6, 0.25);
  }
  .total-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
    font-size: 13px;
    color: #9cb3a8;
  }
  .total-row.grand {
    font-size: 16px;
    font-weight: 800;
    color: #fef08a;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding-top: 10px;
    margin-top: 10px;
    margin-bottom: 0;
  }
</style>

<div class="track-page">
  <div class="track-container">
    <div class="track-header">
      <h1 class="track-title">📦 LACAK PESANAN REAL-TIME</h1>
      <p class="track-sub">Lacak Status Real-Time Pesanan Pusat Hampers Indonesia Anda</p>
    </div>

    <!-- Search Form Card -->
    <div class="track-search-card">
      <form action="{{ route('track') }}" method="GET">
        <label for="invInput" style="font-size: 13px; font-weight: 600; color: #fef08a; letter-spacing: 1px; text-transform: uppercase;">
          🔍 Masukkan Nomor Invoice atau Resi Pembelian Anda:
        </label>
        <div class="track-input-group">
          <input type="text" id="invInput" name="invoice" class="track-input" placeholder="contoh: INV-20260922-RYEB atau KUR-20260929-8812" value="{{ $invoice }}" required>
          <button type="submit" class="track-btn">🚀 Lacak Pesanan</button>
        </div>
      </form>

      @if(!empty($latestSampleOrders) && $latestSampleOrders->count() > 0)
        <div class="sample-pills">
          <span>💡 Uji Coba Lacak Cepat:</span>
          @foreach($latestSampleOrders as $sample)
            <a href="{{ route('track') }}?invoice={{ $sample->invoice_number }}" class="sample-pill">
              {{ $sample->invoice_number }} ({{ $sample->customer_name }})
            </a>
          @endforeach
        </div>
      @endif
    </div>

    <!-- Order Tracking Result -->
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
            <div style="font-size: 12px; color: #8fa59b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px;">NOMOR INVOICE & RESI PEMBELIAN:</div>
            <div class="inv-number">#{{ $order->invoice_number }}</div>
            @if($order->tracking_number)
              <div style="font-size: 14px; color: #f59e0b; font-family: monospace; font-weight: 700; margin-top: 4px;">🏷️ RESI: {{ $order->tracking_number }}</div>
            @endif
            <div style="font-size: 12px; color: #94a3b8; margin-top: 4px;">Tanggal Dibuat: {{ $order->created_at->format('d M Y H:i') }} WIB</div>
          </div>

          <div class="status-badge-group">
            <span class="badge-status status-{{ $order->order_status }}">
              📌 Status: {{ $order->order_status === 'shipping' ? 'DIKIRIM KURIR' : strtoupper($order->order_status) }}
            </span>
            <span class="badge-status {{ $order->payment_status == 'paid' ? 'status-completed' : 'status-processing' }}">
              💳 Pembayaran: {{ strtoupper($order->payment_status) }}
            </span>
          </div>
        </div>

        @if($order->order_status != 'cancelled')
          <!-- 4-Step Live Tracking Progress Stepper -->
          <div style="margin-bottom: 12px; font-size: 13px; font-weight: 700; color: #fef08a; text-transform: uppercase; letter-spacing: 1px;">
            🚦 Progres Pelacakan Pesanan:
          </div>

          <div class="tracker-stepper">
            <div class="tracker-progress-line" style="width: {{ $progressWidth }};"></div>

            <div class="step-node {{ $step >= 1 ? ($step == 1 ? 'active' : 'done') : '' }}">
              <div class="step-icon">{{ $step > 1 ? '✓' : '📝' }}</div>
              <div class="step-label">Pesanan Diterima<br><small style="font-size: 10px; opacity: 0.8;">Tercatat di Sistem</small></div>
            </div>

            <div class="step-node {{ $step >= 2 ? ($step == 2 ? 'active' : 'done') : '' }}">
              <div class="step-icon">{{ $step > 2 ? '✓' : '🥖' }}</div>
              <div class="step-label">Proses Pembuatan Artisan<br><small style="font-size: 10px; opacity: 0.8;">Dapur &amp; Dapur Pâtisserie</small></div>
            </div>

            <div class="step-node {{ $step >= 3 ? ($step == 3 ? 'active' : 'done') : '' }}">
              <div class="step-icon">{{ $step > 3 ? '✓' : '🚚' }}</div>
              <div class="step-label">Dalam Pengiriman<br><small style="font-size: 10px; opacity: 0.8;">Kurir / Ready Pick Up</small></div>
            </div>

            <div class="step-node {{ $step == 4 ? 'active done' : '' }}">
              <div class="step-icon">🎉</div>
              <div class="step-label">Selesai Diterima<br><small style="font-size: 10px; opacity: 0.8;">Pesanan Tiba</small></div>
            </div>
          </div>

          @if($order->order_status == 'shipping')
            <div style="background: linear-gradient(135deg, rgba(2, 132, 199, 0.2) 0%, rgba(14, 165, 233, 0.1) 100%); border: 2px solid #0284c7; border-radius: 12px; padding: 22px; margin-bottom: 30px; box-shadow: 0 8px 25px rgba(2, 132, 199, 0.25);">
              <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 14px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                  <span style="font-size: 32px;">🚚</span>
                  <div>
                    <h3 style="margin: 0; font-size: 17px; font-weight: 800; color: #38bdf8; letter-spacing: 0.5px;">Paket Sedang Dibawa Kurir Ekspedisi</h3>
                    <span style="font-size: 12.5px; color: #cbd5e1;">Paket hampers Anda dalam perjalanan menuju alamat tujuan</span>
                  </div>
                </div>
                <span style="background: #0284c7; color: #fff; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; letter-spacing: 1px;">ON DELIVERY / IN TRANSIT</span>
              </div>

              <div style="background: rgba(13, 23, 19, 0.7); border: 1px solid rgba(56, 189, 248, 0.3); border-radius: 8px; padding: 14px 18px; margin-bottom: 16px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; font-size: 13px;">
                  <div>
                    <span style="color: #94a3b8; font-size: 11px; text-transform: uppercase; display: block;">Ekspedisi Pengantar:</span>
                    <strong style="color: #fff; font-size: 14px;">{{ $order->courier_name ?: 'Kurir Toko Dedicated' }}</strong>
                  </div>
                  <div>
                    <span style="color: #94a3b8; font-size: 11px; text-transform: uppercase; display: block;">Nomor Resi / AWB:</span>
                    <strong style="color: #f59e0b; font-family: monospace; font-size: 15px; letter-spacing: 1px;">{{ $order->tracking_number }}</strong>
                  </div>
                  <div>
                    <span style="color: #94a3b8; font-size: 11px; text-transform: uppercase; display: block;">Jadwal Pengantaran:</span>
                    <strong style="color: #fff;">📅 {{ date('d F Y', strtotime($order->delivery_date)) }}</strong>
                  </div>
                </div>
              </div>

              <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                @if($order->courier_tracking_url)
                  <a href="{{ $order->courier_tracking_url }}" target="_blank" style="padding: 10px 18px; background: #0284c7; color: #fff; text-decoration: none; border-radius: 6px; font-size: 12.5px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.35);">
                    🔍 Cek Resi Resmi di {{ str_contains(strtolower($order->courier_name ?? ''), 'jne') ? 'JNE Express' : ($order->courier_name ?: 'Ekspedisi') }} ↗
                  </a>
                @endif

                <form action="{{ route('order.confirm_received', $order->invoice_number) }}" method="POST" style="margin: 0; display: inline;" onsubmit="return confirm('Konfirmasi bahwa paket hampers telah Anda terima dengan baik?');">
                  @csrf
                  <button type="submit" style="padding: 10px 18px; background: #15803d; color: #fff; border: 1px solid #22c55e; border-radius: 6px; font-size: 12.5px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(22, 128, 61, 0.35);">
                    ✅ Konfirmasi Paket Sudah Diterima
                  </button>
                </form>

                @if($order->google_maps_url)
                  <a href="{{ $order->google_maps_url }}" target="_blank" style="padding: 10px 14px; background: rgba(255,255,255,0.08); color: #86efac; text-decoration: none; border: 1px solid rgba(34, 197, 94, 0.4); border-radius: 6px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                    📍 Rute Google Maps Kurir ↗
                  </a>
                @endif
              </div>
            </div>
          @elseif($order->order_status == 'completed')
            <div style="background: rgba(34, 197, 94, 0.12); border: 1px solid #22c55e; border-radius: 10px; padding: 18px 22px; margin-bottom: 28px; text-align: center;">
              <div style="font-size: 32px; margin-bottom: 4px;">🎉</div>
              <h3 style="color: #4ade80; margin: 0 0 4px 0; font-size: 17px; font-weight: 800;">PESANAN TELAH SELESAI &amp; DITERIMA</h3>
              <p style="font-size: 13px; color: #cbd5e1; margin: 0;">Terima kasih atas kepercayaan Anda memesan di Pusat Hampers Indonesia. Selamat menikmati sajian kami!</p>
            </div>
          @endif
        @else
          <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #f87171; padding: 16px 20px; border-radius: 12px; margin-bottom: 30px;">
            ⚠️ Pesanan ini telah <strong>DIBATALKAN</strong>. Jika Anda memerlukan bantuan lebih lanjut, silakan hubungi layanan Concierge kami.
          </div>
        @endif

        <!-- Info Customer & Delivery -->
        <div class="info-grid">
          <div class="info-item">
            <label>👤 Akun & Pemesan:</label>
            <span>
              {{ $order->customer_name }}
              @if($order->user_id)
                <small style="color: #34d399; font-size: 11px; display: block;">🟢 Akun Member Terverifikasi</small>
              @else
                <small style="color: #94a3b8; font-size: 11px; display: block;">⚪ Pemesan Tamu (Tanpa Akun)</small>
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
                <small style="color: #64748b; font-size: 10px; display: block;">🔒 Disamarkan demi privasi</small>
              @endif
            </span>
          </div>
          <div class="info-item">
            <label>🚚 Ekspedisi / Kurir:</label>
            <span>{{ $order->courier_name ?: 'Kurir Toko (Dedicated Delivery)' }}</span>
          </div>
          <div class="info-item">
            <label>🏷️ Resi Pembelian / Tracking:</label>
            <span style="color: #f59e0b; font-family: monospace;">{{ $order->tracking_number ?: 'Menunggu Penerbitan' }}</span>
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

        <!-- Order Items Detail Table -->
        <h4 style="font-size: 15px; font-weight: 700; color: #fef08a; margin-bottom: 14px; text-transform: uppercase; letter-spacing: 1px;">
          🛒 Rincian Produk Pesanan:
        </h4>

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
                  <div style="font-weight: 600; color: #fff;">{{ $item->product_name }}</div>
                </td>
                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                <td><strong>x{{ $item->quantity }}</strong></td>
                <td style="text-align: right; font-weight: 700; color: #f59e0b;">
                  Rp {{ number_format($item->subtotal ?: ($item->price * $item->quantity), 0, ',', '.') }}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <!-- Totals Summary Box -->
        <div class="totals-box">
          <div class="total-row">
            <span>Subtotal Produk:</span>
            <span>Rp {{ number_format($order->subtotal ?: ($order->total_amount - $order->delivery_fee), 0, ',', '.') }}</span>
          </div>
          <div class="total-row">
            <span>Biaya Pengiriman:</span>
            <span>Rp {{ number_format($order->delivery_fee ?? 0, 0, ',', '.') }}</span>
          </div>
          <div class="total-row grand">
            <span>TOTAL PEMBAYARAN:</span>
            <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
          </div>
        </div>

        <!-- Action Links -->
        <div style="margin-top: 32px; padding-top: 24px; border-top: 1px solid rgba(255, 255, 255, 0.1); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
          <a href="https://wa.me/62811152282?text=Halo%20Pusat%20Hampers%20Indonesia,%20saya%20ingin%20menanyakan%20status%20pesanan%20nomor%20{{ $order->invoice_number }}" target="_blank" class="track-btn" style="background: rgba(34, 197, 94, 0.2); border: 1px solid rgba(34, 197, 94, 0.4); color: #4ade80;">
            💬 Bantuan Live Chat (WhatsApp)
          </a>

          <button onclick="window.print()" class="track-btn" style="background: rgba(255, 255, 255, 0.1); color: #fff;">
            🖨️ Cetak Struk Pesanan
          </button>
        </div>
      </div>
    @elseif($searched)
      <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.35); color: #f87171; padding: 24px; border-radius: 16px; text-align: center;">
        <h3 style="font-size: 18px; margin-bottom: 8px;">⚠️ Pesanan Tidak Ditemukan</h3>
        <p style="font-size: 14px; color: #fca5a5; max-width: 600px; margin: 0 auto 16px;">
          Tidak ditemukan pesanan dengan Nomor Invoice <strong>"{{ $invoice }}"</strong>. Mohon periksa kembali ejaan nomor invoice pada email konfirmasi atau bukti pembayaran Anda.
        </p>
        <p style="font-size: 12px; color: #cbd5e1;">Contoh format invoice yang benar: <code>INV-20260922-RYEB</code></p>
      </div>
    @endif
  </div>
</div>
@endsection
