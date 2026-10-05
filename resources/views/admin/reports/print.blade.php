<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Penjualan PUSAT HAMPERS INDONESIA - {{ date('d M Y') }}</title>
  <style>
    body {
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      color: #1e293b;
      margin: 0;
      padding: 30px;
      background: #fff;
    }
    .report-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 2px solid #0f172a;
      padding-bottom: 20px;
      margin-bottom: 30px;
    }
    .brand-title {
      font-size: 24px;
      font-weight: 700;
      letter-spacing: 2px;
      color: #0f172a;
    }
    .report-tag {
      font-size: 12px;
      color: #64748b;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    .metrics-row {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px;
      margin-bottom: 30px;
    }
    .metric-box {
      border: 1px solid #cbd5e1;
      padding: 16px;
      border-radius: 8px;
      background: #f8fafc;
    }
    .metric-label {
      font-size: 11px;
      text-transform: uppercase;
      color: #64748b;
      letter-spacing: 1px;
    }
    .metric-val {
      font-size: 22px;
      font-weight: 700;
      color: #0f172a;
      margin-top: 4px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      font-size: 12px;
    }
    th, td {
      border: 1px solid #cbd5e1;
      padding: 10px 12px;
      text-align: left;
    }
    th {
      background: #0f172a;
      color: #fff;
      text-transform: uppercase;
      font-size: 11px;
      letter-spacing: 1px;
    }
    .text-right { text-align: right; }
    .text-center { text-align: center; }

    @media print {
      body { padding: 0; }
      .no-print { display: none !important; }
    }
  </style>
</head>
<body>

<div class="no-print" style="margin-bottom: 20px; text-align: right;">
  <button onclick="window.print()" style="padding: 10px 24px; background: #0f172a; color: #fff; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">
    🖨️ Cetak / Simpan sebagai PDF
  </button>
</div>

<div class="report-header">
  <div>
    <div class="brand-title">⚜️ PUSAT HAMPERS INDONESIA</div>
    <div class="report-tag">Laporan Penjualan Resmi & Rekapan Omset</div>
  </div>
  <div style="text-align: right;">
    <div><strong>Tanggal Cetak:</strong> {{ date('d M Y, H:i') }} WIB</div>
    <div><strong>Filter Periode:</strong> {{ strtoupper(str_replace('_', ' ', $period)) }}</div>
  </div>
</div>

<div class="metrics-row">
  <div class="metric-box">
    <div class="metric-label">Total Omset Penjualan</div>
    <div class="metric-val">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
  </div>
  <div class="metric-box" style="border-color: #10b981; background: #f0fdf4;">
    <div class="metric-label" style="color: #166534;">Laba Keuntungan Bersih</div>
    <div class="metric-val" style="color: #15803d;">Rp {{ number_format($netProfit, 0, ',', '.') }}</div>
    <div style="font-size: 11px; color: #15803d; font-weight: 600; margin-top: 2px;">Margin {{ $profitMargin }}%</div>
  </div>
  <div class="metric-box">
    <div class="metric-label">Total Transaksi</div>
    <div class="metric-val">{{ $totalOrdersCount }} Order</div>
  </div>
  <div class="metric-box">
    <div class="metric-label">Pesanan Lunas</div>
    <div class="metric-val">{{ $paidCount }} Order</div>
  </div>
</div>

<h3>Detail Transaksi Penjualan</h3>

<table>
  <thead>
    <tr>
      <th>No. Invoice</th>
      <th>Tanggal</th>
      <th>Pelanggan</th>
      <th>Metode</th>
      <th>Status Pembayaran</th>
      <th class="text-right">Total Tagihan</th>
    </tr>
  </thead>
  <tbody>
    @forelse ($orders as $o)
      <tr>
        <td><strong>{{ $o->invoice_number }}</strong></td>
        <td>{{ $o->created_at->format('d/m/Y H:i') }}</td>
        <td>
          <strong>{{ $o->customer_name }}</strong><br>
          <small style="color: #64748b;">{{ $o->customer_phone }}</small>
        </td>
        <td>{{ strtoupper($o->delivery_option) }}</td>
        <td>{{ strtoupper($o->payment_status) }}</td>
        <td class="text-right" style="font-weight: 700;">
          Rp {{ number_format($o->total_amount, 0, ',', '.') }}
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="6" class="text-center" style="padding: 20px;">
          Tidak ada data transaksi penjualan pada periode ini.
        </td>
      </tr>
    @endforelse
  </tbody>
</table>

</body>
</html>
