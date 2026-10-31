@extends('layouts.admin')

@section('title', 'Kelola Pesanan (Orders)')
@section('page_title', '📝 Kelola Seluruh Pesanan')

@section('content')
<style>
  /* Royal Luxury Color System */
  :root {
    --gold-gradient: linear-gradient(135deg, #fef08a 0%, #e6ca65 35%, #caa436 70%, #9f183c 100%);
    --burgundy-dark: #22040b;
    --burgundy-card: #320712;
    --gold-border: rgba(230, 202, 101, 0.3);
  }

  /* Metric Header Cards */
  .stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
    gap: 16px;
    margin-bottom: 26px;
  }
  .stat-card {
    background: linear-gradient(135deg, #320712 0%, #190207 100%);
    border: 1px solid var(--gold-border);
    border-radius: 14px;
    padding: 20px 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    text-decoration: none;
    color: inherit;
    transition: all 0.28s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 8px 24px rgba(0,0,0,0.35);
    position: relative;
    overflow: hidden;
  }
  .stat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: transparent;
    transition: background 0.25s;
  }
  .stat-card:hover {
    border-color: #fef08a;
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(159, 24, 60, 0.3);
  }
  .stat-card:hover::before, .stat-card.active::before {
    background: linear-gradient(90deg, #fef08a, #e6ca65);
  }
  .stat-card.active {
    border-color: #e6ca65;
    background: linear-gradient(135deg, #520e20 0%, #320712 100%);
    box-shadow: 0 8px 25px rgba(159, 24, 60, 0.35);
  }
  .stat-val {
    font-size: 28px;
    font-weight: 800;
    color: #fff;
    line-height: 1.1;
    font-family: 'Outfit', sans-serif;
    letter-spacing: -0.5px;
  }
  .stat-lbl {
    font-size: 11.5px;
    color: #fcd5df;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    margin-top: 5px;
    font-weight: 600;
  }
  .stat-icon {
    font-size: 30px;
    line-height: 1;
    filter: drop-shadow(0 2px 8px rgba(0,0,0,0.5));
  }

  /* Action Bar */
  .toolbar-container {
    background: linear-gradient(135deg, #320712 0%, #190207 100%);
    border: 1px solid var(--gold-border);
    border-radius: 14px;
    padding: 18px 22px;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    box-shadow: 0 8px 24px rgba(0,0,0,0.3);
  }
  .filter-pills {
    display: flex;
    gap: 8px;
    align-items: center;
    flex-wrap: wrap;
  }
  .filter-pill {
    padding: 8px 16px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 700;
    color: #cbd5e1;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    letter-spacing: 0.3px;
  }
  .filter-pill:hover {
    color: #fef08a;
    border-color: rgba(230, 202, 101, 0.5);
    background: rgba(159, 24, 60, 0.2);
  }
  .filter-pill.active {
    color: #22040b;
    background: linear-gradient(135deg, #fef08a 0%, #e6ca65 100%);
    border-color: #fef08a;
    box-shadow: 0 4px 14px rgba(230, 202, 101, 0.35);
    font-weight: 800;
  }

  /* Royal Table Card */
  .order-table-card {
    background: linear-gradient(180deg, #320712 0%, #190207 100%);
    border: 1px solid var(--gold-border);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 16px 40px rgba(0,0,0,0.45);
  }
  .table-orders-royal {
    width: 100%;
    border-collapse: collapse;
    margin: 0;
    font-family: 'Outfit', sans-serif;
  }
  .table-orders-royal thead th {
    background: #190207;
    padding: 16px 18px;
    font-size: 11px;
    font-weight: 800;
    color: #e6ca65;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    border-bottom: 2px solid rgba(230, 202, 101, 0.35);
    white-space: nowrap;
  }
  .table-orders-royal tbody tr {
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    transition: all 0.2s ease;
  }
  .table-orders-royal tbody tr:hover {
    background: rgba(217, 119, 6, 0.04);
  }
  .table-orders-royal tbody td {
    padding: 18px;
    vertical-align: middle;
    font-size: 13px;
    color: #e2e8f0;
  }

  /* Invoice Badge */
  .inv-badge-box {
    display: inline-block;
  }
  .inv-link {
    font-family: monospace;
    font-weight: 800;
    font-size: 13.5px;
    color: #fbbf24;
    text-decoration: none;
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: color 0.15s;
  }
  .inv-link:hover {
    color: #fef08a;
    text-decoration: underline;
  }
  .tracking-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    color: #e2e8f0;
    font-family: monospace;
    font-weight: 600;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.12);
    padding: 2px 7px;
    border-radius: 5px;
    margin-top: 5px;
    letter-spacing: 0.5px;
  }

  /* Customer Avatar & Royal Monogram */
  .cust-avatar-royal {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: radial-gradient(circle, #520e20 0%, #190207 100%);
    border: 1.5px solid #e6ca65;
    color: #fef08a;
    font-weight: 800;
    font-size: 13.5px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 0 10px rgba(230, 202, 101, 0.25);
    letter-spacing: 0.5px;
  }
  .role-chip-member {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 10px;
    font-weight: 800;
    color: #34d399;
    background: rgba(16, 185, 129, 0.12);
    border: 1px solid rgba(16, 185, 129, 0.35);
    padding: 2px 8px;
    border-radius: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .role-chip-guest {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 10px;
    font-weight: 700;
    color: #94a3b8;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.12);
    padding: 2px 8px;
    border-radius: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .btn-wa-chip {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    color: #4ade80;
    text-decoration: none;
    font-size: 11.5px;
    font-weight: 600;
    background: rgba(37, 211, 102, 0.1);
    border: 1px solid rgba(37, 211, 102, 0.25);
    padding: 2px 8px;
    border-radius: 6px;
    margin-top: 4px;
    transition: background 0.15s;
  }
  .btn-wa-chip:hover {
    background: rgba(37, 211, 102, 0.25);
    color: #86efac;
  }

  /* Royal Status Badges */
  .badge-royal {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    white-space: nowrap;
  }
  .badge-paid {
    background: rgba(16, 185, 129, 0.16);
    color: #34d399;
    border: 1px solid #10b981;
    box-shadow: 0 0 10px rgba(16, 185, 129, 0.2);
  }
  .badge-unpaid {
    background: rgba(239, 68, 68, 0.15);
    color: #fca5a5;
    border: 1px solid rgba(239, 68, 68, 0.4);
  }
  .badge-waiting-proof {
    background: rgba(245, 158, 11, 0.18);
    color: #fbbf24;
    border: 1px solid #f59e0b;
    box-shadow: 0 0 12px rgba(245, 158, 11, 0.25);
    animation: pulseGlow 2s infinite ease-in-out;
  }
  @keyframes pulseGlow {
    0%, 100% { box-shadow: 0 0 8px rgba(245, 158, 11, 0.2); }
    50% { box-shadow: 0 0 16px rgba(245, 158, 11, 0.45); }
  }

  .badge-pending    { background: rgba(59, 130, 246, 0.15); color: #93c5fd; border: 1px solid rgba(59, 130, 246, 0.4); }
  .badge-processing { background: rgba(245, 158, 11, 0.15); color: #fde047; border: 1px solid rgba(245, 158, 11, 0.4); }
  .badge-completed  { background: rgba(16, 185, 129, 0.18); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.4); }
  .badge-cancelled  { background: rgba(239, 68, 68, 0.12); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }

  /* Price & Payment */
  .price-highlight {
    font-size: 15.5px;
    font-weight: 800;
    color: #fef08a;
    font-family: 'Outfit', sans-serif;
    letter-spacing: -0.3px;
    display: block;
  }
  .pay-method-chip {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    color: #94a3b8;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    padding: 2px 7px;
    border-radius: 5px;
    margin-top: 4px;
  }

  /* Control Box on the Right */
  .control-box-royal {
    background: rgba(0, 0, 0, 0.25);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 10px;
    padding: 10px 12px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-width: 210px;
  }
  .select-royal {
    background: #190207;
    border: 1px solid rgba(230, 202, 101, 0.3);
    color: #e2e8f0;
    border-radius: 6px;
    padding: 5px 8px;
    font-size: 11.5px;
    font-family: inherit;
    font-weight: 600;
    outline: none;
    transition: border-color 0.2s;
  }
  .select-royal:focus {
    border-color: #fef08a;
  }
  .input-resi-royal {
    background: #190207;
    border: 1px solid rgba(230, 202, 101, 0.3);
    color: #fff;
    border-radius: 6px;
    padding: 5px 8px;
    font-size: 11.5px;
    font-family: monospace;
    flex: 1;
    outline: none;
  }
  .input-resi-royal:focus {
    border-color: #fef08a;
  }
  .btn-save-royal {
    padding: 5px 12px;
    background: linear-gradient(135deg, #9f183c 0%, #780d26 100%);
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 11.5px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 2px 8px rgba(159, 24, 60, 0.35);
    white-space: nowrap;
  }
  .btn-save-royal:hover {
    background: linear-gradient(135deg, #b81f47 0%, #901131 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(159, 24, 60, 0.5);
  }

  /* Mobile Responsiveness for Admin Orders Overview */
  @media (max-width: 768px) {
    .stat-grid {
      grid-template-columns: repeat(2, 1fr) !important;
      gap: 10px !important;
      margin-bottom: 18px !important;
    }
    .stat-card {
      padding: 12px 14px !important;
      border-radius: 10px !important;
    }
    .stat-val {
      font-size: 20px !important;
    }
    .stat-lbl {
      font-size: 9.5px !important;
      letter-spacing: 0.5px !important;
      margin-top: 3px !important;
    }
    .stat-icon {
      font-size: 22px !important;
    }
    .toolbar-container {
      padding: 12px 14px !important;
      gap: 12px !important;
    }
    .filter-pills {
      gap: 6px !important;
    }
    .filter-pill {
      padding: 6px 12px !important;
      font-size: 11px !important;
    }
  }
</style>

<!-- Stat Cards Overview Bar -->
<div class="stat-grid">
  <a href="{{ route('admin.orders.index') }}" class="stat-card {{ !request('status') ? 'active' : '' }}">
    <div>
      <div class="stat-val">{{ $counts['all'] }}</div>
      <div class="stat-lbl">Semua Pesanan</div>
    </div>
    <div class="stat-icon">📋</div>
  </a>

  <a href="{{ route('admin.orders.verification') }}" class="stat-card" style="border-color: rgba(230, 202, 101, 0.5); background: linear-gradient(135deg, #420a17 0%, #22040b 100%);">
    <div>
      <div class="stat-val" style="color: #fbbf24;">{{ $counts['verification'] ?? 0 }}</div>
      <div class="stat-lbl">Antrian Verifikasi Bukti</div>
    </div>
    <div class="stat-icon">⏳</div>
  </a>

  <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="stat-card {{ request('status') === 'pending' ? 'active' : '' }}">
    <div>
      <div class="stat-val" style="color: {{ $counts['pending'] > 0 ? '#fbbf24' : '#fff' }};">{{ $counts['pending'] }}</div>
      <div class="stat-lbl">Pesanan Baru (Pending)</div>
    </div>
    <div class="stat-icon">🔔</div>
  </a>

  <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="stat-card {{ request('status') === 'processing' ? 'active' : '' }}">
    <div>
      <div class="stat-val" style="color: #fde047;">{{ $counts['processing'] }}</div>
      <div class="stat-lbl">Sedang Diproses</div>
    </div>
    <div class="stat-icon">🥖</div>
  </a>

  <a href="{{ route('admin.orders.index', ['status' => 'shipping']) }}" class="stat-card {{ request('status') === 'shipping' ? 'active' : '' }}" style="border-color: rgba(56, 189, 248, 0.4);">
    <div>
      <div class="stat-val" style="color: #38bdf8;">{{ $counts['shipping'] ?? 0 }}</div>
      <div class="stat-lbl">Dibawa Kurir (Shipping)</div>
    </div>
    <div class="stat-icon">🚚</div>
  </a>

  <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="stat-card {{ request('status') === 'completed' ? 'active' : '' }}">
    <div>
      <div class="stat-val" style="color: #34d399;">{{ $counts['completed'] }}</div>
      <div class="stat-lbl">Selesai & Diterima</div>
    </div>
    <div class="stat-icon">✨</div>
  </a>

  <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="stat-card {{ request('status') === 'cancelled' ? 'active' : '' }}">
    <div>
      <div class="stat-val" style="color: #94a3b8;">{{ $counts['cancelled'] }}</div>
      <div class="stat-lbl">Dibatalkan</div>
    </div>
    <div class="stat-icon">❌</div>
  </a>
</div>

@if (session('success'))
  <div style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(5, 150, 105, 0.15) 100%); border: 1px solid #10b981; color: #34d399; padding: 14px 20px; border-radius: 12px; margin-bottom: 24px; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
    <span>✅</span> <span>{{ session('success') }}</span>
  </div>
@endif

<!-- Action Bar: Filters, Search & Export -->
<div class="toolbar-container">
  <div class="filter-pills">
    <a href="{{ route('admin.orders.index') }}" class="filter-pill {{ !request('status') ? 'active' : '' }}">
      Semua ({{ $counts['all'] }})
    </a>
    <a href="{{ route('admin.orders.verification') }}" class="filter-pill" style="border-color: rgba(245, 158, 11, 0.5); color: #fbbf24;">
      ⏳ Antrian Verifikasi ({{ $counts['verification'] ?? 0 }})
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="filter-pill {{ request('status') === 'pending' ? 'active' : '' }}">
      🔔 Pending ({{ $counts['pending'] }})
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="filter-pill {{ request('status') === 'processing' ? 'active' : '' }}">
      🥖 Diproses ({{ $counts['processing'] }})
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'shipping']) }}" class="filter-pill {{ request('status') === 'shipping' ? 'active' : '' }}" style="color: #38bdf8; border-color: rgba(56, 189, 248, 0.4);">
      🚚 Dikirim Kurir ({{ $counts['shipping'] ?? 0 }})
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="filter-pill {{ request('status') === 'completed' ? 'active' : '' }}">
      ✨ Selesai ({{ $counts['completed'] }})
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="filter-pill {{ request('status') === 'cancelled' ? 'active' : '' }}">
      ❌ Dibatalkan ({{ $counts['cancelled'] }})
    </a>
  </div>

  <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
    <form action="{{ route('admin.orders.index') }}" method="GET" style="display: flex; gap: 8px;">
      @if(request('status'))
        <input type="hidden" name="status" value="{{ request('status') }}">
      @endif
      <input type="text" name="search" class="form-control" placeholder="Cari invoice / nama / WA..." value="{{ request('search') }}" style="width: 220px; padding: 9px 14px; font-size: 12.5px; border-radius: 8px;">
      <button type="submit" class="btn btn-gold" style="padding: 9px 16px; font-size: 12.5px; border-radius: 8px;">Cari</button>
      @if(request()->hasAny(['search', 'status']))
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline" style="padding: 9px 14px; font-size: 12.5px; border-radius: 8px;">Reset</a>
      @endif
    </form>

    <div style="display: flex; gap: 6px;">
      <a href="{{ route('admin.export.excel') }}" class="btn btn-outline" style="padding: 8px 14px; font-size: 12px; border-color: rgba(16, 185, 129, 0.4); color: #34d399; border-radius: 8px;" title="Export Data Pesanan ke Excel">
        📊 Excel
      </a>
      <a href="{{ route('admin.export.pdf') }}" class="btn btn-outline" style="padding: 8px 14px; font-size: 12px; border-color: rgba(239, 68, 68, 0.4); color: #f87171; border-radius: 8px;" title="Export Laporan PDF">
        📄 PDF
      </a>
    </div>
  </div>
</div>

<!-- Orders Table -->
<div class="order-table-card">
  <div class="table-responsive">
    <table class="table-orders-royal">
      <thead>
        <tr>
          <th>INVOICE & RESI</th>
          <th>AKUN PEMBELI & KONTAK</th>
          <th>PRODUK PESANAN</th>
          <th>PENGIRIMAN & EKSPEDISI</th>
          <th>METODE & TOTAL</th>
          <th>STATUS BAYAR</th>
          <th>STATUS PESANAN</th>
          <th style="text-align: right; min-width: 220px;">UPDATE STATUS & RESI</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($orders as $ord)
          @php
            $words = explode(' ', trim($ord->customer_name));
            $initials = count($words) >= 2 
              ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1)) 
              : strtoupper(substr($ord->customer_name, 0, 2));
            
            $cleanPhone = preg_replace('/[^0-9]/', '', $ord->customer_phone);
            if (str_starts_with($cleanPhone, '0')) {
                $cleanPhone = '62' . substr($cleanPhone, 1);
            }
          @endphp
          <tr>
            <!-- Col 1: Invoice & Resi -->
            <td>
              <div class="inv-badge-box">
                <a href="{{ route('order.show', $ord->invoice_number) }}" target="_blank" class="inv-link" title="Buka faktur resmi pesanan">
                  <span>🧾</span> {{ $ord->invoice_number }}
                </a>
              </div>
              <div>
                @if($ord->tracking_number)
                  <div class="tracking-pill" style="color: #fbbf24; border-color: rgba(245, 158, 11, 0.3);">
                    🏷️ {{ $ord->tracking_number }}
                  </div>
                @else
                  <div class="tracking-pill" style="color: #64748b;">
                    🏷️ Resi Belum Terbit
                  </div>
                @endif
              </div>
              <div style="font-size: 11px; color: #64748b; margin-top: 4px;">
                🕒 {{ $ord->created_at->diffForHumans() }}
              </div>
            </td>

            <!-- Col 2: Akun Pembeli & Kontak -->
            <td>
              <div style="display: flex; align-items: flex-start; gap: 12px;">
                <div class="cust-avatar-royal">{{ $initials }}</div>
                <div>
                  <strong style="color: #fff; font-size: 14px; display: block; font-weight: 700;">{{ $ord->customer_name }}</strong>
                  
                  <div style="margin-top: 3px;">
                    <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode('Halo ' . $ord->customer_name . ', terkait pesanan ' . $ord->invoice_number . ' di Pusat Hampers Indonesia...') }}" target="_blank" class="btn-wa-chip" title="Chat WhatsApp Pemesan">
                      💬 {{ $ord->customer_phone }}
                    </a>
                  </div>

                  <div style="margin-top: 5px;">
                    @if($ord->user_id)
                      <span class="role-chip-member">
                        👑 VIP MEMBER
                      </span>
                    @else
                      <span class="role-chip-guest">
                        ✨ TAMU
                      </span>
                    @endif
                    @if($ord->customer_email)
                      <span style="font-size: 11px; color: #64748b; margin-left: 4px;">{{ $ord->customer_email }}</span>
                    @endif
                  </div>
                </div>
              </div>
            </td>

            <!-- Col 3: Produk Pesanan -->
            <td>
              <div style="max-width: 250px;">
                @if($ord->items && $ord->items->count() > 0)
                  <div style="display: flex; flex-direction: column; gap: 4px;">
                    @foreach($ord->items->take(2) as $it)
                      <div style="font-size: 12.5px; color: #e2e8f0; line-height: 1.35;">
                        <strong style="color: #fef08a;">{{ $it->quantity }}x</strong> {{ $it->product_name }}
                      </div>
                    @endforeach
                    @if($ord->items->count() > 2)
                      <a href="{{ route('order.show', $ord->invoice_number) }}" target="_blank" style="font-size: 11px; color: #f59e0b; text-decoration: underline; font-weight: 600; margin-top: 2px;">
                        +{{ $ord->items->count() - 2 }} produk hampers lainnya...
                      </a>
                    @endif
                  </div>
                @else
                  <span style="color: #64748b; font-style: italic; font-size: 12px;">Item detail tidak tersedia</span>
                @endif
              </div>
            </td>

            <!-- Col 4: Pengiriman & Ekspedisi -->
            <td>
              <span style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); padding: 3px 8px; border-radius: 4px; font-size: 10px; font-weight: 800; color: #94a3b8; letter-spacing: 0.5px;">
                {{ strtoupper($ord->delivery_option) }}
              </span>
              <div style="font-size: 12px; color: #e2e8f0; font-weight: 600; margin-top: 4px;">
                🚚 {{ $ord->courier_name ?: 'Kurir Toko' }}
              </div>
              <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                📅 {{ date('d M Y', strtotime($ord->delivery_date)) }}
              </div>
              @if($ord->latitude && $ord->longitude)
                <div style="margin-top: 5px;">
                  <a href="https://www.google.com/maps?q={{ $ord->latitude }},{{ $ord->longitude }}" target="_blank" style="background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.4); color: #86efac; font-size: 10.5px; padding: 2px 7px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px; text-decoration: none; font-weight: 700;" title="Buka Koordinat Maps untuk Kurir: {{ $ord->latitude }}, {{ $ord->longitude }}">
                    <span>📍</span> <span>Maps ({{ round($ord->latitude, 4) }}, {{ round($ord->longitude, 4) }})</span>
                  </a>
                </div>
              @endif
              @if($ord->order_note)
                <div style="margin-top: 6px;">
                  <button type="button" onclick="alert('DETAIL KARTU UCAPAN / CATATAN PESANAN:\n\n' + {{ json_encode($ord->order_note) }})" style="background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.4); color: #fbbf24; font-size: 10.5px; padding: 2px 7px; border-radius: 4px; cursor: pointer; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                    <span>🎁</span> <span>Lihat Ucapan</span>
                  </button>
                </div>
              @endif
            </td>

            <!-- Col 5: Metode & Total Harga -->
            <td>
              <span class="price-highlight">
                Rp {{ number_format($ord->total_amount, 0, ',', '.') }}
              </span>
              <div class="pay-method-chip">
                💳 {{ $ord->payment_method ?: 'Transfer Bank BCA' }}
              </div>
            </td>

            <!-- Col 6: Status Bayar & Bukti -->
            <td>
              @if ($ord->payment_status === 'paid' || $ord->payment_status === 'verified')
                <span class="badge-royal badge-paid">🟢 LUNAS</span>
              @elseif ($ord->payment_status === 'waiting_verification')
                <span class="badge-royal badge-waiting-proof">⏳ VERIFIKASI BUKTI</span>
              @else
                <span class="badge-royal badge-unpaid">⚠️ BELUM BAYAR</span>
              @endif

              @if ($ord->payment_proof)
                <div style="margin-top: 6px;">
                  <a href="{{ $ord->payment_proof_url }}" target="_blank" style="display: inline-flex; align-items: center; gap: 4px; font-size: 11.5px; color: #fbbf24; text-decoration: underline; font-weight: 700;">
                    <span>📸</span> <span>Lihat Bukti Transfer</span>
                  </a>
                </div>
              @endif
            </td>

            <!-- Col 7: Status Pesanan -->
            <td>
              @if ($ord->order_status === 'pending')
                <span class="badge-royal badge-pending">🔔 PENDING</span>
              @elseif ($ord->order_status === 'processing')
                <span class="badge-royal badge-processing">🥖 DIPROSES</span>
              @elseif ($ord->order_status === 'shipping')
                <span class="badge-royal" style="background: rgba(14, 165, 233, 0.2); border: 1px solid #38bdf8; color: #38bdf8; font-weight: 800;">🚚 DIKIRIM KURIR</span>
              @elseif ($ord->order_status === 'completed')
                <span class="badge-royal badge-completed">✨ SELESAI</span>
              @else
                <span class="badge-royal badge-cancelled">❌ DIBATALKAN</span>
              @endif
            </td>

            <!-- Col 8: Update Status & Resi -->
            <td style="text-align: right;">
              <form action="{{ route('admin.orders.updateStatus', $ord->id) }}" method="POST" class="control-box-royal">
                @csrf
                <div style="display: flex; gap: 4px; justify-content: space-between;">
                  <select name="payment_status" class="select-royal" style="flex: 1;" title="Ubah Status Pembayaran">
                    <option value="unpaid" {{ $ord->payment_status == 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                    <option value="waiting_verification" {{ $ord->payment_status == 'waiting_verification' ? 'selected' : '' }}>Antrian Verifikasi</option>
                    <option value="paid" {{ $ord->payment_status == 'paid' ? 'selected' : '' }}>Lunas</option>
                    <option value="verified" {{ $ord->payment_status == 'verified' ? 'selected' : '' }}>Verified</option>
                  </select>

                  <select name="order_status" class="select-royal" style="flex: 1;" title="Ubah Status Pesanan">
                    <option value="pending" {{ $ord->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ $ord->order_status == 'processing' ? 'selected' : '' }}>Diproses</option>
                    <option value="shipping" {{ $ord->order_status == 'shipping' ? 'selected' : '' }}>🚚 Dikirim Kurir</option>
                    <option value="completed" {{ $ord->order_status == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ $ord->order_status == 'cancelled' ? 'selected' : '' }}>Batal</option>
                  </select>
                </div>

                <div style="display: flex; gap: 4px;">
                  <input type="text" name="tracking_number" class="input-resi-royal" value="{{ $ord->tracking_number }}" placeholder="Nomor Resi / Kurir..." title="Nomor Resi Pembelian">
                  <button type="submit" class="btn-save-royal">Simpan</button>
                </div>
              </form>

              <!-- Aksi Pengiriman & Dokumen Ekspedisi -->
              <div style="display: flex; gap: 4px; margin-top: 6px; justify-content: flex-end; align-items: center; flex-wrap: wrap;">
                @if($ord->order_status !== 'shipping' && $ord->order_status !== 'completed' && $ord->order_status !== 'cancelled')
                  <form action="{{ route('admin.orders.markShipped', $ord->id) }}" method="POST" style="margin: 0; display: inline;">
                    @csrf
                    <button type="submit" style="display: inline-flex; align-items: center; gap: 3px; padding: 4px 8px; font-size: 10.5px; font-weight: 700; background: #0284c7; color: #fff; border: 1px solid #38bdf8; border-radius: 5px; cursor: pointer;" title="Ubah status menjadi Sedang Dibawa Kurir Ekspedisi">
                      🚚 Kirim Kurir
                    </button>
                  </form>
                @endif

                <a href="{{ route('admin.orders.shippingLabel', $ord->id) }}" target="_blank" style="display: inline-flex; align-items: center; gap: 3px; padding: 4px 8px; font-size: 10.5px; font-weight: 700; background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid #d97706; text-decoration: none; border-radius: 5px;" title="Cetak Label Pengiriman Ekspedisi">
                  🏷️ Label
                </a>

                <a href="{{ route('order.invoice.pdf', $ord->invoice_number) }}" target="_blank" style="display: inline-flex; align-items: center; gap: 3px; padding: 4px 8px; font-size: 10.5px; font-weight: 700; background: rgba(255, 255, 255, 0.08); color: #cbd5e1; text-decoration: none; border-radius: 5px; border: 1px solid rgba(255, 255, 255, 0.15);" title="Cetak Faktur PDF">
                  🖨️ Faktur
                </a>

                @if($ord->tracking_number)
                  @php
                    $shipMsg = "Halo Kak {$ord->customer_name}, pesanan hampers #{$ord->invoice_number} via {$ord->courier_name} telah diserahkan ke kurir dengan no resi {$ord->tracking_number}. Lacak di: " . route('track', ['invoice' => $ord->invoice_number]);
                  @endphp
                  <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode($shipMsg) }}" target="_blank" style="display: inline-flex; align-items: center; gap: 3px; padding: 4px 8px; font-size: 10.5px; font-weight: 700; background: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid #22c55e; border-radius: 5px; text-decoration: none;" title="Kirim info resi ke WhatsApp Pelanggan">
                    💬 WA Resi
                  </a>
                @endif

                <form action="{{ route('admin.orders.destroy', $ord->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan #{{ $ord->invoice_number }} dari database?');" style="margin: 0; display: inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" style="display: inline-flex; align-items: center; gap: 3px; padding: 4px 7px; font-size: 10.5px; font-weight: 700; background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid #ef4444; border-radius: 5px; cursor: pointer;" title="Hapus pesanan ini">
                    🗑️
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" style="text-align: center; padding: 70px 20px;">
              <div style="font-size: 46px; margin-bottom: 12px;">⚜️</div>
              <h4 style="font-size: 18px; font-weight: 700; color: #fff; margin-bottom: 6px;">Tidak Ada Pesanan Ditemukan</h4>
              <p style="font-size: 13px; color: #94a3b8; margin-bottom: 18px;">Belum ada pesanan yang sesuai dengan filter atau kata kunci pencarian Anda.</p>
              @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.orders.index') }}" class="btn btn-gold" style="border-radius: 8px;">Lihat Semua Pesanan</a>
              @endif
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($orders->hasPages())
    <div class="pagination-wrapper">
      {{ $orders->appends(request()->query())->links() }}
    </div>
  @endif
</div>
@endsection
