@extends('layouts.app')

@section('title', 'Daftar Akun Baru Pelanggan | ' . config('site.brand'))

@section('content')
<style>
  .register-page-wrapper {
    background-color: #fdf8f9;
    min-height: calc(100vh - 180px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 30px 14px;
    font-family: 'Outfit', sans-serif;
  }
  .register-form-box {
    width: 100%;
    max-width: 460px;
    background: #ffffff;
    border: 1px solid #f2d0d8;
    border-radius: 16px;
    padding: 32px 28px;
    box-shadow: 0 10px 35px rgba(120, 13, 38, 0.08);
    text-align: center;
  }
  @media (max-width: 480px) {
    .register-page-wrapper {
      padding: 18px 10px;
    }
    .register-form-box {
      padding: 24px 18px;
      border-radius: 12px;
    }
    .register-title {
      font-size: 21px !important;
      letter-spacing: 1.2px !important;
    }
    .input-custom {
      font-size: 16px !important;
      padding: 12px 14px !important;
    }
  }

  /* Navigation Tabs */
  .auth-tabs {
    display: flex;
    background: #fdf2f4;
    border: 1px solid #fcd5df;
    border-radius: 10px;
    padding: 4px;
    margin-bottom: 24px;
    gap: 4px;
  }
  .auth-tab {
    flex: 1;
    padding: 9px 12px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    color: #780d26;
    border-radius: 8px;
    transition: all 0.2s ease;
    text-align: center;
  }
  .auth-tab.active {
    background: #780d26;
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(120, 13, 38, 0.3);
  }
  .auth-tab:hover:not(.active) {
    background: rgba(159, 24, 60, 0.1);
  }

  .register-title {
    font-family: 'Playfair Display', serif;
    font-size: 24px;
    letter-spacing: 1.5px;
    font-weight: 700;
    text-transform: uppercase;
    color: #22040b;
    margin-bottom: 6px;
    word-break: normal;
  }
  .register-subtitle {
    font-size: 13px;
    color: #64748b;
    margin-bottom: 22px;
    line-height: 1.45;
  }
  .alert-box {
    padding: 12px 14px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 13px;
    text-align: left;
  }
  .alert-err {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #991b1b;
  }
  .form-group-custom {
    margin-bottom: 16px;
    text-align: left;
  }
  .form-group-custom label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #334155;
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .input-custom {
    width: 100%;
    padding: 13px 16px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-family: inherit;
    font-size: 14px;
    color: #1e293b;
    background: #fafafa;
    box-sizing: border-box;
    transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
  }
  .input-custom:focus {
    outline: none;
    border-color: #9f183c;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(159, 24, 60, 0.15);
  }
  .btn-submit-register {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #780d26 0%, #9f183c 100%);
    border: 1px solid #c92350;
    color: #ffffff;
    font-size: 13px;
    letter-spacing: 1.5px;
    font-weight: 700;
    text-transform: uppercase;
    cursor: pointer;
    border-radius: 8px;
    margin-top: 8px;
    transition: all 0.2s;
    box-shadow: 0 4px 14px rgba(120, 13, 38, 0.3);
  }
  .btn-submit-register:hover {
    background: linear-gradient(135deg, #9f183c 0%, #b81f47 100%);
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(159, 24, 60, 0.45);
  }
  .btn-google-login {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    width: 100%;
    padding: 13px 16px;
    background: #ffffff;
    border: 1.5px solid #dadce0;
    border-radius: 8px;
    color: #3c4043;
    font-size: 13.5px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px rgba(60,64,67,0.08);
    box-sizing: border-box;
  }
  .btn-google-login:hover {
    background: #f8fafd;
    border-color: #4285f4;
    color: #1a73e8;
    box-shadow: 0 3px 8px rgba(66,133,244,0.2);
    transform: translateY(-1px);
  }
</style>

<div class="register-page-wrapper">
  <div class="register-form-box">
    
    <!-- Tab Pilihan: Masuk atau Daftar -->
    <div class="auth-tabs">
      <a href="{{ route('login') }}" class="auth-tab">Masuk Akun</a>
      <a href="{{ route('register') }}" class="auth-tab active">Daftar Akun Baru</a>
    </div>

    <div style="font-size: 28px; margin-bottom: 4px;">⚜️</div>
    <h1 class="register-title">DAFTAR AKUN BARU</h1>
    <p class="register-subtitle">Buat akun untuk kemudahan pemesanan, lacak paket pengiriman, dan penawaran hampers istimewa</p>

    <!-- Opsi 1: Google Fast Registration -->
    <a href="{{ route('auth.google') }}" class="btn-google-login">
      <svg width="18" height="18" viewBox="0 0 24 24">
        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
      </svg>
      <span>Daftar Cepat dengan Akun Google</span>
    </a>

    <!-- Divider Antara Google dan Registrasi Manual -->
    <div style="margin: 22px 0 18px; display: flex; align-items: center; justify-content: center; gap: 12px;">
      <div style="flex: 1; height: 1px; background: #f2d0d8;"></div>
      <span style="font-size: 11px; color: #9f183c; text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">atau opsi pendaftaran manual</span>
      <div style="flex: 1; height: 1px; background: #f2d0d8;"></div>
    </div>

    @if (session('warning'))
      <div style="background: #fffbeb; border: 1.5px solid #fcd34d; color: #92400e; padding: 12px 14px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; text-align: left; display: flex; align-items: center; gap: 8px;">
        <span style="font-size: 18px;">🔒</span>
        <span>{{ session('warning') }}</span>
      </div>
    @endif

    @if (session('info'))
      <div style="background: #eff6ff; border: 1.5px solid #93c5fd; color: #1e40af; padding: 12px 14px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; text-align: left; display: flex; align-items: center; gap: 8px;">
        <span style="font-size: 18px;">ℹ️</span>
        <span>{{ session('info') }}</span>
      </div>
    @endif

    @if ($errors->any())
      <div class="alert-box alert-err">
        @foreach ($errors->all() as $error)
          <div>• {{ $error }}</div>
        @endforeach
      </div>
    @endif

    <!-- Form Registrasi Akun Baru -->
    <form action="{{ route('register.submit') }}" method="POST">
      @csrf

      <div class="form-group-custom">
        <label for="reg_name">Nama Lengkap *</label>
        <input type="text" name="name" id="reg_name" class="input-custom" placeholder="Nama Lengkap Anda" value="{{ old('name') }}" required autofocus>
      </div>

      <div class="form-group-custom">
        <label for="reg_email">Alamat Email *</label>
        <input type="email" name="email" id="reg_email" class="input-custom" placeholder="contoh: nama@gmail.com" value="{{ old('email') }}" required>
      </div>

      <div class="form-group-custom">
        <label for="reg_phone">Nomor WhatsApp / HP (Opsional)</label>
        <input type="tel" name="phone" id="reg_phone" class="input-custom" placeholder="contoh: 081234567890" value="{{ old('phone') }}">
      </div>

      <div class="form-group-custom">
        <label for="reg_pwd">Kata Sandi *</label>
        <div style="position: relative; display: flex; align-items: center;">
          <input type="password" name="password" id="reg_pwd" class="input-custom" placeholder="Minimal 6 karakter" required style="padding-right: 46px;">
          <button type="button" onclick="const p=document.getElementById('reg_pwd'); p.type=p.type==='password'?'text':'password'; this.textContent=p.type==='password'?'👁️':'🙈';" style="position: absolute; right: 8px; background: none; border: none; cursor: pointer; font-size: 16px; opacity: 0.7; padding: 6px;" title="Tampilkan/Sembunyikan Sandi">👁️</button>
        </div>
      </div>

      <div class="form-group-custom">
        <label for="reg_pwd_confirmation">Konfirmasi Kata Sandi *</label>
        <div style="position: relative; display: flex; align-items: center;">
          <input type="password" name="password_confirmation" id="reg_pwd_confirmation" class="input-custom" placeholder="Ulangi kata sandi di atas" required style="padding-right: 46px;">
          <button type="button" onclick="const p=document.getElementById('reg_pwd_confirmation'); p.type=p.type==='password'?'text':'password'; this.textContent=p.type==='password'?'👁️':'🙈';" style="position: absolute; right: 8px; background: none; border: none; cursor: pointer; font-size: 16px; opacity: 0.7; padding: 6px;" title="Tampilkan/Sembunyikan Sandi">👁️</button>
        </div>
      </div>

      <button type="submit" class="btn-submit-register">DAFTAR SEKARANG</button>
    </form>

    <div style="margin-top: 24px; padding-top: 18px; border-top: 1px solid #f2d0d8; font-size: 13px; color: #520e20;">
      Sudah memiliki akun? <a href="{{ route('login') }}" style="color: #9f183c; font-weight: 700; text-decoration: underline;">Masuk di Sini</a>
    </div>

    <div style="margin-top: 14px; font-size: 12.5px; color: #64748b;">
      <a href="{{ route('home') }}" style="color: #64748b; text-decoration: none;">← Kembali ke Beranda Toko</a>
    </div>
  </div>
</div>
@endsection
