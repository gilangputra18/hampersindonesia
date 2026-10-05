@extends('layouts.admin')

@section('title', 'Kelola Kupon Promo')
@section('page_title', 'Kelola Kupon & Promo')

@section('content')
<style>
  .coupon-card {
    background: var(--panel-bg);
    border: 1px solid var(--panel-border);
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 28px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
  }
  .coupon-table th { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); padding: 12px 14px; }
  .coupon-table td { padding: 14px; vertical-align: middle; border-bottom: 1px solid var(--panel-border); font-size: 13px; }
  .coupon-table tr:last-child td { border-bottom: none; }
  .code-pill {
    font-family: monospace; font-size: 14px; font-weight: 800;
    background: rgba(245,158,11,0.15); color: #fbbf24;
    border: 1px solid rgba(245,158,11,0.3);
    padding: 4px 12px; border-radius: 6px; letter-spacing: 1.5px;
  }
  .badge-active   { background: #dcfce7; color: #166534; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 700; }
  .badge-inactive { background: #fee2e2; color: #991b1b; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 700; }
  .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
  @media(max-width:640px) { .form-grid { grid-template-columns: 1fr; } }
</style>

{{-- Flash Message --}}
@if(session('success'))
  <div style="background:rgba(16,185,129,0.15);border:1px solid #10b981;color:#6ee7b7;padding:12px 18px;border-radius:8px;margin-bottom:20px;font-size:13px;font-weight:600;">
    ✅ {{ session('success') }}
  </div>
@endif

{{-- ADD COUPON FORM --}}
<div class="coupon-card">
  <h3 style="font-family:'Playfair Display',serif;color:#fef08a;font-size:18px;margin-bottom:20px;border-bottom:1px solid var(--panel-border);padding-bottom:12px;">
    🎟️ Tambah Kupon Baru
  </h3>
  <form action="{{ route('admin.coupons.store') }}" method="POST">
    @csrf
    <div class="form-grid">
      <div class="form-group">
        <label class="form-label">Kode Kupon *</label>
        <input type="text" name="code" class="form-control" placeholder="LEBARAN25" required
          value="{{ old('code') }}" style="text-transform:uppercase;font-family:monospace;font-weight:700;letter-spacing:2px;">
        <small style="color:var(--text-muted);font-size:11px;">Huruf kapital, tanpa spasi. Contoh: GRATIS10</small>
      </div>
      <div class="form-group">
        <label class="form-label">Deskripsi Kupon</label>
        <input type="text" name="description" class="form-control" placeholder="Promo Hari Raya - Diskon 20%" value="{{ old('description') }}">
      </div>
      <div class="form-group">
        <label class="form-label">Tipe Diskon *</label>
        <select name="type" class="form-control" id="coupon-type" onchange="toggleMaxDiscount()">
          <option value="percent" {{ old('type')=='percent'?'selected':'' }}>Persentase (%)</option>
          <option value="fixed"   {{ old('type')=='fixed'  ?'selected':'' }}>Potongan Tetap (Rp)</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Nilai Diskon *</label>
        <input type="number" name="value" class="form-control" placeholder="20 (untuk 20% atau Rp20000)" required min="1" value="{{ old('value') }}">
      </div>
      <div class="form-group" id="max-discount-group">
        <label class="form-label">Maks. Diskon (Rp) — untuk persen</label>
        <input type="number" name="max_discount" class="form-control" placeholder="50000" min="0" value="{{ old('max_discount') }}">
        <small style="color:var(--text-muted);font-size:11px;">Kosongkan = tidak dibatasi</small>
      </div>
      <div class="form-group">
        <label class="form-label">Minimum Pembelian (Rp)</label>
        <input type="number" name="min_purchase" class="form-control" placeholder="0" min="0" value="{{ old('min_purchase', 0) }}">
      </div>
      <div class="form-group">
        <label class="form-label">Batas Penggunaan</label>
        <input type="number" name="usage_limit" class="form-control" placeholder="Kosongkan = tak terbatas" min="1" value="{{ old('usage_limit') }}">
      </div>
      <div class="form-group">
        <label class="form-label">Berlaku Dari</label>
        <input type="date" name="valid_from" class="form-control" value="{{ old('valid_from') }}">
      </div>
      <div class="form-group">
        <label class="form-label">Berlaku Sampai</label>
        <input type="date" name="valid_until" class="form-control" value="{{ old('valid_until') }}">
      </div>
      <div class="form-group" style="display:flex;align-items:center;gap:10px;padding-top:20px;">
        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}
          style="width:18px;height:18px;accent-color:#f59e0b;">
        <label for="is_active" style="margin:0;font-size:13px;">Aktifkan kupon sekarang</label>
      </div>
    </div>
    @if($errors->any())
      <div style="background:rgba(239,68,68,0.1);border:1px solid #ef4444;padding:12px 16px;border-radius:8px;margin-bottom:16px;color:#f87171;font-size:12px;">
        @foreach($errors->all() as $e) <div>❌ {{ $e }}</div> @endforeach
      </div>
    @endif
    <button type="submit" class="btn btn-primary" style="margin-top:8px;">🎟️ Simpan Kupon</button>
  </form>
</div>

{{-- COUPON LIST --}}
<div class="coupon-card">
  <h3 style="font-family:'Playfair Display',serif;color:#fef08a;font-size:18px;margin-bottom:20px;border-bottom:1px solid var(--panel-border);padding-bottom:12px;">
    📋 Daftar Kupon ({{ $coupons->total() }})
  </h3>
  <div style="overflow-x:auto;">
    <table style="width:100%;border-collapse:collapse;" class="coupon-table">
      <thead>
        <tr style="border-bottom:2px solid var(--panel-border);">
          <th>Kode</th><th>Tipe & Nilai</th><th>Min. Beli</th><th>Penggunaan</th><th>Masa Berlaku</th><th>Status</th><th style="text-align:right;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($coupons as $c)
          <tr>
            <td>
              <div class="code-pill">{{ $c->code }}</div>
              @if($c->description)
                <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">{{ $c->description }}</div>
              @endif
            </td>
            <td>
              <strong>{{ $c->discount_label }}</strong>
              @if($c->max_discount) <div style="font-size:11px;color:var(--text-muted);">Maks Rp {{ number_format($c->max_discount,0,',','.') }}</div> @endif
            </td>
            <td>{{ $c->min_purchase > 0 ? 'Rp '.number_format($c->min_purchase,0,',','.') : 'Bebas' }}</td>
            <td>
              <strong>{{ $c->used_count }}</strong>
              {{ $c->usage_limit ? '/ '.$c->usage_limit : '/ ∞' }}
            </td>
            <td style="font-size:12px;">
              @if($c->valid_from || $c->valid_until)
                {{ $c->valid_from?->format('d M Y') ?? '∞' }} —<br>{{ $c->valid_until?->format('d M Y') ?? '∞' }}
              @else
                <span style="color:var(--text-muted);">Tanpa batas</span>
              @endif
            </td>
            <td>
              <span class="{{ $c->is_active ? 'badge-active' : 'badge-inactive' }}">
                {{ $c->is_active ? '✅ Aktif' : '❌ Nonaktif' }}
              </span>
            </td>
            <td style="text-align:right;">
              <div style="display:inline-flex;gap:8px;flex-wrap:wrap;justify-content:flex-end;">
                <form action="{{ route('admin.coupons.toggle', $c->id) }}" method="POST" style="display:inline;">
                  @csrf
                  <button type="submit" class="btn btn-outline" style="padding:5px 10px;font-size:11px;">
                    {{ $c->is_active ? '⏸ Nonaktifkan' : '▶ Aktifkan' }}
                  </button>
                </form>
                <form action="{{ route('admin.coupons.destroy', $c->id) }}" method="POST" style="display:inline;"
                  onsubmit="return confirm('Hapus kupon {{ $c->code }}?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-danger" style="padding:5px 10px;font-size:11px;">🗑 Hapus</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted);">Belum ada kupon. Tambahkan kupon pertama di atas!</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div style="margin-top:20px;">{{ $coupons->links('partials.pagination') }}</div>
</div>

<script>
function toggleMaxDiscount() {
  const type = document.getElementById('coupon-type').value;
  document.getElementById('max-discount-group').style.display = type === 'percent' ? 'block' : 'none';
}
toggleMaxDiscount();
</script>
@endsection
