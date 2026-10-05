@extends('layouts.admin')

@section('title', 'Kelola PDF Menu & Paket Reservasi')
@section('page_title', 'Kelola PDF Menu & Paket Reservasi Toko')

@section('content')
<style>
  .pdf-header-card {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    border: 1px solid rgba(212, 175, 55, 0.3);
    border-radius: 16px;
    padding: 26px 32px;
    margin-bottom: 32px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    box-shadow: 0 12px 32px rgba(0,0,0,0.4);
    position: relative;
    overflow: hidden;
  }
  .pdf-header-card::after {
    content: '📜';
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
    font-size: 24px;
    font-weight: 700;
    color: var(--accent-gold, #d4af37);
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 6px;
  }
  .pdf-header-title p {
    font-size: 14px;
    color: #94a3b8;
    max-width: 680px;
    line-height: 1.6;
  }

  .res-card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(440px, 1fr));
    gap: 28px;
    margin-bottom: 40px;
  }
  @media (max-width: 768px) {
    .res-card-grid { grid-template-columns: 1fr; }
  }

  .res-item-card {
    background: #1e293b;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
  }
  .res-item-banner {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 12px;
    margin-bottom: 18px;
    border: 1px solid rgba(212, 175, 55, 0.3);
  }
  .res-item-title {
    font-family: 'Playfair Display', serif;
    font-size: 20px;
    font-weight: 700;
    color: #f8fafc;
    margin-bottom: 4px;
  }
  .res-item-sub {
    font-size: 12px;
    color: #d4af37;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 12px;
  }
  .res-item-desc {
    font-size: 13px;
    color: #94a3b8;
    line-height: 1.6;
    margin-bottom: 20px;
    background: rgba(15, 23, 42, 0.5);
    padding: 12px;
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.05);
  }

  .pdf-status-box {
    background: rgba(15, 23, 42, 0.8);
    border: 1px solid rgba(212, 175, 55, 0.25);
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 20px;
  }
  .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 12px;
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

  .btn-gold {
    background: linear-gradient(135deg, #d4af37 0%, #aa7c11 100%);
    color: #000;
    font-weight: 700;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    font-size: 13px;
  }
  .btn-gold:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
  }
  .btn-outline-gold {
    background: transparent;
    border: 1px solid #d4af37;
    color: #d4af37;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    font-size: 12px;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .btn-outline-gold:hover {
    background: #d4af37;
    color: #000;
  }
  .btn-danger-sm {
    background: rgba(239, 68, 68, 0.15);
    color: #f87171;
    border: 1px solid rgba(239, 68, 68, 0.3);
    padding: 8px 14px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
  }
  .btn-danger-sm:hover {
    background: rgba(239, 68, 68, 0.3);
  }

  /* Modal Styling */
  .custom-modal {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.75);
    backdrop-filter: blur(6px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    pointer-events: none;
    transition: all 0.3s ease;
  }
  .custom-modal.active {
    opacity: 1;
    pointer-events: auto;
  }
  .modal-box {
    background: #1e293b;
    border: 1px solid rgba(212, 175, 55, 0.3);
    border-radius: 16px;
    width: 100%;
    max-width: 620px;
    max-height: 90vh;
    overflow-y: auto;
    padding: 28px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
  }
  .modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    padding-bottom: 14px;
    margin-bottom: 20px;
  }
  .modal-header h4 {
    font-family: 'Playfair Display', serif;
    font-size: 20px;
    color: #d4af37;
    margin: 0;
  }
  .modal-close {
    background: none;
    border: none;
    color: #94a3b8;
    font-size: 24px;
    cursor: pointer;
  }
</style>

@if(session('ok'))
  <div style="background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.4); color: #4ade80; padding: 14px 20px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between;">
    <div style="display: flex; align-items: center; gap: 10px;">
      <span style="font-size: 20px;">✅</span>
      <span>{{ session('ok') }}</span>
    </div>
    <button onclick="this.parentElement.remove()" style="background: none; border: none; color: #4ade80; font-size: 18px; cursor: pointer;">&times;</button>
  </div>
@endif

<div class="pdf-header-card">
  <div class="pdf-header-title">
    <h3>📜 Kelola PDF Menu & Paket Reservasi</h3>
    <p>Di halaman ini Anda dapat menambahkan berkas PDF Menu spesifik untuk masing-masing tipe reservasi (seperti <strong>Dine In</strong>, <strong>Outside Catering</strong>, dll.), memperbarui teks & info kontak WhatsApp, serta menambahkan Paket Reservasi Baru!</p>
  </div>
  <div>
    <button onclick="openModal('addReservationModal')" class="btn-gold">
      <span>➕ Tambah Paket Reservasi Baru</span>
    </button>
  </div>
</div>

<h4 style="font-size: 18px; color: #f8fafc; margin-bottom: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
  <span>🍽️ Daftar Paket Reservasi Toko</span>
</h4>

<div class="res-card-grid">
  @foreach($reservations as $res)
    <div class="res-item-card">
      <div>
        <img src="{{ $res->image_url }}" alt="{{ $res->title }}" class="res-item-banner">
        <h3 class="res-item-title">{{ $res->title }}</h3>
        @if($res->subtitle)
          <div class="res-item-sub">{{ $res->subtitle }}</div>
        @endif
        <div class="res-item-desc">
          {!! $res->description !!}
        </div>

        <!-- PDF Status Box -->
        <div class="pdf-status-box">
          <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
            <span style="font-size: 13px; font-weight: 700; color: #cbd5e1;">📄 Status PDF Menu Paket Ini:</span>
            @if($res->pdf_path && file_exists(public_path($res->pdf_path)))
              <span class="status-badge badge-active">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: #4ade80; display: inline-block;"></span>
                PDF Kustom Aktif
              </span>
            @else
              <span class="status-badge badge-default">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: #60a5fa; display: inline-block;"></span>
                Katalog Digital Otomatis
              </span>
            @endif
          </div>

          @if($res->pdf_path && file_exists(public_path($res->pdf_path)))
            <div style="margin-top: 10px; font-size: 12px; color: #94a3b8; display: flex; justify-content: space-between; align-items: center;">
              <span>📁 <strong>File:</strong> {{ basename($res->pdf_path) }}</span>
              <div style="display: flex; gap: 8px;">
                <a href="{{ route('menu.pdf', $res->slug) }}" target="_blank" class="btn-outline-gold">
                  👁️ Lihat PDF
                </a>
                <form action="{{ route('admin.menu_pdf.item_destroy_pdf', $res->id) }}" method="POST" onsubmit="return confirm('Hapus PDF kustom untuk {{ $res->title }}?')" style="display: inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-danger-sm">🗑️ Reset</button>
                </form>
              </div>
            </div>
          @endif

          <!-- Form Upload PDF specific to this item -->
          <form action="{{ route('admin.menu_pdf.item_upload_pdf', $res->id) }}" method="POST" enctype="multipart/form-data" style="margin-top: 14px; padding-top: 12px; border-top: 1px dashed rgba(255,255,255,0.1);">
            @csrf
            <label style="font-size: 12px; color: #cbd5e1; display: block; margin-bottom: 6px;">📤 Unggah/Ganti PDF Kustom khusus (Max 20MB):</label>
            <div style="display: flex; gap: 10px; align-items: center;">
              <input type="file" name="menu_pdf" accept=".pdf" required style="font-size: 12px; color: #94a3b8; flex: 1;">
              <button type="submit" class="btn-outline-gold" style="white-space: nowrap;">⚡ Upload PDF</button>
            </div>
          </form>
        </div>
      </div>

      <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 16px; margin-top: 10px;">
        <button onclick="editReservation({{ json_encode($res) }})" class="btn-outline-gold">
          ✏️ Edit Paket & WA
        </button>

        @if($reservations->count() > 1)
          <form action="{{ route('admin.menu_pdf.destroy_reservation', $res->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus paket reservasi {{ $res->title }}?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-danger-sm">🗑️ Hapus Paket</button>
          </form>
        @endif
      </div>
    </div>
  @endforeach
</div>

<!-- Global PDF Catalog Management Section -->
<div style="background: #1e293b; border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 28px; margin-top: 40px;">
  <h4 style="font-size: 18px; color: #f8fafc; margin-bottom: 12px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
    <span>🌐 PDF Menu Utama / Global Website</span>
  </h4>
  <p style="font-size: 13px; color: #94a3b8; margin-bottom: 20px;">Berkas ini dijadikan cadangan (fallback) untuk link "Restaurant Menu 📄" di navbar header utama website apabila paket reservasi tidak memiliki PDF khusus.</p>

  <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; background: rgba(15, 23, 42, 0.6); padding: 20px; border-radius: 12px; border: 1px solid rgba(212, 175, 55, 0.2);">
    <div>
      @if($hasGlobalPdf && $globalFileInfo)
        <span class="status-badge badge-active">🟢 PDF Global Aktif: {{ $globalFileInfo['name'] }} ({{ $globalFileInfo['size'] }})</span>
        <div style="font-size: 12px; color: #94a3b8; margin-top: 4px;">Terakhir diperbarui: {{ $globalFileInfo['updated_at'] }}</div>
      @else
        <span class="status-badge badge-default">🔵 Katalog Digital Otomatis Database</span>
      @endif
    </div>

    <div style="display: flex; gap: 12px; align-items: center;">
      <a href="{{ route('menu.pdf') }}" target="_blank" class="btn-outline-gold">👁️ Pratinjau PDF Utama</a>
      @if($hasGlobalPdf)
        <form action="{{ route('admin.menu_pdf.destroy') }}" method="POST" onsubmit="return confirm('Hapus PDF Global?')">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn-danger-sm">🗑️ Reset PDF Global</button>
        </form>
      @endif
    </div>
  </div>

  <form action="{{ route('admin.menu_pdf.upload') }}" method="POST" enctype="multipart/form-data" style="margin-top: 20px; display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
    @csrf
    <input type="file" name="menu_pdf" accept=".pdf" required style="font-size: 13px; color: #94a3b8; flex: 1;">
    <button type="submit" class="btn-gold">📤 Upload PDF Global Baru</button>
  </form>
</div>

<!-- Modal: Add New Reservation Package -->
<div class="custom-modal" id="addReservationModal">
  <div class="modal-box">
    <div class="modal-header">
      <h4>➕ Tambah Paket Reservasi Baru</h4>
      <button onclick="closeModal('addReservationModal')" class="modal-close">&times;</button>
    </div>

    <form action="{{ route('admin.menu_pdf.store_reservation') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div style="margin-bottom: 16px;">
        <label style="font-size: 13px; color: #cbd5e1; display: block; margin-bottom: 6px;">Judul Paket Reservasi *</label>
        <input type="text" name="title" required placeholder="misal: WEDDING & PRIVATE CELEBRATIONS" style="width: 100%; padding: 10px 14px; background: #0f172a; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff;">
      </div>

      <div style="margin-bottom: 16px;">
        <label style="font-size: 13px; color: #cbd5e1; display: block; margin-bottom: 6px;">Sub-Judul / Topik</label>
        <input type="text" name="subtitle" placeholder="misal: EXCLUSIVE LUXURY PACKAGE" style="width: 100%; padding: 10px 14px; background: #0f172a; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff;">
      </div>

      <div style="margin-bottom: 16px;">
        <label style="font-size: 13px; color: #cbd5e1; display: block; margin-bottom: 6px;">Deskripsi Paket *</label>
        <textarea name="description" rows="4" required placeholder="Ketik deskripsi paket di sini..." style="width: 100%; padding: 10px 14px; background: #0f172a; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff;"></textarea>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
        <div>
          <label style="font-size: 13px; color: #cbd5e1; display: block; margin-bottom: 6px;">Nomor WhatsApp Reservasi</label>
          <input type="text" name="whatsapp_number" value="62811152282" style="width: 100%; padding: 10px 14px; background: #0f172a; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff;">
        </div>
        <div>
          <label style="font-size: 13px; color: #cbd5e1; display: block; margin-bottom: 6px;">Pesan Otomatis WhatsApp</label>
          <input type="text" name="whatsapp_text" placeholder="Halo MAISON DORÉE..." style="width: 100%; padding: 10px 14px; background: #0f172a; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff;">
        </div>
      </div>

      <div style="margin-bottom: 16px;">
        <label style="font-size: 13px; color: #cbd5e1; display: block; margin-bottom: 6px;">Foto Banner Paket</label>
        <input type="file" name="image" accept="image/*" style="font-size: 12px; color: #94a3b8;">
      </div>

      <div style="margin-bottom: 24px;">
        <label style="font-size: 13px; color: #cbd5e1; display: block; margin-bottom: 6px;">Upload Berkas PDF Menu khusus Paket Ini (Opsional)</label>
        <input type="file" name="menu_pdf" accept=".pdf" style="font-size: 12px; color: #94a3b8;">
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 12px;">
        <button type="button" onclick="closeModal('addReservationModal')" style="padding: 10px 20px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #cbd5e1; border-radius: 8px; cursor: pointer;">Batal</button>
        <button type="submit" class="btn-gold">Simpan Paket Baru</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal: Edit Existing Reservation Package -->
<div class="custom-modal" id="editReservationModal">
  <div class="modal-box">
    <div class="modal-header">
      <h4>✏️ Edit Informasi Paket Reservasi</h4>
      <button onclick="closeModal('editReservationModal')" class="modal-close">&times;</button>
    </div>

    <form id="editReservationForm" action="" method="POST" enctype="multipart/form-data">
      @csrf
      <div style="margin-bottom: 16px;">
        <label style="font-size: 13px; color: #cbd5e1; display: block; margin-bottom: 6px;">Judul Paket Reservasi *</label>
        <input type="text" id="edit_title" name="title" required style="width: 100%; padding: 10px 14px; background: #0f172a; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff;">
      </div>

      <div style="margin-bottom: 16px;">
        <label style="font-size: 13px; color: #cbd5e1; display: block; margin-bottom: 6px;">Sub-Judul / Topik</label>
        <input type="text" id="edit_subtitle" name="subtitle" style="width: 100%; padding: 10px 14px; background: #0f172a; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff;">
      </div>

      <div style="margin-bottom: 16px;">
        <label style="font-size: 13px; color: #cbd5e1; display: block; margin-bottom: 6px;">Deskripsi Paket *</label>
        <textarea id="edit_description" name="description" rows="4" required style="width: 100%; padding: 10px 14px; background: #0f172a; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff;"></textarea>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
        <div>
          <label style="font-size: 13px; color: #cbd5e1; display: block; margin-bottom: 6px;">Nomor WhatsApp Reservasi</label>
          <input type="text" id="edit_whatsapp_number" name="whatsapp_number" style="width: 100%; padding: 10px 14px; background: #0f172a; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff;">
        </div>
        <div>
          <label style="font-size: 13px; color: #cbd5e1; display: block; margin-bottom: 6px;">Pesan Otomatis WhatsApp</label>
          <input type="text" id="edit_whatsapp_text" name="whatsapp_text" style="width: 100%; padding: 10px 14px; background: #0f172a; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff;">
        </div>
      </div>

      <div style="margin-bottom: 24px;">
        <label style="font-size: 13px; color: #cbd5e1; display: block; margin-bottom: 6px;">Ganti Foto Banner Paket (Opsional)</label>
        <input type="file" name="image" accept="image/*" style="font-size: 12px; color: #94a3b8;">
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 12px;">
        <button type="button" onclick="closeModal('editReservationModal')" style="padding: 10px 20px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #cbd5e1; border-radius: 8px; cursor: pointer;">Batal</button>
        <button type="submit" class="btn-gold">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

<script>
  function openModal(id) {
    document.getElementById(id).classList.add('active');
  }

  function closeModal(id) {
    document.getElementById(id).classList.remove('active');
  }

  function editReservation(res) {
    document.getElementById('edit_title').value = res.title || '';
    document.getElementById('edit_subtitle').value = res.subtitle || '';
    document.getElementById('edit_description').value = res.description || '';
    document.getElementById('edit_whatsapp_number').value = res.whatsapp_number || '62811152282';
    document.getElementById('edit_whatsapp_text').value = res.whatsapp_text || '';
    
    document.getElementById('editReservationForm').action = '/admin/menu-pdf/item/' + res.id + '/update';
    openModal('editReservationModal');
  }
</script>
@endsection
