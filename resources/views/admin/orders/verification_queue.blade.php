@extends('layouts.admin')

@section('title', 'Antrian Verifikasi Pembayaran')
@section('page_title', '✅ Antrian Verifikasi Pembayaran')

@section('content')
<style>
  :root {
    --gold-border: rgba(217, 119, 6, 0.25);
  }

  .metric-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
    margin-bottom: 26px;
  }
  .metric-card {
    background: linear-gradient(135deg, #13221b 0%, #0d1712 100%);
    border: 1px solid var(--gold-border);
    border-radius: 14px;
    padding: 20px 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 8px 24px rgba(0,0,0,0.35);
    transition: all 0.25s ease;
  }
  .metric-card:hover {
    border-color: #f59e0b;
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(245, 158, 11, 0.2);
  }
  .metric-num {
    font-size: 32px;
    font-weight: 800;
    line-height: 1.1;
    font-family: 'Outfit', sans-serif;
  }
  .metric-label {
    font-size: 11.5px;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    margin-top: 5px;
    font-weight: 600;
  }
  .proof-thumb {
    width: 64px;
    height: 64px;
    border-radius: 10px;
    object-fit: cover;
    border: 2px solid #d97706;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
    background: #09120e;
    box-shadow: 0 4px 12px rgba(0,0,0,0.4);
  }
  .proof-thumb:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.45);
    border-color: #fef08a;
  }
  .status-pill-queue {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 800;
    background: rgba(245, 158, 11, 0.18);
    color: #fbbf24;
    border: 1px solid #f59e0b;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    box-shadow: 0 0 10px rgba(245, 158, 11, 0.2);
  }

  .panel-royal {
    background: linear-gradient(180deg, #111e18 0%, #0c1611 100%);
    border: 1px solid var(--gold-border);
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 16px 40px rgba(0,0,0,0.45);
  }
  .table-orders-royal {
    width: 100%;
    border-collapse: collapse;
    margin: 16px 0 0 0;
    font-family: 'Outfit', sans-serif;
  }
  .table-orders-royal thead th {
    background: #09120e;
    padding: 16px 18px;
    font-size: 11px;
    font-weight: 800;
    color: #e6ca65;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    border-bottom: 2px solid rgba(217, 119, 6, 0.35);
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

  /* Mobile Responsiveness for Verification Queue */
  @media (max-width: 768px) {
    .metric-grid {
      grid-template-columns: repeat(2, 1fr) !important;
      gap: 10px !important;
      margin-bottom: 18px !important;
    }
    .metric-card {
      padding: 12px 14px !important;
      border-radius: 10px !important;
    }
    .metric-card:last-child {
      grid-column: 1 / -1 !important;
      padding: 10px 14px !important;
    }
    .metric-num {
      font-size: 22px !important;
    }
    .metric-label {
      font-size: 9.5px !important;
      letter-spacing: 0.5px !important;
      margin-top: 3px !important;
    }
    .metric-card div[style*="font-size: 36px"] {
      font-size: 24px !important;
    }
    .panel-royal {
      padding: 14px 12px !important;
      border-radius: 12px !important;
    }
    .table-orders-royal thead th,
    .table-orders-royal tbody td {
      padding: 10px 12px !important;
      font-size: 12px !important;
    }
  }
</style>

<div class="metric-grid">
  <div class="metric-card" style="border-left: 4px solid #f59e0b;">
    <div>
      <div class="metric-num" style="color: #fbbf24;">{{ $queueCount }}</div>
      <div class="metric-label">Antrian Menunggu Verifikasi</div>
    </div>
    <div style="font-size: 36px;">⏳</div>
  </div>

  <div class="metric-card" style="border-left: 4px solid #10b981;">
    <div>
      <div class="metric-num" style="color: #34d399;">{{ $verifiedTodayCount }}</div>
      <div class="metric-label">Terverifikasi Lunas Hari Ini</div>
    </div>
    <div style="font-size: 36px;">✅</div>
  </div>

  <div class="metric-card" style="border-left: 4px solid #3b82f6;">
    <div>
      <a href="{{ route('admin.orders.index') }}" style="color: #93c5fd; font-size: 14px; font-weight: 700; display: block; margin-bottom: 4px;">
        📝 Buka Semua Pemesanan →
      </a>
      <div class="metric-label">Lihat Riwayat & Status Pengiriman</div>
    </div>
    <div style="font-size: 36px;">📦</div>
  </div>
</div>

<div class="panel-royal">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 14px;">
    <div>
      <h3 style="font-size: 18px; font-weight: 800; color: #fff; margin-bottom: 4px; letter-spacing: 0.3px;">Daftar Bukti Pembayaran Masuk</h3>
      <p style="font-size: 13px; color: #94a3b8; margin: 0;">
        Silakan cek mutasi rekening bank / QRIS toko Anda. Klik <strong>"Verifikasi Lunas"</strong> hanya jika dana telah benar-benar masuk.
      </p>
    </div>

    <form action="{{ route('admin.orders.verification') }}" method="GET" style="display: flex; gap: 8px;">
      <input type="text" name="search" class="form-control" placeholder="Cari invoice, nama, no WA..." value="{{ request('search') }}" style="width: 240px; padding: 9px 14px; font-size: 12.5px; border-radius: 8px;">
      <button type="submit" class="btn btn-gold" style="padding: 9px 16px; font-size: 12.5px; border-radius: 8px;">Cari</button>
      @if (request('search'))
        <a href="{{ route('admin.orders.verification') }}" class="btn btn-outline" style="padding: 9px 14px; font-size: 12.5px; border-radius: 8px;">Reset</a>
      @endif
    </form>
  </div>

  <div class="table-responsive">
    <table class="table-orders-royal">
      <thead>
        <tr>
          <th>Invoice & Waktu</th>
          <th>Pelanggan / WA</th>
          <th>Total Tagihan</th>
          <th>Metode Bayar</th>
          <th>Bukti Transfer</th>
          <th>Status</th>
          <th style="text-align: right;">Aksi Verifikasi Admin</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($orders as $order)
          <tr>
            <td>
              <a href="{{ route('order.show', $order->invoice_number) }}" target="_blank" style="color: #fbbf24; font-family: monospace; font-size: 14px; font-weight: 800; text-decoration: none;">
                🧾 {{ $order->invoice_number }}
              </a>
              <div style="font-size: 11px; color: #64748b; margin-top: 3px;">
                Dibuat: {{ $order->created_at->format('d M Y, H:i') }}
              </div>
              @if($order->payment_proof_uploaded_at)
                <div style="font-size: 11px; color: #38bdf8; margin-top: 2px; font-weight: 600;">
                  📤 Unggah: {{ $order->payment_proof_uploaded_at->diffForHumans() }}
                </div>
              @endif
            </td>
            <td>
              <div style="font-weight: 700; color: #fff; font-size: 14px;">{{ $order->customer_name }}</div>
              <div style="margin-top: 3px;">
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_phone) }}?text={{ urlencode('Halo ' . $order->customer_name . ', terkait pesanan ' . $order->invoice_number . ' di Pusat Hampers Indonesia...') }}" target="_blank" style="display: inline-flex; align-items: center; gap: 4px; color: #4ade80; text-decoration: none; font-size: 11.5px; background: rgba(37, 211, 102, 0.1); border: 1px solid rgba(37, 211, 102, 0.25); padding: 2px 8px; border-radius: 6px;" title="Chat WhatsApp Pelanggan">
                  💬 {{ $order->customer_phone }}
                </a>
              </div>
              <div style="font-size: 10px; color: #94a3b8; margin-top: 4px;">
                {{ $order->user_id ? '👑 VIP Member' : '✨ Tamu' }}
              </div>
            </td>
            <td>
              <strong style="font-size: 16px; color: #fef08a; font-family: 'Outfit', sans-serif;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
              <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                {{ $order->items->count() }} jenis produk
              </div>
            </td>
            <td>
              <span style="font-size: 12px; color: #cbd5e1; background: rgba(255,255,255,0.06); padding: 3px 8px; border-radius: 5px; border: 1px solid rgba(255,255,255,0.1);">
                💳 {{ $order->payment_method ?? 'Transfer Bank' }}
              </span>
            </td>
            <td>
              @if ($order->payment_proof)
                @php
                  $isPdf = str_ends_with(strtolower($order->payment_proof), '.pdf');
                @endphp
                @if ($isPdf)
                  <a href="{{ $order->payment_proof_url }}" target="_blank" class="btn btn-outline" style="padding: 6px 12px; font-size: 11px; border-radius: 6px;">
                    📄 Buka PDF
                  </a>
                @else
                  <img src="{{ $order->payment_proof_url }}" alt="Bukti Transfer" class="proof-thumb" onclick="openProofModal('{{ $order->payment_proof_url }}', '{{ $order->invoice_number }}', '{{ addslashes($order->customer_name) }}', '{{ number_format($order->total_amount, 0, ',', '.') }}')" title="Klik untuk perbesar bukti transfer">
                @endif
              @else
                <span style="font-size: 11.5px; color: #f87171;">⚠️ Belum unggah struk</span>
              @endif
            </td>
            <td>
              @if ($order->payment_status === 'waiting_verification')
                <span class="status-pill-queue">⏳ Antrian Verifikasi</span>
              @elseif ($order->payment_status === 'paid' || $order->payment_status === 'verified')
                <span style="background: rgba(16, 185, 129, 0.18); color: #34d399; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; border: 1px solid #10b981;">🟢 Lunas</span>
              @else
                <span style="background: rgba(239, 68, 68, 0.18); color: #fca5a5; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; border: 1px solid rgba(239, 68, 68, 0.4);">⚠️ Belum Lunas</span>
              @endif
            </td>
            <td style="text-align: right;">
              <div style="display: inline-flex; gap: 8px; align-items: center; flex-wrap: wrap; justify-content: flex-end;">
                <!-- 1. Verify Payment Button -->
                <form action="{{ route('admin.orders.verifyPayment', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin dana sebesar Rp {{ number_format($order->total_amount, 0, ',', '.') }} sudah masuk ke rekening toko? Status invoice {{ $order->invoice_number }} akan diubah menjadi LUNAS.');" style="margin: 0;">
                  @csrf
                  <button type="submit" class="btn btn-gold" style="padding: 9px 16px; font-size: 12px; font-weight: 800; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 8px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35);">
                    ✅ Verifikasi Lunas
                  </button>
                </form>

                <!-- 2. Reject Button -->
                <button type="button" class="btn btn-outline" style="padding: 9px 14px; font-size: 12px; color: #f87171; border-color: rgba(239, 68, 68, 0.4); border-radius: 8px;" onclick="openRejectModal({{ $order->id }}, '{{ $order->invoice_number }}')">
                  ❌ Tolak
                </button>

                <!-- 3. View Detail Link -->
                <a href="{{ route('order.show', $order->invoice_number) }}" target="_blank" class="btn btn-outline" style="padding: 9px 14px; font-size: 12px; border-radius: 8px;" title="Buka Detail Faktur Pesanan">
                  👁️ Detail
                </a>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" style="text-align: center; color: #94a3b8; padding: 70px 20px;">
              <div style="font-size: 48px; margin-bottom: 12px;">🎉</div>
              <h3 style="font-size: 18px; color: #fff; margin-bottom: 6px; font-weight: 700;">Tidak Ada Antrian Pembayaran</h3>
              <p style="font-size: 13px; margin: 0;">Semua pembayaran yang diunggah tamu atau pelanggan telah Anda verifikasi.</p>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if ($orders->hasPages())
    <div class="pagination-wrapper">
      {{ $orders->links() }}
    </div>
  @endif
</div>

<!-- Modal Zoom Bukti Pembayaran -->
<div id="proof-modal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.85); backdrop-filter:blur(6px); align-items:center; justify-content:center; padding:20px;">
  <div style="background:#13221b; border:1px solid #d97706; border-radius:16px; padding:26px; max-width:580px; width:100%; box-shadow:0 25px 60px rgba(0,0,0,0.8); text-align:center;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; border-bottom:1px solid rgba(217, 119, 6, 0.25); padding-bottom:12px;">
      <div style="text-align:left;">
        <h4 style="font-size:16px; color:#fef08a; margin:0; font-weight:800;" id="modal-invoice-title">Bukti Transfer</h4>
        <div style="font-size:12px; color:#cbd5e1; margin-top:2px;" id="modal-customer-info"></div>
      </div>
      <button onclick="closeProofModal()" style="background:none; border:none; color:#fff; font-size:24px; cursor:pointer;">✕</button>
    </div>

    <div style="max-height:65vh; overflow-y:auto; border-radius:10px; background:#09120e; padding:12px; margin-bottom:18px; border: 1px solid rgba(255,255,255,0.06);">
      <img id="modal-proof-img" src="" alt="Bukti Transfer Full" style="max-width:100%; height:auto; border-radius:8px; display:block; margin:0 auto; box-shadow: 0 4px 16px rgba(0,0,0,0.4);">
    </div>

    <div style="display:flex; justify-content:space-between; align-items:center;">
      <a id="modal-download-link" href="" target="_blank" class="btn btn-outline" style="font-size:12px; border-radius:8px;">
        📥 Buka Gambar Asli
      </a>
      <button onclick="closeProofModal()" class="btn btn-gold" style="font-size:12px; border-radius:8px;">
        Tutup
      </button>
    </div>
  </div>
</div>

<!-- Modal Tolak Pembayaran -->
<div id="reject-modal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.7); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:20px;">
  <div style="background:#13221b; border:1px solid #ef4444; border-radius:16px; padding:28px; max-width:460px; width:100%; box-shadow:0 25px 60px rgba(0,0,0,0.8);">
    <h3 style="font-size:18px; color:#fca5a5; margin-bottom:10px; font-weight:800;">❌ Tolak Bukti Pembayaran</h3>
    <p style="font-size:13px; color:#94a3b8; margin-bottom:16px;">
      Tolak bukti pembayaran untuk invoice <strong id="reject-invoice-title" style="color:#fbbf24;"></strong>:
    </p>

    <form id="reject-form" action="" method="POST">
      @csrf
      <div class="form-group">
        <label for="reject-reason" style="font-size:12px; font-weight:700; color:#e2e8f0;">Alasan Penolakan (Catatan Pelanggan):</label>
        <textarea name="reason" id="reject-reason" class="form-control" rows="3" required placeholder="Contoh: Bukti transfer buram / nominal tidak sesuai / mutasi dana belum masuk rekening." style="border-radius:8px; font-size:13px;"></textarea>
      </div>

      <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
        <button type="button" onclick="closeRejectModal()" class="btn btn-outline" style="border-radius:8px;">Batal</button>
        <button type="submit" class="btn btn-danger" style="border-radius:8px;">Tolak Pembayaran</button>
      </div>
    </form>
  </div>
</div>

<script>
function openProofModal(imgUrl, invoice, customer, amount) {
  document.getElementById('modal-proof-img').src = imgUrl;
  document.getElementById('modal-invoice-title').textContent = 'Bukti Transfer #' + invoice;
  document.getElementById('modal-customer-info').textContent = customer + ' • Rp ' + amount;
  document.getElementById('modal-download-link').href = imgUrl;
  document.getElementById('proof-modal').style.display = 'flex';
}

function closeProofModal() {
  document.getElementById('proof-modal').style.display = 'none';
}

function openRejectModal(orderId, invoice) {
  document.getElementById('reject-invoice-title').textContent = invoice;
  document.getElementById('reject-form').action = '/admin/orders/' + orderId + '/reject-payment';
  document.getElementById('reject-modal').style.display = 'flex';
}

function closeRejectModal() {
  document.getElementById('reject-modal').style.display = 'none';
}

document.getElementById('proof-modal').addEventListener('click', function(e) {
  if (e.target === this) closeProofModal();
});
document.getElementById('reject-modal').addEventListener('click', function(e) {
  if (e.target === this) closeRejectModal();
});
</script>
@endsection
