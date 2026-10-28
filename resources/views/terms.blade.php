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
    margin: 0 auto 36px;
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
    gap: 16px;
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
    padding: 24px 28px;
    box-shadow: 0 3px 12px rgba(80, 50, 10, 0.03);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    position: relative;
  }
  .terms-card:hover {
    box-shadow: 0 6px 18px rgba(80, 50, 10, 0.06);
  }

  /* Title Heading */
  .terms-card-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 19px;
    font-weight: 700;
    color: #241405;
    margin: 0 0 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    letter-spacing: 0.3px;
  }
  .terms-dash-accent {
    color: #9f183c;
    font-weight: 800;
  }

  /* Content Text & Lists */
  .terms-card-body {
    font-size: 14px;
    line-height: 1.7;
    color: #423222;
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
  .terms-list-dash li::before {
    content: "–";
    color: #8c7355;
    font-size: 14px;
    top: 1px;
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
      padding: 18px 18px;
    }
    .terms-page-title {
      font-size: 30px;
    }
    .terms-card-title {
      font-size: 17px;
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
      <a href="{{ route('home') }}">Home</a> &gt; <span>Syarat &amp; Ketentuan</span>
    </div>
    <h1 class="terms-page-title">Syarat &amp; Ketentuan</h1>
  </div>

  <!-- Main Terms Container -->
  <div class="terms-main-container">

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
      <h2 class="terms-card-title"><span class="terms-dash-accent">—</span> Ketentuan Umum</h2>
      <div class="terms-card-body">
        <p>Dengan mengakses dan/atau bertransaksi di website <strong>Pusat Hampers Indonesia</strong> (The Golden Bites / pusathampersindonesia.com), Anda menyatakan setuju untuk terikat dengan seluruh Syarat &amp; Ketentuan ini. Syarat &amp; Ketentuan ini dapat diperbarui sewaktu-waktu tanpa pemberitahuan terlebih dahulu, dan perubahan berlaku efektif sejak dipublikasikan di website.</p>
      </div>
    </div>

    <!-- 2. Produk & Kualitas -->
    <div class="terms-card" id="sec-produk-kualitas">
      <h2 class="terms-card-title"><span class="terms-dash-accent">—</span> Produk &amp; Kualitas</h2>
      <div class="terms-card-body">
        <ul class="terms-list">
          <li>Seluruh produk kue, pastry, dan hampers kami diproduksi menggunakan bahan berkualitas premium tanpa bahan pengawet berbahaya.</li>
          <li>Bentuk, warna kemasan, hiasan pita, dan ornamen hampers dapat sedikit berbeda dari foto display karena proses pembuatan handmade yang unik dan ketersediaan stok aksesoris musiman.</li>
          <li>Seluruh produk makanan telah bersertifikat <strong>Halal Indonesia</strong> dan memiliki izin edar resmi P-IRT.</li>
        </ul>
      </div>
    </div>

    <!-- 3. Pemesanan -->
    <div class="terms-card" id="sec-pemesanan">
      <h2 class="terms-card-title"><span class="terms-dash-accent">—</span> Pemesanan</h2>
      <div class="terms-card-body">
        <p>Pemesanan dapat dilakukan melalui:</p>
        <ul class="terms-list">
          <li>Website resmi: <strong>hampersindonesia.com</strong></li>
          <li>Customer Service resmi (WhatsApp &amp; Telepon)</li>
          <li>Marketplace resmi (Tokopedia &amp; Shopee)</li>
        </ul>
        <p>Pesanan dianggap valid dan masuk antrean proses setelah pembayaran terkonfirmasi lunas oleh sistem.</p>
      </div>
    </div>

    <!-- 4. Ketersediaan Produk -->
    <div class="terms-card" id="sec-ketersediaan">
      <h2 class="terms-card-title"><span class="terms-dash-accent">—</span> Ketersediaan Produk</h2>
      <div class="terms-card-body">
        <p>Ketersediaan produk harian bersifat first-come, first-served. Khusus pesanan Seasonal Hampers (Idul Fitri, Natal, Imlek) atau pemesanan dalam jumlah besar (Corporate Order), disarankan melakukan Pre-Order lebih awal untuk menjamin ketersediaan slot tanggal pengiriman.</p>
      </div>
    </div>

    <!-- 5. Custom Order & Corporate Order -->
    <div class="terms-card" id="sec-custom-corporate">
      <h2 class="terms-card-title"><span class="terms-dash-accent">—</span> Custom Order &amp; Corporate Order</h2>
      <div class="terms-card-body">
        <p>Pusat Hampers Indonesia melayani pesanan khusus (Custom Gift Box &amp; Corporate Hampers) untuk kebutuhan personal maupun perusahaan.</p>
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
      <h2 class="terms-card-title"><span class="terms-dash-accent">—</span> Harga &amp; Pembayaran</h2>
      <div class="terms-card-body">
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
      <h2 class="terms-card-title"><span class="terms-dash-accent">—</span> Pengiriman</h2>
      <div class="terms-card-body">
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
      <h2 class="terms-card-title"><span class="terms-dash-accent">—</span> Tanggung Jawab Pengiriman</h2>
      <div class="terms-card-body">
        <p>Pusat Hampers Indonesia memastikan seluruh pesanan dikemas dengan standar keamanan terbaik dan diserahkan kepada kurir dalam kondisi prima.</p>
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
      <h2 class="terms-card-title"><span class="terms-dash-accent">—</span> Kebijakan Refund &amp; Komplain</h2>
      <div class="terms-card-body">
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
      <h2 class="terms-card-title"><span class="terms-dash-accent">—</span> Operasional Toko</h2>
      <div class="terms-card-body">
        <p>Pusat Hampers Indonesia beroperasi setiap hari:</p>
        <ul class="terms-list">
          <li><strong>Jam Operasional:</strong> 08.00 - 21.00 WIB</li>
          <li><strong>Alamat Toko &amp; Galeri:</strong> {{ config('site.address') }}</li>
          <li><strong>Telepon / Kontak:</strong> {{ config('site.phone') }}</li>
        </ul>
      </div>
    </div>

    <!-- 11. Seasonal & Special Edition -->
    <div class="terms-card" id="sec-seasonal">
      <h2 class="terms-card-title"><span class="terms-dash-accent">—</span> Seasonal &amp; Special Edition</h2>
      <div class="terms-card-body">
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
      <h2 class="terms-card-title"><span class="terms-dash-accent">—</span> Hak Kekayaan Intelektual</h2>
      <div class="terms-card-body">
        <p>Seluruh foto produk, teks, logo, desain grafis kemasan, konsep hampers, dan materi promosi pada website Pusat Hampers Indonesia (The Golden Bites) dilindungi oleh undang-undang Hak Cipta dan tidak boleh disalin, direproduksi, atau dipergunakan kembali tanpa izin tertulis dari manajemen.</p>
      </div>
    </div>

    <!-- 13. Persetujuan -->
    <div class="terms-card" id="sec-persetujuan">
      <h2 class="terms-card-title"><span class="terms-dash-accent">—</span> Persetujuan</h2>
      <div class="terms-card-body">
        <p>Dengan mengakses atau bertransaksi di website Pusat Hampers Indonesia (The Golden Bites), pelanggan dianggap telah membaca, memahami, dan menyetujui seluruh isi Syarat &amp; Ketentuan yang berlaku.</p>
      </div>
    </div>

    <!-- Bottom Notice Banner -->
    <div class="terms-bottom-banner">
      Dengan melakukan pemesanan di Pusat Hampers Indonesia (The Golden Bites), pelanggan dianggap telah menyetujui seluruh Syarat &amp; Ketentuan yang berlaku.
    </div>

  </div>
</div>

<script>
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
