@extends('layouts.app')

@section('title', 'Sign in with Google | ' . config('site.brand'))

@section('content')
@php
  $googleClientId = config('services.google.client_id');
  $googleReady = $googleClientId && !str_contains($googleClientId, 'example') && !str_contains($googleClientId, 'your-');
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
    max-width: 440px;
    background: #ffffff;
    border: 1px solid #dadce0;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(60,64,67,0.08), 0 4px 8px rgba(60,64,67,0.04);
    overflow: hidden;
  }
  .g-header-bar {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 20px;
    border-bottom: 1px solid #f1f3f4;
    font-size: 14px;
    color: #3c4043;
    font-weight: 500;
  }
  .g-header-logo {
    width: 18px;
    height: 18px;
  }
  
  .g-body-content {
    padding: 32px 32px 24px;
    text-align: center;
  }
  .g-app-icon {
    width: 48px;
    height: 48px;
    background: #fce8e6;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin-bottom: 20px;
  }
  .g-title {
    font-size: 24px;
    font-weight: 400;
    color: #202124;
    margin: 0 0 8px 0;
    letter-spacing: -0.2px;
  }
  .g-subtitle {
    font-size: 14px;
    color: #5f6368;
    margin-bottom: 28px;
  }
  .g-subtitle strong {
    color: #1a73e8;
    font-weight: 600;
  }

  .g-accounts-list {
    text-align: left;
    margin-bottom: 24px;
  }
  .g-account-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 12px 14px;
    border-bottom: 1px solid #f1f3f4;
    cursor: pointer;
    transition: background 0.15s ease;
    text-decoration: none;
    color: inherit;
    border-radius: 4px;
  }
  .g-account-item:hover {
    background: #f8f9fa;
  }
  .g-avatar-badge {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #681da8;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
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
    font-weight: 500;
    color: #202124;
    line-height: 1.3;
  }
  .g-account-email {
    font-size: 12px;
    color: #5f6368;
    margin-top: 1px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .g-use-another {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 14px 14px;
    border-bottom: 1px solid #f1f3f4;
    cursor: pointer;
    color: #202124;
    font-size: 14px;
    font-weight: 500;
    transition: background 0.15s ease;
  }
  .g-use-another:hover {
    background: #f8f9fa;
  }
  .g-another-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 1px solid #dadce0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #5f6368;
    font-size: 18px;
  }

  .g-footer-disclaimer {
    font-size: 12px;
    line-height: 1.6;
    color: #5f6368;
    text-align: left;
    margin-top: 24px;
    border-top: 1px solid #f1f3f4;
    padding-top: 20px;
  }
  .g-footer-disclaimer a {
    color: #1a73e8;
    text-decoration: none;
    font-weight: 500;
  }
  .g-footer-disclaimer a:hover {
    text-decoration: underline;
  }

  .g-email-input-form {
    display: none;
    margin-top: 16px;
    text-align: left;
  }
  .g-email-input-form.active {
    display: block;
  }
  .g-input {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #dadce0;
    border-radius: 4px;
    font-size: 14px;
    margin-bottom: 12px;
    outline: none;
  }
  .g-input:focus {
    border-color: #1a73e8;
    box-shadow: 0 0 0 2px rgba(26,115,232,0.2);
  }
  .g-btn-submit {
    width: 100%;
    padding: 10px 24px;
    background: #1a73e8;
    color: #ffffff;
    border: none;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s;
  }
  .g-btn-submit:hover {
    background: #1557b0;
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
      <h1 class="g-title">Choose an account</h1>
      <div class="g-subtitle">to continue to <strong>Pusat Hampers Indonesia</strong></div>

      @if (config('services.google.client_id') && !str_contains(config('services.google.client_id'), 'example') && !str_contains(config('services.google.client_id'), 'your-'))
      <!-- Native Google One Tap Button Container -->
      <div id="g_id_onload"
           data-client_id="{{ config('services.google.client_id') }}"
           data-callback="handleCredentialResponse"
           data-auto_prompt="true">
      </div>
      <div id="googleBtnRender" style="margin-bottom: 20px; display: flex; justify-content: center;"></div>
      @endif

      @if ($googleReady)
      <!-- Real Google account chooser: lists the accounts signed in on THIS device, user just clicks one -->
      <form id="google-credential-form" action="{{ route('auth.google.callback.post') }}" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="credential" id="google-credential-field">
      </form>
      <p style="font-size: 13px; color: #5f6368; margin-bottom: 14px;">Klik tombol di bawah, lalu pilih salah satu akun Google yang ada di perangkat ini.</p>
      @else
      <div style="font-size: 12px; color: #92400e; background: #fffbeb; border: 1px solid #fde68a; border-radius: 6px; padding: 10px 12px; margin-bottom: 16px; text-align: left;">
        Mode manual: daftar akun hanya menampilkan akun yang pernah dimasukkan di perangkat ini. Untuk memilih langsung dari akun Google di perangkat, <code>GOOGLE_CLIENT_ID</code> perlu diatur.
      </div>
      <!-- Device Logged In Accounts List -->
      <div class="g-accounts-list" id="deviceAccountsContainer">
        <!-- Rendered dynamically from user's device -->
      </div>

      <div class="g-use-another" id="toggleAnotherAccountBtn">
        <div class="g-another-icon">👤</div>
        <div>Use another account</div>
      </div>

      <!-- Hidden Email Input Form for Another Account -->
      <form id="google-auto-form" action="{{ route('auth.google.callback.post') }}" method="POST" class="g-email-input-form">
        @csrf
        <input type="hidden" id="google-id-field" name="google_id">
        <input type="hidden" id="google-avatar-field" name="avatar">

        <label style="font-size: 12px; color: #5f6368; display: block; margin-bottom: 6px;">Email address:</label>
        <input type="email" id="google-email-field" name="email" class="g-input" placeholder="email@gmail.com" required value="{{ old('email') }}">

        <label style="font-size: 12px; color: #5f6368; display: block; margin-bottom: 6px;">Name (Optional):</label>
        <input type="text" id="google-name-field" name="name" class="g-input" placeholder="Account Name">

        <button type="submit" class="g-btn-submit">Next</button>
      </form>
      @endif

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
      const userAvatar = avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(name || email)}&background=681da8&color=fff&bold=true`;
      accounts.unshift({ email, name: name || email.split('@')[0], avatar: userAvatar });
      localStorage.setItem('maison_google_accounts', JSON.stringify(accounts.slice(0, 5)));
    } catch(e) {}
  }

  function loadDeviceAccounts() {
    const container = document.getElementById('deviceAccountsContainer');
    if (!container) return;

    try {
      let accounts = JSON.parse(localStorage.getItem('maison_google_accounts') || '[]');
      if (accounts && accounts.length > 0) {
        container.innerHTML = accounts.map(acc => {
          const initial = (acc.name || acc.email).charAt(0).toUpperCase();
          const avatarHtml = acc.avatar && !acc.avatar.includes('ui-avatars') 
            ? `<img src="${acc.avatar}" alt="${acc.name}">` 
            : initial;

          return `
            <div class="g-account-item" onclick="submitGoogleLogin('${acc.email}', '${acc.name}', '${acc.avatar || ''}')">
              <div class="g-avatar-badge">${avatarHtml}</div>
              <div class="g-account-info">
                <div class="g-account-name">${acc.name}</div>
                <div class="g-account-email">${acc.email}</div>
              </div>
            </div>
          `;
        }).join('');
      } else {
        container.innerHTML = '';
        // If no saved account yet on device, activate email input form directly
        const form = document.getElementById('google-auto-form');
        if (form) form.classList.add('active');
      }
    } catch(e) {}
  }

  function handleCredentialResponse(response) {
    const credentialField = document.getElementById('google-credential-field');
    if (credentialField && response && response.credential) {
      credentialField.value = response.credential;
      document.getElementById('google-credential-form').submit();
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
