@extends('layouts.admin')

@section('title', 'Profil & Keamanan Akun')
@section('page_title', 'Profil Admin & Keamanan Password')

@section('content')
<style>
  /* Profile Hero Banner */
  .profile-hero {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    border: 1px solid rgba(245, 158, 11, 0.3);
    border-radius: 16px;
    padding: 28px 32px;
    margin-bottom: 32px;
    display: flex;
    align-items: center;
    gap: 24px;
    flex-wrap: wrap;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    position: relative;
    overflow: hidden;
  }
  .profile-hero::after {
    content: '👑';
    position: absolute;
    right: 24px;
    bottom: -10px;
    font-size: 110px;
    opacity: 0.05;
    pointer-events: none;
  }
  .hero-avatar-wrapper {
    position: relative;
  }
  .hero-avatar-img {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--accent-gold);
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.35);
  }
  .hero-info h2 {
    font-family: 'Playfair Display', serif;
    font-size: 24px;
    color: #fff;
    margin-bottom: 4px;
  }
  .hero-info p {
    font-size: 13px;
    color: var(--text-muted);
  }
  .role-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(245, 158, 11, 0.15);
    border: 1px solid var(--accent-gold);
    color: var(--accent-gold);
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    margin-top: 8px;
  }

  /* Profile Grid */
  .profile-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
  }
  @media (max-width: 900px) {
    .profile-grid {
      grid-template-columns: 1fr;
    }
  }

  .profile-card {
    background: var(--panel-bg);
    border: 1px solid var(--panel-border);
    border-radius: 16px;
    padding: 28px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
    transition: border-color 0.3s ease;
  }
  .profile-card:hover {
    border-color: rgba(245, 158, 11, 0.5);
  }
  .card-header-title {
    font-size: 17px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 24px;
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .form-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
  }

  .password-input-group {
    position: relative;
  }
  .toggle-pwd-btn {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    font-size: 16px;
  }
  .toggle-pwd-btn:hover {
    color: var(--accent-gold);
  }

  .btn-save-gold {
    background: linear-gradient(135deg, var(--accent-gold) 0%, #b45309 100%);
    color: #fff;
    border: none;
    padding: 12px 24px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
    transition: all 0.2s ease;
    width: 100%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 8px;
  }
  .btn-save-gold:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(245, 158, 11, 0.4);
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
  }

  .security-tip {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid var(--panel-border);
    border-radius: 10px;
    padding: 14px 16px;
    margin-top: 20px;
    font-size: 12px;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 10px;
  }
</style>

<!-- 1. Hero Profile Banner -->
<div class="profile-hero">
  <div class="hero-avatar-wrapper">
    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="hero-avatar-img">
  </div>
  <div class="hero-info">
    <h2>{{ $user->name }}</h2>
    <p>📧 {{ $user->email }}</p>
    <div class="role-badge">
      👑 Administrator Utama • Pusat Hampers Indonesia
    </div>
  </div>
</div>

<!-- 2. Profile & Password Cards Grid -->
<div class="profile-grid">
  <!-- Card 1: Information & Profile Avatar -->
  <div class="profile-card">
    <div class="card-header-title">
      <span>👤 Informasi Profil & Foto Avatar</span>
    </div>

    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="form-group">
        <label class="form-label">Nama Lengkap Administrator</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
      </div>

      <div class="form-group">
        <label class="form-label">Alamat E-mail Administrator</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
      </div>

      <div class="form-group">
        <label class="form-label">Unggah Foto Profil / Avatar Baru</label>
        <input type="file" name="avatar_file" class="form-control" accept="image/*" style="padding: 9px 12px;">
        <small style="color: var(--text-muted); font-size: 11px; margin-top: 6px; display: block;">
          File yang didukung: JPG, PNG, WEBP (Ukuran maks: 2MB).
        </small>
      </div>

      <button type="submit" class="btn-save-gold">
        💾 Simpan Perubahan Profil
      </button>
    </form>
  </div>

  <!-- Card 2: Change Password -->
  <div class="profile-card">
    <div class="card-header-title">
      <span>🔒 Keamanan & Ganti Password</span>
    </div>

    <form action="{{ route('admin.profile.password') }}" method="POST">
      @csrf

      <div class="form-group">
        <label class="form-label">Kata Sandi Saat Ini</label>
        <div class="password-input-group">
          <input type="password" id="current_pwd" name="current_password" class="form-control" placeholder="Masukkan kata sandi saat ini..." required>
          <button type="button" class="toggle-pwd-btn" onclick="togglePasswordVisibility('current_pwd', this)">👁️</button>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Kata Sandi Baru</label>
        <div class="password-input-group">
          <input type="password" id="new_pwd" name="new_password" class="form-control" placeholder="Minimal 6 karakter..." required>
          <button type="button" class="toggle-pwd-btn" onclick="togglePasswordVisibility('new_pwd', this)">👁️</button>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Konfirmasi Kata Sandi Baru</label>
        <div class="password-input-group">
          <input type="password" id="confirm_pwd" name="new_password_confirmation" class="form-control" placeholder="Ulangi kata sandi baru..." required>
          <button type="button" class="toggle-pwd-btn" onclick="togglePasswordVisibility('confirm_pwd', this)">👁️</button>
        </div>
      </div>

      <button type="submit" class="btn-save-gold">
        🔑 Update Kata Sandi Akun
      </button>
    </form>

    <div class="security-tip">
      <span style="font-size: 18px;">🛡️</span>
      <div>
        <strong style="color: #fff; font-size: 11px; display: block;">Keamanan Tingkat Tinggi:</strong>
        Kata sandi dienkripsi menggunakan algoritma Bcrypt 12-round hash yang aman.
      </div>
    </div>
  </div>
</div>

<script>
  function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    if (input) {
      if (input.type === 'password') {
        input.type = 'text';
        btn.textContent = '🙈';
      } else {
        input.type = 'password';
        btn.textContent = '👁️';
      }
    }
  }
</script>
@endsection
