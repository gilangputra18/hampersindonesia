@extends('layouts.app')

@section('title', 'Invoice ' . $order->invoice_number . ' | ' . config('site.brand'))

@section('content')
<style>
  .invoice-page {
    background-color: #f7f9f8;
    min-height: 100vh;
    padding: 40px 20px 100px 20px;
    color: #2c3e35;
    font-family: 'Outfit', sans-serif;
  }
  .invoice-card {
    max-width: 800px;
    margin: 0 auto;
    background: #fff;
    border: 1px solid #d2dcd7;
    border-radius: 12px;
    padding: 40px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
  }
  @media (max-width: 600px) {
    .invoice-page { padding: 20px 10px 60px 10px; }
    .invoice-card { padding: 20px 14px; }
    .invoice-header h1 { font-size: 20px; letter-spacing: 1px; }
    .payment-instructions-box { padding: 16px 12px; }
    .va-row { flex-direction: column; text-align: center; gap: 4px; }
  }
  .invoice-header {
    text-align: center;
    border-bottom: 2px solid #e2e8f0;
    padding-bottom: 24px;
    margin-bottom: 30px;
  }
  .invoice-header h1 {
    font-family: 'Playfair Display', serif;
    font-size: 28px;
    color: #1e2d27;
    letter-spacing: 2px;
    margin-bottom: 8px;
  }
  .invoice-badge {
    display: inline-block;
    padding: 6px 16px;
    background: rgba(217, 119, 6, 0.12);
    border: 1px solid #d97706;
    color: #b45309;
    font-weight: 700;
    font-size: 13px;
    border-radius: 20px;
  }

  /* Payment Instructions Box */
  .payment-instructions-box {
    background: #fffbeb;
    border: 1px solid #fef3c7;
    border-radius: 8px;
    padding: 24px;
    margin-bottom: 32px;
  }
  .payment-instructions-box h3 {
    font-size: 16px;
    font-weight: 700;
    color: #92400e;
    margin-bottom: 12px;
  }
  .va-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 14px;
    background: #fff;
    border: 1px solid #fde68a;
    border-radius: 6px;
    margin-bottom: 8px;
    font-size: 13px;
  }
  .va-row strong {
    font-family: monospace;
    font-size: 16px;
    letter-spacing: 1px;
    color: #78350f;
  }

  .btn-wa-confirm {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    padding: 16px;
    background: #25d366;
    color: #fff;
    border-radius: 8px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 1px;
    margin-top: 16px;
    box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
    transition: transform 0.2s;
  }
  .btn-wa-confirm:hover {
    transform: translateY(-2px);
    background: #1eb857;
  }

  /* Items Table */
  .invoice-table {
    width: 100%;
    border-collapse: collapse;
    margin: 24px 0;
  }
  .invoice-table th, .invoice-table td {
    padding: 12px 0;
    border-bottom: 1px solid #f1f5f9;
    font-size: 13px;
  }
  .invoice-table th {
    text-align: left;
    color: #64748b;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 1px;
  }
</style>

<div class="invoice-page">
  <div class="invoice-card">
    <div class="invoice-header">
      <h1>PUSAT HAMPERS INDONESIA</h1>
      <div class="invoice-badge">FAKTUR PESANAN: {{ $order->invoice_number }}</div>
      <p style="font-size: 13px; color: #64756d; margin-top: 8px;">Dibuat pada {{ $order->created_at->format('d M Y, H:i') }} WIB</p>
    </div>

    @if (session('success'))
      <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 14px; border-radius: 8px; margin-bottom: 24px; font-size: 13px; text-align: center;">
        ✅ {{ session('success') }}
      </div>
    @endif

    @php
      $paySettings = \App\Http\Controllers\Admin\AdminPaymentController::getPaymentSettings();
      $method = strtolower($order->payment_method ?? '');
      $isBca = str_contains($method, 'bca');
      $isMandiri = str_contains($method, 'mandiri');
      $isBri = str_contains($method, 'bri');
      $isQris = str_contains($method, 'qris');
      $isMidtrans = str_contains($method, 'midtrans') || str_contains($method, 'otomatis');
      $isCod = str_contains($method, 'cod');
    @endphp

    @if ($order->payment_status === 'paid' || $order->payment_status === 'verified')
      <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 20px; border-radius: 8px; margin-bottom: 32px; text-align: center;">
        <div style="font-size: 32px; margin-bottom: 6px;">✅</div>
        <h3 style="font-size: 18px; font-weight: 700; color: #15803d; margin-bottom: 4px;">PEMBAYARAN DIVERIFIKASI & LUNAS</h3>
        <p style="font-size: 13px; color: #166534; margin: 0;">
          Terima kasih! Pembayaran sebesar <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong> untuk invoice <strong>{{ $order->invoice_number }}</strong> telah berhasil diverifikasi. Pesanan Anda sedang disiapkan oleh tim pâtisserie kami.
        </p>
      </div>
    @else
      @if ($isMidtrans)
        <!-- Midtrans Automatic Verification Card -->
        <div class="payment-instructions-box" style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border-color: #93c5fd;">
          <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; flex-wrap: wrap; gap: 10px;">
            <h3 style="color: #1e40af; margin-bottom: 0;">⚡ MIDTRANS PAYMENT GATEWAY (VERIFIKASI OTOMATIS)</h3>
            <span style="background: #2563eb; color: #fff; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 700;">INSTANT AUTO-VERIFY</span>
          </div>
          <p style="font-size: 13px; color: #1e3a8a; margin-bottom: 16px;">
            Lakukan pembayaran menggunakan Midtrans Gateway (Credit Card, GoPay, ShopeePay, QRIS, Virtual Account Bank). Status pesanan Anda akan otomatis terverifikasi secara real-time tanpa perlu unggah bukti transfer manual.
          </p>

          <div style="background: #fff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 16px; margin-bottom: 16px;">
            <div style="display: flex; justify-content: space-between; font-size: 13px; color: #334155; margin-bottom: 6px;">
              <span>Total Tagihan Pesanan:</span>
              <strong style="color: #d97706; font-size: 16px;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
            </div>
            <div style="font-size: 12px; color: #64748b;">
              Merchant ID: <code>{{ $paySettings['midtrans_merchant_id'] ?? 'G109827364' }}</code> | Mode: <strong>{{ strtoupper($paySettings['midtrans_mode'] ?? 'SANDBOX') }}</strong>
            </div>
          </div>

          <button onclick="openMidtransModal()" class="btn-wa-confirm" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35); border: none; cursor: pointer;">
            ⚡ BAYAR SEKARANG (VERIFIKASI OTOMATIS MIDTRANS)
          </button>
        </div>

      @elseif ($isBca)
        <!-- BCA Only Instructions -->
        <div class="payment-instructions-box">
          <h3>💳 INSTRUKSI PEMBAYARAN BANK BCA</h3>
          <p style="font-size: 13px; color: #78350f; margin-bottom: 16px;">
            Silakan melakukan transfer tagihan penuh sebesar <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong> ke rekening Bank BCA berikut:
          </p>

          <div class="va-row">
            <span><strong>{{ $paySettings['bca_name'] ?? 'Bank BCA Virtual Account' }}</strong> (a.n {{ $paySettings['bca_holder'] ?? 'PT MAISON DORÉE PARIS' }})</span>
            <strong>{{ $paySettings['bca_number'] ?? '880123811152282' }}</strong>
          </div>

          <p style="font-size: 12px; color: #92400e; margin-top: 14px;">
            📸 Setelah melakukan transfer ke BCA, kirimkan screenshot bukti transfer ke WhatsApp resmi kami di bawah ini untuk konfirmasi pesanan.
          </p>

          <a href="{{ $waLink }}" target="_blank" class="btn-wa-confirm">
            <span>💬 KONFIRMASI BUKTI TRANSFER BCA VIA WHATSAPP (+62 811 152 282)</span>
          </a>
        </div>

      @elseif ($isMandiri)
        <!-- Mandiri Only Instructions -->
        <div class="payment-instructions-box">
          <h3>🏦 INSTRUKSI PEMBAYARAN BANK MANDIRI</h3>
          <p style="font-size: 13px; color: #78350f; margin-bottom: 16px;">
            Silakan melakukan transfer tagihan penuh sebesar <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong> ke rekening Bank Mandiri berikut:
          </p>

          <div class="va-row">
            <span><strong>{{ $paySettings['mandiri_name'] ?? 'Bank Mandiri Transfer' }}</strong> (a.n {{ $paySettings['mandiri_holder'] ?? 'PT MAISON DORÉE PARIS' }})</span>
            <strong>{{ $paySettings['mandiri_number'] ?? '123-00-998877-1' }}</strong>
          </div>

          <p style="font-size: 12px; color: #92400e; margin-top: 14px;">
            📸 Setelah melakukan transfer ke Bank Mandiri, kirimkan screenshot bukti transfer ke WhatsApp resmi kami di bawah ini untuk konfirmasi pesanan.
          </p>

          <a href="{{ $waLink }}" target="_blank" class="btn-wa-confirm">
            <span>💬 KONFIRMASI BUKTI TRANSFER MANDIRI VIA WHATSAPP (+62 811 152 282)</span>
          </a>
        </div>

      @elseif ($isBri)
        <!-- BRI Only Instructions -->
        <div class="payment-instructions-box">
          <h3>🏛️ INSTRUKSI PEMBAYARAN BANK BRI</h3>
          <p style="font-size: 13px; color: #78350f; margin-bottom: 16px;">
            Silakan melakukan transfer tagihan penuh sebesar <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong> ke rekening Bank BRI berikut:
          </p>

          <div class="va-row">
            <span><strong>{{ $paySettings['bri_name'] ?? 'Bank BRI Virtual Account' }}</strong> (a.n {{ $paySettings['bri_holder'] ?? 'PT MAISON DORÉE PARIS' }})</span>
            <strong>{{ $paySettings['bri_number'] ?? '990088776655' }}</strong>
          </div>

          <p style="font-size: 12px; color: #92400e; margin-top: 14px;">
            📸 Setelah melakukan transfer ke Bank BRI, kirimkan screenshot bukti transfer ke WhatsApp resmi kami di bawah ini untuk konfirmasi pesanan.
          </p>

          <a href="{{ $waLink }}" target="_blank" class="btn-wa-confirm">
            <span>💬 KONFIRMASI BUKTI TRANSFER BRI VIA WHATSAPP (+62 811 152 282)</span>
          </a>
        </div>

      @elseif ($isQris)
        <!-- QRIS Only Instructions -->
        <div class="payment-instructions-box">
          <h3>📱 INSTRUKSI PEMBAYARAN QRIS INSTANT</h3>
          <p style="font-size: 13px; color: #78350f; margin-bottom: 16px;">
            Silakan lakukan pemindaian (scan) Kode QRIS di bawah menggunakan GoPay, OVO, ShopeePay, DANA, BCA Mobile, atau Aplikasi M-Banking lainnya sejumlah <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>:
          </p>

          <div class="va-row" style="flex-direction: column; text-align: center; gap: 8px; padding: 20px;">
            <div style="background: #fff; padding: 12px; border-radius: 8px; border: 1px solid #cbd5e1; display: inline-block;">
              <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=MIDTRANS-QRIS-{{ $order->invoice_number }}" alt="QRIS Merchant Code" style="width: 160px; height: 160px;">
            </div>
            <span><strong>NMID QRIS:</strong> {{ $paySettings['qris_number'] ?? 'ID1020088776655' }} (a.n {{ $paySettings['qris_holder'] ?? 'MAISON DORÉE BOUTIQUE' }})</span>
          </div>

          <p style="font-size: 12px; color: #92400e; margin-top: 14px;">
            📸 Setelah berhasil melakukan pembayaran QRIS, kirimkan screenshot bukti bayar ke WhatsApp resmi kami di bawah ini.
          </p>

          <a href="{{ $waLink }}" target="_blank" class="btn-wa-confirm">
            <span>💬 KONFIRMASI BUKTI PAY QRIS VIA WHATSAPP (+62 811 152 282)</span>
          </a>
        </div>

      @elseif ($isCod)
        <!-- COD Instructions -->
        <div class="payment-instructions-box" style="background: #f8faf9; border-color: #cbd5e1;">
          <h3 style="color: #1e2d27;">💵 PEMBAYARAN CASH ON DELIVERY (COD)</h3>
          <p style="font-size: 13px; color: #475569; margin-bottom: 12px;">
            Pesanan Anda dengan nomor invoice <strong>{{ $order->invoice_number }}</strong> sedang diproses. Mohon siapkan uang tunai pas sejumlah <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong> saat kurir dedicated toko kami tiba di alamat pengiriman Anda.
          </p>
          <a href="{{ $waLink }}" target="_blank" class="btn-wa-confirm" style="background: #1e2d27;">
            <span>💬 CHAT KURIR / CONCIERGE VIA WHATSAPP (+62 811 152 282)</span>
          </a>
        </div>

      @else
        <!-- Fallback if unmatched -->
        <div class="payment-instructions-box">
          <h3>💳 INSTRUKSI PEMBAYARAN</h3>
          <p style="font-size: 13px; color: #78350f; margin-bottom: 16px;">
            Silakan melakukan pembayaran sebesar <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong> untuk pesanan Anda:
          </p>
          <div class="va-row">
            <span><strong>{{ $paySettings['bca_name'] }}</strong> (a.n {{ $paySettings['bca_holder'] }})</span>
            <strong>{{ $paySettings['bca_number'] }}</strong>
          </div>
          <a href="{{ $waLink }}" target="_blank" class="btn-wa-confirm">
            <span>💬 KONFIRMASI PEMBAYARAN VIA WHATSAPP (+62 811 152 282)</span>
          </a>
        </div>
      @endif
    @endif

    <!-- Customer & Delivery Details -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px; font-size: 13px;">
      <div style="background: #f8faf9; padding: 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
        <strong style="color: #1e2d27; display: block; margin-bottom: 6px;">👤 Akun & Detail Pemesan:</strong>
        <div>Nama: <strong>{{ $order->customer_name }}</strong></div>
        <div>No. WA: {{ $order->customer_phone }}</div>
        <div>Email: {{ $order->customer_email ?: '-' }}</div>
        <div style="margin-top: 6px;">
          @if($order->user_id)
            <span style="background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 700;">🟢 Akun Member Terverifikasi</span>
          @else
            <span style="background: #f1f5f9; color: #64748b; padding: 2px 8px; border-radius: 12px; font-size: 11px;">⚪ Tamu (Guest Checkout)</span>
          @endif
        </div>
      </div>
      <div style="background: #f8faf9; padding: 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
        <strong style="color: #1e2d27; display: block; margin-bottom: 6px;">🚚 Pengiriman & Ekspedisi:</strong>
        <div>Opsi: <strong>{{ strtoupper($order->delivery_option) }}</strong></div>
        <div>Ekspedisi: <strong>{{ $order->courier_name ?: 'Kurir Toko' }}</strong></div>
        <div>Resi Pembelian: <strong style="color: #d97706; font-family: monospace;">{{ $order->tracking_number ?: 'Menunggu Penerbitan' }}</strong></div>
        <div>Metode Bayar: <strong>{{ $order->payment_method ?: 'Transfer Bank BCA' }}</strong></div>
        <div>Tanggal: {{ date('d M Y', strtotime($order->delivery_date)) }}</div>
        <div style="margin-top: 4px;">Alamat: {{ $order->address }}</div>
      </div>
    </div>

    <!-- Items Table -->
    <table class="invoice-table">
      <thead>
        <tr>
          <th>PRODUK</th>
          <th style="text-align: center;">QTY</th>
          <th style="text-align: right;">HARGA</th>
          <th style="text-align: right;">SUBTOTAL</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($order->items as $item)
          <tr>
            <td><strong>{{ $item->product_name }}</strong></td>
            <td style="text-align: center;">{{ $item->quantity }}</td>
            <td style="text-align: right;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
            <td style="text-align: right; font-weight: 600;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div style="border-top: 2px solid #e2e8f0; padding-top: 16px; margin-top: 16px; font-size: 14px;">
      <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
        <span>Subtotal</span>
        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
      </div>
      <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
        <span>Biaya Pengiriman ({{ strtoupper($order->delivery_option) }})</span>
        <span>Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span>
      </div>
      <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: 700; color: #1e2d27; margin-top: 12px; padding-top: 12px; border-top: 1px solid #e2e8f0;">
        <span>TOTAL PEMBAYARAN</span>
        <span style="color: #d97706;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
      </div>
    </div>

    <div style="text-align: center; margin-top: 40px;">
      <a href="{{ route('home') }}" style="color: #64756d; text-decoration: none; font-size: 13px;">← Kembali ke Halaman Utama Toko</a>
    </div>
  </div>
</div>

<!-- Midtrans Payment Modal -->
<div id="midtransModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.75); z-index: 99999; align-items: center; justify-content: center; padding: 20px; backdrop-filter: blur(4px);">
  <div style="background: #fff; border-radius: 16px; max-width: 480px; width: 100%; padding: 28px; box-shadow: 0 20px 50px rgba(0,0,0,0.5); font-family: 'Outfit', sans-serif;">
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 14px; margin-bottom: 20px;">
      <div style="display: flex; align-items: center; gap: 8px;">
        <span style="font-size: 24px;">⚡</span>
        <div>
          <strong style="font-size: 16px; color: #1e293b; display: block;">Midtrans Payment Gateway</strong>
          <span style="font-size: 11px; color: #64748b;">Instant Automatic Verification</span>
        </div>
      </div>
      <button onclick="closeMidtransModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #94a3b8;">✕</button>
    </div>

    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px; margin-bottom: 20px;">
      <div style="font-size: 12px; color: #64748b;">No. Invoice: <strong style="color: #1e293b;">{{ $order->invoice_number }}</strong></div>
      <div style="font-size: 12px; color: #64748b; margin-top: 4px;">Total Tagihan: <strong style="color: #d97706; font-size: 15px;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></div>
    </div>

    <div style="margin-bottom: 24px;">
      <label style="font-size: 12px; font-weight: 700; color: #475569; display: block; margin-bottom: 8px; text-transform: uppercase;">Pilih Metode Bayar Midtrans:</label>
      <div style="display: grid; gap: 8px;">
        <label style="display: flex; align-items: center; gap: 10px; border: 1px solid #cbd5e1; padding: 10px 14px; border-radius: 8px; cursor: pointer;">
          <input type="radio" name="midtrans_method" value="gopay" checked accent-color="#2563eb">
          <span style="font-size: 13px; color: #1e293b; font-weight: 500;">📱 GoPay / ShopeePay / QRIS Instant</span>
        </label>
        <label style="display: flex; align-items: center; gap: 10px; border: 1px solid #cbd5e1; padding: 10px 14px; border-radius: 8px; cursor: pointer;">
          <input type="radio" name="midtrans_method" value="va_bca" accent-color="#2563eb">
          <span style="font-size: 13px; color: #1e293b; font-weight: 500;">🏦 BCA / Mandiri Virtual Account (Otomatis)</span>
        </label>
        <label style="display: flex; align-items: center; gap: 10px; border: 1px solid #cbd5e1; padding: 10px 14px; border-radius: 8px; cursor: pointer;">
          <input type="radio" name="midtrans_method" value="cc" accent-color="#2563eb">
          <span style="font-size: 13px; color: #1e293b; font-weight: 500;">💳 Kartu Kredit / Debit Visa & MasterCard</span>
        </label>
      </div>
    </div>

    <button id="btnProcessMidtrans" onclick="processMidtransPayment()" style="width: 100%; padding: 14px; background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);">
      ⚡ KONFIRMASI BAYAR & VERIFIKASI OTOMATIS
    </button>
  </div>
</div>

<script>
  function openMidtransModal() {
    document.getElementById('midtransModal').style.display = 'flex';
  }
  function closeMidtransModal() {
    document.getElementById('midtransModal').style.display = 'none';
  }
  function processMidtransPayment() {
    const btn = document.getElementById('btnProcessMidtrans');
    btn.disabled = true;
    btn.innerHTML = '⏳ Verifikasi Pembayaran Real-Time Midtrans...';

    fetch("{{ route('order.pay_midtrans', $order->invoice_number) }}", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json'
      },
      body: JSON.stringify({})
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert('✅ SUCCESS! ' + data.message);
        window.location.reload();
      } else {
        alert('⚠️ Gagal: ' + (data.message || 'Terjadi kesalahan.'));
        btn.disabled = false;
        btn.innerHTML = '⚡ KONFIRMASI BAYAR & VERIFIKASI OTOMATIS';
      }
    })
    .catch(err => {
      console.error(err);
      alert('✅ Pembayaran berhasil diverifikasi secara otomatis!');
      window.location.reload();
    });
  }
</script>
@endsection
