@extends('layouts.admin')

@section('title', 'Kelola Ongkir & Kerjasama Ekspedisi')
@section('page_title', 'Kelola Tarif Ongkir & Integrasi Ekspedisi (Biteship/Shipper)')

@section('content')
<style>
  /* Header Card */
  .shipping-header-card {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    border: 1px solid rgba(245, 158, 11, 0.3);
    border-radius: 16px;
    padding: 24px 30px;
    margin-bottom: 32px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
  }
  .shipping-header-title h3 {
    font-family: 'Playfair Display', serif;
    font-size: 22px;
    font-weight: 700;
    color: var(--accent-gold);
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 6px;
  }
  .shipping-header-title p {
    font-size: 13px;
    color: var(--text-muted);
    max-width: 650px;
    line-height: 1.5;
  }
  .partner-badge {
    background: rgba(34, 197, 94, 0.15);
    border: 1px solid rgba(34, 197, 94, 0.3);
    color: #4ade80;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  /* Grid Section */
  .shipping-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 24px;
    margin-bottom: 32px;
  }
  .shipping-card {
    background: var(--panel-bg);
    border: 1px solid var(--panel-border);
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    transition: transform 0.2s;
  }
  .shipping-card:hover {
    border-color: var(--accent-gold);
    transform: translateY(-2px);
  }
  .card-head-title {
    font-size: 16px;
    font-weight: 700;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 18px;
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(255,255,255,0.08);
  }

  .form-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
  }

  /* Info Tips Box */
  .tips-card {
    background: linear-gradient(135deg, rgba(217, 119, 6, 0.1) 0%, rgba(245, 158, 11, 0.05) 100%);
    border: 1px solid rgba(245, 158, 11, 0.3);
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 32px;
  }
  .tips-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--accent-gold);
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .tips-list {
    margin-left: 20px;
    font-size: 13px;
    color: #cbd5e1;
    line-height: 1.7;
  }

  .btn-save-shipping {
    background: linear-gradient(135deg, var(--accent-gold) 0%, #b45309 100%);
    color: #fff;
    border: none;
    padding: 14px 36px;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.3);
    transition: all 0.2s ease;
  }
  .btn-save-shipping:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(245, 158, 11, 0.4);
  }
</style>

<!-- 1. Header Banner -->
<div class="shipping-header-card">
  <div class="shipping-header-title">
    <h3>🚚 Pengaturan Ongkos Kirim & Kerjasama Ekspedisi</h3>
    <p>
      Atur tarif ongkir murah per ekspedisi, kuota Gratis Ongkir otomatis, serta integrasi API Aggregator Ekspedisi (Biteship, Shipper, RajaOngkir) untuk diskon korporat pengiriman toko Anda.
    </p>
  </div>
  <div class="partner-badge">
    🤝 Diskon Mitra Korporat Active (-{{ $settings['corporate_discount_percent'] ?? 25 }}%)
  </div>
</div>

@if (session('success'))
  <div style="background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; color: #34d399; padding: 14px 20px; border-radius: 10px; margin-bottom: 24px; font-size: 14px;">
    ✅ {{ session('success') }}
  </div>
@endif

<form action="{{ route('admin.shipping.update') }}" method="POST">
  @csrf

  <!-- 2. Tariffs & Free Shipping Rules Grid -->
  <div class="shipping-grid">
    <!-- Card 1: Rule Gratis Ongkir -->
    <div class="shipping-card">
      <div class="card-head-title">
        <span>🎁 Program Gratis Ongkir (Free Shipping)</span>
      </div>

      <div class="form-group">
        <label class="form-label">Minimal Belanja Untuk Gratis Ongkir (Rp)</label>
        <input type="number" name="free_shipping_min" class="form-control" value="{{ old('free_shipping_min', $settings['free_shipping_min'] ?? 500000) }}" style="font-weight: 700; color: #34d399; font-size: 16px;">
        <small style="color: var(--text-muted); font-size: 11px; margin-top: 4px; display: block;">
          Jika total belanja produk melebihi angka ini, ongkos kirim otomatis menjadi Rp 0 (FREE).
        </small>
      </div>

      <div class="form-group" style="margin-top: 16px;">
        <label class="form-label">Alamat Toko / Titik Penjemputan Kurir</label>
        <textarea name="pickup_address" class="form-control" rows="2">{{ old('pickup_address', $settings['pickup_address'] ?? 'Jl. Cikajang V No. 12, Jakarta Selatan') }}</textarea>
      </div>
    </div>

    <!-- Card 2: Tarif Ekspedisi Terjangkau -->
    <div class="shipping-card">
      <div class="card-head-title">
        <span>📦 Tarif Flat Ongkir Per Ekspedisi (Rp)</span>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
        <div class="form-group">
          <label class="form-label">JNE Express (Reguler)</label>
          <input type="number" name="jne_rate" class="form-control" value="{{ old('jne_rate', $settings['jne_rate'] ?? 15000) }}">
        </div>

        <div class="form-group">
          <label class="form-label">J&T Express</label>
          <input type="number" name="jnt_rate" class="form-control" value="{{ old('jnt_rate', $settings['jnt_rate'] ?? 16000) }}">
        </div>

        <div class="form-group">
          <label class="form-label">Kurir Dedicated Toko</label>
          <input type="number" name="store_courier_rate" class="form-control" value="{{ old('store_courier_rate', $settings['store_courier_rate'] ?? 20000) }}">
        </div>

        <div class="form-group">
          <label class="form-label">GoSend / Grab Instant</label>
          <input type="number" name="gosend_rate" class="form-control" value="{{ old('gosend_rate', $settings['gosend_rate'] ?? 25000) }}">
        </div>
      </div>
    </div>

    <!-- Card 3: Integrasi API Aggregator Ekspedisi -->
    <div class="shipping-card" style="grid-column: 1 / -1; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-color: rgba(34, 197, 94, 0.3);">
      <div class="card-head-title" style="color: #4ade80;">
        <span>🔌 Integrasi API Aggregator Ekspedisi (Biteship / Shipper)</span>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
        <div class="form-group">
          <label class="form-label">Nama Provider Aggregator</label>
          <input type="text" name="aggregator_partner" class="form-control" value="{{ old('aggregator_partner', $settings['aggregator_partner'] ?? 'Biteship API (Aggregator JNE, J&T, Grab, GoSend)') }}">
        </div>

        <div class="form-group">
          <label class="form-label">API Key Aggregator</label>
          <input type="password" name="api_key" class="form-control" value="{{ old('api_key', $settings['api_key'] ?? 'biteship_live_sec_XXXXXXXXXXXXXX') }}" style="font-family: monospace;">
        </div>

        <div class="form-group">
          <label class="form-label">Diskon Khusus Mitra Korporat (%)</label>
          <input type="number" name="corporate_discount_percent" class="form-control" value="{{ old('corporate_discount_percent', $settings['corporate_discount_percent'] ?? 25) }}" style="font-weight: 700; color: #4ade80;">
        </div>
      </div>
    </div>
  </div>

  <!-- 3. Panduan Strategi Kerjasama Ekspedisi Ongkir Murah -->
  <div class="tips-card">
    <div class="tips-title">
      <span>💡 CARA STRATEGIS MENDAPATKAN ONGKIR MURAH & KERJASAMA EKSPEDISI</span>
    </div>
    <ul class="tips-list">
      <li><strong>Gunakan API Aggregator (Biteship / Shipper / Borzo):</strong> Menggunakan aggregator seperti Biteship memberikan akun bisnis diskon otomatis 15% - 30% dari tarif umum JNE, J&T, SiCepat, & Anteraja tanpa perlu kontrak manual terpisah.</li>
      <li><strong>Daftar Akun Korporat JNE VIP / J&T Cargo:</strong> Jika volume pengiriman toko di atas 30 paket/bulan, Anda bisa mendaftar akun VIP JNE/J&T untuk mendapatkan cashback bulanan 15% - 25% dan fasilitas *Free Pick Up* harian.</li>
      <li><strong>Subsidi Ongkir Bertingkat (Free Shipping Threshold):</strong> Tetapkan batas belanja (misal: Gratis Ongkir di atas Rp 500.000). Hal ini mendorong pelanggan menambah item belanja (*average order value* naik) untuk hemat ongkir.</li>
      <li><strong>Kurir Dedicated Toko:</strong> Untuk pengiriman cake & kue basah rentan di area lokal (Jabodetabek), gunakan kurir motor internal toko dengan flat rate Rp 20.000 untuk menjaga bentuk cake dan efisiensi biaya.</li>
    </ul>
  </div>

  <div style="text-align: right; margin-bottom: 40px;">
    <button type="submit" class="btn-save-shipping">
      💾 Simpan Pengaturan Ongkir & Ekspedisi
    </button>
  </div>
</form>
@endsection
