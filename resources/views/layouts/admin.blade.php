<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Admin Dashboard') | PUSAT HAMPERS INDONESIA</title>
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
      --danger: #ef4444;
      --success: #10b981;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Outfit', sans-serif;
      background-color: var(--bg-dark);
      color: var(--text-main);
      display: flex;
      min-height: 100vh;
    }
    a { color: inherit; text-decoration: none; }

    /* Sidebar */
    .sidebar {
      width: 260px;
      background: #0f172a;
      border-right: 1px solid var(--panel-border);
      display: flex;
      flex-direction: column;
      position: fixed;
      top: 0; bottom: 0; left: 0;
      z-index: 1000;
      transition: transform 0.3s ease;
    }
    .brand {
      padding: 24px 20px;
      border-bottom: 1px solid var(--panel-border);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .brand h2 {
      font-family: 'Playfair Display', serif;
      font-size: 20px;
      letter-spacing: 2px;
      color: var(--accent-gold);
    }
    .brand p {
      font-size: 11px;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    .nav-links {
      padding: 16px 12px;
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 4px;
      overflow-y: auto;
      min-height: 0;
    }
    .nav-link {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 16px;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 500;
      color: var(--text-muted);
      transition: all 0.2s;
    }
    .nav-link:hover, .nav-link.active {
      background: var(--panel-bg);
      color: var(--accent-gold);
    }
    .sidebar-footer {
      padding: 16px;
      border-top: 1px solid var(--panel-border);
    }

    /* Main Container */
    .main-wrapper {
      margin-left: 260px;
      width: calc(100% - 260px);
      max-width: calc(100vw - 260px);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      flex: 1;
      box-sizing: border-box;
      overflow-x: hidden;
    }
    .topbar {
      height: 70px;
      background: var(--panel-bg);
      border-bottom: 1px solid var(--panel-border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 32px;
    }
    .hamburger-btn {
      display: none;
      background: transparent;
      border: none;
      color: var(--text-main);
      font-size: 22px;
      cursor: pointer;
      padding: 4px 8px;
    }
    .admin-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(217, 119, 6, 0.15);
      border: 1px solid var(--accent-gold);
      color: var(--accent-gold);
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
    }
    .content {
      padding: 32px;
      flex: 1;
    }

    /* Common Components */
    .btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 18px;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      border: none;
      transition: all 0.2s;
    }
    .btn-gold {
      background: var(--accent-gold);
      color: #fff;
    }
    .btn-gold:hover {
      background: var(--accent-gold-hover);
    }
    .btn-outline {
      background: transparent;
      border: 1px solid var(--panel-border);
      color: var(--text-main);
    }
    .btn-outline:hover {
      background: var(--panel-bg);
    }
    .btn-danger {
      background: var(--danger);
      color: #fff;
    }
    .btn-danger:hover {
      opacity: 0.9;
    }

    /* Alerts */
    .alert {
      padding: 14px 18px;
      border-radius: 8px;
      margin-bottom: 24px;
      font-size: 14px;
    }
    .alert-success {
      background: rgba(16, 185, 129, 0.15);
      border: 1px solid var(--success);
      color: #6ee7b7;
    }
    .alert-danger {
      background: rgba(239, 68, 68, 0.15);
      border: 1px solid var(--danger);
      color: #fca5a5;
    }

    /* Tables */
    .panel {
      background: var(--panel-bg);
      border: 1px solid var(--panel-border);
      border-radius: 12px;
      padding: 24px;
      margin-bottom: 24px;
    }
    .table-responsive {
      width: 100%;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
    }
    table.data-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 16px;
      min-width: 600px;
    }
    table.data-table th, table.data-table td {
      padding: 14px 16px;
      text-align: left;
      border-bottom: 1px solid var(--panel-border);
      font-size: 14px;
    }
    table.data-table th {
      color: var(--text-muted);
      font-weight: 600;
      text-transform: uppercase;
      font-size: 12px;
      letter-spacing: 1px;
    }
    .img-thumb {
      width: 48px;
      height: 48px;
      object-fit: cover;
      border-radius: 6px;
      background: #334155;
    }

    /* Forms */
    .form-group {
      margin-bottom: 20px;
    }
    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-size: 14px;
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
    .form-row {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
    }
    .checkbox-group {
      display: flex;
      align-items: center;
      gap: 10px;
      cursor: pointer;
    }
    .checkbox-group input {
      width: 18px;
      height: 18px;
      accent-color: var(--accent-gold);
    }

    /* Backdrop Overlay for Mobile */
    .sidebar-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(4px);
      z-index: 900;
    }

    /* Notification Bell & Dropdown */
    .notif-bell-btn {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid var(--panel-border);
      color: var(--text-main);
      width: 40px; height: 40px;
      border-radius: 10px;
      font-size: 18px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      transition: all 0.2s ease;
    }
    .notif-bell-btn:hover {
      background: rgba(245, 158, 11, 0.15);
      border-color: var(--accent-gold);
    }
    .notif-badge {
      position: absolute;
      top: -5px; right: -5px;
      background: #ef4444;
      color: #fff;
      font-size: 10px;
      font-weight: 800;
      padding: 2px 6px;
      border-radius: 10px;
      border: 2px solid var(--panel-bg);
      animation: pulse-badge 2s infinite;
    }
    @keyframes pulse-badge {
      0% { transform: scale(1); }
      50% { transform: scale(1.15); }
      100% { transform: scale(1); }
    }
    .sidebar-badge {
      background: #ef4444;
      color: #fff;
      font-size: 10px;
      font-weight: 700;
      padding: 2px 7px;
      border-radius: 12px;
      margin-left: auto;
    }

    .notif-dropdown {
      position: absolute;
      top: 50px; right: 0;
      width: 340px;
      background: var(--panel-bg);
      border: 1px solid var(--panel-border);
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
      display: none;
      flex-direction: column;
      z-index: 1100;
      overflow: hidden;
    }
    .notif-dropdown.show {
      display: flex;
    }
    .notif-header {
      padding: 12px 16px;
      background: rgba(0, 0, 0, 0.25);
      border-bottom: 1px solid var(--panel-border);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .audio-toggle-btn {
      background: none; border: none; cursor: pointer; font-size: 16px; opacity: 0.8;
    }
    .audio-toggle-btn:hover { opacity: 1; }
    .notif-items-list {
      max-height: 320px;
      overflow-y: auto;
    }
    .notif-item {
      padding: 12px 16px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.05);
      display: flex;
      flex-direction: column;
      gap: 4px;
      transition: background 0.2s;
      text-decoration: none;
      color: inherit;
    }
    .notif-item:hover {
      background: rgba(245, 158, 11, 0.08);
    }
    .notif-item .title-row {
      display: flex; justify-content: space-between; align-items: center;
    }
    .notif-item .inv {
      font-weight: 700; color: var(--accent-gold); font-size: 13px;
    }
    .notif-item .price {
      font-weight: 700; color: #fff; font-size: 12px;
    }
    .notif-item .meta {
      font-size: 11px; color: var(--text-muted); display: flex; justify-content: space-between; margin-top: 2px;
    }
    .notif-footer {
      padding: 10px 16px;
      background: rgba(0, 0, 0, 0.15);
      text-align: center;
      border-top: 1px solid var(--panel-border);
    }

    /* Toast Notification Container */
    .order-toast-container {
      position: fixed;
      bottom: 24px;
      right: 24px;
      z-index: 9999;
      display: flex;
      flex-direction: column;
      gap: 10px;
      max-width: 380px;
      pointer-events: none;
    }
    .order-toast {
      pointer-events: auto;
      background: #0f172a;
      border: 2px solid var(--accent-gold);
      border-radius: 12px;
      padding: 14px 18px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.6);
      display: flex;
      align-items: center;
      gap: 14px;
      color: #fff;
      animation: slideInRight 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes slideInRight {
      from { transform: translateX(100%); opacity: 0; }
      to { transform: translateX(0); opacity: 1; }
    }

    /* Mobile Responsiveness */
    @media (max-width: 1024px) {
      .sidebar {
        transform: translateX(-100%);
      }
      .sidebar.open {
        transform: translateX(0);
      }
      .sidebar-overlay.open {
        display: block;
      }
      .main-wrapper {
        margin-left: 0;
        width: 100%;
        max-width: 100%;
      }
      .hamburger-btn {
        display: inline-block;
      }
      .topbar {
        padding: 0 16px;
      }
      .content {
        padding: 16px;
      }
      .panel {
        padding: 16px;
      }
    }
  </style>
</head>
<body>
  <!-- Mobile Overlay -->
  <div class="sidebar-overlay" id="sidebar-overlay"></div>
  <div id="order-toast-container" class="order-toast-container"></div>

  <!-- Sidebar -->
  <aside class="sidebar" id="sidebar">
    <div class="brand">
      <div>
        <h2>PUSAT HAMPERS INDONESIA</h2>
        <p>Admin Control Panel</p>
      </div>
      <button type="button" id="sidebar-close-btn" style="background:none; border:none; color:var(--text-muted); font-size:20px; cursor:pointer;" class="hamburger-btn">✕</button>
    </div>
    <nav class="nav-links">
      <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        📊 Dashboard
      </a>
      <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
        🥐 Kelola Produk
      </a>
      <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
        📦 Kelola Pesanan
        <span id="sidebar-notif-badge" class="sidebar-badge" style="display:none;">0</span>
      </a>
      <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
        💳 Kelola Pembayaran
      </a>
      <a href="{{ route('admin.shipping.index') }}" class="nav-link {{ request()->routeIs('admin.shipping.*') ? 'active' : '' }}">
        🚚 Kelola Ongkir & Ekspedisi
      </a>
      <a href="{{ route('admin.coupons.index') }}" class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
        🎟️ Kelola Kupon Diskon
      </a>
      <a href="{{ route('admin.reviews.index') }}" class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
        ⭐️ Kelola Ulasan Produk
        @php
          $pendingReviewCount = \App\Models\ProductReview::where('is_approved', false)->count();
        @endphp
        @if ($pendingReviewCount > 0)
          <span class="sidebar-badge" style="background:#f59e0b; color:#000;">{{ $pendingReviewCount }}</span>
        @endif
      </a>
      <a href="{{ route('admin.menu_pdf.index') }}" class="nav-link {{ request()->routeIs('admin.menu_pdf.*') ? 'active' : '' }}">
        📜 Kelola PDF Menu Toko
      </a>
      <a href="{{ route('admin.contact.index') }}" class="nav-link {{ request()->routeIs('admin.contact.*') ? 'active' : '' }}">
        💬 Pesan & Concierge
        @php
          $unreadContactCount = \App\Models\ContactMessage::where('is_read', false)->count();
        @endphp
        @if ($unreadContactCount > 0)
          <span class="sidebar-badge">{{ $unreadContactCount }}</span>
        @endif
      </a>
      <a href="{{ route('admin.profile.index') }}" class="nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
        👤 Profil & Password
      </a>
      <a href="{{ route('admin.products.create') }}" class="nav-link">
        ➕ Tambah Produk
      </a>
      <hr style="border: 0; border-top: 1px solid var(--panel-border); margin: 12px 0;">
      <a href="{{ route('home') }}" target="_blank" class="nav-link">
        🌐 Lihat Website Utama
      </a>
    </nav>
    <div class="sidebar-footer">
      <form action="{{ route('admin.logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-outline" style="width: 100%; justify-content: center;">
          🚪 Keluar (Logout)
        </button>
      </form>
    </div>
  </aside>

  <!-- Main Content -->
  <div class="main-wrapper">
    <header class="topbar">
      <div style="display: flex; align-items: center; gap: 12px;">
        <button type="button" class="hamburger-btn" id="sidebar-toggle-btn">☰</button>
        <h3 style="font-size: 18px; font-weight: 600;">@yield('page_title', 'Dashboard')</h3>
      </div>

      <div style="display: flex; align-items: center; gap: 16px;">
        <!-- Notification Dropdown Toggle -->
        <div style="position: relative;">
          <button type="button" id="notif-bell-btn" class="notif-bell-btn" title="Notifikasi Pesanan Masuk">
            🔔
            <span id="notif-badge-count" class="notif-badge" style="display: none;">0</span>
          </button>

          <!-- Notification Dropdown Panel -->
          <div id="notif-dropdown" class="notif-dropdown">
            <div class="notif-header">
              <div>
                <strong style="color: #fff; font-size: 13px;">🔔 Pesanan Masuk Terbaru</strong>
                <div id="notif-subtext" style="font-size: 11px; color: var(--text-muted);">Memuat pesanan...</div>
              </div>
              <button type="button" id="audio-toggle-btn" class="audio-toggle-btn" title="Suara Notifikasi">🔊</button>
            </div>

            <div id="notif-items-list" class="notif-items-list">
              <div style="padding: 20px; text-align: center; color: var(--text-muted); font-size: 12px;">Memuat data pesanan...</div>
            </div>

            <div class="notif-footer">
              <a href="{{ route('admin.orders.index') }}" style="color: var(--accent-gold); font-size: 12px; font-weight: 600;">Lihat Semua Pesanan (Orders) →</a>
            </div>
          </div>
        </div>

        <!-- Admin Profile Badge & Logout Button -->
        <div style="display: flex; align-items: center; gap: 10px;">
          <a href="{{ route('admin.profile.index') }}" class="admin-badge" title="Edit Profil Admin">
            @if(Auth::user() && Auth::user()->avatar)
              <img src="{{ Auth::user()->avatar_url }}" style="width: 22px; height: 22px; border-radius: 50%; object-fit: cover;">
            @else
              👤
            @endif
            <span>{{ Auth::user()->name ?? 'Admin' }}</span>
          </a>

          <form action="{{ route('admin.logout') }}" method="POST" style="margin: 0; display: inline;">
            @csrf
            <button type="submit" class="btn btn-outline" style="padding: 6px 14px; font-size: 12px; color: #f87171; border-color: rgba(239, 68, 68, 0.4); display: flex; align-items: center; gap: 6px;" title="Keluar dari Panel Admin">
              🚪 <span>Keluar</span>
            </button>
          </form>
        </div>
      </div>
    </header>

    <main class="content">
      @if (session('success'))
        <div class="alert alert-success">
          ✅ {{ session('success') }}
        </div>
      @endif

      @if (session('error'))
        <div class="alert alert-danger">
          ⚠️ {{ session('error') }}
        </div>
      @endif

      @yield('content')
    </main>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Sidebar Toggle Logic
      const toggleBtn = document.getElementById('sidebar-toggle-btn');
      const closeBtn = document.getElementById('sidebar-close-btn');
      const sidebar = document.getElementById('sidebar');
      const overlay = document.getElementById('sidebar-overlay');

      function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('open');
      }

      function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('open');
      }

      if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
      if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
      if (overlay) overlay.addEventListener('click', closeSidebar);

      // Audio & Notification Polling System
      let soundEnabled = true;
      let lastMaxId = null;

      function playOrderChime() {
        if (!soundEnabled) return;
        try {
          const AudioCtx = window.AudioContext || window.webkitAudioContext;
          if (!AudioCtx) return;
          const ctx = new AudioCtx();
          
          const now = ctx.currentTime;
          const osc1 = ctx.createOscillator();
          const gain1 = ctx.createGain();
          osc1.type = 'sine';
          osc1.frequency.setValueAtTime(587.33, now); // D5
          gain1.gain.setValueAtTime(0.15, now);
          gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.5);
          osc1.connect(gain1);
          gain1.connect(ctx.destination);
          osc1.start(now);
          osc1.stop(now + 0.5);

          const osc2 = ctx.createOscillator();
          const gain2 = ctx.createGain();
          osc2.type = 'sine';
          osc2.frequency.setValueAtTime(880, now + 0.15); // A5
          gain2.gain.setValueAtTime(0.2, now + 0.15);
          gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.8);
          osc2.connect(gain2);
          gain2.connect(ctx.destination);
          osc2.start(now + 0.15);
          osc2.stop(now + 0.8);
        } catch(e) {
          console.log('Audio chime error', e);
        }
      }

      function showToastNotification(order) {
        const container = document.getElementById('order-toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = 'order-toast';
        toast.innerHTML = `
          <div style="font-size: 24px;">🛎️</div>
          <div style="flex: 1;">
            <div style="font-size: 11px; color: var(--accent-gold); font-weight: 700; text-transform: uppercase;">PESANAN BARU MASUK!</div>
            <div style="font-weight: 700; font-size: 14px;">${order.invoice_number}</div>
            <div style="font-size: 12px; color: #a7bbb0;">${order.customer_name} • ${order.formatted_total}</div>
          </div>
          <a href="${order.show_url}" target="_blank" style="background: var(--accent-gold); color: #000; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 700; text-decoration: none;">Lihat</a>
        `;
        container.appendChild(toast);

        setTimeout(() => {
          toast.style.transition = 'all 0.4s ease';
          toast.style.opacity = '0';
          toast.style.transform = 'translateX(100%)';
          setTimeout(() => toast.remove(), 400);
        }, 6000);
      }

      function fetchNotifications() {
        fetch('{{ route("admin.orders.notifications") }}')
          .then(res => res.json())
          .then(data => {
            const notifBadge = document.getElementById('notif-badge-count');
            const sidebarBadge = document.getElementById('sidebar-notif-badge');
            const subtext = document.getElementById('notif-subtext');
            const list = document.getElementById('notif-items-list');

            // Update badges
            if (data.pending_count > 0) {
              if (notifBadge) {
                notifBadge.textContent = data.pending_count;
                notifBadge.style.display = 'inline-block';
              }
              if (sidebarBadge) {
                sidebarBadge.textContent = data.pending_count + ' Pending';
                sidebarBadge.style.display = 'inline-block';
              }
            } else {
              if (notifBadge) notifBadge.style.display = 'none';
              if (sidebarBadge) sidebarBadge.style.display = 'none';
            }

            if (subtext) {
              subtext.textContent = `${data.pending_count} pesanan pending memunculkan notifikasi`;
            }

            // Render list items
            if (list) {
              if (!data.latest_orders || data.latest_orders.length === 0) {
                list.innerHTML = `<div style="padding: 20px; text-align: center; color: var(--text-muted); font-size: 12px;">Belum ada pesanan masuk.</div>`;
              } else {
                list.innerHTML = data.latest_orders.map(order => `
                  <a href="${order.show_url}" target="_blank" class="notif-item">
                    <div class="title-row">
                      <span class="inv">${order.invoice_number}</span>
                      <span class="price">${order.formatted_total}</span>
                    </div>
                    <div style="font-size: 12px; font-weight: 600; color: #fff;">${order.customer_name} (${order.delivery_option})</div>
                    <div class="meta">
                      <span>Status: ${order.order_status.toUpperCase()}</span>
                      <span>${order.created_at_human}</span>
                    </div>
                  </a>
                `).join('');
              }
            }

            // Detect new order arrival
            if (lastMaxId !== null && data.max_id > lastMaxId) {
              playOrderChime();
              const newestOrder = data.latest_orders[0];
              if (newestOrder) {
                showToastNotification(newestOrder);
              }
            }
            lastMaxId = data.max_id;
          })
          .catch(err => console.log('Notification fetch error', err));
      }

      // Initial fetch
      fetchNotifications();
      // Poll every 10 seconds
      setInterval(fetchNotifications, 10000);

      // Toggle notification dropdown
      const bellBtn = document.getElementById('notif-bell-btn');
      const dropdown = document.getElementById('notif-dropdown');
      const audioBtn = document.getElementById('audio-toggle-btn');

      if (bellBtn && dropdown) {
        bellBtn.addEventListener('click', function (e) {
          e.stopPropagation();
          dropdown.classList.toggle('show');
        });

        document.addEventListener('click', function (e) {
          if (!dropdown.contains(e.target) && e.target !== bellBtn) {
            dropdown.classList.remove('show');
          }
        });
      }

      if (audioBtn) {
        audioBtn.addEventListener('click', function (e) {
          e.stopPropagation();
          soundEnabled = !soundEnabled;
          audioBtn.textContent = soundEnabled ? '🔊' : '🔇';
          audioBtn.title = soundEnabled ? 'Suara Notifikasi Aktif' : 'Suara Notifikasi Diheningkan';
        });
      }
    });
  </script>
</body>
</html>
