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
  .customer-delivery-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 24px;
    font-size: 13px;
  }
  @media (max-width: 680px) {
    .invoice-page { padding: 16px 8px 60px 8px; }
    .invoice-card { padding: 20px 14px; }
    .invoice-header h1 { font-size: 19px !important; letter-spacing: 0.5px !important; }
    .payment-instructions-box { padding: 16px 12px; }
    .va-row { flex-direction: column; text-align: center; gap: 4px; }
    .customer-delivery-grid { grid-template-columns: 1fr !important; gap: 14px; }
  }
  .invoice-header {
    text-align: center;
    border-bottom: 2px solid #e2e8f0;
    padding-bottom: 20px;
    margin-bottom: 26px;
  }
  .invoice-header h1 {
    font-family: 'Playfair Display', serif;
    font-size: 26px;
    color: #1e2d27;
    letter-spacing: 1.5px;
    margin-bottom: 8px;
    word-break: break-word;
  }
  .invoice-badge {
    display: inline-block;
    padding: 6px 14px;
    background: rgba(217, 119, 6, 0.12);
    border: 1px solid #d97706;
    color: #b45309;
    font-weight: 700;
    font-size: 12.5px;
    border-radius: 20px;
  }

  /* Payment Instructions Box */
  .payment-instructions-box {
    background: #fffbeb;
    border: 1px solid #fef3c7;
    border-radius: 10px;
    padding: 24px;
    margin-bottom: 24px;
  }
  .payment-instructions-box h3 {
    font-size: 15.5px;
    font-weight: 700;
    color: #92400e;
    margin-bottom: 10px;
  }
  .va-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 14px;
    background: #fff;
    border: 1px solid #fde68a;
    border-radius: 8px;
    margin-bottom: 10px;
    font-size: 13px;
  }
  .va-row strong {
    font-family: monospace;
    font-size: 16px;
    letter-spacing: 1px;
    color: #78350f;
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
      <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap; margin-bottom: 8px;">
        <div class="invoice-badge">FAKTUR PESANAN: {{ $order->invoice_number }}</div>
        @if ($order->payment_status === 'paid' || $order->payment_status === 'verified')
          <div class="invoice-badge" style="background: rgba(22, 163, 74, 0.12); border-color: #16a34a; color: #15803d;">
            ✅ LUNAS / TERVERIFIKASI
          </div>
        @elseif ($order->payment_status === 'waiting_verification')
          <div class="invoice-badge" style="background: rgba(245, 158, 11, 0.15); border-color: #d97706; color: #b45309;">
            ⏳ MENUNGGU VERIFIKASI ADMIN
          </div>
        @else
          <div class="invoice-badge" style="background: rgba(239, 68, 68, 0.12); border-color: #ef4444; color: #b91c1c;">
            ⚠️ MENUNGGU PEMBAYARAN
          </div>
        @endif
      </div>
      <div style="margin-top: 10px;">
        <a href="{{ route('order.invoice.pdf', $order->invoice_number) }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #0284c7; color: #fff; text-decoration: none; border-radius: 6px; font-weight: 700; font-size: 13px; box-shadow: 0 2px 8px rgba(2, 132, 199, 0.3); transition: background 0.2s;">
          🖨️ Cetak / Unduh Faktur PDF (Print)
        </a>
      </div>
      <p style="font-size: 13px; color: #64756d; margin-top: 4px;">Dibuat pada {{ $order->created_at->format('d M Y, H:i') }} WIB</p>
    </div>

    @if (session('success'))
      <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 14px; border-radius: 8px; margin-bottom: 24px; font-size: 13.5px; text-align: center; font-weight: 600;">
        ✅ {{ session('success') }}
      </div>
    @endif

    @if ($errors->any())
      <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 14px; border-radius: 8px; margin-bottom: 24px; font-size: 13px;">
        <strong>⚠️ Terjadi Kesalahan Unggah:</strong>
        <ul style="margin: 6px 0 0 18px; padding: 0;">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
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
      <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 22px; border-radius: 10px; margin-bottom: 30px; text-align: center;">
        <div style="font-size: 36px; margin-bottom: 6px;">✅</div>
        <h3 style="font-size: 18px; font-weight: 700; color: #15803d; margin-bottom: 4px;">PEMBAYARAN DIVERIFIKASI & LUNAS</h3>
        <p style="font-size: 13.5px; color: #166534; margin: 0; line-height: 1.6;">
          Terima kasih! Pembayaran sebesar <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong> untuk faktur <strong>{{ $order->invoice_number }}</strong> telah berhasil diverifikasi oleh Admin. Pesanan Anda saat ini sedang disiapkan oleh tim pâtisserie kami.
        </p>

        @if ($order->payment_proof)
          <div style="margin-top: 16px; padding-top: 14px; border-top: 1px dashed #bbf7d0;">
            <a href="{{ $order->payment_proof_url }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; font-size: 12.5px; color: #15803d; text-decoration: underline; font-weight: 600;">
              <span>🔍 Lihat Arsip Bukti Pembayaran yang Terverifikasi</span>
            </a>
          </div>
        @endif
      </div>
    @else
      {{-- DETAIL INSTRUKSI TRANSFER SESUAI METODE BAYAR --}}
      @if ($isMidtrans)
        <div class="payment-instructions-box" style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border-color: #93c5fd;">
          <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; flex-wrap: wrap; gap: 10px;">
            <h3 style="color: #1e40af; margin-bottom: 0;">⚡ MIDTRANS PAYMENT GATEWAY</h3>
            <span style="background: #2563eb; color: #fff; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 700;">GATEWAY DIGITAL</span>
          </div>
          <p style="font-size: 13px; color: #1e3a8a; margin-bottom: 14px;">
            Selesaikan pembayaran tagihan sebesar <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong> via Virtual Account, GoPay, ShopeePay, atau Kartu Kredit:
          </p>
          <div style="background: #fff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 14px; margin-bottom: 14px;">
            <div style="display: flex; justify-content: space-between; font-size: 13px; color: #334155;">
              <span>Total Tagihan Pesanan:</span>
              <strong style="color: #d97706; font-size: 16px;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
            </div>
          </div>
          <p style="font-size: 12px; color: #1e40af; margin: 0;">
            💡 <em>Setelah melakukan transfer atau pembayaran, silakan lampirkan bukti pembayaran pada kolom di bawah agar diverifikasi oleh Admin.</em>
          </p>
        </div>

      @elseif ($isBca)
        <div class="payment-instructions-box">
          <h3>💳 INSTRUKSI PEMBAYARAN BANK BCA</h3>
          <p style="font-size: 13px; color: #78350f; margin-bottom: 14px;">
            Silakan melakukan transfer tagihan penuh sebesar <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong> ke rekening Bank BCA berikut:
          </p>
          <div class="va-row">
            <span><strong>{{ $paySettings['bca_name'] ?? 'Bank BCA Transfer' }}</strong> (a.n {{ $paySettings['bca_holder'] ?? 'PT PUSAT HAMPERS INDONESIA' }})</span>
            <strong>{{ $paySettings['bca_number'] ?? '880123811152282' }}</strong>
          </div>
          <p style="font-size: 12px; color: #92400e; margin: 6px 0 0 0;">
            💡 Pastikan nominal transfer sesuai persis hingga digit terakhir. Setelah transfer selesai, unggah bukti transfer pada formulir di bawah ini.
          </p>
        </div>

      @elseif ($isMandiri)
        <div class="payment-instructions-box">
          <h3>🏦 INSTRUKSI PEMBAYARAN BANK MANDIRI</h3>
          <p style="font-size: 13px; color: #78350f; margin-bottom: 14px;">
            Silakan melakukan transfer tagihan penuh sebesar <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong> ke rekening Bank Mandiri berikut:
          </p>
          <div class="va-row">
            <span><strong>{{ $paySettings['mandiri_name'] ?? 'Bank Mandiri Transfer' }}</strong> (a.n {{ $paySettings['mandiri_holder'] ?? 'PT PUSAT HAMPERS INDONESIA' }})</span>
            <strong>{{ $paySettings['mandiri_number'] ?? '123-00-998877-1' }}</strong>
          </div>
          <p style="font-size: 12px; color: #92400e; margin: 6px 0 0 0;">
            💡 Pastikan nominal transfer sesuai. Setelah transfer selesai, unggah foto bukti transfer di bawah.
          </p>
        </div>

      @elseif ($isBri)
        <div class="payment-instructions-box">
          <h3>🏛️ INSTRUKSI PEMBAYARAN BANK BRI</h3>
          <p style="font-size: 13px; color: #78350f; margin-bottom: 14px;">
            Silakan melakukan transfer tagihan penuh sebesar <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong> ke rekening Bank BRI berikut:
          </p>
          <div class="va-row">
            <span><strong>{{ $paySettings['bri_name'] ?? 'Bank BRI Transfer' }}</strong> (a.n {{ $paySettings['bri_holder'] ?? 'PT PUSAT HAMPERS INDONESIA' }})</span>
            <strong>{{ $paySettings['bri_number'] ?? '990088776655' }}</strong>
          </div>
          <p style="font-size: 12px; color: #92400e; margin: 6px 0 0 0;">
            💡 Pastikan nominal transfer sesuai. Setelah transfer selesai, unggah foto bukti transfer di bawah.
          </p>
        </div>

      @elseif ($isQris)
        <div class="payment-instructions-box">
          <h3>📱 INSTRUKSI PEMBAYARAN QRIS INSTANT</h3>
          <p style="font-size: 13px; color: #78350f; margin-bottom: 14px;">
            Silakan lakukan pemindaian (scan) Kode QRIS di bawah menggunakan GoPay, OVO, ShopeePay, DANA, BCA Mobile, atau Aplikasi M-Banking lainnya sejumlah <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>:
          </p>
          <div class="va-row" style="flex-direction: column; text-align: center; gap: 8px; padding: 18px;">
            <div style="background: #fff; padding: 12px; border-radius: 8px; border: 1px solid #cbd5e1; display: inline-block;">
              <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=MIDTRANS-QRIS-{{ $order->invoice_number }}" alt="QRIS Merchant Code" style="width: 160px; height: 160px;">
            </div>
            <span><strong>NMID QRIS:</strong> {{ $paySettings['qris_number'] ?? 'ID1020088776655' }} (a.n {{ $paySettings['qris_holder'] ?? 'PUSAT HAMPERS INDONESIA' }})</span>
          </div>
          <p style="font-size: 12px; color: #92400e; margin: 6px 0 0 0;">
            💡 Tangkap layar (screenshot) bukti pembayaran QRIS sukses Anda, kemudian unggah pada formulir di bawah.
          </p>
        </div>

      @elseif ($isCod)
        <div class="payment-instructions-box" style="background: #f8faf9; border-color: #cbd5e1;">
          <h3 style="color: #1e2d27;">💵 PEMBAYARAN CASH ON DELIVERY (COD)</h3>
          <p style="font-size: 13px; color: #475569; margin-bottom: 12px;">
            Pesanan Anda dengan nomor invoice <strong>{{ $order->invoice_number }}</strong> sedang disiapkan. Mohon siapkan uang tunai pas sejumlah <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong> saat kurir dedicated toko kami tiba di alamat pengiriman Anda.
          </p>
        </div>

      @else
        <div class="payment-instructions-box">
          <h3>💳 INSTRUKSI PEMBAYARAN REKENING</h3>
          <p style="font-size: 13px; color: #78350f; margin-bottom: 14px;">
            Silakan melakukan pembayaran tagihan sebesar <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong> ke rekening toko kami:
          </p>
          <div class="va-row">
            <span><strong>{{ $paySettings['bca_name'] ?? 'Bank BCA Transfer' }}</strong> (a.n {{ $paySettings['bca_holder'] ?? 'PUSAT HAMPERS INDONESIA' }})</span>
            <strong>{{ $paySettings['bca_number'] ?? '880123811152282' }}</strong>
          </div>
        </div>
      @endif

      {{-- SECTION UPLOAD BUKTI PEMBAYARAN --}}
      @if (!$isCod)
        @if ($order->payment_proof)
          {{-- KONDISI 1: SUDAH UNGGAH BUKTI, MENUNGGU VERIFIKASI ADMIN --}}
          <div style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border: 2px solid #f59e0b; border-radius: 12px; padding: 22px; margin-bottom: 28px; box-shadow: 0 4px 14px rgba(245, 158, 11, 0.15);">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
              <span style="font-size: 26px;">⏳</span>
              <div>
                <h3 style="margin: 0; font-size: 16px; font-weight: 800; color: #92400e; letter-spacing: 0.5px;">BUKTI PEMBAYARAN TELAH DIUNGGAH</h3>
                <span style="font-size: 12px; color: #b45309;">Menunggu Verifikasi & Pengecekan Mutasi oleh Admin Toko</span>
              </div>
            </div>

            <p style="font-size: 13px; color: #78350f; line-height: 1.6; margin: 0 0 14px 0;">
              Bukti transfer Anda telah berhasil dikirim pada 
              <strong>{{ $order->payment_proof_uploaded_at ? $order->payment_proof_uploaded_at->format('d M Y, H:i') : $order->updated_at->format('d M Y, H:i') }} WIB</strong>.
              Sistem telah memasukkan pesanan ini ke <strong>Antrian Verifikasi Admin</strong>. Hanya Admin yang dapat memverifikasi dana masuk dan mengubah status menjadi Lunas.
            </p>

            <div style="display: flex; gap: 14px; align-items: center; background: #fff; border: 1px solid #fde68a; border-radius: 8px; padding: 14px; flex-wrap: wrap;">
              @php $isPdf = str_ends_with(strtolower($order->payment_proof), '.pdf'); @endphp
              @if ($isPdf)
                <div style="font-size: 34px; line-height: 1;">📄</div>
                <div style="flex: 1; min-width: 170px;">
                  <div style="font-size: 13px; font-weight: 700; color: #1e293b;">Dokumen Bukti Transfer (PDF)</div>
                  <div style="font-size: 11px; color: #64748b;">Klik tombol di samping untuk meninjau file</div>
                </div>
                <a href="{{ $order->payment_proof_url }}" target="_blank" style="padding: 8px 16px; background: #2563eb; color: #fff; text-decoration: none; border-radius: 6px; font-size: 12px; font-weight: 700;">
                  🔍 Buka PDF
                </a>
              @else
                <a href="{{ $order->payment_proof_url }}" target="_blank" title="Klik untuk perbesar" style="display: inline-block;">
                  <img src="{{ $order->payment_proof_url }}" alt="Bukti Pembayaran" style="width: 85px; height: 85px; object-fit: cover; border-radius: 8px; border: 2px solid #e2e8f0; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                </a>
                <div style="flex: 1; min-width: 170px;">
                  <div style="font-size: 13px; font-weight: 700; color: #1e293b;">Foto Struk / Resi Pembayaran</div>
                  <div style="font-size: 11.5px; color: #64748b; margin-top: 2px;">Tersimpan di sistem menunggu pengecekan mutasi bank.</div>
                  <a href="{{ $order->payment_proof_url }}" target="_blank" style="display: inline-block; margin-top: 6px; font-size: 12px; color: #2563eb; text-decoration: underline; font-weight: 600;">🔍 Lihat Foto Penuh</a>
                </div>
              @endif
            </div>

            <div style="margin-top: 14px; padding-top: 12px; border-top: 1px dashed #fde68a;">
              <details>
                <summary style="cursor: pointer; font-size: 12.5px; color: #b45309; font-weight: 700;">
                  🔄 Ingin mengganti / mengunggah ulang bukti pembayaran? Klik di sini
                </summary>
                <form action="{{ route('order.upload_payment_proof', $order->invoice_number) }}" method="POST" enctype="multipart/form-data" style="margin-top: 12px; background: #fff; padding: 14px; border-radius: 8px; border: 1px solid #fde68a;">
                  @csrf
                  <label style="display: block; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 6px;">Pilih File Bukti Baru (JPG, PNG, WEBP, PDF - Maks 5MB):</label>
                  <input type="file" name="payment_proof" accept="image/jpeg,image/png,image/webp,application/pdf" required style="display: block; width: 100%; font-size: 12px; margin-bottom: 10px;">
                  <button type="submit" style="padding: 9px 18px; background: #d97706; color: #fff; border: none; border-radius: 6px; font-size: 12.5px; font-weight: 700; cursor: pointer;">
                    Unggah Ulang Bukti Pembayaran
                  </button>
                </form>
              </details>
            </div>
          </div>
        @else
          {{-- KONDISI 2: BELUM UNGGAH BUKTI PEMBAYARAN --}}
          <div style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 2px solid #2563eb; border-radius: 12px; padding: 22px; margin-bottom: 28px; box-shadow: 0 4px 16px rgba(37, 99, 235, 0.1);">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
              <span style="font-size: 26px;">📤</span>
              <div>
                <h3 style="margin: 0; font-size: 16px; font-weight: 800; color: #1e3a8a; letter-spacing: 0.5px;">UNGGAH BUKTI TRANSFER / PEMBAYARAN</h3>
                <span style="font-size: 12px; color: #475569;">Wajib melampirkan bukti agar pembayaran diverifikasi oleh Admin</span>
              </div>
            </div>

            <p style="font-size: 13px; color: #475569; line-height: 1.5; margin: 0 0 16px 0;">
              Setelah Anda menyelesaikan transfer tagihan di atas, silakan unggah foto struk ATM, bukti transfer m-Banking, atau screenshot pembayaran di bawah:
            </p>

            <form action="{{ route('order.upload_payment_proof', $order->invoice_number) }}" method="POST" enctype="multipart/form-data" id="formUploadProof">
              @csrf
              <div style="border: 2px dashed #93c5fd; background: #eff6ff; border-radius: 10px; padding: 20px; text-align: center; margin-bottom: 14px; cursor: pointer; transition: background 0.2s;" onclick="document.getElementById('fileProofInput').click()">
                <input type="file" name="payment_proof" id="fileProofInput" accept="image/jpeg,image/png,image/webp,application/pdf" required style="display: none;" onchange="previewProofImage(this)">
                <div id="uploadPlaceholder">
                  <div style="font-size: 34px; margin-bottom: 6px;">📷</div>
                  <div style="font-size: 14px; font-weight: 700; color: #1d4ed8;">Pilih Foto Struk / Bukti Transfer</div>
                  <div style="font-size: 11.5px; color: #64748b; margin-top: 4px;">Mendukung format JPG, PNG, WEBP, atau PDF (Ukuran Maksimal 5 MB)</div>
                  <button type="button" style="margin-top: 10px; padding: 7px 18px; background: #3b82f6; color: #fff; border: none; border-radius: 6px; font-size: 12px; font-weight: 700; cursor: pointer;">
                    Pilih File Dari Perangkat / Galeri
                  </button>
                </div>
                <div id="uploadPreviewBox" style="display: none; margin-top: 8px;">
                  <img id="imgPreview" src="" alt="Preview Bukti" style="max-height: 180px; max-width: 100%; border-radius: 8px; border: 1px solid #cbd5e1; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                  <div id="fileNameDisplay" style="font-size: 12px; color: #1e293b; font-weight: 700; margin-top: 8px;"></div>
                  <div style="font-size: 11px; color: #2563eb; margin-top: 4px;">Klik kotak ini untuk mengganti file</div>
                </div>
              </div>

              <button type="submit" id="btnSubmitProof" style="width: 100%; padding: 14px; background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35); transition: opacity 0.2s;">
                🚀 UNGGAH & KIRIM KE ANTRIAN VERIFIKASI ADMIN
              </button>
            </form>

            <div style="margin-top: 14px; text-align: center;">
              <span style="font-size: 12px; color: #64748b;">
                ⚠️ Pembayaran hanya sah setelah diverifikasi secara manual oleh Admin Toko.
              </span>
            </div>
          </div>
        @endif
      @endif
    @endif

    <!-- Customer & Delivery Details -->
    <div class="customer-delivery-grid">
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
        <div>Resi Pengiriman: <strong style="color: #d97706; font-family: monospace;">{{ $order->tracking_number ?: 'Menunggu Verifikasi & Kirim' }}</strong></div>
        <div>Metode Bayar: <strong>{{ $order->payment_method ?: 'Transfer Bank BCA' }}</strong></div>
        <div>Tanggal: {{ date('d M Y', strtotime($order->delivery_date)) }}</div>
        <div style="margin-top: 4px;">Alamat: {{ $order->address }}</div>
        @if ($order->latitude && $order->longitude)
          <div style="margin-top: 10px; padding: 10px 14px; background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 6px; font-size: 12.5px; color: #065f46; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
            <div>
              <strong>📍 Koordinat Titik Lokasi (Maps):</strong>
              <span style="font-family: monospace; font-weight: 700;">{{ $order->latitude }}, {{ $order->longitude }}</span>
            </div>
            <a href="https://www.google.com/maps?q={{ $order->latitude }},{{ $order->longitude }}" target="_blank" style="background: #059669; color: #ffffff; text-decoration: none; padding: 5px 12px; border-radius: 4px; font-weight: 700; font-size: 11.5px; display: inline-flex; align-items: center; gap: 5px; box-shadow: 0 2px 6px rgba(5,150,105,0.3);">
              🗺️ Buka di Google Maps / Navigasi Kurir ↗
            </a>
          </div>
        @endif
      </div>
    </div>

    @if ($order->order_note)
      <div style="background: #fdfcf7; border: 1px solid #e7dfc6; padding: 18px 22px; border-radius: 8px; margin-top: 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
        <div style="display: flex; align-items: center; gap: 8px; font-weight: 700; color: #1e2d27; font-size: 14px; margin-bottom: 10px;">
          <span style="font-size: 18px;">🎁</span> <span>Kartu Ucapan Hantaran &amp; Catatan Pesanan:</span>
        </div>
        <div style="font-size: 13.5px; color: #2d3748; line-height: 1.7; white-space: pre-line; background: #ffffff; padding: 14px 18px; border-radius: 6px; border: 1px dashed #d1c7a7;">
{!! nl2br(e($order->order_note)) !!}
        </div>
      </div>
    @endif

    <!-- Items Table -->
    <div style="overflow-x: auto;">
      <table class="invoice-table">
        <thead>
          <tr>
            <th>PRODUK</th>
            <th style="text-align: center;">JUMLAH</th>
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
    </div>

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

    <div style="display: flex; gap: 12px; justify-content: center; align-items: center; flex-wrap: wrap; margin-top: 36px;">
      <a href="{{ route('order.invoice.pdf', $order->invoice_number) }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; background: #0284c7; color: #fff; text-decoration: none; border-radius: 8px; font-weight: 700; font-size: 13.5px; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);">
        🖨️ Cetak / Unduh Faktur PDF (Print)
      </a>
      <a href="{{ route('home') }}" style="color: #64756d; text-decoration: none; font-size: 13.5px; padding: 10px 16px; border: 1px solid #cbd5e1; border-radius: 8px; background: #fff;">
        ← Kembali ke Toko
      </a>
    </div>
  </div>
</div>

<script>
  function previewProofImage(input) {
    if (input.files && input.files[0]) {
      const file = input.files[0];
      const placeholder = document.getElementById('uploadPlaceholder');
      const previewBox = document.getElementById('uploadPreviewBox');
      const imgPreview = document.getElementById('imgPreview');
      const fileNameDisplay = document.getElementById('fileNameDisplay');

      fileNameDisplay.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';

      if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
          imgPreview.src = e.target.result;
          imgPreview.style.display = 'block';
          placeholder.style.display = 'none';
          previewBox.style.display = 'block';
        };
        reader.readAsDataURL(file);
      } else {
        // PDF
        imgPreview.style.display = 'none';
        placeholder.style.display = 'none';
        previewBox.style.display = 'block';
      }
    }
  }

  // Disable button on form submit to prevent double submit
  const formUploadProof = document.getElementById('formUploadProof');
  if (formUploadProof) {
    formUploadProof.addEventListener('submit', function() {
      const btn = document.getElementById('btnSubmitProof');
      if (btn) {
        btn.disabled = true;
        btn.innerHTML = '⏳ Sedang Mengunggah Bukti Pembayaran...';
        btn.style.opacity = '0.7';
      }
    });
  }

  @if (!in_array($order->payment_status, ['paid', 'verified']))
    // Check status in case admin approves while user is on page
    setInterval(function() {
      fetch("{{ route('order.status', $order->invoice_number) }}")
        .then(res => res.json())
        .then(data => {
          if (data && data.is_paid) {
            window.location.reload();
          }
        })
        .catch(e => {});
    }, 8000);
  @endif
</script>
@endsection
