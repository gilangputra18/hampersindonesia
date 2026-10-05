@extends('layouts.app')

@section('title', 'Riwayat Pesanan Saya | ' . config('site.brand'))

@section('content')
<style>
  .my-orders-page {
    background: #f7f9f8;
    min-height: 100vh;
    padding: 48px 20px 100px;
    font-family: 'Outfit', sans-serif;
    color: #1e2d27;
  }
  .my-orders-container {
    max-width: 900px;
    margin: 0 auto;
  }
  .page-header {
    margin-bottom: 32px;
    border-bottom: 1px solid #d2dcd7;
    padding-bottom: 20px;
  }
  .page-header h1 {
    font-family: 'Playfair Display', serif;
    font-size: 28px;
    color: #111f18;
    letter-spacing: 1px;
    margin: 0 0 6px;
  }
  .page-header p {
    font-size: 13px;
    color: #64748b;
    margin: 0;
  }
  .order-card {
    background: #fff;
    border: 1px solid #d2dcd7;
    border-radius: 10px;
    margin-bottom: 20px;
    overflow: hidden;
    transition: box-shadow 0.2s ease;
  }
  .order-card:hover {
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
  }
  .order-card-header {
    background: linear-gradient(90deg, #0e1a15, #15241e);
    padding: 14px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
  }
  .order-invoice {
    font-family: 'Outfit', monospace;
    font-size: 13px;
    font-weight: 700;
    color: #f59e0b;
    letter-spacing: 1px;
  }
  .order-date {
    font-size: 12px;
    color: #94a3b8;
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
  .item-name { color: #334155; }
  .item-qty { color: #64748b; font-size: 12px; }
  .item-price { font-weight: 600; color: #1e2d27; }
  .order-meta {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 12px;
    margin-top: 14px;
    padding-top: 14px;
    border-top: 1px solid #e2e8f0;
  }
  .meta-item label {
    display: block;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #94a3b8;
    margin-bottom: 3px;
  }
  .meta-item span {
    font-size: 13px;
    font-weight: 500;
    color: #1e2d27;
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
  .badge-completed  { background: #dcfce7; color: #166534; }
  .badge-cancelled  { background: #fee2e2; color: #991b1b; }
  .badge-paid       { background: #dcfce7; color: #166534; }
  .badge-unpaid     { background: #fef3c7; color: #92400e; }
  .badge-verified   { background: #e0f2fe; color: #0369a1; }
  .order-total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 14px;
    padding-top: 14px;
    border-top: 2px solid #111f18;
  }
  .total-label {
    font-size: 13px;
    font-weight: 600;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.8px;
  }
  .total-amount {
    font-size: 18px;
    font-weight: 700;
    color: #111f18;
  }
  .btn-view-order {
    display: inline-block;
    padding: 9px 20px;
    background: linear-gradient(135deg, #111f18, #1a3326);
    color: #f59e0b;
    border-radius: 6px;
    font-size: 12.5px;
    font-weight: 700;
    text-decoration: none;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    transition: all 0.2s ease;
    border: 1px solid rgba(245, 158, 11, 0.3);
  }
  .btn-view-order:hover {
    background: linear-gradient(135deg, #1a3326, #243d2e);
    box-shadow: 0 4px 14px rgba(245, 158, 11, 0.2);
    color: #fef08a;
  }
  .empty-state {
    text-align: center;
    padding: 80px 20px;
    color: #64748b;
  }
  .empty-state .icon { font-size: 64px; margin-bottom: 16px; }
  .empty-state h3 { font-family: 'Playfair Display', serif; font-size: 22px; color: #334155; margin-bottom: 8px; }
  .empty-state p { font-size: 14px; margin-bottom: 24px; }
  .btn-shop {
    display: inline-block;
    padding: 12px 28px;
    background: linear-gradient(135deg, #d97706, #f59e0b);
    color: #0d1713;
    border-radius: 6px;
    font-weight: 700;
    text-decoration: none;
    font-size: 13px;
    letter-spacing: 0.8px;
    text-transform: uppercase;
  }
  @media (max-width: 600px) {
    .order-card-header { flex-direction: column; align-items: flex-start; }
    .order-total-row { flex-direction: column; gap: 12px; align-items: flex-start; }
    .my-orders-page { padding: 24px 16px 80px; }
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
                <span class="badge-status badge-{{ $order->order_status }}">{{ ucfirst($order->order_status) }}</span>
              </div>
              <div class="meta-item">
                <label>Status Bayar</label>
                <span class="badge-status badge-{{ $order->payment_status }}">{{ ucfirst($order->payment_status) }}</span>
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
              <a href="{{ route('order.show', $order->invoice_number) }}" class="btn-view-order">
                Lihat Detail & Bayar →
              </a>
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
