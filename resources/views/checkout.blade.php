@extends('layouts.app')

@section('title', 'Checkout | ' . config('site.brand'))

@section('content')
<style>
  .checkout-page {
    background-color: #f7f9f8;
    min-height: 100vh;
    padding: 40px 20px 100px 20px;
    color: #2c3e35;
    font-family: 'Outfit', sans-serif;
  }
  .checkout-container {
    max-width: 960px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 40px;
  }
  @media (max-width: 850px) {
    .checkout-container {
      grid-template-columns: 1fr;
    }
  }

  .checkout-box {
    background: #fff;
    border: 1px solid #d2dcd7;
    border-radius: 8px;
    padding: 32px;
  }
  .box-title {
    font-family: 'Playfair Display', serif;
    font-size: 20px;
    letter-spacing: 2px;
    color: #1e2d27;
    margin-bottom: 24px;
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 12px;
  }

  .form-group {
    margin-bottom: 20px;
  }
  .form-group label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: #334155;
    margin-bottom: 6px;
  }
  .form-control {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 14px;
    font-family: inherit;
    color: #0f172a;
  }
  .form-control:focus {
    outline: none;
    border-color: #1e2d27;
  }

  .summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f1f5f9;
    font-size: 13px;
  }
  .summary-item.total {
    font-size: 16px;
    font-weight: 700;
    color: #1e2d27;
    border-top: 2px solid #e2e8f0;
    border-bottom: none;
    margin-top: 12px;
    padding-top: 16px;
  }

  .btn-submit-order {
    width: 100%;
    padding: 16px;
    background: #1e2d27;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 13px;
    letter-spacing: 2px;
    text-transform: uppercase;
    font-weight: 600;
    cursor: pointer;
    margin-top: 24px;
    transition: background 0.2s;
  }
  .btn-submit-order:hover {
    background: #334e43;
  }
</style>

<div class="checkout-page">
  <div style="max-width: 960px; margin: 0 auto 30px auto;">
    <a href="{{ route('cart.index') }}" style="color: #64756d; text-decoration: none; font-size: 13px;">← Kembali ke Keranjang Belanja</a>
  </div>

  <div class="checkout-container">
    <!-- Form Data Diri & Alamat Pengiriman -->
    <div class="checkout-box">
      <h2 class="box-title">DETAIL PEMESAN & PENGIRIMAN</h2>

      @if ($errors->any())
        <div style="background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; padding: 14px; border-radius: 6px; margin-bottom: 24px; font-size: 13px;">
          <strong>Mohon perbaiki kesalahan berikut:</strong>
          <ul style="margin-left: 20px; margin-top: 6px;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('checkout.process') }}" method="POST">
        @csrf

        <input type="hidden" name="delivery_option" value="{{ $deliveryOption }}">
        <input type="hidden" name="delivery_date" value="{{ $deliveryDate }}">
        <input type="hidden" name="order_note" value="{{ $orderNote }}">

        @if(auth()->check())
          <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-size: 13px; display: flex; align-items: center; justify-content: space-between;">
            <div>
              <strong>👤 Akun Terhubung:</strong> {{ auth()->user()->name }} ({{ auth()->user()->email }})
            </div>
            <span style="background: #166534; color: #fff; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 700;">MEMBER</span>
          </div>
        @else
          <div style="background: #fffbe6; border: 1px solid #ffe58f; color: #873800; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-size: 12.5px;">
            ℹ️ Anda memesan sebagai <strong>Tamu (Guest)</strong>. Memiliki akun? <a href="{{ route('login') }}" style="color: #d97706; font-weight: 700;">Masuk di sini</a> untuk menyimpan riwayat pesanan Anda.
          </div>
        @endif

        <div class="form-group">
          <label for="customer_name">Nama Lengkap Pemesan *</label>
          <input type="text" name="customer_name" id="customer_name" class="form-control" value="{{ old('customer_name', auth()->check() ? auth()->user()->name : '') }}" placeholder="Contoh: Budi Santoso" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
          <div class="form-group">
            <label for="customer_phone">No. WhatsApp *</label>
            <input type="tel" name="customer_phone" id="customer_phone" class="form-control" value="{{ old('customer_phone', auth()->check() ? (auth()->user()->phone ?? '') : '') }}" placeholder="081234567890" required>
          </div>

          <div class="form-group">
            <label for="customer_email">Alamat Email (Opsional)</label>
            <input type="email" name="customer_email" id="customer_email" class="form-control" value="{{ old('customer_email', auth()->check() ? auth()->user()->email : '') }}" placeholder="email@contoh.com">
          </div>
        </div>

        <div class="form-group">
          <label for="delivery_info">Metode & Tanggal Pengiriman</label>
          <div style="padding: 12px; background: #f8faf9; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
            <strong>Opsi:</strong> {{ strtoupper($deliveryOption) }} ({{ $deliveryOption === 'pickup' ? 'Store Pick up di toko' : 'Local Delivery via Kurir' }})<br>
            <strong>Tanggal:</strong> {{ date('d F Y', strtotime($deliveryDate)) }}
          </div>
        </div>

        @if ($deliveryOption === 'delivery')
          <div class="form-group">
            <label>Ekspedisi / Kurir Terpilih</label>
            <input type="hidden" name="courier_name" value="{{ old('courier_name', $courierName) }}">
            <div style="padding: 12px 16px; background: #f8faf9; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; color: #1e2d27; font-weight: 500;">
              @php
                $courierIcons = [
                  'Kurir Toko (Dedicated Bakery Delivery)' => '🥖',
                  'JNE Express (Reguler/YES)' => '📦',
                  'J&T Express' => '🚚',
                  'GoSend / GrabExpress (Instant)' => '⚡',
                  'Ambil Sendiri di Boutique Store' => '🏪',
                ];
                $icon = $courierIcons[$courierName] ?? '🚚';
              @endphp
              {{ $icon }} {{ old('courier_name', $courierName) }}
            </div>
            <div style="font-size: 11px; color: #64748b; margin-top: 6px;">
              💡 Untuk mengubah kurir, kembali ke <a href="{{ route('cart.index') }}" style="color: #d97706; text-decoration: underline;">halaman Keranjang</a>.
            </div>
          </div>

          <div class="form-group">
            <label for="address">Alamat Pengiriman Lengkap *</label>
            <textarea name="address" id="address" class="form-control" rows="3" placeholder="Jl. Contoh No. 12, RT 01/RW 02, Kebayoran Baru, Jakarta Selatan" required>{{ old('address') }}</textarea>
          </div>
        @else
          <input type="hidden" name="courier_name" value="Ambil Sendiri di Boutique Store">
          <div class="form-group">
            <label>Lokasi Pick Up Toko</label>
            <div style="padding: 12px; background: #f1f5f9; border-radius: 6px; font-size: 13px; color: #475569;">
              📍 <strong>PUSAT HAMPERS INDONESIA Store:</strong><br>
              Jl. Cikajang V No. 12, Kebayoran Baru, Jakarta Selatan.
            </div>
            <input type="hidden" name="address" value="Store Pick up (Jl. Cikajang V No. 12, Jakarta Selatan)">
          </div>
        @endif

        <div class="form-group">
          <label for="payment_method">Metode Pembayaran *</label>
          <select name="payment_method" id="payment_method" class="form-control" required style="background: #fff; font-weight: 500;">
            <option value="Transfer Bank BCA" {{ old('payment_method') == 'Transfer Bank BCA' ? 'selected' : '' }}>💳 Transfer Bank BCA (Virtual Account / Rekening)</option>
            <option value="Transfer Bank Mandiri" {{ old('payment_method') == 'Transfer Bank Mandiri' ? 'selected' : '' }}>🏦 Transfer Bank Mandiri</option>
            <option value="Transfer Bank BRI" {{ old('payment_method') == 'Transfer Bank BRI' ? 'selected' : '' }}>🏛️ Transfer Bank BRI</option>
            <option value="QRIS Instant Payment" {{ old('payment_method') == 'QRIS Instant Payment' ? 'selected' : '' }}>📱 QRIS Instant (GoPay, OVO, ShopeePay, DANA, BCA Mobile)</option>
            <option value="Midtrans Payment Gateway (Verifikasi Otomatis)" {{ old('payment_method') == 'Midtrans Payment Gateway (Verifikasi Otomatis)' ? 'selected' : '' }}>⚡ Midtrans Payment Gateway (Verifikasi Otomatis - Credit Card, GoPay, E-Wallet, VA)</option>
            <option value="COD (Cash on Delivery)" {{ old('payment_method') == 'COD (Cash on Delivery)' ? 'selected' : '' }}>💵 COD (Bayar Tunai saat Kurir Toko Tiba)</option>
          </select>
        </div>

        <div class="form-group">
          <label for="note_display">Catatan Pesanan</label>
          <input type="text" class="form-control" value="{{ $orderNote ?: '-' }}" readonly style="background: #f8faf9;">
        </div>

        <div style="margin-top: 30px;">
          <label style="display: flex; align-items: center; gap: 10px; font-size: 13px; cursor: pointer;">
            <input type="checkbox" name="terms" value="1" checked required accent-color="#1e2d27">
            <span>Saya telah membaca dan menyetujui <strong>Terms & Conditions of Order</strong>.</span>
          </label>
        </div>

        <button type="submit" class="btn-submit-order">BUAT PESANAN & BAYAR →</button>
      </form>
    </div>

    <!-- Ringkasan Order & Produk -->
    <div>
      <div class="checkout-box">
        <h3 class="box-title" style="font-size: 16px;">RINGKASAN ITEM</h3>

        @foreach ($cart as $item)
          <div class="summary-item">
            <div style="display: flex; gap: 12px; align-items: center;">
              <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" style="width: 44px; height: 44px; object-fit: cover; border-radius: 4px;">
              <div>
                <div style="font-weight: 500;">{{ $item['name'] }}</div>
                <div style="font-size: 11px; color: #64756d;">Qty: {{ $item['quantity'] }}</div>
              </div>
            </div>
            <div style="font-weight: 600;">
              Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
            </div>
          </div>
        @endforeach

        @php
          $freeMin = $shipSettings['free_shipping_min'] ?? 500000;
          $isFreeShipping = ($deliveryFee == 0 && $deliveryOption !== 'pickup');
          $neededForFree = $freeMin - $subtotal;
        @endphp

        @if($isFreeShipping)
          <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; padding: 10px 14px; border-radius: 6px; margin-top: 16px; font-size: 12px; font-weight: 600;">
            🎉 Selamat! Pesanan Anda memenuhi syarat <strong>GRATIS ONGKIR</strong> (Min. Rp {{ number_format($freeMin, 0, ',', '.') }}).
          </div>
        @elseif($deliveryOption !== 'pickup' && $neededForFree > 0)
          <div style="background: #fffbe6; border: 1px solid #ffe58f; color: #873800; padding: 10px 14px; border-radius: 6px; margin-top: 16px; font-size: 12px;">
            💡 Tambah produk senilai <strong>Rp {{ number_format($neededForFree, 0, ',', '.') }}</strong> lagi untuk mendapatkan <strong>GRATIS ONGKIR</strong>!
          </div>
        @endif

        <div class="summary-item" style="margin-top: 16px;">
          <span>Subtotal</span>
          <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
        </div>

        @if(($discountAmount ?? 0) > 0)
          <div class="summary-item">
            <span>Diskon Kupon (<strong>{{ $appliedCoupon['code'] ?? '' }}</strong>)</span>
            <span style="color: #166534; font-weight: 700;">-Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
          </div>
        @endif

        <div class="summary-item">
          <span>Ongkos Kirim ({{ strtoupper($deliveryOption) }})</span>
          @if($deliveryFee == 0)
            <span style="color: #166534; font-weight: 700;">GRATIS (Rp 0)</span>
          @else
            <span>Rp {{ number_format($deliveryFee, 0, ',', '.') }}</span>
          @endif
        </div>

        <div class="summary-item total">
          <span>TOTAL BAYAR</span>
          <span style="color: #d97706;">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
