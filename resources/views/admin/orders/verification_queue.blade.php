@extends('layouts.admin')

@section('title', 'Antrian Verifikasi Pembayaran')
@section('page_title', '✅ Antrian Verifikasi Pembayaran')

@section('content')
<style>
  .metric-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
  }
  .metric-card {
    background: var(--panel-bg);
    border: 1px solid var(--panel-border);
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .metric-num {
    font-size: 32px;
    font-weight: 700;
    line-height: 1.1;
  }
  .metric-label {
    font-size: 12px;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-top: 4px;
  }
  .proof-thumb {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    object-fit: cover;
    border: 2px solid var(--accent-gold);
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
    background: #0f172a;
  }
  .proof-thumb:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
  }
  .status-pill-queue {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    background: rgba(245, 158, 11, 0.15);
    color: #fbbf24;
    border: 1px solid rgba(245, 158, 11, 0.4);
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

<div class="panel">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 14px;">
    <div>
      <h3 style="font-size: 18px; font-weight: 700; color: #fff; margin-bottom: 4px;">Daftar Bukti Pembayaran Masuk</h3>
      <p style="font-size: 13px; color: var(--text-muted); margin: 0;">
        Silakan cek mutasi rekening bank / QRIS toko Anda. Klik <strong>"Verifikasi Lunas"</strong> hanya jika dana telah benar-benar masuk.
      </p>
    </div>

    <form action="{{ route('admin.orders.verification') }}" method="GET" style="display: flex; gap: 8px;">
      <input type="text" name="search" class="form-control" placeholder="Cari invoice, nama, no WA..." value="{{ request('search') }}" style="width: 240px; padding: 8px 12px; font-size: 13px;">
      <button type="submit" class="btn btn-gold" style="padding: 8px 14px; font-size: 12px;">Cari</button>
      @if (request('search'))
        <a href="{{ route('admin.orders.verification') }}" class="btn btn-outline" style="padding: 8px 12px; font-size: 12px;">Reset</a>
      @endif
    </form>
  </div>

  <div class="table-responsive">
    <table class="data-table">
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
              <strong style="color: var(--accent-gold); font-family: monospace; font-size: 14px;">{{ $order->invoice_number }}</strong>
              <div style="font-size: 11px; color: var(--text-muted); margin-top: 3px;">
                Dibuat: {{ $order->created_at->format('d M Y, H:i') }}
              </div>
              @if($order->payment_proof_uploaded_at)
                <div style="font-size: 11px; color: #a5f3fc; margin-top: 2px;">
                  📤 Unggah: {{ $order->payment_proof_uploaded_at->diffForHumans() }}
                </div>
              @endif
            </td>
            <td>
              <div style="font-weight: 600; color: #fff;">{{ $order->customer_name }}</div>
              <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_phone) }}?text={{ urlencode('Halo ' . $order->customer_name . ', terkait pesanan ' . $order->invoice_number . ' di Pusat Hampers Indonesia...') }}" target="_blank" style="color: #4ade80; text-decoration: underline;" title="Chat WhatsApp Pelanggan">
                  💬 {{ $order->customer_phone }}
                </a>
              </div>
              <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                {{ $order->user_id ? '🟢 Member' : '⚪ Tamu' }}
              </div>
            </td>
            <td>
              <strong style="font-size: 15px; color: #fff;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
              <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                {{ $order->items->count() }} jenis produk
              </div>
            </td>
            <td>
              <span style="font-size: 13px; color: #cbd5e1;">{{ $order->payment_method ?? 'Transfer Bank' }}</span>
            </td>
            <td>
              @if ($order->payment_proof)
                @php
                  $isPdf = str_ends_with(strtolower($order->payment_proof), '.pdf');
                @endphp
                @if ($isPdf)
                  <a href="{{ $order->payment_proof_url }}" target="_blank" class="btn btn-outline" style="padding: 6px 10px; font-size: 11px;">
                    📄 Buka File PDF
                  </a>
                @else
                  <img src="{{ $order->payment_proof_url }}" alt="Bukti Transfer" class="proof-thumb" onclick="openProofModal('{{ $order->payment_proof_url }}', '{{ $order->invoice_number }}', '{{ addslashes($order->customer_name) }}', '{{ number_format($order->total_amount, 0, ',', '.') }}')">
                @endif
              @else
                <span style="font-size: 12px; color: #ef4444;">⚠️ Belum unggah struk</span>
              @endif
            </td>
            <td>
              @if ($order->payment_status === 'waiting_verification')
                <span class="status-pill-queue">⏳ Antrian Verifikasi</span>
              @elseif ($order->payment_status === 'paid' || $order->payment_status === 'verified')
                <span style="background: rgba(16, 185, 129, 0.2); color: #34d399; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;">✅ Lunas</span>
              @else
                <span style="background: rgba(239, 68, 68, 0.2); color: #f87171; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;">Belum Lunas</span>
              @endif
            </td>
            <td style="text-align: right;">
              <div style="display: inline-flex; gap: 8px; align-items: center; flex-wrap: wrap; justify-content: flex-end;">
                <!-- 1. Verify Payment Button -->
                <form action="{{ route('admin.orders.verifyPayment', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin dana sebesar Rp {{ number_format($order->total_amount, 0, ',', '.') }} sudah masuk ke rekening toko? Status invoice {{ $order->invoice_number }} akan diubah menjadi LUNAS.');" style="margin: 0;">
                  @csrf
                  <button type="submit" class="btn btn-gold" style="padding: 8px 14px; font-size: 12px; font-weight: 700; background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    ✅ Verifikasi Lunas
                  </button>
                </form>

                <!-- 2. Reject Button -->
                <button type="button" class="btn btn-outline" style="padding: 8px 12px; font-size: 12px; color: #f87171; border-color: rgba(239, 68, 68, 0.4);" onclick="openRejectModal({{ $order->id }}, '{{ $order->invoice_number }}')">
                  ❌ Tolak
                </button>

                <!-- 3. View Detail Link -->
                <a href="{{ route('order.show', $order->invoice_number) }}" target="_blank" class="btn btn-outline" style="padding: 8px 12px; font-size: 12px;" title="Buka Detail Faktur Pesanan">
                  👁️ Detail
                </a>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 60px 20px;">
              <div style="font-size: 48px; margin-bottom: 12px;">🎉</div>
              <h3 style="font-size: 18px; color: #fff; margin-bottom: 6px;">Tidak Ada Antrian Pembayaran</h3>
              <p style="font-size: 13px; margin: 0;">Semua pembayaran yang diunggah tamu atau pelanggan telah Anda verifikasi.</p>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if ($orders->hasPages())
    <div style="margin-top: 24px; display: flex; justify-content: center;">
      {{ $orders->links() }}
    </div>
  @endif
</div>

<!-- Modal Zoom Bukti Pembayaran -->
<div id="proof-modal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.85); backdrop-filter:blur(6px); align-items:center; justify-content:center; padding:20px;">
  <div style="background:#1e293b; border:1px solid var(--accent-gold); border-radius:14px; padding:24px; max-width:560px; width:100%; box-shadow:0 25px 60px rgba(0,0,0,0.8); text-align:center;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px; border-bottom:1px solid var(--panel-border); padding-bottom:10px;">
      <div style="text-align:left;">
        <h4 style="font-size:16px; color:#fff; margin:0;" id="modal-invoice-title">Bukti Transfer</h4>
        <div style="font-size:12px; color:var(--text-muted);" id="modal-customer-info"></div>
      </div>
      <button onclick="closeProofModal()" style="background:none; border:none; color:#fff; font-size:24px; cursor:pointer;">✕</button>
    </div>

    <div style="max-height:65vh; overflow-y:auto; border-radius:8px; background:#0f172a; padding:10px; margin-bottom:16px;">
      <img id="modal-proof-img" src="" alt="Bukti Transfer Full" style="max-width:100%; height:auto; border-radius:6px; display:block; margin:0 auto;">
    </div>

    <div style="display:flex; justify-content:space-between; align-items:center;">
      <a id="modal-download-link" href="" target="_blank" class="btn btn-outline" style="font-size:12px;">
        📥 Buka Gambar Asli
      </a>
      <button onclick="closeProofModal()" class="btn btn-gold" style="font-size:12px;">
        Tutup
      </button>
    </div>
  </div>
</div>

<!-- Modal Tolak Pembayaran -->
<div id="reject-modal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.7); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:20px;">
  <div style="background:#1e293b; border:1px solid #ef4444; border-radius:14px; padding:28px; max-width:440px; width:100%; box-shadow:0 25px 60px rgba(0,0,0,0.8);">
    <h3 style="font-size:18px; color:#fff; margin-bottom:10px;">❌ Tolak Bukti Pembayaran</h3>
    <p style="font-size:13px; color:var(--text-muted); margin-bottom:16px;">
      Tolak bukti pembayaran untuk invoice <strong id="reject-invoice-title" style="color:#fbbf24;"></strong>:
    </p>

    <form id="reject-form" action="" method="POST">
      @csrf
      <div class="form-group">
        <label for="reject-reason" style="font-size:12px;">Alasan Penolakan (Catatan Pelanggan):</label>
        <textarea name="reason" id="reject-reason" class="form-control" rows="3" required placeholder="Contoh: Bukti transfer buram / nominal tidak sesuai / dana belum masuk rekening."></textarea>
      </div>

      <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
        <button type="button" onclick="closeRejectModal()" class="btn btn-outline">Batal</button>
        <button type="submit" class="btn btn-danger">Tolak Pembayaran</button>
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
