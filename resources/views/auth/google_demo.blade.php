@extends('layouts.app')

@section('title', 'Sign in with Google | ' . config('site.brand'))

@section('content')
@php
  $googleClientId = config('services.google.client_id');
  $googleReady = $googleClientId && !str_contains($googleClientId, 'example') && !str_contains($googleClientId, 'your-');
  $directAuthUrl = $oauthUrl ?? (route('auth.google') . '?direct=1');
@endphp

<!-- Official Google Identity Services SDK -->
<script src="https://accounts.google.com/gsi/client" async defer></script>

<style>
  .g-auth-wrapper {
    min-height: 85vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 30px 16px;
    background: #f8f9fa;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
  }
  .g-auth-card {
    width: 100%;
    max-width: 450px;
    background: #ffffff;
    border: 1px solid #dadce0;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(60,64,67,0.12), 0 1px 3px rgba(60,64,67,0.08);
    overflow: hidden;
  }
  .g-header-bar {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 22px;
    border-bottom: 1px solid #f1f3f4;
    font-size: 14px;
    color: #3c4043;
    font-weight: 500;
    background: #fff;
  }
  .g-header-logo {
    width: 20px;
    height: 20px;
  }
  
  .g-body-content {
    padding: 32px 30px 24px;
    text-align: center;
  }
  .g-app-icon {
    width: 52px;
    height: 52px;
    background: #fdf2f4;
    border: 1px solid #fcd5df;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin-bottom: 18px;
  }
  .g-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 24px;
    font-weight: 600;
    color: #202124;
    margin: 0 0 8px 0;
    letter-spacing: 0.5px;
  }
  .g-subtitle {
    font-size: 14px;
    color: #5f6368;
    margin-bottom: 22px;
  }
  .g-subtitle strong {
    color: #9f183c;
    font-weight: 600;
  }

  /* Primary Direct Google Action Button */
  .g-official-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    width: 100%;
    padding: 13px 20px;
    background: #ffffff;
    border: 1.5px solid #dadce0;
    border-radius: 8px;
    color: #3c4043;
    font-size: 14.5px;
    font-weight: 600;
    text-decoration: none;
    box-shadow: 0 1px 3px rgba(60,64,67,0.12);
    transition: all 0.2s ease;
    margin-bottom: 18px;
    box-sizing: border-box;
    cursor: pointer;
  }
  .g-official-btn:hover {
    background: #f8fafd;
    border-color: #4285f4;
    color: #1a73e8;
    box-shadow: 0 3px 8px rgba(66,133,244,0.25);
    transform: translateY(-1px);
  }

  .g-divider {
    margin: 20px 0 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
  }
  .g-divider-line {
    flex: 1;
    height: 1px;
    background: #e8eaed;
  }
  .g-divider-text {
    font-size: 11.5px;
    color: #80868b;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    font-weight: 600;
  }

  .g-accounts-list {
    text-align: left;
    margin-bottom: 12px;
  }
  .g-account-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 14px;
    border: 1px solid #e8eaed;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.18s ease;
    text-decoration: none;
    color: inherit;
    margin-bottom: 10px;
    background: #ffffff;
  }
  .g-account-item:hover {
    background: #f8fafd;
    border-color: #4285f4;
    box-shadow: 0 2px 6px rgba(66,133,244,0.15);
    transform: translateY(-1px);
  }
  .g-avatar-badge {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #780d26;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 15px;
    flex-shrink: 0;
    overflow: hidden;
  }
  .g-avatar-badge img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .g-account-info {
    flex: 1;
    overflow: hidden;
  }
  .g-account-name {
    font-size: 14px;
    font-weight: 600;
    color: #202124;
    line-height: 1.3;
  }
  .g-account-email {
    font-size: 12px;
    color: #5f6368;
    margin-top: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .g-account-arrow {
    font-size: 14px;
    color: #80868b;
    transition: transform 0.2s;
  }
  .g-account-item:hover .g-account-arrow {
    transform: translateX(3px);
    color: #1a73e8;
  }

  .g-use-another {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 14px;
    border: 1px dashed #dadce0;
    border-radius: 8px;
    cursor: pointer;
    color: #3c4043;
    font-size: 13.5px;
    font-weight: 600;
    transition: all 0.18s ease;
    background: #fafafa;
    margin-bottom: 14px;
  }
  .g-use-another:hover {
    background: #f1f3f4;
    border-color: #9f183c;
    color: #9f183c;
  }
  .g-another-icon {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    border: 1px solid #dadce0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #5f6368;
    font-size: 17px;
    background: #fff;
  }

  .g-email-input-form {
    display: none;
    margin-top: 14px;
    text-align: left;
    padding: 16px;
    background: #f8fafd;
    border: 1px solid #d2e3fc;
    border-radius: 8px;
  }
  .g-email-input-form.active {
    display: block;
    animation: fadeIn 0.25s ease;
  }
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(-4px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .g-input {
    width: 100%;
    padding: 11px 14px;
    border: 1px solid #dadce0;
    border-radius: 6px;
    font-size: 13.5px;
    margin-bottom: 12px;
    outline: none;
    box-sizing: border-box;
    background: #fff;
  }
  .g-input:focus {
    border-color: #1a73e8;
    box-shadow: 0 0 0 2px rgba(26,115,232,0.2);
  }
  .g-btn-submit {
    width: 100%;
    padding: 11px 24px;
    background: #1a73e8;
    color: #ffffff;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
  }
  .g-btn-submit:hover {
    background: #1557b0;
  }

  .g-footer-disclaimer {
    font-size: 12px;
    line-height: 1.6;
    color: #5f6368;
    text-align: left;
    margin-top: 22px;
    border-top: 1px solid #f1f3f4;
    padding-top: 18px;
  }
  .g-footer-disclaimer a {
    color: #1a73e8;
    text-decoration: none;
    font-weight: 500;
  }
  .g-footer-disclaimer a:hover {
    text-decoration: underline;
  }
</style>

<div class="g-auth-wrapper">
  <div class="g-auth-card">
    <div class="g-header-bar">
      <svg class="g-header-logo" viewBox="0 0 24 24">
        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
      </svg>
      <span>Sign in with Google</span>
    </div>

    <div class="g-body-content">
      <div class="g-app-icon">✌️</div>
      <h1 class="g-title">CHOOSE AN ACCOUNT</h1>
      <div class="g-subtitle">to continue to <strong>Pusat Hampers Indonesia</strong></div>

      <!-- 1. Tombol Utama Resmi Google (Membuka Layar Akun Google di Perangkat) -->
      <a href="{{ $directAuthUrl }}" class="g-official-btn" id="mainGoogleBtn">
        <svg width="20" height="20" viewBox="0 0 24 24">
          <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
          <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
          <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
          <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
        </svg>
        <span>Buka Pilihan Akun Google Resmi</span>
      </a>

      <!-- Google Identity Services Container (Jika Didukung Peramban) -->
      @if ($googleReady)
      <div id="g_id_onload"
           data-client_id="{{ config('services.google.client_id') }}"
           data-callback="handleCredentialResponse"
           data-auto_prompt="false">
      </div>
      <div id="googleBtnRender" style="display:none; margin-bottom: 16px; justify-content: center;"></div>

      <form id="google-credential-form" action="{{ route('auth.google.callback.post') }}" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="credential" id="google-credential-field">
      </form>
      @endif

      <!-- Divider -->
      <div class="g-divider">
        <div class="g-divider-line"></div>
        <span class="g-divider-text">atau pilih akun perangkat di bawah</span>
        <div class="g-divider-line"></div>
      </div>

      <!-- 2. Daftar Pilihan Akun Google yang Tersimpan di Perangkat Ini -->
      <div class="g-accounts-list" id="deviceAccountsContainer">
        <!-- Rendered dynamically -->
      </div>

      <!-- 3. Tombol Gunakan Akun Google Lain / Masuk Manual -->
      <div class="g-use-another" id="toggleAnotherAccountBtn">
        <div class="g-another-icon">👤</div>
        <div style="flex:1;">Gunakan akun Google lain / Ketik Email</div>
        <span style="font-size:12px; color:#1a73e8;">✏️</span>
      </div>

      <!-- Form Input Email Akun Google -->
      <form id="google-auto-form" action="{{ route('auth.google.callback.post') }}" method="POST" class="g-email-input-form">
        @csrf
        <input type="hidden" id="google-id-field" name="google_id">
        <input type="hidden" id="google-avatar-field" name="avatar">

        <label style="font-size: 12px; font-weight: 600; color: #3c4043; display: block; margin-bottom: 6px;">Email Akun Google:</label>
        <input type="email" id="google-email-field" name="email" class="g-input" placeholder="contoh: namaanda@gmail.com" required value="{{ old('email') }}">

        <label style="font-size: 12px; font-weight: 600; color: #3c4043; display: block; margin-bottom: 6px;">Nama Anda (Opsional):</label>
        <input type="text" id="google-name-field" name="name" class="g-input" placeholder="Nama Lengkap">

        <button type="submit" class="g-btn-submit">Lanjutkan Masuk</button>
      </form>

      <div style="margin-top: 18px; padding-top: 14px; border-top: 1px solid #f1f3f4; font-size: 13px; color: #5f6368;">
        Ingin mendaftar tanpa akun Google? <a href="{{ route('register') }}" style="color: #9f183c; font-weight: 700; text-decoration: underline;">Daftar Akun Baru Manual</a>
      </div>

      <div class="g-footer-disclaimer">
        To continue, Google will share your name, email address, language preference, and profile picture with Pusat Hampers Indonesia. Before using this app, you can review Pusat Hampers Indonesia's <a href="{{ route('home') }}">privacy policy</a> and <a href="{{ route('home') }}">terms of service</a>.
      </div>
    </div>
  </div>
</div>

<script>
  function submitGoogleLogin(email, name, avatar) {
    if (!email) return;

    document.getElementById('google-email-field').value = email;
    if (name) document.getElementById('google-name-field').value = name;
    if (avatar) document.getElementById('google-avatar-field').value = avatar;

    const displayName = name || email.split('@')[0];
    saveAccountToDevice(email, displayName, avatar);
    document.getElementById('google-auto-form').submit();
  }

  function saveAccountToDevice(email, name, avatar) {
    try {
      let accounts = JSON.parse(localStorage.getItem('maison_google_accounts') || '[]');
      accounts = accounts.filter(a => a.email !== email);
      const userAvatar = avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(name || email)}&background=780d26&color=fff&bold=true`;
      accounts.unshift({ email, name: name || email.split('@')[0], avatar: userAvatar });
      localStorage.setItem('maison_google_accounts', JSON.stringify(accounts.slice(0, 5)));
    } catch(e) {}
  }

  function loadDeviceAccounts() {
    const container = document.getElementById('deviceAccountsContainer');
    if (!container) return;

    try {
      let accounts = JSON.parse(localStorage.getItem('maison_google_accounts') || '[]');
      
      // Default account options if first time on device
      if (!accounts || accounts.length === 0) {
        accounts = [
          {
            name: 'Akun Google Utama',
            email: 'Gunakan akun Google yang aktif di HP / laptop ini',
            avatar: ''
          }
        ];
      }

      container.innerHTML = accounts.map(acc => {
        const isDefault = acc.email.includes('Gunakan');
        const initial = isDefault ? 'G' : (acc.name || acc.email).charAt(0).toUpperCase();
        const avatarHtml = acc.avatar && !acc.avatar.includes('ui-avatars') 
          ? `<img src="${acc.avatar}" alt="${acc.name}">` 
          : initial;

        const clickAction = isDefault 
          ? `window.location.href='${document.getElementById('mainGoogleBtn').href}'`
          : `submitGoogleLogin('${acc.email}', '${acc.name}', '${acc.avatar || ''}')`;

        return `
          <div class="g-account-item" onclick="${clickAction}">
            <div class="g-avatar-badge" style="${isDefault ? 'background:#4285F4;' : ''}">${avatarHtml}</div>
            <div class="g-account-info">
              <div class="g-account-name">${acc.name}</div>
              <div class="g-account-email">${acc.email}</div>
            </div>
            <div class="g-account-arrow">→</div>
          </div>
        `;
      }).join('');
    } catch(e) {}
  }

  function handleCredentialResponse(response) {
    const credentialField = document.getElementById('google-credential-field');
    if (credentialField && response && response.credential) {
      credentialField.value = response.credential;
      document.getElementById('google-credential-form').submit();
    }
  }

  document.addEventListener('DOMContentLoaded', function() {
    loadDeviceAccounts();

    const toggleBtn = document.getElementById('toggleAnotherAccountBtn');
    const form = document.getElementById('google-auto-form');
    if (toggleBtn && form) {
      toggleBtn.addEventListener('click', function() {
        form.classList.toggle('active');
        if (form.classList.contains('active')) {
          document.getElementById('google-email-field').focus();
        }
      });
    }

    // Attempt GIS initialization with retry
    let attempts = 0;
    const checkGsi = setInterval(function() {
      attempts++;
      if (window.google && google.accounts && google.accounts.id) {
        clearInterval(checkGsi);
        @if ($googleReady)
        try {
          google.accounts.id.initialize({
            client_id: "{{ config('services.google.client_id') }}",
            callback: handleCredentialResponse
          });
          const btnElem = document.getElementById("googleBtnRender");
          if (btnElem) {
            btnElem.style.display = "flex";
            google.accounts.id.renderButton(btnElem, {
              theme: "outline",
              size: "large",
              width: "100%",
              text: "signin_with"
            });
          }
        } catch(err) {}
        @endif
      }
      if (attempts > 20) clearInterval(checkGsi);
    }, 200);
  });
</script>
@endsection
