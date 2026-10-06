@extends('layouts.app')

@section('title', 'Cart | ' . config('site.brand'))

@section('content')
<style>
  .cart-page-container {
    background-color: #f7f9f8;
    min-height: 100vh;
    padding: 40px 20px 100px 20px;
    color: #2c3e35;
    font-family: 'Outfit', sans-serif;
  }
  .cart-wrapper {
    max-width: 900px;
    margin: 0 auto;
  }
  .cart-header-title {
    text-align: center;
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    letter-spacing: 4px;
    font-weight: 400;
    text-transform: uppercase;
    margin-bottom: 50px;
    color: #1e2d27;
  }

  /* Cart Table */
  .cart-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 60px;
  }
  .cart-table th {
    font-size: 11px;
    letter-spacing: 3px;
    text-transform: uppercase;
    font-weight: 600;
    color: #64756d;
    padding-bottom: 16px;
    border-bottom: 1px solid #d2dcd7;
  }
  .cart-table td {
    padding: 24px 0;
    border-bottom: 1px solid #d2dcd7;
    vertical-align: middle;
  }
  .cart-item-info {
    display: flex;
    align-items: center;
    gap: 20px;
  }
  .cart-item-img {
    width: 90px;
    height: 90px;
    object-fit: cover;
    border-radius: 4px;
    background: #e2e8f0;
  }
  .cart-item-name {
    font-size: 14px;
    font-weight: 500;
    color: #1e2d27;
    margin-bottom: 4px;
  }
  .cart-item-price {
    font-size: 13px;
    color: #55675f;
  }

  /* Quantity Control */
  .qty-box {
    display: inline-flex;
    align-items: center;
    border: 1px solid #b5c7c0;
    border-radius: 4px;
    background: #fff;
    overflow: hidden;
  }
  .qty-btn {
    background: transparent;
    border: none;
    padding: 6px 12px;
    font-size: 14px;
    cursor: pointer;
    color: #2c3e35;
  }
  .qty-btn:hover {
    background: #f0f4f2;
  }
  .qty-val {
    padding: 0 12px;
    font-size: 13px;
    font-weight: 600;
  }
  .remove-link {
    display: block;
    font-size: 11px;
    color: #7a8b83;
    text-decoration: underline;
    margin-top: 6px;
    cursor: pointer;
    background: none;
    border: none;
  }

  /* Delivery Options Card */
  .delivery-card {
    border: 1px solid #d2dcd7;
    border-radius: 6px;
    background: #fff;
    padding: 30px;
    margin-bottom: 60px;
  }
  .delivery-card-title {
    text-align: center;
    font-size: 12px;
    letter-spacing: 3px;
    text-transform: uppercase;
    font-weight: 600;
    color: #64756d;
    margin-bottom: 24px;
  }
  .delivery-tabs {
    display: flex;
    justify-content: center;
    gap: 16px;
    margin-bottom: 30px;
  }
  .delivery-tab-btn {
    flex: 1;
    max-width: 180px;
    padding: 16px 20px;
    border: 1px solid #d2dcd7;
    border-radius: 8px;
    background: #f8faf9;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
  }
  .delivery-tab-btn.active {
    background: #dcd6cd;
    border-color: #8c8275;
    font-weight: 600;
  }
  .delivery-tab-btn .icon {
    font-size: 22px;
    display: block;
    margin-bottom: 6px;
  }
  .delivery-tab-btn .label {
    font-size: 12px;
    font-weight: 600;
    color: #1e2d27;
  }

  .date-picker-row {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    font-size: 13px;
    color: #4a5c53;
    margin-bottom: 30px;
  }
  .date-picker-input {
    padding: 8px 14px;
    border: 1px solid #b5c7c0;
    border-radius: 4px;
    background: #fff;
    font-family: inherit;
    font-size: 13px;
    color: #1e2d27;
  }

  .note-and-total-row {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 30px;
    align-items: end;
  }
  @media (max-width: 768px) {
    .note-and-total-row {
      grid-template-columns: 1fr;
    }
  }

  .order-note-box label {
    display: block;
    font-size: 12px;
    color: #64756d;
    margin-bottom: 8px;
  }
  .order-note-box textarea {
    width: 100%;
    height: 100px;
    padding: 12px;
    border: 1px solid #d2dcd7;
    border-radius: 4px;
    font-family: inherit;
    font-size: 13px;
    resize: none;
  }

  .cart-summary-box {
    text-align: right;
  }
  .summary-total {
    font-size: 18px;
    font-weight: 600;
    color: #1e2d27;
    margin-bottom: 4px;
  }
  .summary-subtext {
    font-size: 11px;
    color: #7a8b83;
    margin-bottom: 16px;
  }
  .terms-check-label {
    font-size: 12px;
    color: #4a5c53;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
    margin-bottom: 20px;
    cursor: pointer;
  }
  .terms-check-label input {
    accent-color: #1e2d27;
  }
  .checkout-btn {
    width: 100%;
    padding: 14px;
    background: #dcd6cd;
    border: 1px solid #8c8275;
    color: #1e2d27;
    font-size: 12px;
    letter-spacing: 3px;
    text-transform: uppercase;
    font-weight: 600;
    cursor: pointer;
    border-radius: 4px;
    transition: background 0.2s;
  }
  .checkout-btn:hover {
    background: #cbbfb0;
  }

  /* Terms & Conditions Accordion Section */
  .terms-section {
    background: #e5ece9;
    border-radius: 8px;
    padding: 50px 40px;
    margin-top: 80px;
  }
  .terms-header-title {
    text-align: center;
    font-size: 12px;
    letter-spacing: 3px;
    text-transform: uppercase;
    font-weight: 600;
    color: #4a5c53;
    margin-bottom: 30px;
    border-bottom: 1px solid #c8d5d0;
    padding-bottom: 12px;
  }
  .terms-content {
    max-width: 720px;
    margin: 0 auto;
    font-size: 12px;
    line-height: 1.8;
    color: #3b4e45;
  }
  .terms-content h4 {
    text-align: center;
    font-size: 13px;
    text-decoration: underline;
    margin: 24px 0 12px 0;
    color: #1e2d27;
  }
  .terms-content strong {
    color: #1e2d27;
  }

  /* Mobile Responsiveness for Cart Page */
  @media (max-width: 650px) {
    .cart-page-container { padding: 20px 12px 60px; }
    .cart-header-title { font-size: 24px; margin-bottom: 30px; letter-spacing: 2px; }
    .cart-table th, .cart-table td { padding: 12px 4px; }
    .cart-item-info { gap: 10px; flex-direction: row; }
    .cart-item-img { width: 56px; height: 56px; }
    .cart-item-name { font-size: 12.5px; line-height: 1.3; }
    .cart-item-price { font-size: 11.5px; }
    .delivery-card { padding: 16px; margin-bottom: 30px; }
    .delivery-tabs { flex-direction: row; gap: 10px; }
    .delivery-tab-btn { max-width: none; flex: 1; padding: 12px 8px; }
    .delivery-tab-btn .icon { font-size: 18px; margin-bottom: 2px; }
    .delivery-tab-btn .label { font-size: 11px; }
    .date-picker-row { flex-direction: column; text-align: center; gap: 6px; }
    .terms-section { padding: 24px 16px; margin-top: 40px; }
    .checkout-btn { font-size: 11px; padding: 14px 10px; letter-spacing: 1.5px; }
  }
</style>

<div class="cart-page-container">
  <div class="cart-wrapper">
    <h1 class="cart-header-title">KERANJANG BELANJA</h1>

    @if (session('error'))
      <div style="background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; padding: 12px; border-radius: 4px; margin-bottom: 24px; font-size: 13px;">
        ⚠️ {{ session('error') }}
      </div>
    @endif

    @if (!empty($cart) && count($cart) > 0)
      <form action="{{ route('checkout.show') }}" method="GET" id="cart-form">
        <table class="cart-table">
          <thead>
            <tr>
              <th style="text-align: left;">PRODUK</th>
              <th style="text-align: center;">JUMLAH</th>
              <th style="text-align: right;">TOTAL HARGA</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($cart as $id => $item)
              <tr>
                <td>
                  <div class="cart-item-info">
                    <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" class="cart-item-img" onerror="this.src='{{ asset('images/cat-cakes.jpg') }}'">
                    <div>
                      <div class="cart-item-name">{{ $item['name'] }}</div>
                      <div class="cart-item-price">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                    </div>
                  </div>
                </td>
                <td style="text-align: center;">
                  <div class="qty-box">
                    <form action="{{ route('cart.update') }}" method="POST" style="display: inline;">
                      @csrf
                      <input type="hidden" name="product_id" value="{{ $id }}">
                      <input type="hidden" name="action" value="decrease">
                      <button type="submit" class="qty-btn">-</button>
                    </form>
                    <span class="qty-val">{{ $item['quantity'] }}</span>
                    <form action="{{ route('cart.update') }}" method="POST" style="display: inline;">
                      @csrf
                      <input type="hidden" name="product_id" value="{{ $id }}">
                      <input type="hidden" name="action" value="increase">
                      <button type="submit" class="qty-btn">+</button>
                    </form>
                  </div>
                  <form action="{{ route('cart.remove') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $id }}">
                    <button type="submit" class="remove-link">Hapus</button>
                  </form>
                </td>
                <td style="text-align: right; font-weight: 500; font-size: 14px;">
                  Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        @php
          $shipSettings = \App\Http\Controllers\Admin\AdminShippingController::getShippingSettings();
          $freeMin = $shipSettings['free_shipping_min'] ?? 500000;
          $isFreeShippingSubtotal = ($subtotal >= $freeMin);
        @endphp

        <!-- DELIVERY OPTIONS Section -->
        <div class="delivery-card">
          <div class="delivery-card-title">PILIHAN PENGIRIMAN & EKSPEDISI</div>
          
          <input type="hidden" name="delivery_option" id="delivery_option_input" value="delivery">
          <input type="hidden" name="courier_name" id="courier_name_input" value="Kurir Toko (Dedicated Bakery Delivery)">

          <div class="delivery-tabs">
            <div class="delivery-tab-btn active" id="tab-delivery" onclick="setDeliveryOption('delivery')">
              <span class="icon">🚚</span>
              <span class="label">Pengiriman Kurir</span>
            </div>
            <div class="delivery-tab-btn" id="tab-pickup" onclick="setDeliveryOption('pickup')">
              <span class="icon">🏪</span>
              <span class="label">Ambil di Toko</span>
            </div>
          </div>

          <!-- Direct Courier Expedition Selector -->
          <div id="courier-selector-wrapper" style="margin-bottom: 30px; background: #f8faf9; padding: 20px; border-radius: 8px; border: 1px solid #d2dcd7;">
            <label style="display: block; font-size: 11px; font-weight: 700; color: #64756d; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 12px; text-align: center;">
              PILIH JASA EKSPEDISI / KURIR PENGIRIMAN:
            </label>

            @if($isFreeShippingSubtotal)
              <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; padding: 8px 12px; border-radius: 6px; margin-bottom: 14px; text-align: center; font-size: 12px; font-weight: 600;">
                🎉 Selamat! Subtotal belanja Anda mencapai Rp {{ number_format($freeMin, 0, ',', '.') }} — Berhak <strong>GRATIS ONGKIR (Rp 0)</strong>!
              </div>
            @endif

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px;">
              <label class="courier-option-card active" id="courier-card-store" onclick="selectCourier('Kurir Toko (Dedicated Bakery Delivery)', {{ $shipSettings['store_courier_rate'] ?? 20000 }})" style="border: 2px solid #1e2d27; background: #fff; padding: 12px; border-radius: 6px; cursor: pointer; text-align: center; transition: all 0.2s;">
                <div style="font-weight: 600; font-size: 13px; color: #1e2d27;">🥖 Kurir Toko Dedicated</div>
                <div style="font-size: 11px; color: #55675f; margin-top: 2px;">
                  {{ $isFreeShippingSubtotal ? 'GRATIS (Rp 0)' : 'Rp ' . number_format($shipSettings['store_courier_rate'] ?? 20000, 0, ',', '.') }}
                </div>
              </label>

              <label class="courier-option-card" id="courier-card-jne" onclick="selectCourier('JNE Express (Reguler/YES)', {{ $shipSettings['jne_rate'] ?? 15000 }})" style="border: 1px solid #d2dcd7; background: #fff; padding: 12px; border-radius: 6px; cursor: pointer; text-align: center; transition: all 0.2s;">
                <div style="font-weight: 600; font-size: 13px; color: #1e2d27;">📦 JNE Express</div>
                <div style="font-size: 11px; color: #55675f; margin-top: 2px;">
                  {{ $isFreeShippingSubtotal ? 'GRATIS (Rp 0)' : 'Rp ' . number_format($shipSettings['jne_rate'] ?? 15000, 0, ',', '.') }}
                </div>
              </label>

              <label class="courier-option-card" id="courier-card-jnt" onclick="selectCourier('J&T Express', {{ $shipSettings['jnt_rate'] ?? 16000 }})" style="border: 1px solid #d2dcd7; background: #fff; padding: 12px; border-radius: 6px; cursor: pointer; text-align: center; transition: all 0.2s;">
                <div style="font-weight: 600; font-size: 13px; color: #1e2d27;">🚚 J&T Express</div>
                <div style="font-size: 11px; color: #55675f; margin-top: 2px;">
                  {{ $isFreeShippingSubtotal ? 'GRATIS (Rp 0)' : 'Rp ' . number_format($shipSettings['jnt_rate'] ?? 16000, 0, ',', '.') }}
                </div>
              </label>

              <label class="courier-option-card" id="courier-card-gosend" onclick="selectCourier('GoSend / GrabExpress (Instant)', {{ $shipSettings['gosend_rate'] ?? 25000 }})" style="border: 1px solid #d2dcd7; background: #fff; padding: 12px; border-radius: 6px; cursor: pointer; text-align: center; transition: all 0.2s;">
                <div style="font-weight: 600; font-size: 13px; color: #1e2d27;">⚡ GoSend / Grab</div>
                <div style="font-size: 11px; color: #55675f; margin-top: 2px;">
                  {{ $isFreeShippingSubtotal ? 'GRATIS (Rp 0)' : 'Rp ' . number_format($shipSettings['gosend_rate'] ?? 25000, 0, ',', '.') }}
                </div>
              </label>
            </div>
          </div>

          <div class="date-picker-row">
            <span>Tanggal pengiriman atau pengambilan:</span>
            <input type="date" name="delivery_date" class="date-picker-input" value="{{ date('Y-m-d', strtotime('+1 day')) }}" min="{{ date('Y-m-d') }}" required>
          </div>

          <div class="note-and-total-row">
            <div class="order-note-box">
              <label for="order_note">Catatan khusus pesanan (opsional)</label>
              <textarea name="order_note" id="order_note" placeholder="Tuliskan ucapan selamat atau catatan khusus di sini..."></textarea>
            </div>

            <div class="cart-summary-box">
              @php
                $appliedCoupon = session('applied_coupon');
                $discountAmount = $appliedCoupon['discount'] ?? 0;
                $initialCourierRate = $isFreeShippingSubtotal ? 0 : ($shipSettings['store_courier_rate'] ?? 20000);
              @endphp

              <!-- Coupon Code Card -->
              <div style="background: #f8faf9; border: 1px solid #d2dcd7; border-radius: 8px; padding: 14px; margin-bottom: 20px; text-align: left;">
                <label style="display: block; font-size: 11px; font-weight: 700; color: #64756d; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 8px;">
                  🎟️ KODE KUPON DISKON
                </label>
                <div style="display: flex; gap: 8px;">
                  <input type="text" id="coupon_code_input" placeholder="Contoh: DISKON10" value="{{ $appliedCoupon['code'] ?? '' }}" style="flex: 1; padding: 8px 12px; border: 1px solid #b5c7c0; border-radius: 4px; font-size: 13px; text-transform: uppercase;" {{ $appliedCoupon ? 'readonly' : '' }}>
                  <button type="button" id="coupon_btn" onclick="{{ $appliedCoupon ? 'removeCouponAjax()' : 'applyCouponAjax()' }}" style="background: {{ $appliedCoupon ? '#ef4444' : '#1e2d27' }}; color: #fff; border: none; padding: 8px 14px; border-radius: 4px; font-size: 12px; font-weight: 600; cursor: pointer;">
                    {{ $appliedCoupon ? 'Hapus' : 'Gunakan' }}
                  </button>
                </div>
                <div id="coupon-msg" style="font-size: 11px; margin-top: 6px; display: {{ $appliedCoupon ? 'block' : 'none' }}; color: {{ $appliedCoupon ? '#15803d' : '#ef4444' }}; font-weight: 600;">
                  {{ $appliedCoupon ? '🎉 Kupon: ' . ($appliedCoupon['code'] ?? '') . ' (' . ($appliedCoupon['label'] ?? '') . ' - Diskon Rp ' . number_format($discountAmount, 0, ',', '.') . ')' : '' }}
                </div>
              </div>

              <div class="summary-total">Total: Rp <span id="cart-total-display">{{ number_format(max(0, $subtotal + $initialCourierRate - $discountAmount), 0, ',', '.') }}</span></div>
              <div class="summary-subtext" id="shipping-fee-subtitle">
                Ongkir <span id="courier-name-label">Kurir Toko Dedicated</span>: 
                <strong style="color: #1e2d27;" id="courier-fee-label">{{ $isFreeShippingSubtotal ? 'GRATIS (Rp 0)' : 'Rp ' . number_format($initialCourierRate, 0, ',', '.') }}</strong>
                @if($discountAmount > 0)
                  <br><span style="color: #15803d; font-weight: 600;" id="discount-label-row">Diskon Kupon: -Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
                @endif
              </div>

              <label class="terms-check-label">
                <input type="checkbox" name="terms_agree" id="terms_agree" required>
                <span>Saya telah membaca dan menyetujui <a href="#terms-of-order" style="color: inherit; text-decoration: underline;">Syarat & Ketentuan Pesanan</a></span>
              </label>

              <button type="submit" class="checkout-btn">PROSES PEMBAYARAN</button>
            </div>
          </div>
        </div>
      </form>
    @else
      <div style="text-align: center; padding: 60px 20px; background: #fff; border-radius: 8px; border: 1px solid #d2dcd7;">
        <div style="font-size: 40px; margin-bottom: 12px;">🥐</div>
        <h3 style="font-size: 18px; font-weight: 500; color: #1e2d27; margin-bottom: 10px;">Keranjang belanja Anda masih kosong</h3>
        <p style="font-size: 13px; color: #64756d; margin-bottom: 24px;">Jelajahi koleksi produk toko roti kami dan tambahkan item favorit Anda.</p>
        <a href="{{ route('home') }}" class="checkout-btn" style="display: inline-block; width: auto; padding: 12px 30px; text-decoration: none;">BELANJA SEKARANG</a>
      </div>
    @endif

    <!-- TERMS & CONDITIONS OF ORDER Section -->
    <div class="terms-section" id="terms-of-order">
      <div class="terms-header-title">SYARAT & KETENTUAN PESANAN</div>
      <div class="terms-content">
        <h4>Pemesanan dan Konfirmasi</h4>
        <p><strong>Perubahan:</strong> Setelah pesanan Anda dikonfirmasi dan pembayaran diterima, perubahan, pembatalan, atau pengembalian dana tidak dapat dilakukan.</p>
        <p><strong>Ketersediaan Produk:</strong> Ketersediaan produk dapat bervariasi tergantung musim dan stok harian. Kami berhak mengganti item dengan produk bernilai setara jika diperlukan.</p>

        <h4>Pembayaran</h4>
        <p><strong>Pembayaran Penuh:</strong> Pembayaran penuh diperlukan sebelum pesanan diproses oleh dapur kami.</p>
        <p><strong>Metode Pembayaran:</strong> Pembayaran dapat dilakukan via Transfer BCA, Mandiri, BRI, QRIS Instant, maupun Midtrans Gateway (Verifikasi Otomatis).</p>
        <p><strong>Bukti Transfer:</strong> Setelah melakukan pembayaran manual, harap kirim screenshot bukti transfer ke WhatsApp resmi kami di <strong>+62 811 152 282</strong>.</p>

        <h4>Pengiriman & Pengambilan Toko</h4>
        <p><strong>Pilihan Ekspedisi:</strong> Anda dapat memilih pengiriman Kurir Toko Dedicated, JNE Express, J&T Express, atau GoSend/GrabExpress Instant.</p>
        <p><strong>Gratis Ongkir:</strong> Bebas biaya kirim berlaku untuk setiap pemesanan dengan nilai di atas Rp 500.000.</p>

        <p style="text-align: center; margin-top: 30px; font-style: italic; color: #55675f;">
          Terima kasih atas kepercayaan Anda memilih toko roti kami! Kami siap menyajikan pesanan terbaik untuk Anda.
        </p>
      </div>
    </div>
  </div>
</div>

<script>
const subtotal = {{ $subtotal ?? 0 }};
const isFreeShippingSubtotal = {{ !empty($isFreeShippingSubtotal) ? 'true' : 'false' }};
let currentCourierRate = isFreeShippingSubtotal ? 0 : {{ !empty($shipSettings['store_courier_rate']) ? $shipSettings['store_courier_rate'] : 20000 }};
let currentDeliveryOption = 'delivery';
let appliedDiscount = {{ $discountAmount ?? 0 }};

function calculateGrandTotal() {
  const delivery = (currentDeliveryOption === 'delivery') ? currentCourierRate : 0;
  return Math.max(0, subtotal + delivery - appliedDiscount);
}

function setDeliveryOption(option) {
  currentDeliveryOption = option;
  document.getElementById('delivery_option_input').value = option;
  
  document.getElementById('tab-delivery').classList.remove('active');
  document.getElementById('tab-pickup').classList.remove('active');

  const courierWrapper = document.getElementById('courier-selector-wrapper');

  if (option === 'delivery') {
    document.getElementById('tab-delivery').classList.add('active');
    courierWrapper.style.display = 'block';
    document.getElementById('cart-total-display').textContent = formatRupiah(calculateGrandTotal());
    document.getElementById('courier-fee-label').textContent = (currentCourierRate === 0) ? 'GRATIS (Rp 0)' : ('Rp ' + formatRupiah(currentCourierRate));
  } else {
    document.getElementById('tab-pickup').classList.add('active');
    courierWrapper.style.display = 'none';
    document.getElementById('courier_name_input').value = 'Ambil Sendiri di Boutique Store';
    document.getElementById('cart-total-display').textContent = formatRupiah(calculateGrandTotal());
    document.getElementById('courier-fee-label').textContent = 'Ambil di Toko (Rp 0)';
  }
}

function selectCourier(name, rate) {
  document.getElementById('courier_name_input').value = name;
  document.getElementById('courier-name-label').textContent = name;
  
  currentCourierRate = isFreeShippingSubtotal ? 0 : rate;

  document.querySelectorAll('.courier-option-card').forEach(card => {
    card.style.border = '1px solid #d2dcd7';
  });

  if (name.includes('Kurir Toko')) {
    document.getElementById('courier-card-store').style.border = '2px solid #1e2d27';
  } else if (name.includes('JNE')) {
    document.getElementById('courier-card-jne').style.border = '2px solid #1e2d27';
  } else if (name.includes('J&T')) {
    document.getElementById('courier-card-jnt').style.border = '2px solid #1e2d27';
  } else if (name.includes('GoSend') || name.includes('Grab')) {
    document.getElementById('courier-card-gosend').style.border = '2px solid #1e2d27';
  }

  if (currentDeliveryOption === 'delivery') {
    document.getElementById('cart-total-display').textContent = formatRupiah(calculateGrandTotal());
    document.getElementById('courier-fee-label').textContent = (currentCourierRate === 0) ? 'GRATIS (Rp 0)' : ('Rp ' + formatRupiah(currentCourierRate));
  }
}

function applyCouponAjax() {
  const input = document.getElementById('coupon_code_input');
  const msg = document.getElementById('coupon-msg');
  const code = input.value.trim();

  if (!code) {
    msg.style.display = 'block';
    msg.style.color = '#ef4444';
    msg.textContent = 'Silakan masukkan kode kupon terlebih dahulu.';
    return;
  }

  fetch('{{ route("coupon.apply") }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({ code: code, subtotal: subtotal })
  })
  .then(res => res.json())
  .then(data => {
    msg.style.display = 'block';
    if (data.success) {
      appliedDiscount = data.discount;
      msg.style.color = '#15803d';
      msg.textContent = data.message;
      input.readOnly = true;
      
      const btn = document.getElementById('coupon_btn');
      btn.style.background = '#ef4444';
      btn.textContent = 'Hapus';
      btn.onclick = removeCouponAjax;

      document.getElementById('cart-total-display').textContent = formatRupiah(calculateGrandTotal());
      
      // Reload page after short delay to sync session
      setTimeout(() => location.reload(), 1200);
    } else {
      msg.style.color = '#ef4444';
      msg.textContent = data.message;
    }
  })
  .catch(err => {
    msg.style.display = 'block';
    msg.style.color = '#ef4444';
    msg.textContent = 'Terjadi kesalahan sistem saat memproses kupon.';
  });
}

function removeCouponAjax() {
  fetch('{{ route("coupon.remove") }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
  })
  .then(res => res.json())
  .then(data => {
    appliedDiscount = 0;
    location.reload();
  });
}

function formatRupiah(number) {
  return new Intl.NumberFormat('id-ID').format(number);
}
</script>
@endsection
