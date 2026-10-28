@extends('layouts.admin')

@section('title', 'Kelola Galeri Foto (Gallery)')
@section('page_title', 'Kelola Galeri Foto (Gallery)')

@section('content')
<style>
  /* Header Card */
  .gallery-admin-header {
    background: linear-gradient(135deg, #320712 0%, #190207 100%);
    border: 1px solid rgba(230, 202, 101, 0.35);
    border-radius: 16px;
    padding: 24px 28px;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 18px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
  }
  .gallery-admin-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: 22px;
    font-weight: 700;
    color: var(--accent-gold);
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0 0 6px 0;
  }
  .gallery-admin-header p {
    font-size: 13px;
    color: var(--text-muted);
    max-width: 650px;
    line-height: 1.5;
    margin: 0;
  }
  .gallery-header-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
  }

  /* Metric Stat Cards */
  .gallery-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
  }
  .gallery-stat-card {
    background: var(--panel-bg);
    border: 1px solid var(--panel-border);
    border-radius: 12px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.2);
  }
  .gallery-stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: rgba(159, 24, 60, 0.25);
    border: 1px solid rgba(230, 202, 101, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: var(--accent-gold);
    flex-shrink: 0;
  }
  .gallery-stat-info h4 {
    margin: 0;
    font-size: 22px;
    font-weight: 800;
    color: #fff;
    line-height: 1.1;
  }
  .gallery-stat-info span {
    font-size: 12px;
    color: var(--text-muted);
  }

  /* Filter Tabs */
  .admin-filter-bar {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
    flex-wrap: wrap;
    background: #190207;
    padding: 10px 16px;
    border-radius: 10px;
    border: 1px solid var(--panel-border);
  }
  .admin-filter-btn {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: var(--text-muted);
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12.5px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
  }
  .admin-filter-btn:hover {
    color: #fff;
    border-color: var(--accent-gold);
  }
  .admin-filter-btn.active {
    background: #9f183c;
    border-color: var(--accent-gold);
    color: #fef08a;
  }

  /* Gallery Item Cards Grid */
  .admin-gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
  }
  .admin-gallery-card {
    background: var(--panel-bg);
    border: 1px solid var(--panel-border);
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: 0 4px 16px rgba(0,0,0,0.25);
    transition: transform 0.2s, border-color 0.2s;
  }
  .admin-gallery-card:hover {
    transform: translateY(-2px);
    border-color: rgba(230, 202, 101, 0.5);
  }
  .admin-gallery-thumb-wrap {
    position: relative;
    width: 100%;
    aspect-ratio: 1 / 1;
    background: #0f0a0d;
    overflow: hidden;
  }
  .admin-gallery-thumb-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
  }
  .admin-gallery-card:hover .admin-gallery-thumb-wrap img {
    transform: scale(1.05);
  }
  .admin-gallery-order-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: rgba(10, 5, 5, 0.75);
    backdrop-filter: blur(4px);
    border: 1px solid rgba(230, 202, 101, 0.4);
    color: #fef08a;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 6px;
  }
  .admin-gallery-cat-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #9f183c;
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: #ffffff;
    font-size: 10.5px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 12px;
  }
  .admin-gallery-status-badge {
    position: absolute;
    bottom: 10px;
    left: 10px;
    font-size: 10.5px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
  }
  .status-active {
    background: rgba(22, 101, 52, 0.85);
    color: #86efac;
    border: 1px solid #16a34a;
  }
  .status-inactive {
    background: rgba(127, 29, 29, 0.85);
    color: #fca5a5;
    border: 1px solid #dc2626;
  }

  .admin-gallery-card-body {
    padding: 16px;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }
  .admin-gallery-title {
    font-size: 15px;
    font-weight: 700;
    color: #fff;
    margin: 0 0 4px 0;
    line-height: 1.3;
  }
  .admin-gallery-cardname {
    font-size: 12px;
    color: var(--accent-gold);
    font-weight: 600;
    margin-bottom: 4px;
  }
  .admin-gallery-tagline {
    font-size: 11.5px;
    font-style: italic;
    color: #cbd5e1;
    margin-bottom: 8px;
  }
  .admin-gallery-desc {
    font-size: 11.5px;
    color: var(--text-muted);
    line-height: 1.45;
    margin-bottom: 14px;
    flex: 1;
  }
  .admin-gallery-actions {
    display: flex;
    align-items: center;
    gap: 6px;
    border-top: 1px solid var(--panel-border);
    padding-top: 12px;
    margin-top: 8px;
  }
  .admin-gallery-actions form {
    margin: 0;
  }

  /* Modals */
  .modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.8);
    backdrop-filter: blur(5px);
    z-index: 1000;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }
  .modal-overlay.active {
    display: flex;
  }
  .modal-box {
    background: #25050d;
    border: 1px solid rgba(230, 202, 101, 0.4);
    border-radius: 14px;
    width: 100%;
    max-width: 620px;
    max-height: 90vh;
    overflow-y: auto;
    padding: 24px 28px;
    color: #fff;
    box-shadow: 0 20px 60px rgba(0,0,0,0.7);
  }
  .modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid var(--panel-border);
    padding-bottom: 12px;
    margin-bottom: 18px;
  }
  .modal-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: var(--accent-gold);
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .modal-close-btn {
    background: transparent;
    border: none;
    color: var(--text-muted);
    font-size: 22px;
    cursor: pointer;
    line-height: 1;
    padding: 4px;
  }
  .modal-close-btn:hover {
    color: #fff;
  }
  .form-group-custom {
    margin-bottom: 14px;
  }
  .form-group-custom label {
    display: block;
    font-size: 12.5px;
    font-weight: 600;
    color: #cbd5e1;
    margin-bottom: 5px;
  }
  .form-group-custom input,
  .form-group-custom select,
  .form-group-custom textarea {
    width: 100%;
    padding: 9px 13px;
    background: #140206;
    border: 1px solid var(--panel-border);
    border-radius: 7px;
    color: #fff;
    font-size: 13px;
    box-sizing: border-box;
    font-family: inherit;
  }
  .form-group-custom input:focus,
  .form-group-custom select:focus,
  .form-group-custom textarea:focus {
    border-color: var(--accent-gold);
    outline: none;
    box-shadow: 0 0 0 2px rgba(230, 202, 101, 0.2);
  }
  .form-row-custom {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
  }
  .img-preview-box {
    margin-top: 8px;
    width: 90px;
    height: 90px;
    border-radius: 8px;
    border: 1px dashed rgba(230, 202, 101, 0.4);
    overflow: hidden;
    background: #0f0a0d;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .img-preview-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  @media (max-width: 640px) {
    .gallery-admin-header {
      padding: 18px 16px !important;
    }
    .admin-gallery-grid {
      grid-template-columns: 1fr !important;
    }
    .modal-box {
      padding: 18px 16px !important;
    }
    .form-row-custom {
      grid-template-columns: 1fr !important;
      gap: 0 !important;
    }
  }
</style>

@if(session('success'))
  <div class="alert alert-success" style="background: rgba(22, 101, 52, 0.2); border: 1px solid #16a34a; color: #86efac; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px;">
    ✅ {{ session('success') }}
  </div>
@endif

@if(session('error'))
  <div class="alert alert-danger" style="background: rgba(127, 29, 29, 0.2); border: 1px solid #dc2626; color: #fca5a5; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px;">
    ⚠️ {{ session('error') }}
  </div>
@endif

@if ($errors->any())
  <div class="alert alert-danger" style="background: rgba(127, 29, 29, 0.2); border: 1px solid #dc2626; color: #fca5a5; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px;">
    <ul style="margin: 0; padding-left: 20px;">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<!-- Header Banner -->
<div class="gallery-admin-header">
  <div>
    <h3><span>📸</span> Kelola Galeri Foto &amp; Karya</h3>
    <p>
      Atur foto-foto karya hampers eksklusif, aneka bolu lapis legit otentik, cookies, dan bites yang ditampilkan pada halaman Galeri Publik. Anda dapat menambah foto baru, mengubah judul/tagline, mengganti gambar, mengatur urutan, serta mengaktifkan/menonaktifkan foto.
    </p>
  </div>
  <div class="gallery-header-actions">
    <button type="button" class="btn btn-primary" onclick="openAddModal()" style="display: inline-flex; align-items: center; gap: 6px; background: #9f183c; border: 1px solid #e6ca65; color: #fef08a; padding: 10px 18px; border-radius: 8px; font-weight: 700; cursor: pointer;">
      <span>➕</span>
      <span>Tambah Foto Galeri Baru</span>
    </button>
    <a href="{{ route('gallery') }}" target="_blank" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: 6px; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.2); color: #fff; padding: 10px 16px; border-radius: 8px; font-weight: 600; text-decoration: none;">
      <span>🌐</span>
      <span>Lihat Halaman Galeri</span>
    </a>
  </div>
</div>

<!-- Quick Stats -->
@php
  $totalItems = count($items);
  $activeCount = count(array_filter($items, fn($i) => ($i['is_active'] ?? true)));
  $inactiveCount = $totalItems - $activeCount;
@endphp
<div class="gallery-stats-grid">
  <div class="gallery-stat-card">
    <div class="gallery-stat-icon">🖼️</div>
    <div class="gallery-stat-info">
      <h4>{{ $totalItems }}</h4>
      <span>Total Foto Galeri</span>
    </div>
  </div>
  <div class="gallery-stat-card">
    <div class="gallery-stat-icon" style="background: rgba(22, 101, 52, 0.25); border-color: #22c55e; color: #4ade80;">🟢</div>
    <div class="gallery-stat-info">
      <h4>{{ $activeCount }}</h4>
      <span>Foto Aktif (Tampil)</span>
    </div>
  </div>
  <div class="gallery-stat-card">
    <div class="gallery-stat-icon" style="background: rgba(127, 29, 29, 0.25); border-color: #ef4444; color: #f87171;">⚪</div>
    <div class="gallery-stat-info">
      <h4>{{ $inactiveCount }}</h4>
      <span>Foto Nonaktif (Disembunyikan)</span>
    </div>
  </div>
  <div class="gallery-stat-card">
    <div class="gallery-stat-icon" style="background: rgba(202, 164, 54, 0.2); border-color: #caa436; color: #fef08a;">🏷️</div>
    <div class="gallery-stat-info">
      <h4>{{ count($categories) }}</h4>
      <span>Kategori Karya</span>
    </div>
  </div>
</div>

<!-- Filter Tabs -->
<div class="admin-filter-bar">
  <span style="font-size: 12.5px; color: var(--accent-gold); font-weight: 700; margin-right: 4px;">Filter Kategori:</span>
  <button type="button" class="admin-filter-btn active" data-filter="all" onclick="filterGallery('all', this)">
    Semua ({{ $totalItems }})
  </button>
  @foreach($categories as $cKey => $cLabel)
    @php
      $countCat = count(array_filter($items, fn($i) => ($i['category'] ?? '') === $cKey));
    @endphp
    <button type="button" class="admin-filter-btn" data-filter="{{ $cKey }}" onclick="filterGallery('{{ $cKey }}', this)">
      {{ $cLabel }} ({{ $countCat }})
    </button>
  @endforeach
</div>

<!-- Photo Cards Grid -->
<div class="admin-gallery-grid" id="adminGalleryGrid">
  @forelse($items as $item)
    @php
      $isActive = $item['is_active'] ?? true;
      $catKey = $item['category'] ?? 'lapis';
      $catName = $categories[$catKey] ?? ucfirst($catKey);
    @endphp
    <div class="admin-gallery-card" data-category="{{ $catKey }}">
      <div class="admin-gallery-thumb-wrap">
        <img src="{{ asset(ltrim($item['image'], '/')) }}" alt="{{ $item['title'] }}" loading="lazy">
        <span class="admin-gallery-order-badge">#{{ $item['display_order'] ?? $loop->iteration }}</span>
        <span class="admin-gallery-cat-badge">{{ $catName }}</span>
        <span class="admin-gallery-status-badge {{ $isActive ? 'status-active' : 'status-inactive' }}">
          {{ $isActive ? '● Aktif' : '○ Nonaktif' }}
        </span>
      </div>

      <div class="admin-gallery-card-body">
        <div>
          <h4 class="admin-gallery-title">{{ $item['title'] }}</h4>
          <div class="admin-gallery-cardname">Tag Kartu: {{ $item['card_name'] ?? $item['title'] }}</div>
          <div class="admin-gallery-tagline">"{{ $item['tagline'] ?? "Once You Bite, You Can't Stop!" }}"</div>
          <p class="admin-gallery-desc">
            {{ Str::limit($item['desc'] ?? 'Tidak ada deskripsi detail.', 85) }}
          </p>
        </div>

        <div class="admin-gallery-actions">
          <!-- Toggle Status -->
          <form action="{{ route('admin.gallery.toggle', $item['id']) }}" method="POST" style="flex: 1;">
            @csrf
            <button type="submit" class="btn btn-outline" style="width: 100%; font-size: 11.5px; padding: 6px 8px; justify-content: center; background: {{ $isActive ? 'rgba(239, 68, 68, 0.15)' : 'rgba(34, 197, 94, 0.15)' }}; border-color: {{ $isActive ? 'rgba(239, 68, 68, 0.4)' : 'rgba(34, 197, 94, 0.4)' }}; color: {{ $isActive ? '#fca5a5' : '#86efac' }};">
              {{ $isActive ? 'Sembunyikan' : 'Aktifkan' }}
            </button>
          </form>

          <!-- Edit Button -->
          <button type="button" class="btn btn-outline" style="font-size: 11.5px; padding: 6px 12px; background: rgba(202, 164, 54, 0.15); border-color: rgba(202, 164, 54, 0.4); color: #fef08a;"
                  onclick='openEditModal(@json($item))'>
            ✏️ Edit
          </button>

          <!-- Delete Button -->
          <form action="{{ route('admin.gallery.destroy', $item['id']) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto galeri &quot;{{ addslashes($item['title']) }}&quot;?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline" style="font-size: 11.5px; padding: 6px 10px; background: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.3); color: #f87171;" title="Hapus Foto">
              🗑️
            </button>
          </form>
        </div>
      </div>
    </div>
  @empty
    <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: var(--panel-bg); border-radius: 12px; border: 1px dashed var(--panel-border);">
      <p style="font-size: 16px; color: var(--text-muted); margin-bottom: 14px;">Belum ada foto galeri.</p>
      <button type="button" class="btn btn-primary" onclick="openAddModal()">➕ Tambah Foto Pertama</button>
    </div>
  @endforelse
</div>

<!-- Modal Tambah Foto Galeri -->
<div class="modal-overlay" id="addGalleryModal">
  <div class="modal-box">
    <div class="modal-header">
      <h3><span>➕</span> Tambah Foto Galeri Baru</h3>
      <button type="button" class="modal-close-btn" onclick="closeAddModal()">&times;</button>
    </div>

    <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="form-row-custom">
        <div class="form-group-custom">
          <label>Judul Lengkap Foto <span style="color: #ef4444;">*</span></label>
          <input type="text" name="title" required placeholder="Contoh: Lapis Legit Prunes Premium" value="{{ old('title') }}">
        </div>

        <div class="form-group-custom">
          <label>Kategori Karya <span style="color: #ef4444;">*</span></label>
          <select name="category" required>
            @foreach($categories as $cKey => $cLabel)
              <option value="{{ $cKey }}" {{ old('category') == $cKey ? 'selected' : '' }}>{{ $cLabel }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-row-custom">
        <div class="form-group-custom">
          <label>Nama Display Kartu (Singkat)</label>
          <input type="text" name="card_name" placeholder="Contoh: Lapis Prunes" value="{{ old('card_name') }}">
          <small style="color: var(--text-muted); font-size: 11px;">Teks nama yang tampil di sudut bawah kartu foto.</small>
        </div>

        <div class="form-group-custom">
          <label>Tagline Kartu (Motto)</label>
          <input type="text" name="tagline" placeholder="Once You Bite, You Can't Stop!" value="{{ old('tagline', "Once You Bite, You Can't Stop!") }}">
          <small style="color: var(--text-muted); font-size: 11px;">Kutipan mewah di bawah nama kartu.</small>
        </div>
      </div>

      <div class="form-group-custom">
        <label>Deskripsi Detail (Tampil Saat Foto Diklik / Lightbox)</label>
        <textarea name="desc" rows="3" placeholder="Jelaskan bahan, keistimewaan, atau varian rasa kue/hampers ini...">{{ old('desc') }}</textarea>
      </div>

      <div class="form-row-custom">
        <div class="form-group-custom">
          <label>Urutan Tampilan (Display Order)</label>
          <input type="number" name="display_order" min="1" value="{{ old('display_order', count($items) + 1) }}">
        </div>

        <div class="form-group-custom">
          <label>Upload File Foto Baru</label>
          <input type="file" name="image_file" accept="image/*" onchange="previewNewImage(this, 'addImagePreview')">
          <small style="color: var(--text-muted); font-size: 11px;">Format JPG, PNG, WEBP (Maks 8MB).</small>
        </div>
      </div>

      <div class="form-group-custom">
        <label>Atau Gunakan Path / URL Gambar</label>
        <input type="text" name="image_url" placeholder="/images/cat-cakes.jpg atau https://..." value="{{ old('image_url') }}">
      </div>

      <div class="form-group-custom" style="display: flex; align-items: center; gap: 14px;">
        <span style="font-size: 12px; color: var(--text-muted);">Pratinjau Foto:</span>
        <div class="img-preview-box">
          <img id="addImagePreview" src="{{ asset('images/cat-cakes.jpg') }}" alt="Preview">
        </div>
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; border-top: 1px solid var(--panel-border); padding-top: 16px;">
        <button type="button" class="btn btn-outline" onclick="closeAddModal()" style="padding: 9px 18px;">Batal</button>
        <button type="submit" class="btn btn-primary" style="background: #9f183c; border-color: #e6ca65; color: #fef08a; padding: 9px 22px; font-weight: 700;">Simpan Foto Galeri</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit Foto Galeri -->
<div class="modal-overlay" id="editGalleryModal">
  <div class="modal-box">
    <div class="modal-header">
      <h3><span>✏️</span> Edit Foto Galeri</h3>
      <button type="button" class="modal-close-btn" onclick="closeEditModal()">&times;</button>
    </div>

    <form id="editGalleryForm" action="" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="form-row-custom">
        <div class="form-group-custom">
          <label>Judul Lengkap Foto <span style="color: #ef4444;">*</span></label>
          <input type="text" id="edit_title" name="title" required>
        </div>

        <div class="form-group-custom">
          <label>Kategori Karya <span style="color: #ef4444;">*</span></label>
          <select id="edit_category" name="category" required>
            @foreach($categories as $cKey => $cLabel)
              <option value="{{ $cKey }}">{{ $cLabel }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-row-custom">
        <div class="form-group-custom">
          <label>Nama Display Kartu (Singkat)</label>
          <input type="text" id="edit_card_name" name="card_name">
        </div>

        <div class="form-group-custom">
          <label>Tagline Kartu (Motto)</label>
          <input type="text" id="edit_tagline" name="tagline">
        </div>
      </div>

      <div class="form-group-custom">
        <label>Deskripsi Detail (Tampil Saat Foto Diklik / Lightbox)</label>
        <textarea id="edit_desc" name="desc" rows="3"></textarea>
      </div>

      <div class="form-row-custom">
        <div class="form-group-custom">
          <label>Urutan Tampilan (Display Order)</label>
          <input type="number" id="edit_display_order" name="display_order" min="1">
        </div>

        <div class="form-group-custom">
          <label>Ganti File Foto (Opsional)</label>
          <input type="file" name="image_file" accept="image/*" onchange="previewNewImage(this, 'editImagePreview')">
          <small style="color: var(--text-muted); font-size: 11px;">Biarkan kosong jika tidak ingin mengganti foto saat ini.</small>
        </div>
      </div>

      <div class="form-group-custom">
        <label>Atau Ganti URL / Path Gambar</label>
        <input type="text" id="edit_image_url" name="image_url" placeholder="/images/... atau https://...">
      </div>

      <div class="form-group-custom" style="display: flex; align-items: center; gap: 14px;">
        <span style="font-size: 12px; color: var(--text-muted);">Foto Saat Ini:</span>
        <div class="img-preview-box">
          <img id="editImagePreview" src="" alt="Current Photo">
        </div>
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; border-top: 1px solid var(--panel-border); padding-top: 16px;">
        <button type="button" class="btn btn-outline" onclick="closeEditModal()" style="padding: 9px 18px;">Batal</button>
        <button type="submit" class="btn btn-primary" style="background: #9f183c; border-color: #e6ca65; color: #fef08a; padding: 9px 22px; font-weight: 700;">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

<script>
  function filterGallery(category, button) {
    document.querySelectorAll('.admin-filter-btn').forEach(btn => btn.classList.remove('active'));
    button.classList.add('active');

    const cards = document.querySelectorAll('.admin-gallery-card');
    cards.forEach(card => {
      if (category === 'all' || card.getAttribute('data-category') === category) {
        card.style.display = 'flex';
      } else {
        card.style.display = 'none';
      }
    });
  }

  function openAddModal() {
    document.getElementById('addGalleryModal').classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeAddModal() {
    document.getElementById('addGalleryModal').classList.remove('active');
    document.body.style.overflow = '';
  }

  function openEditModal(item) {
    const form = document.getElementById('editGalleryForm');
    form.action = "{{ url('admin/gallery') }}/" + item.id;

    document.getElementById('edit_title').value = item.title || '';
    document.getElementById('edit_category').value = item.category || 'lapis';
    document.getElementById('edit_card_name').value = item.card_name || item.title || '';
    document.getElementById('edit_tagline').value = item.tagline || "Once You Bite, You Can't Stop!";
    document.getElementById('edit_desc').value = item.desc || '';
    document.getElementById('edit_display_order').value = item.display_order || 1;
    document.getElementById('edit_image_url').value = item.image && !item.image.startsWith('/uploads/') ? item.image : '';

    const preview = document.getElementById('editImagePreview');
    let imgPath = item.image || '/images/cat-cakes.jpg';
    if (!imgPath.startsWith('http') && !imgPath.startsWith('/')) {
      imgPath = '/' + imgPath;
    }
    preview.src = imgPath;

    document.getElementById('editGalleryModal').classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeEditModal() {
    document.getElementById('editGalleryModal').classList.remove('active');
    document.body.style.overflow = '';
  }

  function previewNewImage(input, previewElementId) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById(previewElementId).src = e.target.result;
      }
      reader.readAsDataURL(input.files[0]);
    }
  }

  // Close modals on ESC or clicking outside
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeAddModal();
      closeEditModal();
    }
  });

  const addModal = document.getElementById('addGalleryModal');
  if (addModal) {
    addModal.addEventListener('click', function(e) {
      if (e.target === addModal) closeAddModal();
    });
  }

  const editModal = document.getElementById('editGalleryModal');
  if (editModal) {
    editModal.addEventListener('click', function(e) {
      if (e.target === editModal) closeEditModal();
    });
  }
</script>
@endsection
