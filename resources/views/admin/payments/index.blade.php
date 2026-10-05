@extends('layouts.admin')

@section('title', 'Kelola Metode Pembayaran')
@section('page_title', 'Kelola Rekening Bank & Metode Pembayaran')

@section('content')
<style>
  /* Payment Header Banner */
  .payment-header-card {
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
    position: relative;
    overflow: hidden;
  }
  .payment-header-card::after {
    content: '💳';
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 100px;
    opacity: 0.05;
    pointer-events: none;
  }
  .payment-header-title h3 {
    font-family: 'Playfair Display', serif;
    font-size: 22px;
    font-weight: 700;
    color: var(--accent-gold);
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 6px;
  }
  .payment-header-title p {
    font-size: 13px;
    color: var(--text-muted);
    max-width: 650px;
    line-height: 1.5;
  }
  .live-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(34, 197, 94, 0.15);
    border: 1px solid rgba(34, 197, 94, 0.3);
    color: #4ade80;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
  }

  /* Payment Bank Cards Grid */
  .payment-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
    gap: 24px;
    margin-bottom: 32px;
  }

  .payment-card {
    background: var(--panel-bg);
    border: 1px solid var(--panel-border);
    border-radius: 16px;
    padding: 24px;
    position: relative;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
  }
  .payment-card:hover {
    border-color: var(--accent-gold);
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(245, 158, 11, 0.15);
  }
  .payment-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 14px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
  }
  .bank-logo-title {
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .bank-icon-box {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: rgba(245, 158, 11, 0.1);
    border: 1px solid rgba(245, 158, 11, 0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
  }
  .bank-title-text {
    font-size: 16px;
    font-weight: 700;
    color: #fff;
  }
  .bank-subtext {
    font-size: 11px;
    color: var(--text-muted);
  }
  .badge-status-tag {
    background: rgba(245, 158, 11, 0.15);
    color: var(--accent-gold);
    border: 1px solid rgba(245, 158, 11, 0.3);
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 700;
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

  /* Instructions & Save Button */
  .instructions-card {
    background: var(--panel-bg);
    border: 1px solid var(--panel-border);
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 32px;
  }
  .instructions-header {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 16px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 16px;
  }

  /* Preview Mockup Card */
  .preview-box {
    background: rgba(15, 23, 42, 0.8);
    border: 1px dashed var(--accent-gold);
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 32px;
  }
  .preview-title {
    font-size: 13px;
    font-weight: 700;
    color: var(--accent-gold);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .mini-invoice-mock {
    background: #1e293b;
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 12px;
    padding: 20px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
  }
  .mock-bank-item {
    background: rgba(0, 0, 0, 0.25);
    border: 1px solid var(--panel-border);
    border-radius: 8px;
    padding: 12px 14px;
  }

  .btn-save-gold {
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
    display: inline-flex;
    align-items: center;
    gap: 10px;
  }
  .btn-save-gold:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(245, 158, 11, 0.4);
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
  }
</style>

<!-- 1. Header Banner -->
<div class="payment-header-card">
  <div class="payment-header-title">
    <h3>💳 Pengaturan Rekening Bank & QRIS</h3>
    <p>
      Atur nomor rekening, nama pemilik akun bank, dan instruksi pembayaran toko Anda. Data yang disimpan di sini akan secara otomatis terupdate di halaman checkout dan lembar invoice pelanggan.
    </p>
  </div>
  <div class="live-badge">
    <span style="width: 8px; height: 8px; border-radius: 50%; background: #22c55e; display: inline-block;"></span>
    Sync Real-Time dengan Invoice
  </div>
</div>

<form action="{{ route('admin.payments.update') }}" method="POST">
  @csrf

  <!-- 2. Bank Accounts & QRIS Grid -->
  <div class="payment-grid">
    <!-- Card 1: Bank BCA -->
    <div class="payment-card">
      <div class="payment-card-header">
        <div class="bank-logo-title">
          <div class="bank-icon-box">🏦</div>
          <div>
            <div class="bank-title-text">Bank BCA</div>
            <div class="bank-subtext">Virtual Account / Transfer Bank</div>
          </div>
        </div>
        <span class="badge-status-tag">Utama</span>
      </div>

      <div class="form-group">
        <label class="form-label">Nama Bank / Label Metode</label>
        <input type="text" name="bca_name" class="form-control" value="{{ old('bca_name', $settings['bca_name'] ?? 'BCA Virtual Account') }}" required>
      </div>

      <div class="form-group">
        <label class="form-label">Nomor Rekening / Virtual Account</label>
        <input type="text" name="bca_number" class="form-control" value="{{ old('bca_number', $settings['bca_number'] ?? '880123811152282') }}" required style="font-weight: 700; color: var(--accent-gold); letter-spacing: 0.5px;">
      </div>

      <div class="form-group">
        <label class="form-label">Atas Nama (Account Holder)</label>
        <input type="text" name="bca_holder" class="form-control" value="{{ old('bca_holder', $settings['bca_holder'] ?? 'PT PUSAT HAMPERS INDONESIA') }}" required>
      </div>
    </div>

    <!-- Card 2: Bank Mandiri -->
    <div class="payment-card">
      <div class="payment-card-header">
        <div class="bank-logo-title">
          <div class="bank-icon-box">🏦</div>
          <div>
            <div class="bank-title-text">Bank Mandiri</div>
            <div class="bank-subtext">Transfer Rekening / VA</div>
          </div>
        </div>
        <span class="badge-status-tag">Aktif</span>
      </div>

      <div class="form-group">
        <label class="form-label">Nama Bank / Label Metode</label>
        <input type="text" name="mandiri_name" class="form-control" value="{{ old('mandiri_name', $settings['mandiri_name'] ?? 'Bank Mandiri Transfer') }}" required>
      </div>

      <div class="form-group">
        <label class="form-label">Nomor Rekening / Account Number</label>
        <input type="text" name="mandiri_number" class="form-control" value="{{ old('mandiri_number', $settings['mandiri_number'] ?? '123-00-998877-1') }}" required style="font-weight: 700; color: var(--accent-gold); letter-spacing: 0.5px;">
      </div>

      <div class="form-group">
        <label class="form-label">Atas Nama (Account Holder)</label>
        <input type="text" name="mandiri_holder" class="form-control" value="{{ old('mandiri_holder', $settings['mandiri_holder'] ?? 'PT PUSAT HAMPERS INDONESIA') }}" required>
      </div>
    </div>

    <!-- Card 3: Bank BRI -->
    <div class="payment-card">
      <div class="payment-card-header">
        <div class="bank-logo-title">
          <div class="bank-icon-box">🏛️</div>
          <div>
            <div class="bank-title-text">Bank BRI</div>
            <div class="bank-subtext">Transfer Bank / Virtual Account</div>
          </div>
        </div>
        <span class="badge-status-tag">Aktif</span>
      </div>

      <div class="form-group">
        <label class="form-label">Nama Bank / Label Metode</label>
        <input type="text" name="bri_name" class="form-control" value="{{ old('bri_name', $settings['bri_name'] ?? 'Bank BRI Virtual Account') }}">
      </div>

      <div class="form-group">
        <label class="form-label">Nomor Rekening / Account Number</label>
        <input type="text" name="bri_number" class="form-control" value="{{ old('bri_number', $settings['bri_number'] ?? '990088776655') }}" style="font-weight: 700; color: var(--accent-gold); letter-spacing: 0.5px;">
      </div>

      <div class="form-group">
        <label class="form-label">Atas Nama (Account Holder)</label>
        <input type="text" name="bri_holder" class="form-control" value="{{ old('bri_holder', $settings['bri_holder'] ?? 'PT PUSAT HAMPERS INDONESIA') }}">
      </div>
    </div>

    <!-- Card 4: QRIS Instant Payment -->
    <div class="payment-card">
      <div class="payment-card-header">
        <div class="bank-logo-title">
          <div class="bank-icon-box">📱</div>
          <div>
            <div class="bank-title-text">QRIS All Payment</div>
            <div class="bank-subtext">Gopay, OVO, ShopeePay, Dana, LinkAja</div>
          </div>
        </div>
        <span class="badge-status-tag" style="background: rgba(34, 197, 94, 0.15); color: #4ade80; border-color: rgba(34, 197, 94, 0.3);">Instant Scan</span>
      </div>

      <div class="form-group">
        <label class="form-label">Kode Merchant / ID QRIS</label>
        <input type="text" name="qris_number" class="form-control" value="{{ old('qris_number', $settings['qris_number'] ?? 'ID1020088776655') }}" style="font-weight: 700; color: var(--accent-gold); letter-spacing: 0.5px;">
      </div>

      <div class="form-group">
        <label class="form-label">Atas Nama Merchant QRIS</label>
        <input type="text" name="qris_holder" class="form-control" value="{{ old('qris_holder', $settings['qris_holder'] ?? 'PUSAT HAMPERS INDONESIA') }}">
      </div>
    </div>

    <!-- Card 5: Midtrans Payment Gateway (Verifikasi Otomatis) -->
    <div class="payment-card" style="grid-column: 1 / -1; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-color: rgba(59, 130, 246, 0.4);">
      <div class="payment-card-header">
        <div class="bank-logo-title">
          <div class="bank-icon-box" style="background: rgba(59, 130, 246, 0.15); border-color: rgba(59, 130, 246, 0.3);">⚡</div>
          <div>
            <div class="bank-title-text" style="color: #60a5fa;">Midtrans Payment Gateway (Verifikasi Otomatis)</div>
            <div class="bank-subtext">Automated Payment Verification - Snap Popup & Callback</div>
          </div>
        </div>
        <span class="badge-status-tag" style="background: rgba(59, 130, 246, 0.15); color: #60a5fa; border-color: rgba(59, 130, 246, 0.3);">⚡ OTOMATIS</span>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
        <div class="form-group">
          <label class="form-label">Midtrans Client Key</label>
          <input type="text" name="midtrans_client_key" class="form-control" value="{{ old('midtrans_client_key', $settings['midtrans_client_key'] ?? 'SB-Mid-client-W_17bXa892xKL') }}" style="font-family: monospace; font-size: 12px;">
        </div>

        <div class="form-group">
          <label class="form-label">Midtrans Server Key</label>
          <input type="password" name="midtrans_server_key" class="form-control" value="{{ old('midtrans_server_key', $settings['midtrans_server_key'] ?? 'SB-Mid-server-P_98zLm001xOP') }}" style="font-family: monospace; font-size: 12px;">
        </div>

        <div class="form-group">
          <label class="form-label">Merchant ID</label>
          <input type="text" name="midtrans_merchant_id" class="form-control" value="{{ old('midtrans_merchant_id', $settings['midtrans_merchant_id'] ?? 'G109827364') }}">
        </div>

        <div class="form-group">
          <label class="form-label">Mode Gateway</label>
          <select name="midtrans_mode" class="form-control" style="background: #0f172a;">
            <option value="sandbox" {{ ($settings['midtrans_mode'] ?? 'sandbox') == 'sandbox' ? 'selected' : '' }}>🧪 Sandbox (Uji Coba)</option>
            <option value="production" {{ ($settings['midtrans_mode'] ?? 'sandbox') == 'production' ? 'selected' : '' }}>🚀 Production (Live Real Money)</option>
          </select>
        </div>
      </div>
    </div>
  </div>

  <!-- 3. Payment Instructions Card -->
  <div class="instructions-card">
    <div class="instructions-header">
      📝 Instruksi & Catatan Pembayaran Pelanggan
    </div>
    <div class="form-group" style="margin-bottom: 0;">
      <label class="form-label">Teks Instruksi pada Halaman Invoice Tagihan</label>
      <textarea name="payment_instructions" class="form-control" rows="3" placeholder="Contoh: Harap lakukan transfer sesuai nominal unik. Konfirmasi otomatis akan diverifikasi dalam 5-10 menit.">{{ old('payment_instructions', $settings['payment_instructions'] ?? '') }}</textarea>
    </div>
  </div>

  <!-- 4. Live Invoice Preview Mockup -->
  <div class="preview-box">
    <div class="preview-title">
      <span>👁️ Preview Tampilan di Invoice Pelanggan</span>
    </div>
    <div class="mini-invoice-mock">
      <div class="mock-bank-item">
        <div style="font-size: 11px; color: var(--accent-gold); font-weight: 700;">{{ $settings['bca_name'] ?? 'BCA Virtual Account' }}</div>
        <div style="font-size: 14px; font-weight: 700; color: #fff; margin: 4px 0;">{{ $settings['bca_number'] ?? '880123811152282' }}</div>
        <div style="font-size: 11px; color: var(--text-muted);">a.n {{ $settings['bca_holder'] ?? 'PT PUSAT HAMPERS INDONESIA' }}</div>
      </div>

      <div class="mock-bank-item">
        <div style="font-size: 11px; color: var(--accent-gold); font-weight: 700;">{{ $settings['mandiri_name'] ?? 'Bank Mandiri Transfer' }}</div>
        <div style="font-size: 14px; font-weight: 700; color: #fff; margin: 4px 0;">{{ $settings['mandiri_number'] ?? '123-00-998877-1' }}</div>
        <div style="font-size: 11px; color: var(--text-muted);">a.n {{ $settings['mandiri_holder'] ?? 'PT PUSAT HAMPERS INDONESIA' }}</div>
      </div>

      <div class="mock-bank-item">
        <div style="font-size: 11px; color: var(--accent-gold); font-weight: 700;">QRIS Merchant</div>
        <div style="font-size: 14px; font-weight: 700; color: #fff; margin: 4px 0;">{{ $settings['qris_number'] ?? 'ID1020088776655' }}</div>
        <div style="font-size: 11px; color: var(--text-muted);">{{ $settings['qris_holder'] ?? 'PUSAT HAMPERS INDONESIA' }}</div>
      </div>
    </div>
  </div>

  <!-- 5. Save Button Container -->
  <div style="text-align: right; margin-bottom: 40px;">
    <button type="submit" class="btn-save-gold">
      💾 Simpan Pengaturan Pembayaran
    </button>
  </div>
</form>
@endsection
