@extends('layouts.admin')

@section('title', 'Kelola Hero Banner Slider (Gambar Bergeser)')
@section('page_title', 'Kelola Hero Banner Slider (Gambar Bergeser ke Kanan)')

@section('content')
<style>
  .banner-header-card {
    background: linear-gradient(135deg, #320712 0%, #190207 100%);
    border: 1px solid rgba(230, 202, 101, 0.35);
    border-radius: 16px;
    padding: 24px 30px;
    margin-bottom: 28px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
  }
  .banner-header-title h3 {
    font-family: 'Playfair Display', serif;
    font-size: 22px;
    font-weight: 700;
    color: var(--accent-gold);
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0 0 6px 0;
  }
  .banner-header-title p {
    font-size: 13px;
    color: var(--text-muted);
    max-width: 650px;
    line-height: 1.5;
    margin: 0;
  }
  .slide-card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
  }
  .slide-box {
    background: var(--panel-bg);
    border: 1px solid var(--panel-border);
    border-radius: 14px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 6px 20px rgba(0,0,0,0.25);
    transition: transform 0.2s ease, border-color 0.2s ease;
  }
  .slide-box:hover {
    border-color: rgba(245, 158, 11, 0.5);
    transform: translateY(-2px);
  }
  .slide-img-preview {
    width: 100%;
    height: 180px;
    object-fit: cover;
    background: #0b1120;
    display: block;
    border-bottom: 1px solid var(--panel-border);
  }
  .slide-body {
    padding: 18px;
    flex: 1;
    display: flex;
    flex-direction: column;
  }
  .slide-badge-text {
    font-size: 11px;
    color: var(--accent-gold);
    font-weight: 700;
    letter-spacing: 1px;
    margin-bottom: 4px;
  }
  .slide-title-text {
    font-size: 16px;
    font-weight: 700;
    color: #fff;
    margin: 0 0 6px 0;
  }
  .slide-quote-text {
    font-size: 12.5px;
    font-style: italic;
    color: #fde047;
    margin-bottom: 8px;
  }
  .slide-desc-text {
    font-size: 12px;
    color: var(--text-muted);
    line-height: 1.5;
    margin-bottom: 12px;
    flex: 1;
  }
  .slide-meta-row {
    font-size: 11.5px;
    color: #94a3b8;
    background: rgba(0,0,0,0.2);
    padding: 6px 10px;
    border-radius: 6px;
    margin-bottom: 14px;
  }
  .slide-actions {
    display: flex;
    gap: 8px;
    border-top: 1px solid var(--panel-border);
    padding-top: 12px;
  }
  .form-group-custom {
    margin-bottom: 16px;
  }
  .form-group-custom label {
    display: block;
    font-size: 12.5px;
    font-weight: 600;
    color: #cbd5e1;
    margin-bottom: 6px;
  }
  .form-group-custom input, .form-group-custom textarea {
    width: 100%;
    padding: 10px 14px;
    background: #190207;
    border: 1px solid var(--panel-border);
    border-radius: 8px;
    color: #fff;
    font-size: 13.5px;
    box-sizing: border-box;
  }
  .form-group-custom input:focus, .form-group-custom textarea:focus {
    border-color: var(--accent-gold);
    outline: none;
  }
  .modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.75);
    backdrop-filter: blur(4px);
    z-index: 1000;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }
  .modal-overlay.active {
    display: flex;
  }
  .modal-content {
    background: #320712;
    border: 1px solid var(--panel-border);
    border-radius: 14px;
    width: 100%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    padding: 26px;
    color: #fff;
    box-shadow: 0 20px 50px rgba(0,0,0,0.6);
  }

  @media (max-width: 640px) {
    .banner-header-card {
      padding: 16px 14px !important;
      margin-bottom: 20px !important;
    }
    .slide-card-grid {
      grid-template-columns: 1fr !important;
      gap: 16px !important;
    }
    .modal-content {
      padding: 18px 14px !important;
    }
  }
</style>

@if(session('success'))
  <div class="alert alert-success">
    ✅ {{ session('success') }}
  </div>
@endif

@if(session('error'))
  <div class="alert alert-danger">
    ⚠️ {{ session('error') }}
  </div>
@endif

<!-- Header Banner Info -->
<div class="banner-header-card">
  <div class="banner-header-title">
    <h3><span>🖼️</span> Hero Banner Slider (Pergeseran Gambar ke Kanan)</h3>
    <p>
      Atur daftar gambar hero banner di halaman depan yang bergeser ke arah kanan secara otomatis atau manual, beserta teks promosi, kutipan elegan, dan tombol tujuannya.
    </p>
  </div>
  <div>
    <button type="button" class="btn btn-primary" onclick="openAddModal()">
      ➕ Tambah Slide Gambar Baru
    </button>
  </div>
</div>

<!-- Pengaturan Slider (Interval & Auto-Slide) -->
<div class="panel" style="margin-bottom: 28px;">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
    <h4 style="margin: 0; font-size: 16px; color: #fff;">⚙️ Pengaturan Waktu & Efek Geser</h4>
    <span style="font-size: 12px; color: #10b981; font-weight: 600;">✓ Arah Geser: Ke Kanan (Slide Right)</span>
  </div>
  <form action="{{ route('admin.hero_banner.settings') }}" method="POST" style="display: flex; flex-wrap: wrap; gap: 20px; align-items: flex-end;">
    @csrf
    <div style="flex: 1; min-width: 220px;">
      <label style="display: block; font-size: 12.5px; color: #cbd5e1; margin-bottom: 6px;">
        Kecepatan Interval Geser (Milidetik)
      </label>
      <input type="number" name="interval" value="{{ $settings['interval'] ?? 4000 }}" min="1000" max="20000" step="500" style="width: 100%; padding: 9px 12px; background: #190207; border: 1px solid var(--panel-border); border-radius: 8px; color: #fff;">
      <small style="color: #64748b; font-size: 11px;">Contoh: 4000 = gambar bergeser otomatis setiap 4 detik</small>
    </div>

    <div style="display: flex; align-items: center; gap: 8px; padding-bottom: 10px;">
      <input type="checkbox" id="autoplay" name="autoplay" value="1" {{ ($settings['autoplay'] ?? true) ? 'checked' : '' }} style="width: 18px; height: 18px;">
      <label for="autoplay" style="font-size: 13.5px; color: #fff; cursor: pointer;">
        Aktifkan Pergeseran Otomatis (Auto-play)
      </label>
    </div>

    <div>
      <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">
        Simpan Pengaturan
      </button>
    </div>
  </form>
</div>

<!-- Daftar Slide Hero Banner -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
  <h4 style="margin: 0; font-size: 18px; color: #fff;">Daftar Slide Aktif ({{ count($slides) }} Slide)</h4>
  <span style="font-size: 12px; color: #cbd5e1;">Urutan bergeser dari Slide 1 ke slide berikutnya menuju kanan</span>
</div>

<div class="slide-card-grid">
  @forelse($slides as $index => $slide)
    <div class="slide-box">
      <div style="position: relative;">
        <img src="{{ $slide['image'] }}" alt="{{ $slide['title'] }}" class="slide-img-preview" onerror="this.src='{{ asset('images/hero-poster.jpg') }}';">
        <span style="position: absolute; top: 10px; left: 10px; background: rgba(0,0,0,0.75); color: #fef08a; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;">
          Slide #{{ $index + 1 }}
        </span>
        @if($slide['is_active'] ?? true)
          <span style="position: absolute; top: 10px; right: 10px; background: rgba(16, 185, 129, 0.85); color: #fff; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;">
            Aktif
          </span>
        @else
          <span style="position: absolute; top: 10px; right: 10px; background: rgba(239, 68, 68, 0.85); color: #fff; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;">
            Non-Aktif
          </span>
        @endif
      </div>

      <div class="slide-body">
        <div class="slide-badge-text">{{ $slide['badge'] ?? '⚜️ PUSAT HAMPERS INDONESIA ⚜️' }}</div>
        <h4 class="slide-title-text">{{ $slide['title'] }}</h4>
        <div class="slide-quote-text">{{ $slide['quote'] ?? '' }}</div>
        <div class="slide-desc-text">{{ $slide['desc'] ?? '' }}</div>
        
        <div class="slide-meta-row">
          <strong>Tombol:</strong> {{ $slide['btn_text'] ?? 'Jelajahi' }} ➔ <code>{{ $slide['btn_url'] ?? '/shop' }}</code>
        </div>

        <div class="slide-actions">
          <button type="button" class="btn btn-outline" style="flex: 1; font-size: 12px; padding: 7px 10px;" onclick="openEditModal({{ json_encode($slide) }})">
            ✏️ Edit
          </button>
          
          <form action="{{ route('admin.hero_banner.toggle', $slide['id']) }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="btn btn-outline" style="font-size: 12px; padding: 7px 10px;" title="Ubah status">
              {{ ($slide['is_active'] ?? true) ? 'Matikan' : 'Aktifkan' }}
            </button>
          </form>

          @if(count($slides) > 1)
            <form action="{{ route('admin.hero_banner.destroy', $slide['id']) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Yakin ingin menghapus slide ini?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger" style="font-size: 12px; padding: 7px 10px;" title="Hapus slide">
                🗑️
              </button>
            </form>
          @endif
        </div>
      </div>
    </div>
  @empty
    <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-muted); background: var(--panel-bg); border-radius: 12px;">
      Belum ada slide tersimpan. Klik "Tambah Slide Gambar Baru" di atas.
    </div>
  @endforelse
</div>

<!-- Modal Tambah Slide Baru -->
<div class="modal-overlay" id="addSlideModal">
  <div class="modal-content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--panel-border); padding-bottom: 12px;">
      <h3 style="margin: 0; font-size: 18px; color: var(--accent-gold);">➕ Tambah Slide Gambar Hero Banner Baru</h3>
      <button type="button" onclick="closeAddModal()" style="background: none; border: none; font-size: 20px; color: #94a3b8; cursor: pointer;">✕</button>
    </div>

    <form action="{{ route('admin.hero_banner.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="form-group-custom">
        <label>Unggah Gambar Banner (JPG, PNG, WEBP)</label>
        <input type="file" name="image_file" accept="image/*">
        <small style="color: #64748b; font-size: 11px;">Rekomendasi resolusi: 1920x800px atau foto lanskap resolusi tinggi</small>
      </div>

      <div class="form-group-custom">
        <label>Atau Gunakan Path / URL Gambar</label>
        <input type="text" name="image_url" placeholder="/images/events.jpg atau https://...">
      </div>

      <div class="form-group-custom">
        <label>Badge Atas</label>
        <input type="text" name="badge" value="⚜️ PUSAT HAMPERS INDONESIA ⚜️" required>
      </div>

      <div class="form-group-custom">
        <label>Judul Slide</label>
        <input type="text" name="title" placeholder="Contoh: PARSEL & HAMPERS MEWAH" required>
      </div>

      <div class="form-group-custom">
        <label>Kutipan Signature (Quote)</label>
        <input type="text" name="quote" value='"Have a bite, you can&apos;t stop!"'>
      </div>

      <div class="form-group-custom">
        <label>Deskripsi Singkat</label>
        <textarea name="desc" rows="3" placeholder="Deskripsi slide yang memikat..."></textarea>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
        <div class="form-group-custom">
          <label>Teks Tombol CTA</label>
          <input type="text" name="btn_text" value="JELAJAHI KOLEKSI">
        </div>
        <div class="form-group-custom">
          <label>Link Tombol</label>
          <input type="text" name="btn_url" value="/shop">
        </div>
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 16px;">
        <button type="button" class="btn btn-outline" onclick="closeAddModal()">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Slide Baru</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit Slide -->
<div class="modal-overlay" id="editSlideModal">
  <div class="modal-content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--panel-border); padding-bottom: 12px;">
      <h3 style="margin: 0; font-size: 18px; color: var(--accent-gold);">✏️ Edit Slide Hero Banner</h3>
      <button type="button" onclick="closeEditModal()" style="background: none; border: none; font-size: 20px; color: #94a3b8; cursor: pointer;">✕</button>
    </div>

    <form id="editSlideForm" action="" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="form-group-custom">
        <label>Ganti Gambar Banner (Opsional)</label>
        <input type="file" name="image_file" accept="image/*">
        <div style="margin-top: 6px;">
          <small style="color: #94a3b8;">Gambar saat ini: <span id="currentImageLabel" style="color: #fde047;"></span></small>
        </div>
      </div>

      <div class="form-group-custom">
        <label>Atau Ganti Path / URL Gambar</label>
        <input type="text" id="edit_image_url" name="image_url">
      </div>

      <div class="form-group-custom">
        <label>Badge Atas</label>
        <input type="text" id="edit_badge" name="badge" required>
      </div>

      <div class="form-group-custom">
        <label>Judul Slide</label>
        <input type="text" id="edit_title" name="title" required>
      </div>

      <div class="form-group-custom">
        <label>Kutipan Signature (Quote)</label>
        <input type="text" id="edit_quote" name="quote">
      </div>

      <div class="form-group-custom">
        <label>Deskripsi Singkat</label>
        <textarea id="edit_desc" name="desc" rows="3"></textarea>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
        <div class="form-group-custom">
          <label>Teks Tombol CTA</label>
          <input type="text" id="edit_btn_text" name="btn_text">
        </div>
        <div class="form-group-custom">
          <label>Link Tombol</label>
          <input type="text" id="edit_btn_url" name="btn_url">
        </div>
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 16px;">
        <button type="button" class="btn btn-outline" onclick="closeEditModal()">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

<script>
  function openAddModal() {
    document.getElementById('addSlideModal').classList.add('active');
  }
  function closeAddModal() {
    document.getElementById('addSlideModal').classList.remove('active');
  }

  function openEditModal(slide) {
    const form = document.getElementById('editSlideForm');
    form.action = '/admin/hero-banner/slide/' + slide.id + '/update';
    document.getElementById('currentImageLabel').innerText = slide.image;
    document.getElementById('edit_image_url').value = slide.image;
    document.getElementById('edit_badge').value = slide.badge || '';
    document.getElementById('edit_title').value = slide.title || '';
    document.getElementById('edit_quote').value = slide.quote || '';
    document.getElementById('edit_desc').value = slide.desc || '';
    document.getElementById('edit_btn_text').value = slide.btn_text || 'JELAJAHI KOLEKSI';
    document.getElementById('edit_btn_url').value = slide.btn_url || '/shop';

    document.getElementById('editSlideModal').classList.add('active');
  }
  function closeEditModal() {
    document.getElementById('editSlideModal').classList.remove('active');
  }
</script>
@endsection
