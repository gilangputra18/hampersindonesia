<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Label Pengiriman Ekspedisi - {{ $order->invoice_number }}</title>
  <style>
    * { box-sizing: border-box; }
    body {
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
      margin: 0;
      padding: 20px;
      background: #f1f5f9;
      color: #0f172a;
    }
    .label-wrapper {
      max-width: 450px;
      margin: 0 auto;
      background: #ffffff;
      border: 2px solid #000000;
      border-radius: 4px;
      padding: 16px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .label-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 2px solid #000;
      padding-bottom: 10px;
      margin-bottom: 10px;
    }
    .brand-title {
      font-size: 14px;
      font-weight: 900;
      letter-spacing: 1px;
      text-transform: uppercase;
    }
    .courier-badge {
      font-size: 13px;
      font-weight: 900;
      background: #000;
      color: #fff;
      padding: 4px 8px;
      border-radius: 3px;
      text-transform: uppercase;
    }
    .resi-box {
      border: 2px dashed #000;
      padding: 10px;
      text-align: center;
      margin-bottom: 12px;
      background: #fafafa;
    }
    .resi-label {
      font-size: 10px;
      text-transform: uppercase;
      font-weight: 700;
      color: #475569;
    }
    .resi-number {
      font-family: monospace;
      font-size: 20px;
      font-weight: 900;
      letter-spacing: 2px;
      margin: 4px 0;
    }
    .barcode-mock {
      font-family: 'Libre Barcode 39', monospace, sans-serif;
      font-size: 28px;
      letter-spacing: 4px;
      line-height: 1;
      height: 32px;
      overflow: hidden;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .barcode-stripes {
      display: inline-block;
      height: 28px;
      background: repeating-linear-gradient(
        90deg,
        #000 0px,
        #000 2px,
        #fff 2px,
        #fff 4px,
        #000 4px,
        #000 7px,
        #fff 7px,
        #fff 9px,
        #000 9px,
        #000 13px,
        #fff 13px,
        #fff 15px
      );
      width: 85%;
    }
    .section-box {
      border: 1px solid #000;
      padding: 10px;
      margin-bottom: 10px;
      font-size: 12px;
      line-height: 1.45;
    }
    .section-title {
      font-size: 10.5px;
      font-weight: 900;
      text-transform: uppercase;
      background: #000;
      color: #fff;
      padding: 2px 6px;
      display: inline-block;
      margin-bottom: 6px;
      border-radius: 2px;
    }
    .items-list-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 11px;
      margin-top: 6px;
    }
    .items-list-table th, .items-list-table td {
      border: 1px solid #000;
      padding: 4px 6px;
      text-align: left;
    }
    .warning-banner {
      background: #fee2e2;
      border: 2px solid #ef4444;
      color: #991b1b;
      padding: 8px;
      text-align: center;
      font-weight: 900;
      font-size: 11px;
      text-transform: uppercase;
      margin-bottom: 10px;
      letter-spacing: 0.5px;
    }
    .signature-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 8px;
      margin-top: 10px;
      font-size: 10.5px;
      text-align: center;
    }
    .sig-box {
      border: 1px solid #000;
      padding: 6px;
      height: 70px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .print-actions {
      max-width: 450px;
      margin: 15px auto;
      display: flex;
      gap: 10px;
    }
    .btn-print {
      flex: 1;
      padding: 12px;
      background: #0284c7;
      color: #fff;
      border: none;
      border-radius: 6px;
      font-weight: 700;
      cursor: pointer;
      text-align: center;
      font-size: 13px;
    }
    .btn-close {
      padding: 12px 18px;
      background: #e2e8f0;
      color: #334155;
      text-decoration: none;
      border-radius: 6px;
      font-weight: 700;
      font-size: 13px;
    }
    @media print {
      body { background: #fff; padding: 0; }
      .print-actions { display: none !important; }
      .label-wrapper {
        border: 2px solid #000;
        box-shadow: none;
        width: 100%;
        max-width: 100%;
        page-break-inside: avoid;
      }
    }
  </style>
</head>
<body>

  <div class="print-actions">
    <button class="btn-print" onclick="window.print()">🖨️ Cetak Label Ekspedisi (Print / PDF)</button>
    <a href="{{ route('admin.orders.index') }}" class="btn-close">Kembali</a>
  </div>

  <div class="label-wrapper">
    <div class="label-header">
      <div class="brand-title">
        PUSAT HAMPERS INDONESIA
        <div style="font-size: 9.5px; font-weight: 600; color: #475569;">Gourmet & Luxury Artisan Bakery</div>
      </div>
      <div class="courier-badge">
        {{ $order->courier_name ?: 'Kurir Toko' }}
      </div>
    </div>

    <!-- Airwaybill / Tracking Barcode -->
    <div class="resi-box">
      <div class="resi-label">NOMOR RESI PENGIRIMAN (AWB) / INVOICE:</div>
      <div class="resi-number">{{ $order->tracking_number ?: $order->invoice_number }}</div>
      <div class="barcode-stripes"></div>
      <div style="font-size: 9.5px; font-family: monospace; margin-top: 3px;">INV: {{ $order->invoice_number }}</div>
    </div>

    <!-- Shipper & Consignee -->
    <div class="section-box" style="background: #f8fafc;">
      <div class="section-title">PENERIMA (CONSIGNEE):</div>
      <div style="font-size: 14px; font-weight: 800; color: #000;">{{ $order->customer_name }}</div>
      <div style="font-size: 12.5px; font-weight: 700; margin: 2px 0;">📞 {{ $order->customer_phone }}</div>
      <div style="margin-top: 4px; font-weight: 600;">
        📍 {{ $order->address }}
      </div>
      @if ($order->latitude && $order->longitude)
        <div style="margin-top: 6px; padding: 4px 6px; background: #e0f2fe; border: 1px dashed #0284c7; border-radius: 4px; font-size: 10.5px;">
          <strong>🗺️ Koordinat Maps:</strong> {{ $order->latitude }}, {{ $order->longitude }}
          <div style="font-size: 9.5px; color: #0369a1;">(Kurir dapat membuka link navigasi Google Maps)</div>
        </div>
      @endif
    </div>

    <div class="section-box">
      <div class="section-title">PENGIRIM (SHIPPER):</div>
      <div style="font-weight: 700;">PUSAT HAMPERS INDONESIA</div>
      <div>Butik Surabaya: Jl. Raya Darmo No. 45 | Butik Bandung: Jl. LLRE Martadinata No. 68</div>
      <div>CS & Dispatch: +62 811-1522-82</div>
    </div>

    <!-- Warning Banner -->
    <div class="warning-banner">
      ⚠️ FRAGILE! MAKANAN & KUE ARTISAN MEWAH<br>
      JANGAN DIBANTING • JANGAN DIBALIK • HINDARI PANAS TERIK
    </div>

    <!-- Items Detail -->
    <div class="section-box">
      <div style="display: flex; justify-content: space-between; align-items: center;">
        <span class="section-title">DAFTAR ISI PAKET:</span>
        <span style="font-size: 11px; font-weight: 700;">Jadwal: {{ date('d M Y', strtotime($order->delivery_date)) }}</span>
      </div>
      <table class="items-list-table">
        <thead>
          <tr style="background: #f1f5f9;">
            <th>Item Produk</th>
            <th style="width: 50px; text-align: center;">Qty</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($order->items as $item)
            <tr>
              <td>{{ $item->product_name }}</td>
              <td style="text-align: center; font-weight: 800;">{{ $item->quantity }}x</td>
            </tr>
          @endforeach
        </tbody>
      </table>

      @if ($order->order_note)
        <div style="margin-top: 8px; font-size: 10.5px; border-top: 1px dashed #cbd5e1; padding-top: 6px;">
          <strong>Catatan Pengiriman / Ucapan:</strong>
          <div style="white-space: pre-line; color: #334155; font-style: italic;">{!! nl2br(e(Str::limit($order->order_note, 250))) !!}</div>
        </div>
      @endif
    </div>

    <!-- Signature -->
    <div class="signature-grid">
      <div class="sig-box">
        <span>Pengirim / Packer:</span>
        <span style="border-bottom: 1px solid #94a3b8; width: 80%; margin: 0 auto 4px;"></span>
        <span style="font-size: 9px; color: #64748b;">(Petugas Toko)</span>
      </div>
      <div class="sig-box">
        <span>Kurir / Driver Ekspedisi:</span>
        <span style="border-bottom: 1px solid #94a3b8; width: 80%; margin: 0 auto 4px;"></span>
        <span style="font-size: 9px; color: #64748b;">(Tanda Tangan & Nama Terang)</span>
      </div>
    </div>
  </div>

</body>
</html>
