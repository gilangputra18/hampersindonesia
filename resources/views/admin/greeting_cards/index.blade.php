@extends('layouts.admin')

@section('title', 'Kelola Template Kartu Ucapan')
@section('page_title', 'Kelola Template Kartu Ucapan')

@section('content')
<style>
  .gcard-header-card {
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
  .gcard-header-card h3 {
    font-family: 'Playfair Display', serif;
    font-size: 22px;
    font-weight: 700;
    color: var(--accent-gold);
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0 0 6px 0;
  }
  .gcard-header-card p {
    font-size: 13px;
    color: var(--text-muted);
    max-width: 650px;
    line-height: 1.5;
    margin: 0;
  }
  .gcard-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
  }
  .gcard-stat-card {
    background: var(--panel-bg);
    border: 1px solid var(--panel-border);
    border-radius: 12px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
  }
  .gcard-stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: rgba(159, 24, 60, 0.25);
    border: 1px solid rgba(230, 202, 101, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
  }
  .gcard-stat-info h4 {
    margin: 0;
    font-size: 22px;
    font-weight: 800;
    color: #fff;
  }
  .gcard-stat-info span {
    font-size: 12px;
    color: var(--text-muted);
  }

  .template-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
  }
  .template-box {
    background: var(--panel-bg);
    border: 1px solid var(--panel-border);
    border-radius: 14px;
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 4px 16px rgba(0,0,0,0.25);
    transition: transform 0.2s, border-color 0.2s;
  }
  .template-box:hover {
    transform: translateY(-2px);
    border-color: rgba(230, 202, 101, 0.5);
  }
  .template-box-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 12px;
  }
  .template-chip-pill {
    background: rgba(202, 164, 54, 0.15);
    border: 1px solid rgba(202, 164, 54, 0.4);
    color: #fef08a;
    font-size: 13.5px;
    font-weight: 700;
    padding: 5px 12px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .template-order-tag {
    font-size: 11px;
    color: var(--text-muted);
    font-weight: 700;
    background: rgba(0,0,0,0.3);
    padding: 3px 8px;
    border-radius: 6px;
  }
  .template-message-body {
    background: rgba(0, 0, 0, 0.25);
    border: 1px solid rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    padding: 12px 14px;
    font-size: 12.5px;
    color: #e2e8f0;
    line-height: 1.6;
    margin-bottom: 16px;
    flex: 1;
    font-style: italic;
  }
  .template-box-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-top: 1px solid var(--panel-border);
    padding-top: 12px;
    gap: 8px;
  }
  .status-badge {
    font-size: 11px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 6px;
  }
  .status-badge.active {
    background: rgba(22, 101, 52, 0.85);
    color: #86efac;
    border: 1px solid #16a34a;
  }
  .status-badge.inactive {
    background: rgba(127, 29, 29, 0.85);
    color: #fca5a5;
    border: 1px solid #dc2626;
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
    max-width: 580px;
    max-height: 90vh;
    overflow-y: auto;
    padding: 26px;
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
  .form-group-custom input,
  .form-group-custom textarea {
    width: 100%;
    padding: 10px 14px;
    background: #140206;
    border: 1px solid var(--panel-border);
    border-radius: 8px;
    color: #fff;
    font-size: 13.5px;
    box-sizing: border-box;
    font-family: inherit;
  }
  .form-group-custom input:focus,
  .form-group-custom textarea:focus {
    border-color: var(--accent-gold);
    outline: none;
    box-shadow: 0 0 0 2px rgba(230, 202, 101, 0.2);
  }
</style>

@if(session('success'))
  <div class="alert alert-success" style="background: rgba(22, 101, 52, 0.2); border: 1px solid #16a34a; color: #86efac; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px;">
    {{ session('success') }}
  </div>
@endif

@if(session('error'))
  <div class="alert alert-danger" style="background: rgba(127, 29, 29, 0.2); border: 1px solid #dc2626; color: #fca5a5; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px;">
    {{ session('error') }}
  </div>
@endif

<!-- Header Banner -->
<div class="gcard-header-card">
  <div>
    <h3><span>💌</span> Kelola Template Kartu Ucapan Hantaran</h3>
    <p>
      Atur kata-kata inspirasi ucapan siap pakai (Natal, Imlek, Lebaran, Ulang Tahun, Rekan Bisnis, dll.) yang tampil sebagai tombol cepat di form kartu ucapan halaman pembayaran (checkout). Anda dapat menambah, mengedit kalimat ucapan, atau mengaktifkan tema tertentu sesuai momen perayaan.
    </p>
  </div>
  <div style="display: flex; gap: 10px; flex-wrap: wrap;">
    <button type="button" class="btn btn-primary" onclick="openAddModal()" style="display: inline-flex; align-items: center; gap: 6px; background: #9f183c; border: 1px solid #e6ca65; color: #fef08a; padding: 10px 18px; border-radius: 8px; font-weight: 700; cursor: pointer;">
      <span>➕</span>
      <span>Tambah Template Baru</span>
    </button>
    <form action="{{ route('admin.greeting_cards.reset') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mereset seluruh template ke pengaturan bawaan (Natal, Imlek, Idul Fitri, dll)?');">
      @csrf
      <button type="submit" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: 6px; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.2); color: #fff; padding: 10px 16px; border-radius: 8px; font-weight: 600;">
        <span>🔄</span>
        <span>Reset ke Default</span>
      </button>
    </form>
  </div>
</div>

<!-- Quick Stats -->
@php
  $total = count($templates);
  $active = count(array_filter($templates, fn($t) => ($t['is_active'] ?? true)));
  $inactive = $total - $active;
@endphp
<div class="gcard-stats-grid">
  <div class="gcard-stat-card">
    <div class="gcard-stat-icon" style="color: var(--accent-gold);">📋</div>
    <div class="gcard-stat-info">
      <h4>{{ $total }}</h4>
      <span>Total Template Ucapan</span>
    </div>
  </div>
  <div class="gcard-stat-card">
    <div class="gcard-stat-icon" style="background: rgba(22, 101, 52, 0.25); border-color: #22c55e; color: #4ade80;">🟢</div>
    <div class="gcard-stat-info">
      <h4>{{ $active }}</h4>
      <span>Template Aktif (Tampil di Checkout)</span>
    </div>
  </div>
  <div class="gcard-stat-card">
    <div class="gcard-stat-icon" style="background: rgba(127, 29, 29, 0.25); border-color: #ef4444; color: #f87171;">⚪</div>
    <div class="gcard-stat-info">
      <h4>{{ $inactive }}</h4>
      <span>Template Nonaktif (Disembunyikan)</span>
    </div>
  </div>
</div>

<!-- Template Grid -->
<div class="template-grid">
  @forelse($templates as $tpl)
    @php
      $isActive = $tpl['is_active'] ?? true;
    @endphp
    <div class="template-box">
      <div>
        <div class="template-box-header">
          <div class="template-chip-pill">{{ $tpl['title'] }}</div>
          <span class="template-order-tag">Urutan #{{ $tpl['display_order'] ?? $loop->iteration }}</span>
        </div>
        <div class="template-message-body">
          "{{ $tpl['message'] }}"
        </div>
      </div>

      <div class="template-box-footer">
        <div style="display: flex; align-items: center; gap: 8px;">
          <span class="status-badge {{ $isActive ? 'active' : 'inactive' }}">
            {{ $isActive ? '● Aktif' : '○ Nonaktif' }}
          </span>
          <form action="{{ route('admin.greeting_cards.toggle', $tpl['id']) }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="btn btn-outline" style="font-size: 11px; padding: 4px 8px; color: {{ $isActive ? '#fca5a5' : '#86efac' }}; border-color: {{ $isActive ? 'rgba(239, 68, 68, 0.3)' : 'rgba(34, 197, 94, 0.3)' }};">
              {{ $isActive ? 'Sembunyikan' : 'Aktifkan' }}
            </button>
          </form>
        </div>

        <div style="display: flex; gap: 6px;">
          <button type="button" class="btn btn-outline" style="font-size: 11.5px; padding: 5px 12px; background: rgba(202, 164, 54, 0.15); border-color: rgba(202, 164, 54, 0.4); color: #fef08a;"
                  onclick='openEditModal(@json($tpl))'>
            ✏️ Edit
          </button>

          <form action="{{ route('admin.greeting_cards.destroy', $tpl['id']) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus template &quot;{{ addslashes($tpl['title']) }}&quot;?');" style="margin: 0;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline" style="font-size: 11.5px; padding: 5px 9px; color: #f87171; border-color: rgba(239, 68, 68, 0.3);" title="Hapus">
              🗑️
            </button>
          </form>
        </div>
      </div>
    </div>
  @empty
    <div style="grid-column: 1 / -1; text-align: center; padding: 50px; background: var(--panel-bg); border-radius: 12px;">
      <p style="color: var(--text-muted); font-size: 14px;">Belum ada template kartu ucapan.</p>
      <button type="button" class="btn btn-primary" onclick="openAddModal()">➕ Tambah Template Pertama</button>
    </div>
  @endforelse
</div>

<!-- Modal Tambah Template -->
<div class="modal-overlay" id="addTemplateModal">
  <div class="modal-box">
    <div class="modal-header">
      <h3><span>➕</span> Tambah Template Kartu Ucapan</h3>
      <button type="button" class="modal-close-btn" onclick="closeAddModal()">&times;</button>
    </div>

    <form action="{{ route('admin.greeting_cards.store') }}" method="POST">
      @csrf

      <div class="form-group-custom">
        <label>Judul Tombol / Tema Ucapan <span style="color: #ef4444;">*</span></label>
        <input type="text" name="title" required placeholder="Contoh: 🎄 Natal &amp; Tahun Baru atau 🧧 Tahun Baru Imlek" value="{{ old('title') }}">
        <small style="color: var(--text-muted); font-size: 11px;">Teks tombol pilihan kartu ucapan yang diklik pembeli di form pemesanan.</small>
      </div>

      <div class="form-group-custom">
        <label>Isi Kalimat / Pesan Ucapan Hantaran <span style="color: #ef4444;">*</span></label>
        <textarea name="message" rows="4" required placeholder="Tuliskan kalimat ucapan penuh doa dan makna di sini...">{{ old('message') }}</textarea>
        <small style="color: var(--text-muted); font-size: 11px;">Kalimat ini akan otomatis mengisi kotak pesan ucapan saat pembeli mengklik tombol.</small>
      </div>

      <div class="form-group-custom">
        <label>Urutan Tampilan</label>
        <input type="number" name="display_order" min="1" value="{{ count($templates) + 1 }}">
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid var(--panel-border); padding-top: 16px; margin-top: 20px;">
        <button type="button" class="btn btn-outline" onclick="closeAddModal()">Batal</button>
        <button type="submit" class="btn btn-primary" style="background: #9f183c; border-color: #e6ca65; color: #fef08a; font-weight: 700;">Simpan Template</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit Template -->
<div class="modal-overlay" id="editTemplateModal">
  <div class="modal-box">
    <div class="modal-header">
      <h3><span>✏️</span> Edit Template Kartu Ucapan</h3>
      <button type="button" class="modal-close-btn" onclick="closeEditModal()">&times;</button>
    </div>

    <form id="editTemplateForm" action="" method="POST">
      @csrf
      @method('PUT')

      <div class="form-group-custom">
        <label>Judul Tombol / Tema Ucapan <span style="color: #ef4444;">*</span></label>
        <input type="text" id="edit_title" name="title" required>
      </div>

      <div class="form-group-custom">
        <label>Isi Kalimat / Pesan Ucapan Hantaran <span style="color: #ef4444;">*</span></label>
        <textarea id="edit_message" name="message" rows="4" required></textarea>
      </div>

      <div class="form-group-custom">
        <label>Urutan Tampilan</label>
        <input type="number" id="edit_display_order" name="display_order" min="1">
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid var(--panel-border); padding-top: 16px; margin-top: 20px;">
        <button type="button" class="btn btn-outline" onclick="closeEditModal()">Batal</button>
        <button type="submit" class="btn btn-primary" style="background: #9f183c; border-color: #e6ca65; color: #fef08a; font-weight: 700;">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

<script>
  function openAddModal() {
    document.getElementById('addTemplateModal').classList.add('active');
    document.body.style.overflow = 'hidden';
  }
  function closeAddModal() {
    document.getElementById('addTemplateModal').classList.remove('active');
    document.body.style.overflow = '';
  }

  function openEditModal(tpl) {
    const form = document.getElementById('editTemplateForm');
    form.action = "{{ url('admin/greeting-cards') }}/" + tpl.id;

    document.getElementById('edit_title').value = tpl.title || '';
    document.getElementById('edit_message').value = tpl.message || '';
    document.getElementById('edit_display_order').value = tpl.display_order || 1;

    document.getElementById('editTemplateModal').classList.add('active');
    document.body.style.overflow = 'hidden';
  }
  function closeEditModal() {
    document.getElementById('editTemplateModal').classList.remove('active');
    document.body.style.overflow = '';
  }

  // Close modals on ESC or clicking outside
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeAddModal();
      closeEditModal();
    }
  });

  const addM = document.getElementById('addTemplateModal');
  if (addM) {
    addM.addEventListener('click', function(e) {
      if (e.target === addM) closeAddModal();
    });
  }
  const editM = document.getElementById('editTemplateModal');
  if (editM) {
    editM.addEventListener('click', function(e) {
      if (e.target === editM) closeEditModal();
    });
  }
</script>
@endsection
