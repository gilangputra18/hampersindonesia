@extends('layouts.app')

@section('title', 'Login Pelanggan & Tamu | ' . config('site.brand'))

@section('content')
<style>
  .login-page-wrapper {
    background-color: #f8faf9;
    min-height: calc(100vh - 180px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 30px 14px;
    font-family: 'Outfit', sans-serif;
  }
  .login-form-box {
    width: 100%;
    max-width: 420px;
    background: #ffffff;
    border: 1px solid #d2dcd7;
    border-radius: 14px;
    padding: 32px 24px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    text-align: center;
  }
  @media (max-width: 480px) {
    .login-page-wrapper {
      padding: 18px 10px;
    }
    .login-form-box {
      padding: 24px 16px;
      border-radius: 12px;
    }
    .login-title {
      font-size: 22px !important;
      letter-spacing: 1.5px !important;
    }
    .input-custom {
      font-size: 16px !important; /* Prevents auto-zoom jitter on Android & iOS */
      padding: 12px 14px !important;
    }
  }
  .login-title {
    font-family: 'Playfair Display', serif;
    font-size: 26px;
    letter-spacing: 2px;
    font-weight: 700;
    text-transform: uppercase;
    color: #1e2d27;
    margin-bottom: 6px;
    word-break: normal;
  }
  .login-subtitle {
    font-size: 13px;
    color: #64756d;
    margin-bottom: 24px;
    line-height: 1.4;
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
    color: #1e2d27;
    background: #fafafa;
    box-sizing: border-box;
    transition: border-color 0.2s, background 0.2s;
  }
  .input-custom:focus {
    outline: none;
    border-color: #d97706;
    background: #fff;
  }
  .password-label-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
  }
  .forgot-link {
    font-size: 11px;
    color: #7a8b83;
    text-decoration: none;
  }
  .forgot-link:hover {
    text-decoration: underline;
    color: #b45309;
  }
  .btn-submit-login {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #111f18 0%, #1e3529 100%);
    border: 1px solid rgba(217, 119, 6, 0.4);
    color: #fef08a;
    font-size: 13px;
    letter-spacing: 1.5px;
    font-weight: 700;
    text-transform: uppercase;
    cursor: pointer;
    border-radius: 8px;
    margin-top: 6px;
    transition: all 0.2s;
    box-shadow: 0 4px 12px rgba(17, 31, 24, 0.2);
  }
  .btn-submit-login:hover {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
    color: #fff;
  }
  .btn-google-login {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    width: 100%;
    padding: 13px 16px;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    color: #1e293b;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    box-sizing: border-box;
  }
  .btn-google-login:hover {
    background: #f8fafc;
    border-color: #94a3b8;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
  }
</style>

<div class="login-page-wrapper">
  <div class="login-form-box">
    <div style="font-size: 28px; margin-bottom: 4px;">⚜️</div>
    <h1 class="login-title">LOGIN TAMU & PELANGGAN</h1>
    <p class="login-subtitle">Masuk dengan email Anda untuk mengecek riwayat pesanan & status pembayaran hampers</p>

    @if ($errors->any())
      <div class="alert-box alert-err">
        @foreach ($errors->all() as $error)
          <div>• {{ $error }}</div>
        @endforeach
      </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
      @csrf

      <div class="form-group-custom">
        <label for="customer_email">Alamat Email</label>
        <input type="email" name="email" id="customer_email" class="input-custom" placeholder="contoh@gmail.com" value="{{ old('email') }}" required autofocus>
      </div>

      <div class="form-group-custom">
        <div class="password-label-row">
          <label for="customer_pwd" style="margin-bottom: 0;">Kata Sandi</label>
          <a href="#" onclick="alert('Silakan hubungi Concierge Layanan kami via email ({{ config('site.email') }}) atau telepon ({{ config('site.phone') }}) untuk mereset kata sandi Anda.'); return false;" class="forgot-link">Lupa sandi?</a>
        </div>
        <div style="position: relative; display: flex; align-items: center;">
          <input type="password" name="password" id="customer_pwd" class="input-custom" placeholder="Masukkan kata sandi" required style="padding-right: 46px;">
          <button type="button" onclick="const p=document.getElementById('customer_pwd'); p.type=p.type==='password'?'text':'password'; this.textContent=p.type==='password'?'👁️':'🙈';" style="position: absolute; right: 8px; background: none; border: none; cursor: pointer; font-size: 16px; opacity: 0.7; padding: 6px;" title="Tampilkan/Sembunyikan Sandi">👁️</button>
        </div>
      </div>

      <button type="submit" class="btn-submit-login">MASUK KE AKUN</button>
    </form>

    <!-- Google Sign In Button -->
    <div style="margin: 20px 0 16px; display: flex; align-items: center; justify-content: center; gap: 12px;">
      <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
      <span style="font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">atau</span>
      <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
    </div>

    <a href="{{ route('auth.google') }}" class="btn-google-login">
      <svg width="18" height="18" viewBox="0 0 24 24">
        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
      </svg>
      <span>Masuk Cepat dengan Akun Google</span>
    </a>

    <div style="margin-top: 22px; font-size: 13px; color: #64756d;">
      <a href="{{ route('home') }}" style="color: #64756d; text-decoration: none;">← Kembali ke Beranda Toko</a>
    </div>

    <div style="margin-top: 12px; font-size: 12.5px; color: #64756d;">
      Belum memiliki akun? <a href="{{ route('shop') }}" style="color: #1e2d27; font-weight: 700; text-decoration: underline;">Belanja langsung sebagai Tamu</a>, akun otomatis terdaftar saat checkout.
    </div>
  </div>
</div>
@endsection
