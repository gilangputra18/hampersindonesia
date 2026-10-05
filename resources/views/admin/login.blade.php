<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login | PUSAT HAMPERS INDONESIA</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;1,400&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg-dark: #0f172a;
      --panel-bg: #1e293b;
      --panel-border: #334155;
      --accent-gold: #d97706;
      --accent-gold-hover: #b45309;
      --text-main: #f8fafc;
      --text-muted: #94a3b8;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Outfit', sans-serif;
      background: radial-gradient(circle at center, #1e293b 0%, #0f172a 100%);
      color: var(--text-main);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    
    .page-container {
      width: 100%;
      max-width: 420px;
    }

    .top-back-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      color: var(--text-muted);
      text-decoration: none;
      font-size: 13px;
      font-weight: 500;
      margin-bottom: 16px;
      padding: 8px 14px;
      border-radius: 8px;
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid var(--panel-border);
      transition: all 0.2s ease;
    }
    .top-back-btn:hover {
      color: #fff;
      background: rgba(245, 158, 11, 0.15);
      border-color: var(--accent-gold);
      transform: translateX(-3px);
    }

    .login-card {
      width: 100%;
      background: rgba(30, 41, 59, 0.85);
      backdrop-filter: blur(12px);
      border: 1px solid var(--panel-border);
      border-radius: 16px;
      padding: 36px 32px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
    }
    .brand-header {
      text-align: center;
      margin-bottom: 28px;
    }
    .brand-header h1 {
      font-family: 'Playfair Display', serif;
      font-size: 26px;
      letter-spacing: 2px;
      color: var(--accent-gold);
      margin-bottom: 6px;
    }
    .brand-header p {
      font-size: 13px;
      color: var(--text-muted);
    }
    .form-group {
      margin-bottom: 20px;
    }
    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-size: 13px;
      font-weight: 500;
      color: var(--text-main);
    }
    .form-control {
      width: 100%;
      padding: 12px 16px;
      background: #0f172a;
      border: 1px solid var(--panel-border);
      border-radius: 8px;
      color: var(--text-main);
      font-size: 14px;
      font-family: inherit;
    }
    .form-control:focus {
      outline: none;
      border-color: var(--accent-gold);
    }

    .pwd-wrapper {
      position: relative;
      display: flex;
      align-items: center;
    }
    .pwd-wrapper input {
      padding-right: 48px;
    }
    .btn-toggle-pwd {
      position: absolute;
      right: 10px;
      background: none;
      border: none;
      color: var(--text-muted);
      cursor: pointer;
      font-size: 18px;
      padding: 4px 8px;
      line-height: 1;
      border-radius: 6px;
      transition: all 0.2s ease;
    }
    .btn-toggle-pwd:hover {
      color: var(--accent-gold);
      background: rgba(255, 255, 255, 0.08);
    }

    .btn-login {
      width: 100%;
      padding: 14px;
      background: linear-gradient(135deg, var(--accent-gold) 0%, #b45309 100%);
      color: #fff;
      border: none;
      border-radius: 8px;
      font-size: 15px;
      font-weight: 700;
      cursor: pointer;
      box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
      transition: all 0.2s ease;
      margin-top: 10px;
    }
    .btn-login:hover {
      background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(245, 158, 11, 0.4);
    }
    .alert {
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-size: 13px;
    }
    .alert-danger {
      background: rgba(239, 68, 68, 0.15);
      border: 1px solid #ef4444;
      color: #fca5a5;
    }
    .alert-success {
      background: rgba(16, 185, 129, 0.15);
      border: 1px solid #10b981;
      color: #6ee7b7;
    }
    .bottom-back-link {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      margin-top: 24px;
      font-size: 13px;
      color: var(--text-muted);
      text-decoration: none;
      transition: color 0.2s;
    }
    .bottom-back-link:hover {
      color: var(--accent-gold);
    }
  </style>
</head>
<body>
  <div class="page-container">
    <!-- Top Back Button -->
    <a href="{{ route('home') }}" class="top-back-btn">
      <span>←</span> Kembali ke Toko Utama
    </a>

    <div class="login-card">
      <div class="brand-header">
        <h1>PUSAT HAMPERS INDONESIA</h1>
        <p>Masuk ke Portal Administrator</p>
      </div>

      @if (session('error'))
        <div class="alert alert-danger">
          ⚠️ {{ session('error') }}
        </div>
      @endif

      @if (session('success'))
        <div class="alert alert-success">
          ✅ {{ session('success') }}
        </div>
      @endif

      @if ($errors->any())
        <div class="alert alert-danger">
          @foreach ($errors->all() as $error)
            <div>• {{ $error }}</div>
          @endforeach
        </div>
      @endif

      <form action="{{ route('admin.login.submit') }}" method="POST">
        @csrf
        <div class="form-group">
          <label for="email">Alamat Email Admin</label>
          <input type="email" name="email" id="email" class="form-control" value="{{ old('email', 'admin@bakery.com') }}" required autofocus placeholder="admin@bakery.com">
        </div>

        <div class="form-group">
          <label for="password">Kata Sandi</label>
          <div class="pwd-wrapper">
            <input type="password" name="password" id="password" class="form-control" required placeholder="••••••••">
            <button type="button" class="btn-toggle-pwd" id="togglePasswordBtn" title="Tampilkan Kata Sandi">
              👁️
            </button>
          </div>
        </div>

        <button type="submit" class="btn-login">Masuk ke Admin Panel</button>
      </form>

      <a href="{{ route('home') }}" class="bottom-back-link">
        <span>←</span> Kembali ke Halaman Utama Toko
      </a>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const toggleBtn = document.getElementById('togglePasswordBtn');
      const pwdInput = document.getElementById('password');

      if (toggleBtn && pwdInput) {
        toggleBtn.addEventListener('click', function () {
          const type = pwdInput.getAttribute('type') === 'password' ? 'text' : 'password';
          pwdInput.setAttribute('type', type);
          this.textContent = type === 'password' ? '👁️' : '🙈';
          this.title = type === 'password' ? 'Tampilkan Kata Sandi' : 'Sembunyikan Kata Sandi';
        });
      }
    });
  </script>
</body>
</html>
