@extends('layouts.admin')

@section('title', 'Dashboard Analitik & Penjualan')
@section('page_title', 'Dashboard Analitik & Penjualan')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
  /* Header Toolbar Filter & Export */
  .filter-toolbar {
    background: var(--panel-bg);
    border: 1px solid var(--panel-border);
    border-radius: 12px;
    padding: 18px 24px;
    margin-bottom: 28px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
  }
  .filter-group {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
  }
  .filter-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-muted);
    margin-right: 4px;
  }
  .btn-filter {
    padding: 7px 15px;
    border-radius: 20px;
    font-size: 12.5px;
    font-weight: 600;
    text-decoration: none;
    color: var(--text-muted);
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid var(--panel-border);
    transition: all 0.2s ease;
  }
  .btn-filter:hover, .btn-filter.active {
    background: var(--accent-gold);
    color: #0d1713;
    border-color: var(--accent-gold);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);
  }
  .custom-date-form {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
  }
  .date-input {
    background: rgba(15, 23, 42, 0.8);
    border: 1px solid var(--panel-border);
    color: #fff;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    outline: none;
  }
  .date-input:focus {
    border-color: var(--accent-gold);
  }
  .btn-submit-filter {
    background: rgba(255, 255, 255, 0.08);
    color: #fff;
    border: 1px solid var(--panel-border);
    padding: 6px 14px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
  }
  .btn-submit-filter:hover {
    background: var(--accent-gold);
    color: #0d1713;
    border-color: var(--accent-gold);
  }
  .export-group {
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .btn-export {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s ease;
  }
  .btn-excel {
    background: rgba(16, 185, 129, 0.15);
    color: #34d399;
    border: 1px solid rgba(16, 185, 129, 0.4);
  }
  .btn-excel:hover {
    background: #10b981;
    color: #fff;
  }
  .btn-pdf {
    background: rgba(239, 68, 68, 0.15);
    color: #f87171;
    border: 1px solid rgba(239, 68, 68, 0.4);
  }
  .btn-pdf:hover {
    background: #ef4444;
    color: #fff;
  }

  /* Metric Stat Cards Grid */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
  }
  .stat-card {
    background: var(--panel-bg);
    border: 1px solid var(--panel-border);
    border-radius: 12px;
    padding: 22px;
    position: relative;
    overflow: hidden;
    transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
  }
  .stat-card:hover {
    transform: translateY(-3px);
    border-color: var(--accent-gold);
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.15);
  }
  .stat-card .icon-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: rgba(245, 158, 11, 0.12);
    border: 1px solid rgba(245, 158, 11, 0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
  }
  .stat-card .label {
    font-size: 11.5px;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 1.2px;
    margin-bottom: 8px;
    font-weight: 600;
  }
  .stat-card .value {
    font-size: 26px;
    font-weight: 700;
    color: var(--accent-gold);
    line-height: 1.2;
  }
  .stat-card .subtext {
    font-size: 12px;
    color: #94a3b8;
    margin-top: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .growth-pill {
    display: inline-flex;
    align-items: center;
    padding: 2px 8px;
    background: rgba(16, 185, 129, 0.15);
    color: #34d399;
    border: 1px solid rgba(16, 185, 129, 0.3);
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
  }

  /* Analytics Charts Section */
  .charts-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
    margin-bottom: 32px;
  }
  @media (max-width: 1024px) {
    .charts-grid {
      grid-template-columns: 1fr;
    }
  }

  .chart-panel {
    background: var(--panel-bg);
    border: 1px solid var(--panel-border);
    border-radius: 12px;
    padding: 24px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
  }
  .chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    padding-bottom: 12px;
  }
  .chart-title {
    font-size: 15px;
    font-weight: 600;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  /* Top Selling & Recent Transactions Grid */
  .tables-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-bottom: 32px;
  }
  @media (max-width: 1024px) {
    .tables-grid {
      grid-template-columns: 1fr;
    }
  }

  .status-badge-sm {
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    display: inline-block;
  }
  .status-paid, .status-completed {
    background: rgba(16, 185, 129, 0.15);
    color: #34d399;
    border: 1px solid rgba(16, 185, 129, 0.3);
  }
  .status-pending, .status-processing {
    background: rgba(245, 158, 11, 0.15);
    color: #fde047;
    border: 1px solid rgba(245, 158, 11, 0.3);
  }
  .status-unpaid, .status-cancelled {
    background: rgba(239, 68, 68, 0.15);
    color: #f87171;
    border: 1px solid rgba(239, 68, 68, 0.3);
  }
</style>

<!-- 0. Filter & Export Toolbar -->
<div class="filter-toolbar">
  <div class="filter-group">
    <span class="filter-label">📅 Filter Periode:</span>
    <a href="{{ route('admin.dashboard', array_merge(request()->except('period', 'page'), ['period' => 'today'])) }}" class="btn-filter {{ $period == 'today' ? 'active' : '' }}">Hari Ini</a>
    <a href="{{ route('admin.dashboard', array_merge(request()->except('period', 'page'), ['period' => 'this_month'])) }}" class="btn-filter {{ $period == 'this_month' ? 'active' : '' }}">Bulan Ini</a>
    <a href="{{ route('admin.dashboard', array_merge(request()->except('period', 'page'), ['period' => 'this_year'])) }}" class="btn-filter {{ $period == 'this_year' ? 'active' : '' }}">Tahun Ini</a>
    <a href="{{ route('admin.dashboard', array_merge(request()->except('period', 'page'), ['period' => '30days'])) }}" class="btn-filter {{ $period == '30days' ? 'active' : '' }}">30 Hari Terakhir</a>
    <a href="{{ route('admin.dashboard', array_merge(request()->except('period', 'page'), ['period' => 'all_time'])) }}" class="btn-filter {{ $period == 'all_time' ? 'active' : '' }}">Semua Waktu</a>
  </div>

  <form action="{{ route('admin.dashboard') }}" method="GET" class="custom-date-form">
    <input type="hidden" name="period" value="custom">
    <input type="date" name="start_date" value="{{ request('start_date') }}" class="date-input" required>
    <span style="color: var(--text-muted); font-size: 12px;">s/d</span>
    <input type="date" name="end_date" value="{{ request('end_date') }}" class="date-input" required>
    <button type="submit" class="btn-submit-filter">Filter Custom</button>
  </form>

  <div class="export-group">
    <a href="{{ route('admin.export.excel', request()->all()) }}" class="btn-export btn-excel" title="Download Excel (.csv)">
      📊 Export Excel
    </a>
    <a href="{{ route('admin.export.pdf', request()->all()) }}" target="_blank" class="btn-export btn-pdf" title="Cetak Laporan PDF">
      📄 Export PDF
    </a>
  </div>
</div>

<!-- 1. Top Sales Metric Cards -->
<div class="stats-grid">
  <!-- Total Revenue Card -->
  <div class="stat-card">
    <div class="icon-badge">💰</div>
    <div class="label">Total Omset Penjualan</div>
    <div class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
    <div class="subtext">
      <span class="growth-pill">↑ Omset Kotor</span>
    </div>
  </div>

  <!-- Net Profit Card (Laba Keuntungan Bersih) -->
  <div class="stat-card">
    <div class="icon-badge" style="background: rgba(16, 185, 129, 0.15); border-color: rgba(16, 185, 129, 0.3);">📈</div>
    <div class="label">Estimasi Laba Bersih</div>
    <div class="value" style="color: #34d399;">Rp {{ number_format($netProfit, 0, ',', '.') }}</div>
    <div class="subtext">
      <span class="growth-pill">Margin {{ $profitMargin }}% Net Profit</span>
    </div>
  </div>

  <!-- Total Orders Card -->
  <div class="stat-card">
    <div class="icon-badge">📦</div>
    <div class="label">Total Transaksi Masuk</div>
    <div class="value">{{ $totalOrders }}</div>
    <div class="subtext">
      <span>{{ $paidOrdersCount }} pesanan lunas terverifikasi</span>
    </div>
  </div>

  <!-- Average Order Value (AOV) -->
  <div class="stat-card">
    <div class="icon-badge">📊</div>
    <div class="label">Rata-Rata Order (AOV)</div>
    <div class="value">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</div>
    <div class="subtext">
      <span>Nilai transaksi per pelanggan</span>
    </div>
  </div>

  <!-- Catalog Size Card -->
  <div class="stat-card">
    <div class="icon-badge">🎂</div>
    <div class="label">Katalog & Best Sellers</div>
    <div class="value">{{ $totalProducts }} <span style="font-size: 15px; color: var(--text-muted);">Produk</span></div>
    <div class="subtext">
      <span>{{ $totalCategories }} Kategori • {{ $totalBestSellers }} Best Sellers</span>
    </div>
  </div>
</div>

<!-- 2. Interactive Charts Section -->
<div class="charts-grid">
  <!-- Chart 1: 30-Day Revenue Trend -->
  <div class="chart-panel">
    <div class="chart-header">
      <div class="chart-title">📈 Grafik Tren Penjualan Harian (Omset Rp)</div>
      <div style="font-size: 12px; color: var(--accent-gold); font-weight: 600;">Pembaruan Real-Time</div>
    </div>
    <div style="height: 280px; position: relative;">
      <canvas id="salesTrendChart"></canvas>
    </div>
  </div>

  <!-- Chart 2: Sales Distribution by Category -->
  <div class="chart-panel">
    <div class="chart-header">
      <div class="chart-title">🍩 Proporsi Penjualan per Kategori</div>
      <div style="font-size: 12px; color: var(--text-muted);">Persentase Omset</div>
    </div>
    <div style="height: 280px; position: relative; display: flex; align-items: center; justify-content: center;">
      <canvas id="categorySalesChart"></canvas>
    </div>
  </div>
</div>

<!-- 3. Tables Section: Top Selling Products & Recent Transactions -->
<div class="tables-grid">
  <!-- Top 5 Best Selling Products Table -->
  <div class="panel" style="margin: 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); padding-bottom: 12px;">
      <h3 style="font-size: 15px; font-weight: 600; color: #fff;">🏆 5 Produk Terlaris (Best Seller)</h3>
      <a href="{{ route('admin.products.index') }}" style="font-size: 12px; color: var(--accent-gold); text-decoration: none;">Kelola Produk →</a>
    </div>

    <table class="data-table">
      <thead>
        <tr>
          <th style="width: 70px;">PERINGKAT</th>
          <th>NAMA PRODUK</th>
          <th>TERJUAL</th>
          <th style="text-align: right;">TOTAL PENDAPATAN</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($topSellingProducts as $index => $item)
          <tr>
            <td style="font-weight: 700; color: var(--accent-gold); text-align: center;">
              #{{ $index + 1 }}
            </td>
            <td><strong>{{ $item->product_name }}</strong></td>
            <td><span class="growth-pill">{{ $item->total_qty }} unit</span></td>
            <td style="font-weight: 700; color: #fef08a; text-align: right;">
              Rp {{ number_format($item->total_revenue, 0, ',', '.') }}
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 20px;">
              Belum ada data rekapan produk terjual.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Recent Transactions Table -->
  <div class="panel" style="margin: 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); padding-bottom: 12px;">
      <h3 style="font-size: 15px; font-weight: 600; color: #fff;">📜 Transaksi Pesanan Terbaru</h3>
      <a href="{{ route('admin.orders.index') }}" style="font-size: 12px; color: var(--accent-gold); text-decoration: none;">Lihat Semua Pesanan →</a>
    </div>

    <table class="data-table">
      <thead>
        <tr>
          <th>INVOICE</th>
          <th>PELANGGAN</th>
          <th>TOTAL</th>
          <th>PEMBAYARAN</th>
          <th>STATUS PESANAN</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($recentOrders as $order)
          <tr>
            <td>
              <a href="{{ route('order.show', $order->invoice_number) }}" target="_blank" style="color: var(--accent-gold); font-weight: 700; font-size: 12px; font-family: monospace;">{{ $order->invoice_number }}</a>
              <div style="font-size: 10px; color: var(--text-muted);">{{ $order->created_at->diffForHumans() }}</div>
            </td>
            <td>
              <strong style="color: #fff;">{{ $order->customer_name }}</strong>
              <div style="font-size: 11px; color: var(--text-muted);">{{ strtoupper($order->delivery_option) }}</div>
            </td>
            <td style="font-weight: 700; color: var(--accent-gold);">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
            <td>
              <span class="status-badge-sm {{ ($order->payment_status === 'paid' || $order->payment_status === 'verified') ? 'status-paid' : 'status-unpaid' }}">
                {{ $order->payment_status === 'paid' || $order->payment_status === 'verified' ? 'LUNAS' : 'BELUM BAYAR' }}
              </span>
            </td>
            <td>
              <span class="status-badge-sm status-{{ $order->order_status }}">
                {{ strtoupper($order->order_status) }}
              </span>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 20px;">
              Belum ada pesanan masuk.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- 4. Chart.js Initialization Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Chart 1: Sales Trend Line Chart
  const salesCtx = document.getElementById('salesTrendChart').getContext('2d');
  const gradient = salesCtx.createLinearGradient(0, 0, 0, 280);
  gradient.addColorStop(0, 'rgba(245, 158, 11, 0.4)');
  gradient.addColorStop(1, 'rgba(245, 158, 11, 0.0)');

  new Chart(salesCtx, {
    type: 'line',
    data: {
      labels: {!! json_encode($dates) !!},
      datasets: [{
        label: 'Omset Penjualan (Rp)',
        data: {!! json_encode($salesTrend) !!},
        borderColor: '#f59e0b',
        borderWidth: 3,
        backgroundColor: gradient,
        fill: true,
        tension: 0.35,
        pointBackgroundColor: '#fef08a',
        pointBorderColor: '#b45309',
        pointRadius: 4,
        pointHoverRadius: 7
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: function(context) {
              return ' Omset: Rp ' + context.parsed.y.toLocaleString('id-ID');
            }
          }
        }
      },
      scales: {
        x: {
          grid: { color: 'rgba(255, 255, 255, 0.05)' },
          ticks: { color: '#8fa59b', font: { size: 11 } }
        },
        y: {
          grid: { color: 'rgba(255, 255, 255, 0.05)' },
          ticks: {
            color: '#8fa59b',
            font: { size: 11 },
            callback: function(value) {
              if (value >= 1000000) return 'Rp ' + (value/1000000).toFixed(1) + 'Jt';
              if (value >= 1000) return 'Rp ' + (value/1000).toFixed(0) + 'Rb';
              return 'Rp ' + value;
            }
          }
        }
      }
    }
  });

  // Chart 2: Category Breakdown Doughnut Chart
  const catCtx = document.getElementById('categorySalesChart').getContext('2d');
  new Chart(catCtx, {
    type: 'doughnut',
    data: {
      labels: {!! json_encode($categoryLabels) !!},
      datasets: [{
        data: {!! json_encode($categoryTotals) !!},
        backgroundColor: [
          '#f59e0b',
          '#d97706',
          '#b45309',
          '#10b981',
          '#3b82f6',
          '#8b5cf6'
        ],
        borderWidth: 2,
        borderColor: '#1e293b'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: { color: '#cad8d1', padding: 14, font: { size: 11 } }
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              return ' ' + context.label + ': Rp ' + context.parsed.toLocaleString('id-ID');
            }
          }
        }
      }
    }
  });
});
</script>
@endsection
