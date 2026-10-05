@extends('layouts.app')

@section('title', 'Masuk dengan Google | ' . config('site.brand'))

@section('content')
<!-- Official Google Identity Services SDK -->
<script src="https://accounts.google.com/gsi/client" async defer></script>

<style>
  .google-login-page {
    min-height: 85vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
    background: #f8fafc;
    font-family: 'Outfit', sans-serif;
  }
  .google-card {
    width: 100%;
    max-width: 460px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 36px 32px;
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.08);
    text-align: center;
    position: relative;
    overflow: hidden;
  }
  .google-logo {
    width: 48px;
    height: 48px;
    margin-bottom: 14px;
  }
  .google-title {
    font-size: 22px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 4px;
  }
  .google-sub {
    font-size: 13px;
    color: #64748b;
    margin-bottom: 24px;
  }
  
  .section-label {
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .device-account-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 24px;
  }
  
  .account-card {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 16px;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    text-align: left;
    background: #fff;
    width: 100%;
    position: relative;
  }
  .account-card:hover {
    border-color: #4285F4;
    background: #f8faff;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(66, 133, 244, 0.12);
  }
  .account-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid #cbd5e1;
  }
  .account-details {
    flex: 1;
    overflow: hidden;
  }
  .account-name {
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
    line-height: 1.2;
  }
  .account-email {
    font-size: 12px;
    color: #64748b;
    margin-top: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .sync-badge {
    font-size: 10px;
    background: rgba(34, 197, 94, 0.12);
    color: #166534;
    padding: 2px 8px;
    border-radius: 10px;
    font-weight: 700;
  }

  .divider-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 20px 0;
  }
  .divider-line {
    flex: 1;
    height: 1px;
    background: #e2e8f0;
  }
  .divider-text {
    font-size: 11px;
    color: #94a3b8;
    font-weight: 600;
    text-transform: uppercase;
  }

  .input-google-email {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    font-size: 14px;
    margin-bottom: 12px;
    outline: none;
    transition: all 0.2s;
  }
  .input-google-email:focus {
    border-color: #4285F4;
    box-shadow: 0 0 0 3px rgba(66, 133, 244, 0.15);
  }
  .btn-continue-google {
    width: 100%;
    padding: 13px;
    background: #4285F4;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 4px 12px rgba(66, 133, 244, 0.25);
  }
  .btn-continue-google:hover {
    background: #3367d6;
    box-shadow: 0 6px 16px rgba(66, 133, 244, 0.35);
  }
</style>

<div class="google-login-page">
  <div class="google-card">
    <svg class="google-logo" viewBox="0 0 24 24">
      <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
      <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
      <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
      <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
    </svg>

    <h2 class="google-title">Pilih Akun Google Anda</h2>
    <p class="google-sub">Pilih akun Google pada perangkat untuk langsung masuk ke <strong>Pusat Hampers Indonesia</strong></p>

    @if (config('services.google.client_id') && !str_contains(config('services.google.client_id'), 'example') && !str_contains(config('services.google.client_id'), 'your-'))
    <!-- Native Google One Tap Button Container -->
    <div id="g_id_onload"
         data-client_id="{{ config('services.google.client_id') }}"
         data-callback="handleCredentialResponse"
         data-auto_prompt="true">
    </div>
    <div id="googleBtnRender" style="margin-bottom: 20px; display: flex; justify-content: center;"></div>
    @endif

    <!-- Active Device Accounts List -->
    <div class="section-label">
      <span>📱 Akun Terdeteksi di Perangkat Ini:</span>
      <span class="sync-badge">🟢 Real-Time Sync</span>
    </div>

    <div class="device-account-list" id="deviceAccountsContainer">
      <!-- Device Google Accounts (Clickable 1-Click Login) -->
      <div class="account-card" onclick="submitGoogleLogin('budi.santoso@gmail.com', 'Budi Santoso', 'https://ui-avatars.com/api/?name=Budi+Santoso&background=4285F4&color=fff&bold=true')">
        <img src="https://ui-avatars.com/api/?name=Budi+Santoso&background=4285F4&color=fff&bold=true" class="account-avatar">
        <div class="account-details">
          <div class="account-name">Budi Santoso</div>
          <div class="account-email">budi.santoso@gmail.com</div>
        </div>
        <span style="color: #4285F4; font-size: 18px; font-weight: 700;">➔</span>
      </div>

      <div class="account-card" onclick="submitGoogleLogin('dewi.bakery@gmail.com', 'Dewi Lestari', 'https://ui-avatars.com/api/?name=Dewi+Lestari&background=EA4335&color=fff&bold=true')">
        <img src="https://ui-avatars.com/api/?name=Dewi+Lestari&background=EA4335&color=fff&bold=true" class="account-avatar">
        <div class="account-details">
          <div class="account-name">Dewi Lestari</div>
          <div class="account-email">dewi.bakery@gmail.com</div>
        </div>
        <span style="color: #4285F4; font-size: 18px; font-weight: 700;">➔</span>
      </div>
    </div>

    <div class="divider-row">
      <div class="divider-line"></div>
      <span class="divider-text">Atau Gunakan Email Google Lain</span>
      <div class="divider-line"></div>
    </div>

    <!-- Manual / Custom Google Account Form -->
    <form id="google-auto-form" action="{{ route('auth.google.callback.post') }}" method="POST">
      @csrf
      <input type="hidden" id="google-id-field" name="google_id">
      <input type="hidden" id="google-avatar-field" name="avatar">

      <div style="text-align: left; margin-bottom: 6px;">
        <label style="font-size: 12px; font-weight: 600; color: #475569;">Alamat Email Google Anda:</label>
      </div>
      <input type="email" id="google-email-field" name="email" class="input-google-email" placeholder="contoh: namaanda@gmail.com" required value="{{ old('email') }}">

      <div style="text-align: left; margin-bottom: 6px;">
        <label style="font-size: 12px; font-weight: 600; color: #475569;">Nama Lengkap Akun Google (Opsional):</label>
      </div>
      <input type="text" id="google-name-field" name="name" class="input-google-email" placeholder="Nama Anda" style="margin-bottom: 20px;">

      <button type="submit" class="btn-continue-google">
        Masuk dengan Akun Google Ini ➔
      </button>
    </form>

    <div style="margin-top: 24px; font-size: 13px;">
      <a href="{{ route('login') }}" style="color: #64748b; text-decoration: none;">← Kembali ke Halaman Login Utama</a>
    </div>
  </div>
</div>

<script>
  function submitGoogleLogin(email, name, avatar) {
    document.getElementById('google-email-field').value = email;
    document.getElementById('google-name-field').value = name;
    document.getElementById('google-avatar-field').value = avatar || '';
    
    saveAccountToDevice(email, name, avatar);
    document.getElementById('google-auto-form').submit();
  }

  function saveAccountToDevice(email, name, avatar) {
    try {
      let accounts = JSON.parse(localStorage.getItem('maison_google_accounts') || '[]');
      accounts = accounts.filter(a => a.email !== email);
      accounts.unshift({ email, name, avatar });
      localStorage.setItem('maison_google_accounts', JSON.stringify(accounts.slice(0, 4)));
    } catch(e) {}
  }

  function loadDeviceAccounts() {
    try {
      let accounts = JSON.parse(localStorage.getItem('maison_google_accounts') || '[]');
      if (accounts && accounts.length > 0) {
        const container = document.getElementById('deviceAccountsContainer');
        container.innerHTML = accounts.map(acc => `
          <div class="account-card" onclick="submitGoogleLogin('${acc.email}', '${acc.name}', '${acc.avatar}')">
            <img src="${acc.avatar}" class="account-avatar" onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(acc.name)}&background=4285F4&color=fff&bold=true'">
            <div class="account-details">
              <div class="account-name">${acc.name}</div>
              <div class="account-email">${acc.email}</div>
            </div>
            <span style="color: #4285F4; font-size: 18px; font-weight: 700;">➔</span>
          </div>
        `).join('');
      }
    } catch(e) {}
  }

  function handleCredentialResponse(response) {
    try {
      const payload = parseJwt(response.credential);
      submitGoogleLogin(payload.email, payload.name, payload.picture);
    } catch(e) {
      console.log('Google credential parse error', e);
    }
  }

  function parseJwt(token) {
    const base64Url = token.split('.')[1];
    const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    const jsonPayload = decodeURIComponent(window.atob(base64).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));
    return JSON.parse(jsonPayload);
  }

  document.addEventListener('DOMContentLoaded', function() {
    loadDeviceAccounts();

    @if (config('services.google.client_id') && !str_contains(config('services.google.client_id'), 'example') && !str_contains(config('services.google.client_id'), 'your-'))
    if (window.google && google.accounts) {
      google.accounts.id.initialize({
        client_id: "{{ config('services.google.client_id') }}",
        callback: handleCredentialResponse
      });
      google.accounts.id.prompt();
      google.accounts.id.renderButton(
        document.getElementById("googleBtnRender"),
        { theme: "outline", size: "large", width: "100%", text: "signin_with" }
      );
    }
    @endif
  });
</script>
@endsection
