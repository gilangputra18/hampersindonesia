@extends('layouts.app')

@section('title', 'Pembayaran & Pengiriman | ' . config('site.brand'))

@section('content')
<style>
  /* Payment & Shipping Page Styles */
  .payment-shipping-wrapper {
    background-color: #fbf9f5;
    background: linear-gradient(180deg, #fdfbf7 0%, #f7f3ea 100%);
    min-height: 100vh;
    padding: 36px 16px 80px;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    color: #3b2c1d;
  }

  /* Header & Breadcrumb */
  .ps-header-box {
    text-align: center;
    max-width: 760px;
    margin: 0 auto 36px;
  }
  .ps-breadcrumb {
    font-size: 13px;
    color: #8c7355;
    margin-bottom: 12px;
    letter-spacing: 0.5px;
  }
  .ps-breadcrumb a {
    color: #8c7355;
    text-decoration: none;
    transition: color 0.2s;
  }
  .ps-breadcrumb a:hover {
    color: #9f183c;
    text-decoration: underline;
  }
  .ps-breadcrumb span {
    color: #caa436;
    font-weight: 600;
  }
  .ps-page-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 38px;
    font-weight: 600;
    color: #2b1406;
    letter-spacing: 0.5px;
    margin: 0;
    line-height: 1.25;
  }

  /* Main Container */
  .ps-main-container {
    max-width: 760px;
    margin: 0 auto;
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 20px;
  }

  /* Floating Scroll Indicator Dots (Right Side) */
  .ps-dots-nav {
    position: fixed;
    right: 28px;
    top: 50%;
    transform: translateY(-50%);
    display: flex;
    flex-direction: column;
    gap: 14px;
    z-index: 100;
  }
  .ps-dot {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: rgba(160, 160, 160, 0.4);
    border: 2px solid #ffffff;
    cursor: pointer;
    transition: all 0.25s ease;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    text-decoration: none;
    display: block;
  }
  .ps-dot:hover, .ps-dot.active {
    background: #caa436;
    transform: scale(1.3);
    box-shadow: 0 2px 10px rgba(202, 164, 54, 0.5);
  }
  @media (max-width: 1080px) {
    .ps-dots-nav {
      display: none;
    }
  }

  /* Content Cards */
  .ps-card {
    background: #ffffff;
    border: 1px solid #ebd9b8;
    border-radius: 8px;
    padding: 28px 32px;
    box-shadow: 0 3px 12px rgba(80, 50, 10, 0.03);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .ps-card:hover {
    box-shadow: 0 6px 18px rgba(80, 50, 10, 0.06);
  }

  /* Headings */
  .ps-main-section-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 22px;
    font-weight: 700;
    color: #241405;
    margin: 0 0 14px;
    display: flex;
    align-items: center;
    gap: 10px;
    letter-spacing: 0.2px;
  }
  .ps-sub-section-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 18px;
    font-weight: 700;
    color: #241405;
    margin: 20px 0 10px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  /* Body Text & Lists */
  .ps-card-text {
    font-size: 14px;
    line-height: 1.7;
    color: #423222;
    margin: 0 0 14px;
  }
  .ps-card-text strong {
    color: #241405;
  }

  .ps-list-group {
    margin: 10px 0 16px;
    padding-left: 0;
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 12px;
  }
  .ps-list-item {
    font-size: 14px;
    line-height: 1.6;
    color: #423222;
  }
  .ps-item-label {
    font-weight: 700;
    color: #241405;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .ps-item-label::before {
    content: "•";
    color: #caa436;
    font-size: 18px;
    line-height: 1;
  }
  .ps-sub-desc {
    padding-left: 22px;
    margin-top: 2px;
    color: #61503f;
    font-size: 13.5px;
  }

  /* Verification Badges */
  .ps-trust-badges-wrap {
    margin-top: 18px;
    padding-top: 14px;
    border-top: 1px dashed #e2cfad;
    display: flex;
    flex-direction: column;
    gap: 8px;
  }
  .ps-trust-badge-row {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13.5px;
    font-weight: 600;
    color: #15803d;
  }

  /* Callout Note Box (Matching Reference Quote Style) */
  .ps-quote-callout {
    background: #fffdfa;
    border: 1px solid #dfcfb2;
    border-radius: 6px;
    padding: 16px 20px;
    margin: 22px 0 14px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.02);
  }
  .ps-quote-icon {
    font-size: 26px;
    line-height: 1;
    color: #caa436;
    font-family: Georgia, serif;
  }
  .ps-exclamation-badge {
    color: #dc2626;
    font-weight: 800;
    font-size: 16px;
    line-height: 1.3;
  }
  .ps-callout-content {
    font-size: 13.5px;
    line-height: 1.6;
    color: #524233;
    font-style: italic;
  }
  .ps-callout-content strong {
    color: #2b1d0c;
    font-style: normal;
  }

  /* Action Buttons Box */
  .ps-cta-box {
    display: flex;
    gap: 12px;
    margin-top: 14px;
    flex-wrap: wrap;
  }
  .btn-ps-contact {
    background: #320712;
    background: linear-gradient(135deg, #3b0816 0%, #1e0208 100%);
    border: 1px solid #d4af37;
    color: #fef08a;
    font-size: 13.5px;
    font-weight: 700;
    padding: 10px 22px;
    border-radius: 6px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
  }
  .btn-ps-contact:hover {
    background: #540b1e;
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(84, 11, 30, 0.4);
  }
  .btn-ps-shop {
    background: #ffffff;
    border: 1.5px solid #d4c5a3;
    color: #4a3419;
    font-size: 13.5px;
    font-weight: 600;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
  }
  .btn-ps-shop:hover {
    background: #fbf5e7;
    border-color: #caa436;
    color: #caa436;
  }

  /* Responsive Adjustments */
  @media (max-width: 640px) {
    .payment-shipping-wrapper {
      padding: 24px 12px 60px;
    }
    .ps-card {
      padding: 20px 18px;
    }
    .ps-page-title {
      font-size: 28px;
    }
    .ps-main-section-title {
      font-size: 19px;
    }
  }
</style>

<div class="payment-shipping-wrapper">
  <!-- Header & Breadcrumb -->
  <div class="ps-header-box">
    <div class="ps-breadcrumb">
      <button type="button" onclick="window.history.length > 1 ? window.history.back() : window.location.href='{{ route('home') }}'" class="btn-crumb-back" title="Kembali ke halaman sebelumnya">
        <span>❮</span> <span>Kembali</span>
      </button>
      <a href="{{ route('home') }}">Home</a> &gt; <span>Pembayaran &amp; Pengiriman</span>
    </div>
    <h1 class="ps-page-title">Pembayaran &amp; Pengiriman</h1>
  </div>

  <!-- Main Container -->
  <div class="ps-main-container">

    <!-- Floating Navigation Scroll Dots (Desktop) -->
    <div class="ps-dots-nav">
      <a href="#sec-pembayaran" class="ps-dot active" title="Metode Pembayaran (Xendit)" onclick="setPsDot(this)"></a>
      <a href="#sec-pengiriman-domestik" class="ps-dot" title="Pengiriman Domestik (Indonesia)" onclick="setPsDot(this)"></a>
      <a href="#sec-bantuan" class="ps-dot" title="Butuh Bantuan Pemesanan?" onclick="setPsDot(this)"></a>
    </div>

    <!-- ==========================================
         CARD 1: METODE PEMBAYARAN (MELALUI XENDIT)
         ========================================== -->
    <div class="ps-card" id="sec-pembayaran">
      <h2 class="ps-main-section-title">
        <span>💳</span>
        <span>Pembayaran &amp; Pengiriman</span>
      </h2>
      <p class="ps-card-text">
        Kami memastikan proses belanja Anda nyaman, aman, dan cepat. Berikut informasi lengkap mengenai metode pembayaran dan pengiriman di <strong>{{ config('site.brand') }}</strong>:
      </p>

      <h3 class="ps-sub-section-title">
        <span>💰</span>
        <span>Metode Pembayaran (Melalui Xendit)</span>
      </h3>
      <p class="ps-card-text">
        Kami bekerja sama dengan <strong>Xendit</strong>, payment gateway terpercaya, untuk memproses pembayaran secara otomatis dan aman.
      </p>

      <ul class="ps-list-group">
        <li class="ps-list-item">
          <div class="ps-item-label">Virtual Account (Bank Transfer)</div>
          <div class="ps-sub-desc">– BCA, Mandiri, BNI, BRI, Permata Bank</div>
        </li>
        <li class="ps-list-item">
          <div class="ps-item-label">E-Wallet</div>
          <div class="ps-sub-desc">– OVO, DANA, ShopeePay, LinkAja, GoPay</div>
        </li>
        <li class="ps-list-item">
          <div class="ps-item-label">QRIS</div>
          <div class="ps-sub-desc">– Scan sekali untuk semua dompet digital &amp; m-banking</div>
        </li>
        <li class="ps-list-item">
          <div class="ps-item-label">Kartu Kredit / Debit</div>
          <div class="ps-sub-desc">– Visa &amp; MasterCard dengan proteksi 3D Secure</div>
        </li>
        <li class="ps-list-item">
          <div class="ps-item-label">Alfamart / Indomaret Payment</div>
          <div class="ps-sub-desc">– Cetak kode bayar dan bayar di gerai terdekat</div>
        </li>
      </ul>

      <div class="ps-trust-badges-wrap">
        <div class="ps-trust-badge-row">
          <span>✅</span>
          <span>Pembayaran otomatis terverifikasi dalam hitungan menit</span>
        </div>
        <div class="ps-trust-badge-row">
          <span>✅</span>
          <span>Tanpa konfirmasi manual</span>
        </div>
      </div>
    </div>

    <!-- ==========================================
         CARD 2: PENGIRIMAN DOMESTIK (INDONESIA)
         ========================================== -->
    <div class="ps-card" id="sec-pengiriman-domestik">
      <h2 class="ps-main-section-title">
        <span>🚚</span>
        <span>Pengiriman Domestik (Indonesia)</span>
      </h2>
      <p class="ps-card-text">Kami menggunakan ekspedisi terpercaya untuk menjamin pesanan tiba dengan aman:</p>

      <ul class="ps-list-group" style="gap: 6px;">
        <li class="ps-list-item">
          <div class="ps-item-label">JNE (YES, Reguler, JTR Kargo)</div>
        </li>
        <li class="ps-list-item">
          <div class="ps-item-label">J&amp;T Express</div>
        </li>
        <li class="ps-list-item">
          <div class="ps-item-label">SiCepat</div>
        </li>
        <li class="ps-list-item">
          <div class="ps-item-label">AnterAja</div>
        </li>
        <li class="ps-list-item">
          <div class="ps-item-label">Kurir Instan &amp; Sameday (GoSend / GrabExpress) untuk area Jabodetabek</div>
        </li>
      </ul>

      <h3 class="ps-sub-section-title">
        <span>📍</span>
        <span>Estimasi waktu:</span>
      </h3>
      <ul class="ps-list-group" style="gap: 6px;">
        <li class="ps-list-item">
          <div class="ps-item-label">Jabodetabek: 1–3 hari kerja (Tersedia Instant / Sameday dalam hitungan jam)</div>
        </li>
        <li class="ps-list-item">
          <div class="ps-item-label">Pulau Jawa: 2–4 hari kerja</div>
        </li>
        <li class="ps-list-item">
          <div class="ps-item-label">Luar Jawa: 3–7 hari kerja</div>
        </li>
      </ul>
    </div>

    <!-- ==========================================
         CARD 3: BUTUH BANTUAN PEMESANAN?
         ========================================== -->
    <div class="ps-card" id="sec-bantuan">
      <h2 class="ps-main-section-title">
        <span>👥</span>
        <span>Butuh bantuan pemesanan?</span>
      </h2>
      <p class="ps-card-text">
        Hubungi layanan concierge kami untuk panduan dan konsultasi pemesanan produk maupun hampers:
      </p>

      <div class="ps-cta-box">
        <a href="{{ route('contact') }}" class="btn-ps-contact" title="Hubungi Tim Concierge Kami">
          <span>✉️</span>
          <span>Hubungi Tim Concierge</span>
        </a>
        <a href="{{ route('shop') }}" class="btn-ps-shop" title="Jelajahi Produk & Hampers">
          <span>🛍️</span>
          <span>Lihat Katalog Produk</span>
        </a>
      </div>
    </div>

  </div>
</div>

<script>
  function setPsDot(elem) {
    document.querySelectorAll('.ps-dot').forEach(d => d.classList.remove('active'));
    elem.classList.add('active');
  }

  // Smooth scroll spy for dots
  document.addEventListener('scroll', function() {
    const cards = [
      document.getElementById('sec-pembayaran'),
      document.getElementById('sec-pengiriman-domestik'),
      document.getElementById('sec-bantuan')
    ];
    const dots = document.querySelectorAll('.ps-dot');
    if (!cards[0] || dots.length !== cards.length) return;

    const scrollPos = window.scrollY + 220;
    for (let i = cards.length - 1; i >= 0; i--) {
      if (cards[i] && scrollPos >= cards[i].offsetTop) {
        dots.forEach(d => d.classList.remove('active'));
        dots[i].classList.add('active');
        break;
      }
    }
  });
</script>
@endsection
