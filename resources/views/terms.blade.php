@extends('layouts.app')

@section('title', 'Syarat & Ketentuan | ' . config('site.brand'))

@section('content')
<style>
  /* Terms & Conditions Page Styles */
  .terms-page-wrapper {
    background-color: #fbf9f5;
    background: linear-gradient(180deg, #fdfbf7 0%, #f7f3ea 100%);
    min-height: 100vh;
    padding: 36px 16px 80px;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    color: #3b2c1d;
  }

  /* Header & Breadcrumb */
  .terms-header-box {
    text-align: center;
    max-width: 760px;
    margin: 0 auto 28px;
  }
  .terms-breadcrumb {
    font-size: 13px;
    color: #8c7355;
    margin-bottom: 12px;
    letter-spacing: 0.5px;
  }
  .terms-breadcrumb a {
    color: #8c7355;
    text-decoration: none;
    transition: color 0.2s;
  }
  .terms-breadcrumb a:hover {
    color: #9f183c;
    text-decoration: underline;
  }
  .terms-breadcrumb span {
    color: #caa436;
    font-weight: 600;
  }
  .terms-page-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 38px;
    font-weight: 500;
    color: #caa436;
    letter-spacing: 1px;
    margin: 0;
    line-height: 1.25;
  }

  /* Main Container */
  .terms-main-container {
    max-width: 760px;
    margin: 0 auto;
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 14px;
  }

  /* Quick Expand/Collapse Toolbar */
  .terms-toolbar {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-bottom: 4px;
  }
  .btn-terms-action {
    background: #ffffff;
    border: 1px solid #dfcfb2;
    border-radius: 4px;
    padding: 5px 12px;
    font-size: 12px;
    font-weight: 600;
    color: #614a30;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
  }
  .btn-terms-action:hover {
    background: #fbf5e7;
    border-color: #caa436;
    color: #9f183c;
  }

  /* Floating Scroll Indicator Dots (Right Side) */
  .terms-dots-nav {
    position: fixed;
    right: 28px;
    top: 50%;
    transform: translateY(-50%);
    display: flex;
    flex-direction: column;
    gap: 14px;
    z-index: 100;
  }
  .terms-dot {
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
  .terms-dot:hover, .terms-dot.active {
    background: #caa436;
    transform: scale(1.3);
    box-shadow: 0 2px 10px rgba(202, 164, 54, 0.5);
  }
  @media (max-width: 1080px) {
    .terms-dots-nav {
      display: none;
    }
  }

  /* Section Card */
  .terms-card {
    background: #ffffff;
    border: 1px solid #ebd9b8;
    border-radius: 6px;
    padding: 18px 24px;
    box-shadow: 0 3px 12px rgba(80, 50, 10, 0.03);
    transition: all 0.2s ease;
    position: relative;
  }
  .terms-card:hover {
    box-shadow: 0 6px 18px rgba(80, 50, 10, 0.06);
    border-color: #dfcfb2;
  }

  /* Interactive Card Header */
  .terms-card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    user-select: none;
    padding: 2px 0;
  }
  .terms-toggle-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    border-radius: 4px;
    background: #fdf2f4;
    border: 1.5px solid #9f183c;
    color: #9f183c;
    font-size: 19px;
    font-weight: 800;
    line-height: 1;
    transition: all 0.2s ease;
    flex-shrink: 0;
  }
  .terms-card-header:hover .terms-toggle-btn {
    background: #9f183c;
    color: #ffffff;
  }
  .terms-card.is-closed .terms-toggle-btn {
    background: #fdfbf5;
    border-color: #caa436;
    color: #caa436;
  }
  .terms-card.is-closed:hover .terms-toggle-btn {
    background: #caa436;
    color: #ffffff;
  }

  /* Title Heading */
  .terms-card-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 18.5px;
    font-weight: 700;
    color: #241405;
    margin: 0;
    letter-spacing: 0.3px;
    transition: color 0.2s ease;
  }
  .terms-card-header:hover .terms-card-title {
    color: #9f183c;
  }

  /* Content Text & Lists */
  .terms-card-body {
    font-size: 14px;
    line-height: 1.7;
    color: #423222;
    padding-top: 14px;
    border-top: 1px dashed rgba(223, 207, 178, 0.65);
    margin-top: 12px;
    transition: all 0.3s ease;
  }
  .terms-card.is-closed .terms-card-body {
    display: none;
  }

  .terms-card-body p {
    margin: 0 0 12px;
  }
  .terms-card-body p:last-child {
    margin-bottom: 0;
  }
  .terms-sub-heading {
    font-weight: 700;
    color: #241405;
    margin: 12px 0 6px;
    display: block;
  }
  .terms-list {
    margin: 6px 0 12px;
    padding-left: 20px;
    list-style: none;
  }
  .terms-list li {
    position: relative;
    padding-left: 14px;
    margin-bottom: 6px;
    line-height: 1.6;
  }
  .terms-list li::before {
    content: "•";
    position: absolute;
    left: 0;
    color: #caa436;
    font-size: 18px;
    line-height: 1;
    top: 0px;
  }

  /* Bottom Notice Banner */
  .terms-bottom-banner {
    background: #fbf5e7;
    border: 1px solid #dfcfb2;
    border-radius: 6px;
    padding: 16px 24px;
    text-align: center;
    font-size: 13.5px;
    font-style: italic;
    color: #4a3419;
    margin-top: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
  }

  /* Responsive Adjustments */
  @media (max-width: 640px) {
    .terms-page-wrapper {
      padding: 24px 12px 60px;
    }
    .terms-card {
      padding: 16px 16px;
    }
    .terms-page-title {
      font-size: 28px;
    }
    .terms-card-title {
      font-size: 16.5px;
    }
    .terms-card-body {
      font-size: 13.5px;
    }
  }
</style>

<div class="terms-page-wrapper">
  <!-- Header & Breadcrumb -->
  <div class="terms-header-box">
    <div class="terms-breadcrumb">
      <a href="{{ route('home') }}">Beranda</a> &gt; <span>Syarat &amp; Ketentuan</span>
    </div>
    <h1 class="terms-page-title">Syarat &amp; Ketentuan</h1>
  </div>

  <!-- Main Terms Container -->
  <div class="terms-main-container">

    <!-- Expand / Collapse All Toolbar -->
    <div class="terms-toolbar">
      <button type="button" class="btn-terms-action" onclick="toggleAllTerms(true)" title="Buka Semua Penjelasan">
        <span>+</span> <span>Buka Semua</span>
      </button>
      <button type="button" class="btn-terms-action" onclick="toggleAllTerms(false)" title="Tutup Semua Penjelasan">
        <span>−</span> <span>Tutup Semua</span>
      </button>
    </div>

    <!-- Floating Navigation Scroll Dots (Desktop) -->
    <div class="terms-dots-nav">
      <a href="#sec-ketentuan-umum" class="terms-dot active" title="Ketentuan Umum" onclick="setTermsDot(this)"></a>
      <a href="#sec-produk-kualitas" class="terms-dot" title="Produk &amp; Kualitas" onclick="setTermsDot(this)"></a>
      <a href="#sec-pemesanan" class="terms-dot" title="Pemesanan" onclick="setTermsDot(this)"></a>
      <a href="#sec-ketersediaan" class="terms-dot" title="Ketersediaan Produk" onclick="setTermsDot(this)"></a>
      <a href="#sec-custom-corporate" class="terms-dot" title="Custom Order &amp; Corporate" onclick="setTermsDot(this)"></a>
      <a href="#sec-harga-pembayaran" class="terms-dot" title="Harga &amp; Pembayaran" onclick="setTermsDot(this)"></a>
      <a href="#sec-pengiriman" class="terms-dot" title="Pengiriman" onclick="setTermsDot(this)"></a>
      <a href="#sec-tanggung-jawab" class="terms-dot" title="Tanggung Jawab Pengiriman" onclick="setTermsDot(this)"></a>
      <a href="#sec-refund-komplain" class="terms-dot" title="Kebijakan Refund &amp; Komplain" onclick="setTermsDot(this)"></a>
      <a href="#sec-operasional" class="terms-dot" title="Operasional Toko" onclick="setTermsDot(this)"></a>
      <a href="#sec-seasonal" class="terms-dot" title="Seasonal &amp; Special Edition" onclick="setTermsDot(this)"></a>
      <a href="#sec-hki" class="terms-dot" title="Hak Kekayaan Intelektual" onclick="setTermsDot(this)"></a>
      <a href="#sec-persetujuan" class="terms-dot" title="Persetujuan" onclick="setTermsDot(this)"></a>
    </div>

    <!-- 1. Ketentuan Umum -->
    <div class="terms-card" id="sec-ketentuan-umum">
      <div class="terms-card-header" onclick="toggleTermsCard('sec-ketentuan-umum')" role="button" tabindex="0" title="Klik untuk membuka atau menutup penjelasan">
        <span class="terms-toggle-btn" id="icon-sec-ketentuan-umum">−</span>
        <h2 class="terms-card-title">Ketentuan Umum</h2>
      </div>
      <div class="terms-card-body" id="body-sec-ketentuan-umum">
        <p>Dengan mengakses dan/atau bertransaksi di website <strong>{{ config('site.brand') }}</strong> (hampersindonesia.com), Anda menyatakan setuju untuk terikat dengan seluruh Syarat &amp; Ketentuan ini. Syarat &amp; Ketentuan ini dapat diperbarui sewaktu-waktu tanpa pemberitahuan terlebih dahulu, dan perubahan berlaku efektif sejak dipublikasikan di website.</p>
      </div>
    </div>

    <!-- 2. Produk & Kualitas -->
    <div class="terms-card" id="sec-produk-kualitas">
      <div class="terms-card-header" onclick="toggleTermsCard('sec-produk-kualitas')" role="button" tabindex="0" title="Klik untuk membuka atau menutup penjelasan">
        <span class="terms-toggle-btn" id="icon-sec-produk-kualitas">−</span>
        <h2 class="terms-card-title">Produk &amp; Kualitas</h2>
      </div>
      <div class="terms-card-body" id="body-sec-produk-kualitas">
        <ul class="terms-list">
          <li>Seluruh produk kue, pastry, dan hampers kami diproduksi menggunakan bahan berkualitas premium tanpa bahan pengawet berbahaya.</li>
          <li>Bentuk, warna kemasan, hiasan pita, dan ornamen hampers dapat sedikit berbeda dari foto display karena proses pembuatan handmade yang unik dan ketersediaan stok aksesoris musiman.</li>
          <li>Seluruh produk makanan telah bersertifikat <strong>Halal Indonesia</strong> dan memiliki izin edar resmi P-IRT.</li>
        </ul>
      </div>
    </div>

    <!-- 3. Pemesanan -->
    <div class="terms-card" id="sec-pemesanan">
      <div class="terms-card-header" onclick="toggleTermsCard('sec-pemesanan')" role="button" tabindex="0" title="Klik untuk membuka atau menutup penjelasan">
        <span class="terms-toggle-btn" id="icon-sec-pemesanan">−</span>
        <h2 class="terms-card-title">Pemesanan</h2>
      </div>
      <div class="terms-card-body" id="body-sec-pemesanan">
        <p>Pemesanan dapat dilakukan melalui:</p>
        <ul class="terms-list">
          <li>Website resmi: <strong>hampersindonesia.com</strong></li>
          <li>Customer Service resmi (Telepon: {{ config('site.phone') }})</li>
          <li>Marketplace resmi (Tokopedia &amp; Shopee)</li>
        </ul>
        <p>Pesanan dianggap valid dan masuk antrean proses setelah pembayaran terkonfirmasi lunas oleh sistem.</p>
      </div>
    </div>

    <!-- 4. Ketersediaan Produk -->
    <div class="terms-card" id="sec-ketersediaan">
      <div class="terms-card-header" onclick="toggleTermsCard('sec-ketersediaan')" role="button" tabindex="0" title="Klik untuk membuka atau menutup penjelasan">
        <span class="terms-toggle-btn" id="icon-sec-ketersediaan">−</span>
        <h2 class="terms-card-title">Ketersediaan Produk</h2>
      </div>
      <div class="terms-card-body" id="body-sec-ketersediaan">
        <p>Ketersediaan produk harian bersifat first-come, first-served. Khusus pesanan Seasonal Hampers (Idul Fitri, Natal, Imlek) atau pemesanan dalam jumlah besar (Corporate Order), disarankan melakukan Pre-Order lebih awal untuk menjamin ketersediaan slot tanggal pengiriman.</p>
      </div>
    </div>

    <!-- 5. Custom Order & Corporate Order -->
    <div class="terms-card" id="sec-custom-corporate">
      <div class="terms-card-header" onclick="toggleTermsCard('sec-custom-corporate')" role="button" tabindex="0" title="Klik untuk membuka atau menutup penjelasan">
        <span class="terms-toggle-btn" id="icon-sec-custom-corporate">−</span>
        <h2 class="terms-card-title">Custom Order &amp; Corporate Order</h2>
      </div>
      <div class="terms-card-body" id="body-sec-custom-corporate">
        <p>{{ config('site.brand') }} melayani pesanan khusus (Custom Gift Box &amp; Corporate Hampers) untuk kebutuhan personal maupun perusahaan.</p>
        <span class="terms-sub-heading">Ketentuan:</span>
        <ul class="terms-list">
          <li>Minimal order berlaku untuk custom packaging / logo branding perusahaan.</li>
          <li>Desain kartu ucapan eksklusif, pita khusus, dan hangtag dapat disesuaikan dengan identitas brand.</li>
          <li>Pilihan isi hampers dapat disesuaikan dengan preferensi budget, tema acara, maupun brand identity.</li>
        </ul>
      </div>
    </div>

    <!-- 6. Harga & Pembayaran -->
    <div class="terms-card" id="sec-harga-pembayaran">
      <div class="terms-card-header" onclick="toggleTermsCard('sec-harga-pembayaran')" role="button" tabindex="0" title="Klik untuk membuka atau menutup penjelasan">
        <span class="terms-toggle-btn" id="icon-sec-harga-pembayaran">−</span>
        <h2 class="terms-card-title">Harga &amp; Pembayaran</h2>
      </div>
      <div class="terms-card-body" id="body-sec-harga-pembayaran">
        <ul class="terms-list">
          <li>Harga produk sesuai yang tercantum di katalog website saat pemesanan dilakukan.</li>
          <li>Harga dapat berubah sewaktu-waktu tanpa pemberitahuan sebelumnya mengikuti ketersediaan bahan baku premium.</li>
          <li>Metode pembayaran yang didukung meliputi Virtual Account (BCA, Mandiri, BNI, BRI, Permata), QRIS, Transfer Bank Manual, E-Wallet (GoPay, OVO, ShopeePay), dan Kartu Kredit.</li>
          <li>Pesanan baru akan diproses ke dapur dan pengemasan setelah pembayaran berhasil diverifikasi sistem.</li>
        </ul>
      </div>
    </div>

    <!-- 7. Pengiriman -->
    <div class="terms-card" id="sec-pengiriman">
      <div class="terms-card-header" onclick="toggleTermsCard('sec-pengiriman')" role="button" tabindex="0" title="Klik untuk membuka atau menutup penjelasan">
        <span class="terms-toggle-btn" id="icon-sec-pengiriman">−</span>
        <h2 class="terms-card-title">Pengiriman</h2>
      </div>
      <div class="terms-card-body" id="body-sec-pengiriman">
        <p>Pengiriman pesanan menggunakan kurir dengan standar pengemasan aman.</p>
        <span class="terms-sub-heading">Ketentuan estimasi dan pengiriman:</span>
        <ul class="terms-list">
          <li>Pengiriman Instant / Sameday untuk area Jabodetabek.</li>
          <li>Pengiriman Next Day / Regular / Kargo untuk pengiriman luar kota.</li>
        </ul>
        <span class="terms-sub-heading">Standar perlindungan kemasan hantaran:</span>
        <ul class="terms-list">
          <li>Hardbox eksklusif &amp; kokoh</li>
          <li>Lapisan Bubble Wrap tebal berlapis</li>
          <li>Segel pengaman &amp; stiker Fragile</li>
        </ul>
        <p>Biaya pengiriman dihitung otomatis berdasarkan jarak tempuh atau berat volume kemasan.</p>
      </div>
    </div>

    <!-- 8. Tanggung Jawab Pengiriman -->
    <div class="terms-card" id="sec-tanggung-jawab">
      <div class="terms-card-header" onclick="toggleTermsCard('sec-tanggung-jawab')" role="button" tabindex="0" title="Klik untuk membuka atau menutup penjelasan">
        <span class="terms-toggle-btn" id="icon-sec-tanggung-jawab">−</span>
        <h2 class="terms-card-title">Tanggung Jawab Pengiriman</h2>
      </div>
      <div class="terms-card-body" id="body-sec-tanggung-jawab">
        <p>{{ config('site.brand') }} memastikan seluruh pesanan dikemas dengan standar keamanan terbaik dan diserahkan kepada kurir dalam kondisi prima.</p>
        <span class="terms-sub-heading">Hal-hal yang berada di luar kendali kami:</span>
        <ul class="terms-list">
          <li>Keterlambatan pengantaran yang disebabkan oleh kendala operasional ekspedisi / cuaca ekstrem / bencana alam.</li>
          <li>Kerusakan kemasan akibat kelalaian penanganan oleh pihak ekspedisi selama perjalanan.</li>
          <li>Penerima tidak berada di tempat atau nomor kontak penerima tidak dapat dihubungi saat kurir tiba di lokasi.</li>
        </ul>
      </div>
    </div>

    <!-- 9. Kebijakan Refund & Komplain -->
    <div class="terms-card" id="sec-refund-komplain">
      <div class="terms-card-header" onclick="toggleTermsCard('sec-refund-komplain')" role="button" tabindex="0" title="Klik untuk membuka atau menutup penjelasan">
        <span class="terms-toggle-btn" id="icon-sec-refund-komplain">−</span>
        <h2 class="terms-card-title">Kebijakan Refund &amp; Komplain</h2>
      </div>
      <div class="terms-card-body" id="body-sec-refund-komplain">
        <p>Komplain atas kerusakan produk yang murni akibat kelalaian internal kami dapat diajukan maksimal <strong>1x24 jam</strong> sejak paket diterima oleh penerima, dengan wajib menyertakan:</p>
        <ul class="terms-list">
          <li>Video unboxing utuh tanpa jeda/edit sejak paket tersegel dibuka.</li>
          <li>Foto resi pengiriman yang tertera jelas pada paket.</li>
          <li>Foto detail fisik produk yang mengalami kendala.</li>
        </ul>
        <p>Komplain tanpa kelengkapan video unboxing yang valid tidak dapat diproses lebih lanjut.</p>
      </div>
    </div>

    <!-- 10. Operasional Toko -->
    <div class="terms-card" id="sec-operasional">
      <div class="terms-card-header" onclick="toggleTermsCard('sec-operasional')" role="button" tabindex="0" title="Klik untuk membuka atau menutup penjelasan">
        <span class="terms-toggle-btn" id="icon-sec-operasional">−</span>
        <h2 class="terms-card-title">Operasional Toko</h2>
      </div>
      <div class="terms-card-body" id="body-sec-operasional">
        <p>{{ config('site.brand') }} beroperasi setiap hari:</p>
        <ul class="terms-list">
          <li><strong>Jam Operasional:</strong> {{ config('site.hours', '08.00 - 21.00 WIB') }}</li>
          <li><strong>Alamat Toko &amp; Galeri:</strong> {{ config('site.address') }}</li>
          <li><strong>Telepon / Kontak:</strong> {{ config('site.phone') }}</li>
        </ul>
      </div>
    </div>

    <!-- 11. Seasonal & Special Edition -->
    <div class="terms-card" id="sec-seasonal">
      <div class="terms-card-header" onclick="toggleTermsCard('sec-seasonal')" role="button" tabindex="0" title="Klik untuk membuka atau menutup penjelasan">
        <span class="terms-toggle-btn" id="icon-sec-seasonal">−</span>
        <h2 class="terms-card-title">Seasonal &amp; Special Edition</h2>
      </div>
      <div class="terms-card-body" id="body-sec-seasonal">
        <p>Kami merilis edisi koleksi hampers khusus pada setiap momen perayaan besar nasional:</p>
        <ul class="terms-list">
          <li>Hampers Idul Fitri / Lebaran</li>
          <li>Hampers Natal &amp; Tahun Baru</li>
          <li>Hampers Chinese New Year / Imlek</li>
          <li>Hari Guru, Mother's Day, Valentine, dan momen spesial lainnya.</li>
        </ul>
      </div>
    </div>

    <!-- 12. Hak Kekayaan Intelektual -->
    <div class="terms-card" id="sec-hki">
      <div class="terms-card-header" onclick="toggleTermsCard('sec-hki')" role="button" tabindex="0" title="Klik untuk membuka atau menutup penjelasan">
        <span class="terms-toggle-btn" id="icon-sec-hki">−</span>
        <h2 class="terms-card-title">Hak Kekayaan Intelektual</h2>
      </div>
      <div class="terms-card-body" id="body-sec-hki">
        <p>Seluruh foto produk, teks, logo, desain grafis kemasan, konsep hampers, dan materi promosi pada website <strong>{{ config('site.brand') }}</strong> dilindungi oleh undang-undang Hak Cipta dan tidak boleh disalin, direproduksi, atau dipergunakan kembali tanpa izin tertulis dari manajemen.</p>
      </div>
    </div>

    <!-- 13. Persetujuan -->
    <div class="terms-card" id="sec-persetujuan">
      <div class="terms-card-header" onclick="toggleTermsCard('sec-persetujuan')" role="button" tabindex="0" title="Klik untuk membuka atau menutup penjelasan">
        <span class="terms-toggle-btn" id="icon-sec-persetujuan">−</span>
        <h2 class="terms-card-title">Persetujuan</h2>
      </div>
      <div class="terms-card-body" id="body-sec-persetujuan">
        <p>Dengan mengakses atau bertransaksi di website <strong>{{ config('site.brand') }}</strong>, pelanggan dianggap telah membaca, memahami, dan menyetujui seluruh isi Syarat &amp; Ketentuan yang berlaku.</p>
      </div>
    </div>

    <!-- Bottom Notice Banner -->
    <div class="terms-bottom-banner">
      Dengan melakukan pemesanan di <strong>{{ config('site.brand') }}</strong>, pelanggan dianggap telah menyetujui seluruh Syarat &amp; Ketentuan yang berlaku.
    </div>

  </div>
</div>

<script>
  // Accordion Toggle: Klik minus untuk menutup (jadi plus), klik plus untuk membuka penjelasan (jadi minus)
  function toggleTermsCard(cardId) {
    const card = document.getElementById(cardId);
    if (!card) return;
    const isClosed = card.classList.contains('is-closed');
    const icon = document.getElementById('icon-' + cardId);

    if (isClosed) {
      // Buka penjelasan (tampilkan body & ubah icon jadi minus)
      card.classList.remove('is-closed');
      if (icon) icon.textContent = '−';
    } else {
      // Tutup penjelasan (sembunyikan body & ubah icon jadi plus)
      card.classList.add('is-closed');
      if (icon) icon.textContent = '+';
    }
  }

  // Buka Semua / Tutup Semua
  function toggleAllTerms(shouldOpen) {
    document.querySelectorAll('.terms-card').forEach(card => {
      const cardId = card.id;
      const icon = document.getElementById('icon-' + cardId);
      if (shouldOpen) {
        card.classList.remove('is-closed');
        if (icon) icon.textContent = '−';
      } else {
        card.classList.add('is-closed');
        if (icon) icon.textContent = '+';
      }
    });
  }

  function setTermsDot(elem) {
    document.querySelectorAll('.terms-dot').forEach(d => d.classList.remove('active'));
    elem.classList.add('active');
  }

  // Smooth scroll spy for terms dot indicators
  document.addEventListener('scroll', function() {
    const cards = [
      document.getElementById('sec-ketentuan-umum'),
      document.getElementById('sec-produk-kualitas'),
      document.getElementById('sec-pemesanan'),
      document.getElementById('sec-ketersediaan'),
      document.getElementById('sec-custom-corporate'),
      document.getElementById('sec-harga-pembayaran'),
      document.getElementById('sec-pengiriman'),
      document.getElementById('sec-tanggung-jawab'),
      document.getElementById('sec-refund-komplain'),
      document.getElementById('sec-operasional'),
      document.getElementById('sec-seasonal'),
      document.getElementById('sec-hki'),
      document.getElementById('sec-persetujuan')
    ];
    const dots = document.querySelectorAll('.terms-dot');
    if (!cards[0] || dots.length !== cards.length) return;

    const scrollPos = window.scrollY + 200;
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
