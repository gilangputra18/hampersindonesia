@extends('layouts.admin')

@section('title', 'Kelola Pricelist & Katalog PDF')
@section('page_title', 'Kelola Berkas Pricelist & Katalog PDF Toko')

@section('content')
<style>
  .pdf-header-card {
    background: linear-gradient(135deg, #320712 0%, #190207 100%);
    border: 1px solid rgba(230, 202, 101, 0.35);
    border-radius: 16px;
    padding: 26px 28px;
    margin-bottom: 28px;
    box-shadow: 0 12px 32px rgba(0,0,0,0.4);
    position: relative;
    overflow: hidden;
  }
  .pdf-header-card::after {
    content: '📄';
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 110px;
    opacity: 0.05;
    pointer-events: none;
  }
  .pdf-header-title h3 {
    font-family: 'Playfair Display', serif;
    font-size: 22px;
    font-weight: 700;
    color: var(--accent-gold, #d4af37);
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 8px;
    flex-wrap: wrap;
  }
  .pdf-header-title p {
    font-size: 13.5px;
    color: #94a3b8;
    max-width: 720px;
    line-height: 1.6;
    margin: 0;
  }

  .pdf-status-card {
    background: var(--panel-bg);
    border: 1px solid var(--panel-border);
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 28px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.25);
  }
  .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12.5px;
    font-weight: 600;
  }
  .badge-active {
    background: rgba(34, 197, 94, 0.15);
    color: #4ade80;
    border: 1px solid rgba(34, 197, 94, 0.3);
  }
  .badge-default {
    background: rgba(59, 130, 246, 0.15);
    color: #60a5fa;
    border: 1px solid rgba(59, 130, 246, 0.3);
  }

  .pdf-info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    background: rgba(15, 23, 42, 0.6);
    padding: 20px;
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    margin-top: 16px;
  }
  .pdf-actions-grp {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
  }

  .btn-gold {
    background: linear-gradient(135deg, #d4af37 0%, #aa7c11 100%);
    color: #000;
    font-weight: 700;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    font-size: 13px;
    white-space: nowrap;
  }
  .btn-gold:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
  }
  .btn-outline-gold {
    background: transparent;
    border: 1px solid #d4af37;
    color: #d4af37;
    padding: 9px 18px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    font-size: 12.5px;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
  }
  .btn-outline-gold:hover {
    background: #d4af37;
    color: #000;
  }
  .btn-danger-sm {
    background: rgba(239, 68, 68, 0.15);
    color: #f87171;
    border: 1px solid rgba(239, 68, 68, 0.3);
    padding: 9px 16px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 12.5px;
    font-weight: 600;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .btn-danger-sm:hover {
    background: #ef4444;
    color: #fff;
  }

  .upload-box {
    background: var(--panel-bg);
    border: 1px solid var(--panel-border);
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.25);
  }
  .upload-form-row {
    display: flex;
    gap: 14px;
    align-items: center;
    flex-wrap: wrap;
    margin-top: 16px;
  }
  .upload-form-row input[type="file"] {
    flex: 1;
    min-width: 240px;
    padding: 10px 14px;
    background: #190207;
    border: 1px dashed rgba(230, 202, 101, 0.4);
    border-radius: 8px;
    color: #cbd5e1;
    font-size: 13px;
  }

  .tips-list {
    margin-top: 20px;
    padding: 16px 20px;
    background: rgba(217, 119, 6, 0.08);
    border: 1px solid rgba(217, 119, 6, 0.25);
    border-radius: 10px;
    font-size: 12.5px;
    color: #cbd5e1;
    line-height: 1.6;
  }
  .tips-list strong {
    color: #fbbf24;
  }

  @media (max-width: 640px) {
    .pdf-header-card {
      padding: 18px 14px !important;
      margin-bottom: 20px !important;
    }
    .pdf-status-card, .upload-box {
      padding: 18px 14px !important;
      margin-bottom: 20px !important;
    }
    .pdf-info-row {
      flex-direction: column !important;
      align-items: stretch !important;
      padding: 14px !important;
    }
    .pdf-actions-grp {
      flex-direction: column !important;
      width: 100% !important;
    }
    .pdf-actions-grp a,
    .pdf-actions-grp form,
    .pdf-actions-grp button {
      width: 100% !important;
      justify-content: center !important;
    }
    .upload-form-row {
      flex-direction: column !important;
      align-items: stretch !important;
    }
    .upload-form-row input[type="file"],
    .upload-form-row .btn-gold {
      width: 100% !important;
      justify-content: center !important;
    }
  }
</style>

{{-- Flash Notification --}}
@if(session('ok'))
  <div style="background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.4); color: #4ade80; padding: 14px 20px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between;">
    <div style="display: flex; align-items: center; gap: 10px;">
      <span style="font-size: 20px;">✅</span>
      <span>{{ session('ok') }}</span>
    </div>
    <button onclick="this.parentElement.remove()" style="background: none; border: none; color: #4ade80; font-size: 18px; cursor: pointer;">&times;</button>
  </div>
@endif

<!-- 1. Header Card -->
<div class="pdf-header-card">
  <div class="pdf-header-title">
    <h3>📄 Kelola Berkas Pricelist & Katalog PDF Toko</h3>
    <p>
      Atur berkas PDF Katalog & Pricelist resmi <strong>Pusat Hampers Indonesia</strong>. Berkas PDF yang diunggah di sini terhubung langsung dengan menu <strong>"PRICELIST"</strong> pada drawer dan footer website, sehingga pelanggan dapat langsung mengunduh atau membaca brosur penawaran hampers Anda.
    </p>
  </div>
</div>

<!-- 2. Status Berkas Saat Ini -->
<div class="pdf-status-card">
  <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 12px;">
    <h4 style="font-size: 16px; font-weight: 700; color: #f8fafc; margin: 0; display: flex; align-items: center; gap: 8px;">
      <span>📑</span> Status Berkas Katalog PDF Saat Ini
    </h4>
    @if($hasGlobalPdf && $globalFileInfo)
      <span class="status-badge badge-active">
        <span style="width: 8px; height: 8px; border-radius: 50%; background: #22c55e; display: inline-block;"></span>
        PDF Kustom Aktif
      </span>
    @else
      <span class="status-badge badge-default">
        <span style="width: 8px; height: 8px; border-radius: 50%; background: #3b82f6; display: inline-block;"></span>
        Katalog Digital Otomatis Database
      </span>
    @endif
  </div>

  <div class="pdf-info-row">
    <div>
      @if($hasGlobalPdf && $globalFileInfo)
        <div style="font-size: 14px; font-weight: 700; color: #fff; margin-bottom: 4px;">
          📁 Berkas: {{ $globalFileInfo['name'] }} ({{ $globalFileInfo['size'] }})
        </div>
        <div style="font-size: 12px; color: #94a3b8;">
          Terakhir diperbarui: {{ $globalFileInfo['updated_at'] }} WIB
        </div>
      @else
        <div style="font-size: 13.5px; color: #cbd5e1; margin-bottom: 4px;">
          Belum ada berkas PDF khusus yang diunggah.
        </div>
        <div style="font-size: 12px; color: #94a3b8;">
          Sistem saat ini otomatis merender katalog digital berbasis produk database saat tombol Pricelist diklik pelanggan.
        </div>
      @endif
    </div>

    <div class="pdf-actions-grp">
      <a href="{{ route('menu.pdf') }}" target="_blank" class="btn-outline-gold">
        👁️ Pratinjau {{ $hasGlobalPdf ? 'PDF' : 'Katalog' }} di Tab Baru
      </a>
      @if($hasGlobalPdf)
        <form action="{{ route('admin.menu_pdf.destroy') }}" method="POST" onsubmit="return confirm('Yakin ingin mereset dan menghapus berkas PDF ini?')">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn-danger-sm">🗑️ Reset / Hapus Berkas</button>
        </form>
      @endif
    </div>
  </div>
</div>

<!-- 3. Form Upload PDF Baru -->
<div class="upload-box">
  <h4 style="font-size: 16px; font-weight: 700; color: #f8fafc; margin-bottom: 8px; display: flex; align-items: center; gap: 8px;">
    <span>📤</span> Unggah Berkas PDF Pricelist / Katalog Baru
  </h4>
  <p style="font-size: 13px; color: #94a3b8; margin: 0 0 16px 0;">
    Pilih berkas PDF dari komputer atau ponsel Anda. File lama akan otomatis digantikan dengan file yang baru diunggah.
  </p>

  <form action="{{ route('admin.menu_pdf.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="upload-form-row">
      <input type="file" name="menu_pdf" accept=".pdf" required>
      <button type="submit" class="btn-gold">
        <span>⚡ Upload & Publikasikan PDF</span>
      </button>
    </div>
  </form>

  <div class="tips-list">
    <div>💡 <strong>Panduan & Tips Katalog PDF:</strong></div>
    <ul style="margin: 6px 0 0 18px; padding: 0;">
      <li>Format berkas wajib berupa dokumen <strong>.PDF</strong> dengan ukuran maksimal <strong>20 MB</strong>.</li>
      <li>Sangat cocok untuk mengunggah <em>E-Catalogue</em> musiman seperti <strong>Katalog Hampers Lebaran, Natal & Tahun Baru, Imlek, atau Paket Corporate</strong>.</li>
      <li>Pelanggan dapat langsung mengunduh atau mencetak berkas PDF ini melalui link "PRICELIST" di website Anda.</li>
    </ul>
  </div>
</div>
@endsection
