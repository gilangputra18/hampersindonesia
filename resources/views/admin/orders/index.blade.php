@extends('layouts.admin')

@section('title', 'Kelola Pesanan (Orders)')
@section('page_title', 'Kelola Pesanan (Orders)')

@section('content')
<style>
  /* Metric Header Cards */
  .stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 28px;
  }
  .stat-card {
    background: var(--panel-bg);
    border: 1px solid var(--panel-border);
    border-radius: 12px;
    padding: 18px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    text-decoration: none;
    color: inherit;
    transition: all 0.25s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
  }
  .stat-card:hover {
    border-color: var(--accent-gold);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.15);
  }
  .stat-card.active {
    border-color: var(--accent-gold);
    background: linear-gradient(135deg, #1e293b 0%, rgba(217, 119, 6, 0.15) 100%);
  }
  .stat-val {
    font-size: 26px;
    font-weight: 700;
    color: #fff;
    line-height: 1.1;
  }
  .stat-lbl {
    font-size: 11.5px;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-top: 4px;
  }
  .stat-icon {
    font-size: 26px;
    opacity: 0.8;
  }

  /* Action Bar */
  .toolbar-container {
    background: var(--panel-bg);
    border: 1px solid var(--panel-border);
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
  }
  .filter-pills {
    display: flex;
    gap: 8px;
    align-items: center;
    flex-wrap: wrap;
  }
  .filter-pill {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 12.5px;
    font-weight: 600;
    color: var(--text-muted);
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid var(--panel-border);
    transition: all 0.2s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .filter-pill:hover, .filter-pill.active {
    color: #fff;
    background: var(--accent-gold);
    border-color: var(--accent-gold);
    box-shadow: 0 4px 12px rgba(217, 119, 6, 0.3);
  }

  /* Table Customizations */
  .order-table-card {
    background: var(--panel-bg);
    border: 1px solid var(--panel-border);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
  }
  .inv-link {
    font-family: monospace;
    font-weight: 700;
    font-size: 14px;
    color: var(--accent-gold);
    text-decoration: none;
    transition: color 0.2s;
  }
  .inv-link:hover {
    color: #fef08a;
    text-decoration: underline;
  }

  .cust-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    border: 1px solid var(--panel-border);
    color: var(--accent-gold);
    font-weight: 700;
    font-size: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
  }
  .status-paid { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
  .status-unpaid { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }
  
  .status-pending { background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); }
  .status-processing { background: rgba(245, 158, 11, 0.15); color: #fde047; border: 1px solid rgba(245, 158, 11, 0.3); }
  .status-completed { background: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3); }
  .status-cancelled { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }

  .items-list-preview {
    font-size: 12px;
    color: #cbd5e1;
    max-width: 240px;
    line-height: 1.4;
  }
</style>

<!-- Stat Cards Overview Bar -->
<div class="stat-grid">
  <a href="{{ route('admin.orders.index') }}" class="stat-card {{ !request('status') ? 'active' : '' }}">
    <div>
      <div class="stat-val">{{ $counts['all'] }}</div>
      <div class="stat-lbl">Semua Pesanan</div>
    </div>
    <div class="stat-icon">📦</div>
  </a>

  <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="stat-card {{ request('status') === 'pending' ? 'active' : '' }}">
    <div>
      <div class="stat-val" style="color: {{ $counts['pending'] > 0 ? '#ef4444' : '#fff' }};">{{ $counts['pending'] }}</div>
      <div class="stat-lbl">Pesanan Masuk (Pending)</div>
    </div>
    <div class="stat-icon">🔔</div>
  </a>

  <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="stat-card {{ request('status') === 'processing' ? 'active' : '' }}">
    <div>
      <div class="stat-val" style="color: #f59e0b;">{{ $counts['processing'] }}</div>
      <div class="stat-lbl">Sedang Diproses</div>
    </div>
    <div class="stat-icon">🥖</div>
  </a>

  <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="stat-card {{ request('status') === 'completed' ? 'active' : '' }}">
    <div>
      <div class="stat-val" style="color: #10b981;">{{ $counts['completed'] }}</div>
      <div class="stat-lbl">Selesai / Diterima</div>
    </div>
    <div class="stat-icon">✅</div>
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
  <div style="background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; color: #34d399; padding: 14px 20px; border-radius: 10px; margin-bottom: 24px; font-size: 14px;">
    ✅ {{ session('success') }}
  </div>
@endif

<!-- Action Bar: Filters, Search & Export -->
<div class="toolbar-container">
  <div class="filter-pills">
    <a href="{{ route('admin.orders.index') }}" class="filter-pill {{ !request('status') ? 'active' : '' }}">
      Semua ({{ $counts['all'] }})
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="filter-pill {{ request('status') === 'pending' ? 'active' : '' }}">
      🔔 Pending ({{ $counts['pending'] }})
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="filter-pill {{ request('status') === 'processing' ? 'active' : '' }}">
      🥖 Diproses ({{ $counts['processing'] }})
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="filter-pill {{ request('status') === 'completed' ? 'active' : '' }}">
      ✅ Selesai ({{ $counts['completed'] }})
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
      <input type="text" name="search" class="form-control" placeholder="Cari invoice / nama / WA..." value="{{ request('search') }}" style="width: 220px; padding: 8px 12px; font-size: 13px;">
      <button type="submit" class="btn btn-gold" style="padding: 8px 14px; font-size: 12px;">Cari</button>
      @if(request()->hasAny(['search', 'status']))
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline" style="padding: 8px 12px; font-size: 12px;">Reset</a>
      @endif
    </form>

    <div style="display: flex; gap: 6px;">
      <a href="{{ route('admin.export.excel') }}" class="btn btn-outline" style="padding: 8px 12px; font-size: 12px; border-color: rgba(16, 185, 129, 0.4); color: #34d399;" title="Export Data Pesanan ke Excel">
        📊 Excel
      </a>
      <a href="{{ route('admin.export.pdf') }}" class="btn btn-outline" style="padding: 8px 12px; font-size: 12px; border-color: rgba(239, 68, 68, 0.4); color: #f87171;" title="Export Laporan PDF">
        📄 PDF
      </a>
    </div>
  </div>
</div>

<!-- Orders Table -->
<div class="order-table-card">
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>INVOICE & RESI</th>
          <th>AKUN PEMBELI & KONTAK</th>
          <th>PRODUK PESANAN</th>
          <th>PENGIRIMAN & EKSPEDISI</th>
          <th>METODE & HARGA</th>
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
            <td>
              <a href="{{ route('order.show', $ord->invoice_number) }}" target="_blank" class="inv-link" title="Buka faktur pesanan">
                {{ $ord->invoice_number }}
              </a>
              <div style="font-size: 11px; color: #f59e0b; font-family: monospace; font-weight: 600; margin-top: 3px;" title="Resi Pembelian / Tracking Number">
                🏷️ {{ $ord->tracking_number ?: 'Belum ada resi' }}
              </div>
              <div style="font-size: 10.5px; color: var(--text-muted); margin-top: 2px;">
                🕒 {{ $ord->created_at->diffForHumans() }}
              </div>
            </td>

            <td>
              <div style="display: flex; align-items: center; gap: 10px;">
                <div class="cust-avatar">{{ $initials }}</div>
                <div>
                  <strong style="color: #fff; font-size: 13.5px; display: block;">{{ $ord->customer_name }}</strong>
                  <div style="font-size: 11.5px; color: var(--text-muted); margin-top: 2px;">
                    <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" style="color: #34d399; text-decoration: none;" title="Chat WhatsApp Pemesan">
                      💬 {{ $ord->customer_phone }}
                    </a>
                  </div>
                  <div style="margin-top: 3px;">
                    @if($ord->user_id)
                      <span style="font-size: 10px; background: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3); padding: 1px 6px; border-radius: 10px; font-weight: 700;">
                        👤 MEMBER ({{ $ord->user ? $ord->user->email : ($ord->customer_email ?: 'Terverifikasi') }})
                      </span>
                    @else
                      <span style="font-size: 10px; background: rgba(255, 255, 255, 0.08); color: #94a3b8; border: 1px solid var(--panel-border); padding: 1px 6px; border-radius: 10px;">
                        👤 TAMU (GUEST)
                      </span>
                    @endif
                  </div>
                </div>
              </div>
            </td>

            <td>
              <div class="items-list-preview">
                @if($ord->items && $ord->items->count() > 0)
                  @foreach($ord->items->take(2) as $it)
                    <div>• {{ $it->quantity }}x {{ $it->product_name }}</div>
                  @endforeach
                  @if($ord->items->count() > 2)
                    <div style="font-size: 10.5px; color: var(--accent-gold);">+{{ $ord->items->count() - 2 }} produk lainnya...</div>
                  @endif
                @else
                  <span style="color: var(--text-muted); font-style: italic;">Item detail</span>
                @endif
              </div>
            </td>

            <td>
              <span style="background: rgba(255,255,255,0.06); border: 1px solid var(--panel-border); padding: 3px 8px; border-radius: 4px; font-size: 10.5px; font-weight: 600; color: #cbd5e1;">
                {{ strtoupper($ord->delivery_option) }}
              </span>
              <div style="font-size: 11.5px; color: #cbd5e1; font-weight: 600; margin-top: 4px;">
                🚚 {{ $ord->courier_name ?: 'Kurir Toko' }}
              </div>
              <div style="font-size: 10.5px; color: var(--text-muted); margin-top: 2px;">
                📅 {{ date('d M Y', strtotime($ord->delivery_date)) }}
              </div>
            </td>

            <td>
              <strong style="font-size: 14px; color: var(--accent-gold); display: block;">
                Rp {{ number_format($ord->total_amount, 0, ',', '.') }}
              </strong>
              <div style="font-size: 11px; color: #94a3b8; margin-top: 2px;">
                💳 {{ $ord->payment_method ?: 'Transfer Bank BCA' }}
              </div>
            </td>

            <td>
              @if ($ord->payment_status === 'paid' || $ord->payment_status === 'verified')
                <span class="status-badge status-paid">✅ LUNAS</span>
              @else
                <span class="status-badge status-unpaid">⏳ BELUM BAYAR</span>
              @endif
            </td>

            <td>
              <span class="status-badge status-{{ $ord->order_status }}">
                📌 {{ strtoupper($ord->order_status) }}
              </span>
            </td>

            <td style="text-align: right;">
              <form action="{{ route('admin.orders.updateStatus', $ord->id) }}" method="POST" style="display: flex; flex-direction: column; gap: 6px; align-items: flex-end;">
                @csrf
                <div style="display: flex; gap: 4px; flex-wrap: wrap; justify-content: flex-end;">
                  <select name="payment_status" class="form-control" style="width: auto; padding: 4px 6px; font-size: 11px; background: #0f172a;" title="Status Pembayaran">
                    <option value="unpaid" {{ $ord->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="paid" {{ $ord->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="verified" {{ $ord->payment_status == 'verified' ? 'selected' : '' }}>Verified</option>
                  </select>

                  <select name="order_status" class="form-control" style="width: auto; padding: 4px 6px; font-size: 11px; background: #0f172a;" title="Status Pesanan">
                    <option value="pending" {{ $ord->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ $ord->order_status == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ $ord->order_status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $ord->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                  </select>
                </div>

                <div style="display: flex; gap: 4px; width: 100%; justify-content: flex-end;">
                  <input type="text" name="tracking_number" class="form-control" value="{{ $ord->tracking_number }}" placeholder="Nomor Resi / Tracking..." style="padding: 4px 8px; font-size: 11px; width: 140px; background: #0f172a;" title="Edit Nomor Resi Pembelian">
                  <button type="submit" class="btn btn-gold" style="padding: 4px 10px; font-size: 11px;">Simpan</button>
                </div>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" style="text-align: center; padding: 60px 20px;">
              <div style="font-size: 40px; margin-bottom: 10px;">📭</div>
              <h4 style="font-size: 16px; font-weight: 600; color: #fff; margin-bottom: 4px;">Tidak Ada Pesanan Ditemukan</h4>
              <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">Belum ada pesanan yang sesuai dengan filter atau kata kunci pencarian Anda.</p>
              @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.orders.index') }}" class="btn btn-gold">Lihat Semua Pesanan</a>
              @endif
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($orders->hasPages())
    <div style="padding: 20px; display: flex; justify-content: center; border-top: 1px solid var(--panel-border);">
      {{ $orders->appends(request()->query())->links() }}
    </div>
  @endif
</div>
@endsection
