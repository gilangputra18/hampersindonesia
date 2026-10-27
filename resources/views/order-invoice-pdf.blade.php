<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Faktur Pengiriman #{{ $order->invoice_number }} - {{ config('site.brand') }}</title>
  <style>
    body {
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
      color: #0f172a;
      background: #f8fafc;
      margin: 0;
      padding: 30px 20px;
    }
    .invoice-card {
      max-width: 820px;
      margin: 0 auto;
      background: #ffffff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 4px 25px rgba(0,0,0,0.08);
      border: 1px solid #e2e8f0;
      box-sizing: border-box;
    }
    .action-bar {
      max-width: 820px;
      margin: 0 auto 20px auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .btn-print {
      background: #d97706;
      color: #fff;
      padding: 10px 22px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 700;
      font-size: 13.5px;
      border: none;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: background 0.2s;
    }
    .btn-print:hover {
      background: #b45309;
    }
    .btn-back {
      color: #64748b;
      text-decoration: none;
      font-size: 13px;
      font-weight: 600;
    }
    .btn-back:hover {
      color: #0f172a;
    }

    /* Invoice Header */
    .inv-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      border-bottom: 2px solid #0f172a;
      padding-bottom: 20px;
      margin-bottom: 24px;
    }
    .inv-brand-name {
      font-size: 24px;
      font-weight: 800;
      letter-spacing: 2px;
      color: #0f172a;
      text-transform: uppercase;
      margin: 0 0 4px 0;
    }
    .inv-brand-sub {
      font-size: 11px;
      color: #64748b;
      letter-spacing: 1px;
      margin-bottom: 8px;
    }
    .inv-brand-address {
      font-size: 12px;
      color: #475569;
      line-height: 1.5;
    }
    .inv-title-box {
      text-align: right;
    }
    .inv-main-title {
      font-size: 18px;
      font-weight: 800;
      color: #d97706;
      letter-spacing: 1px;
      margin: 0 0 6px 0;
      text-transform: uppercase;
    }
    .inv-number {
      font-size: 14px;
      font-weight: 700;
      color: #0f172a;
      margin-bottom: 4px;
    }
    .inv-date {
      font-size: 12px;
      color: #64748b;
    }
    .inv-badge-paid {
      display: inline-block;
      margin-top: 6px;
      padding: 4px 10px;
      background: #dcfce7;
      color: #15803d;
      font-size: 11px;
      font-weight: 800;
      border-radius: 4px;
      border: 1px solid #86efac;
      text-transform: uppercase;
    }
    .inv-badge-unpaid {
      display: inline-block;
      margin-top: 6px;
      padding: 4px 10px;
      background: #fef3c7;
      color: #b45309;
      font-size: 11px;
      font-weight: 800;
      border-radius: 4px;
      border: 1px solid #fde68a;
      text-transform: uppercase;
    }

    /* 2 Column Customer & Shipping Box */
    .inv-grid-info {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 24px;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 18px 20px;
      margin-bottom: 28px;
    }
    .inv-info-col h4 {
      font-size: 12px;
      font-weight: 800;
      text-transform: uppercase;
      color: #64748b;
      letter-spacing: 0.5px;
      margin: 0 0 8px 0;
      border-bottom: 1px solid #e2e8f0;
      padding-bottom: 4px;
    }
    .inv-info-col p {
      margin: 0 0 4px 0;
      font-size: 13px;
      line-height: 1.5;
      color: #1e293b;
    }

    /* Product Table Responsive Wrapper */
    .inv-table-wrap {
      width: 100%;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      margin-bottom: 24px;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
    }
    .inv-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 0;
      min-width: 520px;
    }
    .inv-table th, .inv-table td {
      border: 1px solid #e2e8f0;
      padding: 10px 14px;
      font-size: 13px;
      text-align: left;
    }
    .inv-table th {
      background: #0f172a;
      color: #ffffff;
      font-weight: 700;
      text-transform: uppercase;
      font-size: 11px;
      letter-spacing: 1px;
    }
    .inv-table tr:nth-child(even) {
      background: #fcfcfc;
    }

    /* Summary Calculation */
    .inv-calc-box {
      width: 320px;
      margin-left: auto;
      margin-bottom: 30px;
      font-size: 13px;
    }
    .inv-calc-row {
      display: flex;
      justify-content: space-between;
      padding: 6px 0;
      color: #475569;
    }
    .inv-calc-total {
      display: flex;
      justify-content: space-between;
      padding: 10px 0;
      border-top: 2px solid #0f172a;
      font-weight: 800;
      font-size: 16px;
      color: #0f172a;
      margin-top: 6px;
    }

    /* Signatures Section */
    .inv-signatures {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      margin-top: 40px;
      padding-top: 20px;
      border-top: 1px dashed #cbd5e1;
      text-align: center;
    }
    .sig-box-title {
      font-size: 11.5px;
      font-weight: 700;
      color: #64748b;
      text-transform: uppercase;
      margin-bottom: 60px;
    }
    .sig-box-line {
      border-bottom: 1px solid #94a3b8;
      width: 80%;
      margin: 0 auto 6px auto;
    }
    .sig-box-name {
      font-size: 12px;
      color: #1e293b;
    }

    /* Footer Note */
    .inv-footer-note {
      text-align: center;
      margin-top: 30px;
      font-size: 11px;
      color: #94a3b8;
      line-height: 1.5;
    }

    /* Mobile Responsiveness for Invoice Page */
    @media (max-width: 768px) {
      body {
        padding: 12px 8px;
        background: #f1f5f9;
      }
      .action-bar {
        flex-direction: column;
        gap: 10px;
        align-items: stretch;
        margin-bottom: 14px;
      }
      .btn-print {
        width: 100%;
        justify-content: center;
        padding: 12px 16px;
        font-size: 13.5px;
        box-sizing: border-box;
      }
      .btn-back {
        text-align: center;
        padding: 6px;
      }
      .invoice-card {
        padding: 18px 14px;
        border-radius: 8px;
        width: 100%;
        box-sizing: border-box;
      }
      .inv-header {
        flex-direction: column;
        gap: 14px;
        padding-bottom: 16px;
        margin-bottom: 18px;
      }
      .inv-brand-name {
        font-size: 20px;
      }
      .inv-title-box {
        text-align: left;
        border-top: 1px dashed #cbd5e1;
        padding-top: 12px;
        width: 100%;
      }
      .inv-main-title {
        font-size: 16px;
      }
      .inv-grid-info {
        grid-template-columns: 1fr;
        gap: 14px;
        padding: 14px 12px;
        margin-bottom: 20px;
      }
      .inv-calc-box {
        width: 100%;
        margin-bottom: 24px;
      }
      .inv-signatures {
        grid-template-columns: 1fr;
        gap: 28px;
        margin-top: 28px;
        padding-top: 18px;
      }
      .sig-box-title {
        margin-bottom: 40px;
      }
      .sig-box-line {
        width: 60%;
      }
    }

    /* Print Styles for A4 Paper & PDF Output */
    @media print {
      @page {
        size: A4 portrait;
        margin: 15mm 12mm;
      }
      body {
        background: #fff !important;
        padding: 0 !important;
        margin: 0 !important;
        color: #000 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
      }
      .action-bar {
        display: none !important;
      }
      .invoice-card {
        box-shadow: none !important;
        border: none !important;
        padding: 0 !important;
        max-width: 100% !important;
        width: 100% !important;
      }
      .inv-header {
        display: flex !important;
        flex-direction: row !important;
        justify-content: space-between !important;
      }
      .inv-title-box {
        text-align: right !important;
        border-top: none !important;
        padding-top: 0 !important;
        width: auto !important;
      }
      .inv-grid-info {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 20px !important;
      }
      .inv-table-wrap {
        overflow: visible !important;
        border: none !important;
      }
      .inv-table {
        width: 100% !important;
        min-width: 100% !important;
      }
      .inv-calc-box {
        width: 300px !important;
        margin-left: auto !important;
      }
      .inv-signatures {
        display: grid !important;
        grid-template-columns: repeat(3, 1fr) !important;
        gap: 20px !important;
        margin-top: 36px !important;
      }
      .sig-box-title {
        margin-bottom: 55px !important;
      }
      .sig-box-line {
        width: 80% !important;
      }
    }
  </style>
</head>
<body>

  <div class="action-bar">
    <a href="javascript:history.back()" class="btn-back">← Kembali</a>
    <button type="button" class="btn-print" onclick="window.print()">
      🖨️ Cetak / Unduh Faktur PDF (Print)
    </button>
  </div>

  <div class="invoice-card">
    <!-- Header -->
    <div class="inv-header">
      <div>
        <h1 class="inv-brand-name">⚜️ {{ config('site.brand') }}</h1>
        <div class="inv-brand-sub">PUSAT HAMPERS, GIFT BOX &amp; PARCEL GOURMET INDONESIA</div>
        <div class="inv-brand-address">
          {{ config('site.address') }}<br>
          Email: {{ config('site.email') }}
        </div>
      </div>

      <div class="inv-title-box">
        <h2 class="inv-main-title">FAKTUR PENGIRIMAN &amp; INVOICE</h2>
        <div class="inv-number">No: #{{ $order->invoice_number }}</div>
        <div class="inv-date">{{ $order->created_at->format('d/m/Y H:i') }} WIB</div>
        @if($order->payment_status === 'paid' || $order->payment_status === 'verified')
          <span class="inv-badge-paid">✓ LUNAS / TERBAYAR</span>
        @else
          <span class="inv-badge-unpaid">⏳ MENUNGGU PEMBAYARAN</span>
        @endif
      </div>
    </div>

    <!-- 2 Column Customer & Courier Info -->
    <div class="inv-grid-info">
      <div class="inv-info-col">
        <h4>Tujuan Pengiriman / Penerima</h4>
        <p><strong>Nama:</strong> {{ $order->customer_name }}</p>
        <p><strong>Telepon / HP:</strong> {{ $order->customer_phone }}</p>
        <p><strong>Email:</strong> {{ $order->customer_email }}</p>
        <p><strong>Alamat:</strong> {{ $order->address ?: ($order->shipping_address ?: '-') }}</p>
      </div>

      <div class="inv-info-col">
        <h4>Metode Pengiriman &amp; Pembayaran</h4>
        <p><strong>Layanan Ekspedisi:</strong> {{ $order->expedition ?: 'Kurir Khusus / Ambil Sendiri' }}</p>
        <p><strong>Nomor Resi:</strong> <code>{{ $order->tracking_number ?: 'Menunggu Penjemputan Kurir' }}</code></p>
        <p><strong>Metode Bayar:</strong> {{ strtoupper($order->payment_method) }}</p>
        <p><strong>Catatan Khusus / Ucapan:</strong><br>{!! nl2br(e($order->order_note ?: ($order->notes ?: '-'))) !!}</p>
      </div>
    </div>

    <!-- Product Table (Responsive Wrapper) -->
    <div class="inv-table-wrap">
      <table class="inv-table">
        <thead>
          <tr>
            <th style="width: 30px; text-align: center;">No</th>
            <th>Nama Produk &amp; Bingkisan</th>
            <th style="width: 80px; text-align: center;">Qty</th>
            <th style="width: 140px; text-align: right;">Harga Satuan</th>
            <th style="width: 140px; text-align: right;">Subtotal</th>
          </tr>
        </thead>
        <tbody>
          @foreach($order->items as $idx => $item)
            <tr>
              <td style="text-align: center;">{{ $idx + 1 }}</td>
              <td>
                <strong>{{ $item->product_name }}</strong>
              </td>
              <td style="text-align: center;">{{ $item->quantity }}</td>
              <td style="text-align: right;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
              <td style="text-align: right;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Calculation -->
    <div class="inv-calc-box">
      <div class="inv-calc-row">
        <span>Subtotal Produk:</span>
        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
      </div>
      @if($order->discount_amount > 0)
        <div class="inv-calc-row" style="color: #15803d;">
          <span>Potongan Kupon:</span>
          <span>- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
        </div>
      @endif
      <div class="inv-calc-row">
        <span>Biaya Pengiriman:</span>
        <span>Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</span>
      </div>
      <div class="inv-calc-total">
        <span>Total Tagihan:</span>
        <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
      </div>
    </div>

    <!-- Serah Terima Tanda Tangan (Faktur Pengiriman / Pengambilan) -->
    <div class="inv-signatures">
      <div>
        <div class="sig-box-title">Bagian Packing &amp; QC</div>
        <div class="sig-box-line"></div>
        <div class="sig-box-name">( Tim Gudang / Admin )</div>
      </div>
      <div>
        <div class="sig-box-title">Kurir Pengantar / Ekspedisi</div>
        <div class="sig-box-line"></div>
        <div class="sig-box-name">( Driver / Ekspedisi )</div>
      </div>
      <div>
        <div class="sig-box-title">Penerima Paket</div>
        <div class="sig-box-line"></div>
        <div class="sig-box-name">( {{ $order->customer_name }} )</div>
      </div>
    </div>

    <div class="inv-footer-note">
      * Dokumen ini sah sebagai tanda terima penyerahan barang dan faktur penjualan resmi {{ config('site.brand') }}.<br>
      Harap periksa kelengkapan isi hampers dan segel box saat serah terima paket. Terima kasih atas kepercayaan Anda.
    </div>
  </div>

</body>
</html>
