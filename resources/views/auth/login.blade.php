@extends('layouts.app')

@section('title', 'Login | ' . config('site.brand'))

@section('content')
<style>
  .login-page-wrapper {
    background-color: #fff;
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    font-family: 'Outfit', sans-serif;
  }
  .login-form-box {
    width: 100%;
    max-width: 440px;
    text-align: center;
  }
  .login-title {
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    letter-spacing: 4px;
    font-weight: 400;
    text-transform: uppercase;
    color: #1e2d27;
    margin-bottom: 12px;
  }
  .login-subtitle {
    font-size: 13px;
    color: #64756d;
    margin-bottom: 36px;
  }
  .form-group-custom {
    margin-bottom: 20px;
    text-align: left;
  }
  .input-custom {
    width: 100%;
    padding: 14px 16px;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
    font-family: inherit;
    font-size: 13px;
    color: #1e2d27;
    background: #fafafa;
    transition: border-color 0.2s, background 0.2s;
  }
  .input-custom:focus {
    outline: none;
    border-color: #8c8275;
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
  }
  .btn-submit-login {
    width: 100%;
    padding: 14px;
    background: #dcd6cd;
    border: 1px solid #8c8275;
    color: #1e2d27;
    font-size: 12px;
    letter-spacing: 3px;
    font-weight: 600;
    text-transform: uppercase;
    cursor: pointer;
    border-radius: 4px;
    margin-top: 10px;
    transition: background 0.2s;
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
    border-radius: 6px;
    color: #1e293b;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    box-shadow: 0 2px 6px rgba(0,0,0,0.06);
  }
  .btn-google-login:hover {
    background: #f8fafc;
    border-color: #94a3b8;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-1px);
  }
</style>

<div class="login-page-wrapper">
  <div class="login-form-box">
    <h1 class="login-title">LOGIN</h1>
    <p class="login-subtitle">Enter your email and password to login:</p>

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
        <input type="email" name="email" class="input-custom" placeholder="E-mail" value="{{ old('email') }}" required autofocus>
      </div>

      <div class="form-group-custom">
        <div class="password-label-row">
          <span></span>
          <a href="#" onclick="alert('Silakan hubungi customer service kami via WhatsApp untuk mereset kata sandi Anda.'); return false;" class="forgot-link">Forgot your password?</a>
        </div>
        <div style="position: relative; display: flex; align-items: center;">
          <input type="password" name="password" id="customer_pwd" class="input-custom" placeholder="Password" required style="padding-right: 44px;">
          <button type="button" onclick="const p=document.getElementById('customer_pwd'); p.type=p.type==='password'?'text':'password'; this.textContent=p.type==='password'?'👁️':'🙈';" style="position: absolute; right: 10px; background: none; border: none; cursor: pointer; font-size: 16px; opacity: 0.7;">👁️</button>
        </div>
      </div>

      <button type="submit" class="btn-submit-login">LOGIN</button>
    </form>

    <!-- Google Sign In Button -->
    <div style="margin: 24px 0 18px; display: flex; align-items: center; justify-content: center; gap: 12px;">
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
      <span>Masuk dengan Akun Google</span>
    </a>

    <div style="margin-top: 24px; font-size: 13px; color: #7a8b83;">
      <a href="{{ route('home') }}" style="color: #64756d; text-decoration: none;">← Kembali ke Halaman Utama Toko</a>
    </div>

    <div style="margin-top: 14px; font-size: 13px; color: #7a8b83;">
      Belum memiliki akun? <a href="#" onclick="alert('Silakan langsung berbelanja sebagai Tamu, akun akan otomatis terbuat saat checkout.'); return false;" style="color: #1e2d27; text-decoration: underline;">Buat Akun Baru</a>
    </div>
  </div>
</div>
@endsection
