@extends('layouts.app')

@section('title', 'Riwayat Pesanan Saya | ' . config('site.brand'))

@section('content')
<style>
  .my-orders-page {
    background: #faf7f4;
    min-height: 100vh;
    padding: 48px 20px 100px;
    font-family: 'Outfit', sans-serif;
    color: #1e293b;
  }
  .my-orders-container {
    max-width: 900px;
    margin: 0 auto;
  }
  .page-header {
    margin-bottom: 32px;
    border-bottom: 1.5px solid #ecd5dc;
    padding-bottom: 20px;
  }
  .page-header h1 {
    font-family: 'Playfair Display', serif;
    font-size: 28px;
    color: #722F37;
    letter-spacing: 1px;
    margin: 0 0 6px;
    font-weight: 700;
  }
  .page-header p {
    font-size: 13px;
    color: #64748b;
    margin: 0;
  }
  .order-card {
    background: #fff;
    border: 1.5px solid #ecd5dc;
    border-radius: 14px;
    margin-bottom: 20px;
    overflow: hidden;
    transition: all 0.2s ease;
    box-shadow: 0 4px 20px rgba(114, 47, 55, 0.05);
  }
  .order-card:hover {
    border-color: #722F37;
    box-shadow: 0 8px 30px rgba(114, 47, 55, 0.1);
  }
  .order-card-header {
    background: linear-gradient(90deg, #380a17 0%, #1a0207 100%);
    border-bottom: 1px solid rgba(230, 202, 101, 0.35);
    padding: 14px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
  }
  .order-invoice {
    font-family: 'Outfit', monospace;
    font-size: 13.5px;
    font-weight: 700;
    color: #fef08a;
    letter-spacing: 0.5px;
  }
  .order-date {
    font-size: 12px;
    color: #cbd5e1;
  }
  .order-card-body {
    padding: 18px 20px;
  }
  .order-items-list {
    list-style: none;
    padding: 0;
    margin: 0 0 16px;
  }
  .order-items-list li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px dashed #e2e8f0;
    font-size: 13.5px;
  }
  .order-items-list li:last-child {
    border-bottom: none;
  }
  .item-name { color: #334155; font-weight: 600; }
  .item-qty { color: #64748b; font-size: 12px; }
  .item-price { font-weight: 700; color: #722F37; }
  .order-meta {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 12px;
    margin-top: 14px;
    padding-top: 14px;
    border-top: 1px solid #f1f5f9;
  }
  .meta-item label {
    display: block;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #722F37;
    margin-bottom: 3px;
  }
  .meta-item span {
    font-size: 13px;
    font-weight: 600;
    color: #1e293b;
  }
  .badge-status {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
  }
  .badge-pending    { background: #fef3c7; color: #92400e; }
  .badge-processing { background: #dbeafe; color: #1e40af; }
  .badge-shipping   { background: #e0f2fe; color: #0369a1; border: 1px solid #38bdf8; font-weight: 800; }
  .badge-completed  { background: #dcfce7; color: #166534; }
  .badge-cancelled  { background: #fee2e2; color: #991b1b; }
  .badge-paid                 { background: #dcfce7; color: #166534; }
  .badge-unpaid               { background: #fef3c7; color: #92400e; }
  .badge-waiting_verification { background: #fef3c7; color: #b45309; border: 1px solid #f59e0b; }
  .badge-verified             { background: #e0f2fe; color: #0369a1; }
  .order-total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 14px;
    padding-top: 14px;
    border-top: 1.5px solid #ecd5dc;
  }
  .total-label {
    font-size: 12px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.8px;
  }
  .total-amount {
    font-size: 19px;
    font-weight: 800;
    color: #722F37;
  }
  .btn-view-order {
    display: inline-block;
    padding: 9px 20px;
    background: linear-gradient(135deg, #722F37 0%, #4a0d1f 100%);
    color: #ffffff !important;
    border-radius: 6px;
    font-size: 12.5px;
    font-weight: 700;
    text-decoration: none;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    transition: all 0.2s ease;
    border: 1px solid rgba(230, 202, 101, 0.4);
    box-shadow: 0 3px 12px rgba(114, 47, 55, 0.2);
  }
  .btn-view-order:hover {
    background: linear-gradient(135deg, #873843 0%, #591226 100%);
    box-shadow: 0 6px 18px rgba(114, 47, 55, 0.3);
    transform: translateY(-1px);
  }
  .empty-state {
    text-align: center;
    padding: 80px 20px;
    color: #64748b;
  }
  .empty-state .icon { font-size: 64px; margin-bottom: 16px; }
  .empty-state h3 { font-family: 'Playfair Display', serif; font-size: 22px; color: #722F37; margin-bottom: 8px; font-weight: 700; }
  .empty-state p { font-size: 14px; margin-bottom: 24px; }
  .btn-shop {
    display: inline-block;
    padding: 12px 28px;
    background: linear-gradient(135deg, #722F37 0%, #4a0d1f 100%);
    color: #ffffff !important;
    border-radius: 6px;
    font-weight: 700;
    text-decoration: none;
    font-size: 13px;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    border: 1px solid rgba(230, 202, 101, 0.4);
    box-shadow: 0 4px 15px rgba(114, 47, 55, 0.25);
    transition: all 0.2s ease;
  }
  .btn-shop:hover {
    background: linear-gradient(135deg, #873843 0%, #591226 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(114, 47, 55, 0.35);
  }
  @media (max-width: 600px) {
    .order-card-header { flex-direction: column; align-items: flex-start; gap: 6px; }
    .order-total-row { flex-direction: column; gap: 12px; align-items: stretch; }
    .btn-view-order { text-align: center; width: 100%; box-sizing: border-box; }
    .my-orders-page { padding: 20px 10px 80px; }
    .order-card-body { padding: 14px 14px; }
    .order-meta { grid-template-columns: 1fr 1fr; gap: 10px; }
  }
</style>

<div class="my-orders-page">
  <div class="my-orders-container">

    <div class="page-header">
      <h1>📦 Riwayat Pesanan Saya</h1>
      <p>Halo, <strong>{{ auth()->user()->name }}</strong> — berikut semua pesanan yang pernah Anda buat.</p>
    </div>

    @if(session('success'))
      <div style="background:#dcfce7;color:#166534;padding:12px 16px;border-radius:8px;margin-bottom:20px;font-size:13px;font-weight:600;">
        ✅ {{ session('success') }}
      </div>
    @endif

    @if($orders->count() > 0)
      @foreach($orders as $order)
        <div class="order-card">
          <div class="order-card-header">
            <div>
              <div class="order-invoice">🧾 {{ $order->invoice_number }}</div>
              @if($order->tracking_number)
                <div style="font-size:11px;color:#86efac;margin-top:2px;">📍 Resi: {{ $order->tracking_number }}</div>
              @endif
            </div>
            <div class="order-date">{{ $order->created_at->format('d M Y, H:i') }} WIB</div>
          </div>

          <div class="order-card-body">
            <ul class="order-items-list">
              @foreach($order->items as $item)
                <li>
                  <span class="item-name">{{ $item->product_name }}</span>
                  <span class="item-qty">× {{ $item->quantity }}</span>
                  <span class="item-price">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </li>
              @endforeach
            </ul>

            <div class="order-meta">
              <div class="meta-item">
                <label>Status Pesanan</label>
                <span class="badge-status badge-{{ $order->order_status }}">
                  @if($order->order_status === 'shipping')
                    🚚 Dikirim Kurir
                  @elseif($order->order_status === 'processing')
                    ⚙️ Diproses
                  @elseif($order->order_status === 'completed')
                    ✅ Selesai
                  @elseif($order->order_status === 'cancelled')
                    ❌ Dibatalkan
                  @else
                    ⏳ Menunggu
                  @endif
                </span>
              </div>
              <div class="meta-item">
                <label>Status Bayar</label>
                <span class="badge-status badge-{{ $order->payment_status }}">
                  @if($order->payment_status === 'waiting_verification')
                    ⏳ Menunggu Verifikasi
                  @elseif($order->payment_status === 'paid' || $order->payment_status === 'verified')
                    ✅ Lunas
                  @elseif($order->payment_status === 'unpaid')
                    ⚠️ Belum Bayar
                  @else
                    {{ ucfirst($order->payment_status) }}
                  @endif
                </span>
                @if($order->payment_proof)
                  <div style="font-size: 11px; color: #0284c7; margin-top: 3px; font-weight: 600;">
                    📎 Bukti sudah diunggah
                  </div>
                @endif
              </div>
              <div class="meta-item">
                <label>Metode Bayar</label>
                <span>{{ $order->payment_method ?? '-' }}</span>
              </div>
              <div class="meta-item">
                <label>Pengiriman</label>
                <span>{{ strtoupper($order->delivery_option) }} — {{ $order->courier_name ?? 'Kurir Toko' }}</span>
              </div>
              <div class="meta-item">
                <label>Tanggal Kirim</label>
                <span>{{ $order->delivery_date ? $order->delivery_date->format('d M Y') : '-' }}</span>
              </div>
              <div class="meta-item">
                <label>Ongkir</label>
                <span>{{ $order->delivery_fee > 0 ? 'Rp ' . number_format($order->delivery_fee, 0, ',', '.') : 'GRATIS' }}</span>
              </div>
            </div>

            <div class="order-total-row">
              <div>
                <div class="total-label">Total Pembayaran</div>
                <div class="total-amount">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
              </div>
              <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
                @if($order->order_status === 'shipping' || $order->tracking_number)
                  <a href="{{ route('order.track', ['invoice' => $order->invoice_number]) }}" class="btn-view-order" style="background:#0284c7;color:#fff;">
                    🚚 Lacak Kurir
                  </a>
                @endif
                <a href="{{ route('order.show', $order->invoice_number) }}" class="btn-view-order">
                  Lihat Detail →
                </a>
              </div>
            </div>
          </div>
        </div>
      @endforeach

      <div style="margin-top:24px;">
        {{ $orders->links() }}
      </div>

    @else
      <div class="empty-state">
        <div class="icon">🛒</div>
        <h3>Belum Ada Pesanan</h3>
        <p>Anda belum pernah melakukan pemesanan. Yuk mulai belanja produk pilihan kami!</p>
        <a href="{{ route('home') }}" class="btn-shop">🥐 Belanja Sekarang</a>
      </div>
    @endif

  </div>
</div>
@endsection
