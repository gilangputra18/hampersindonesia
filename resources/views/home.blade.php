@extends('layouts.app')
@section('content')
<section class="hero">
  <video id="hv" autoplay muted loop playsinline poster="{{ asset('images/hero-poster.jpg') }}">
    <source src="https://assets.mixkit.co/videos/preview/mixkit-pastry-chef-making-a-cake-41223-large.mp4" type="video/mp4">
    <source src="https://cdn.coverr.co/videos/coverr-baking-fresh-bread-5764/1080p.mp4" type="video/mp4">
    <source src="{{ config('site.hero_video') }}" type="video/mp4">
  </video>

  <div class="hero-overlay-content">
    <span class="hero-tag">⚜️ PUSAT HAMPERS INDONESIA ⚜️</span>
    <h1 class="hero-headline">Pusat Hampers, Gift Box & Parcel Gourmet Terlengkap</h1>
    <p class="hero-subtext">Dikemas Elegan & Mewah untuk Setiap Momen Spesial Anda</p>
  </div>
</section>

<style>
  @keyframes kenburns-video {
    0% {
      transform: scale(1.0) translate(0, 0);
    }
    50% {
      transform: scale(1.12) translate(-1.5%, -1%);
    }
    100% {
      transform: scale(1.05) translate(1%, 1.5%);
    }
  }

  .hero {
    position: relative;
    background: #0d1713;
    height: min(75vh, 650px);
    overflow: hidden;
  }
  .hero video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    animation: kenburns-video 25s infinite alternate ease-in-out;
  }
  .hero::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(13, 23, 19, 0.85) 0%, rgba(0, 0, 0, 0.25) 50%, rgba(13, 23, 19, 0.6) 100%);
    pointer-events: none;
  }

  .hero-overlay-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 10;
    text-align: center;
    color: #fff;
    width: 90%;
    max-width: 800px;
    pointer-events: none;
  }
  .hero-tag {
    font-size: 13px;
    letter-spacing: 4px;
    color: #f59e0b;
    text-transform: uppercase;
    font-weight: 700;
    display: block;
    margin-bottom: 12px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.9);
  }
  .hero-headline {
    font-family: 'Playfair Display', serif;
    font-size: clamp(26px, 4.5vw, 46px);
    font-weight: 700;
    color: #fff;
    line-height: 1.25;
    margin-bottom: 14px;
    text-shadow: 0 4px 20px rgba(0,0,0,0.95);
  }
  .hero-subtext {
    font-size: clamp(13px, 1.8vw, 16px);
    color: #e2e8f0;
    letter-spacing: 1px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.9);
  }
  /* Auto-sliding Feature Banner Slider Styling */
  .feature-slider-container {
    max-width: 1100px;
    margin: 50px auto;
    aspect-ratio: 16/6;
    border-radius: 14px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.18);
    border: 1px solid rgba(217, 119, 6, 0.3);
    position: relative;
    overflow: hidden;
    background: #0d1713;
  }
  .feature-slider-track {
    display: flex;
    width: 100%;
    height: 100%;
    transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1);
  }
  .feature-slide {
    min-width: 100%;
    height: 100%;
    display: block;
    position: relative;
  }
  .feature-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }
  .feature-slider-btn {
    position: absolute;
    inset: auto 0 35px;
    text-align: center;
    font: 700 16px 'Outfit', var(--serif, serif);
    letter-spacing: .3em;
    text-transform: uppercase;
    color: #0d1713;
    background: rgba(254, 240, 138, 0.95);
    padding: 12px 32px;
    width: fit-content;
    margin: 0 auto;
    border-radius: 6px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.4);
    border: 1px solid #f59e0b;
    transition: all 0.25s ease;
    text-decoration: none;
    z-index: 10;
  }
  .feature-slider-btn:hover {
    background: linear-gradient(135deg, #d97706, #f59e0b);
    color: #fff;
    transform: scale(1.05);
  }
  .feature-slider-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(13, 23, 19, 0.6);
    color: #fef08a;
    border: 1px solid rgba(254, 240, 138, 0.4);
    width: 42px;
    height: 42px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    cursor: pointer;
    z-index: 10;
    transition: all 0.2s ease;
    backdrop-filter: blur(4px);
  }
  .feature-slider-arrow:hover {
    background: rgba(217, 119, 6, 0.9);
    color: #fff;
    transform: translateY(-50%) scale(1.1);
  }
  .feature-arrow-left { left: 16px; }
  .feature-arrow-right { right: 16px; }

  .feature-slider-dots {
    position: absolute;
    bottom: 12px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 8px;
    z-index: 10;
  }
  .feature-slider-dots .dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.4);
    cursor: pointer;
    transition: all 0.25s ease;
  }
  .feature-slider-dots .dot.active {
    background: #f59e0b;
    width: 28px;
    border-radius: 6px;
  }
  @media (max-width: 768px) {
    .feature-slider-container { margin: 20px 12px; aspect-ratio: 16/9; }
    .feature-slider-btn { font-size: 12px; padding: 8px 18px; bottom: 16px; letter-spacing: .2em; }
    .feature-slider-arrow { width: 32px; height: 32px; font-size: 14px; }
    .feature-arrow-left { left: 8px; }
    .feature-arrow-right { right: 8px; }
  }
</style>

<p class="banner">{{ config('site.tagline') }}</p>

<!-- Auto-Sliding 4-Image Feature Banner Carousel -->
<div class="feature-slider-container">
  <div class="feature-slider-track" id="feature-slider-track">
    <!-- Slide 1: Primary Gourmet Hampers -->
    <a class="feature-slide" href="{{ route('category', 'hampers') }}" title="Hampers Gourmet Premium">
      <img src="{{ asset('images/feature.jpg') }}" alt="Pusat Hampers Indonesia - Gourmet Collection">
    </a>
    <!-- Slide 2: Luxury Hampers Box -->
    <a class="feature-slide" href="{{ route('category', 'hampers') }}" title="Koleksi Hampers Mewah Eksklusif">
      <img src="{{ asset('images/cat-hampers.jpg') }}" alt="Pusat Hampers Indonesia - Luxury Box">
    </a>
    <!-- Slide 3: Royal Rose & Emerald Parcel -->
    <a class="feature-slide" href="{{ route('category', 'hampers') }}" title="Hampers Edisi Spesial Festival">
      <img src="{{ asset('images/rose.jpg') }}" alt="Pusat Hampers Indonesia - Royal Rose">
    </a>
    <!-- Slide 4: Festive Celebration Gift Set -->
    <a class="feature-slide" href="{{ route('category', 'hampers') }}" title="Pilihan Hadiah Acara & Selebrasi">
      <img src="{{ asset('images/events.jpg') }}" alt="Pusat Hampers Indonesia - Celebration Set">
    </a>
  </div>

  <!-- Overlaid Button in Center -->
  <a href="{{ route('category', 'hampers') }}" class="feature-slider-btn">
    BELANJA SEKARANG
  </a>

  <!-- Navigation Arrows -->
  <button type="button" class="feature-slider-arrow feature-arrow-left" id="feature-prev-btn" title="Gambar Sebelumnya">❮</button>
  <button type="button" class="feature-slider-arrow feature-arrow-right" id="feature-next-btn" title="Gambar Selanjutnya">❯</button>

  <!-- Indicator Dots -->
  <div class="feature-slider-dots" id="feature-slider-dots">
    <span class="dot active" data-index="0"></span>
    <span class="dot" data-index="1"></span>
    <span class="dot" data-index="2"></span>
    <span class="dot" data-index="3"></span>
  </div>
</div>

<section class="band">
  <h2>Hadiah Sempurna untuk Setiap Momen Spesial</h2>
  <div class="grid4">
    @foreach ($gifts as $g)
      <a class="tile" href="{{ route('category', $g) }}"><span class="ph"><img src="{{ asset('images/cat-'.$g.'.jpg') }}" alt="" onerror="this.remove()"></span><span class="n">{{ config("site.categories.$g.title") }}</span></a>
    @endforeach
  </div>
</section>

<section class="sec">
  <h2>Saatnya Menikmati Hidangan</h2>
  <p class="lead">Rayakan setiap momen dengan sajian lezat yang tak tergoyahkan, sempurna untuk berbagi dan dinikmati bersama.</p>
  <div class="grid5">
    @foreach ($treats as $t) @include('partials.card', ['item' => $t, 'slug' => 'light-bites', 'from' => true]) @endforeach
  </div>
  <a class="btn" href="{{ route('category', 'light-bites') }}">Lihat Semua Hidangan Ringan</a>
</section>

<section class="split">
  <div class="ph" style="aspect-ratio: 16/10; position: relative; overflow: hidden; background: #0d1713 url('{{ asset('images/reservation-dinein.jpg') }}') center/cover no-repeat;">
    <video autoplay muted loop playsinline poster="{{ asset('images/reservation-dinein.jpg') }}" style="width: 100%; height: 100%; object-fit: cover; animation: kenburns-video 20s infinite alternate ease-in-out;">
      <source src="https://assets.mixkit.co/videos/preview/mixkit-hands-of-a-baker-shaping-the-dough-41222-large.mp4" type="video/mp4">
      <source src="https://cdn.coverr.co/videos/coverr-chef-decorating-a-cake-4573/1080p.mp4" type="video/mp4">
      <img src="{{ asset('images/reservation-dinein.jpg') }}" alt="Private Events" style="width: 100%; height: 100%; object-fit: cover;">
    </video>
  </div>
  <div class="navy">
    <h2>Apakah Anda mencari tempat untuk merayakan acara pribadi Anda?</h2>
    <p><b>Kami siap melayani Anda!</b></p>
    <p>Berkumpullah bersama orang-orang tercinta di ruangan mewah yang dirancang khusus untuk keluarga, kerabat, dan acara penting.</p>
    <a class="btn ghost" href="{{ route('reservations') }}">Pesan Tempat Sekarang</a>
  </div>
</section>

<section class="sec grey">
  <p class="lead">Jelajahi produk-produk yang paling diminati di toko kami</p>
  <h2>Produk Terlaris</h2>
  <div class="grid5">
    @foreach ($best as $b) @include('partials.card', ['item' => $b, 'slug' => 'cakes', 'from' => true]) @endforeach
  </div>
  <a class="btn dark" href="{{ route('category', 'cakes') }}">Lihat Semua Produk</a>
</section>

@if(isset($reviews) && count($reviews) > 0)
<section class="sec" style="background: #0d1713; color: #fff; padding: 60px 20px;">
  <p class="lead" style="color: #d97706; text-transform: uppercase; letter-spacing: 3px; font-size: 12px; font-weight: 700;">⚜️ Ulasan & Pengalaman Pelanggan VIP ⚜️</p>
  <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 36px; color: #fef08a; margin-bottom: 30px;">Kata Mereka Tentang Pusat Hampers Indonesia</h2>

  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; max-width: 1200px; margin: 0 auto; text-align: left;">
    @foreach($reviews as $rev)
      <div style="background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(217, 119, 6, 0.25); border-radius: 12px; padding: 24px; position: relative;">
        <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 14px;">
          <img src="{{ $rev->avatar_url }}" alt="{{ $rev->reviewer_name }}" style="width: 52px; height: 52px; border-radius: 50%; object-fit: cover; border: 2px solid #d97706; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
          <div>
            <div style="font-size: 15px; font-weight: 700; color: #fff;">{{ $rev->reviewer_name }}</div>
            <div style="color: #f59e0b; font-size: 14px; margin-top: 2px;">{{ $rev->stars }}</div>
          </div>
        </div>

        @if($rev->title)
          <div style="font-size: 15px; font-weight: 700; color: #fef08a; margin-bottom: 8px;">"{{ $rev->title }}"</div>
        @endif

        <p style="font-size: 13.5px; color: #cad8d1; line-height: 1.65; margin: 0; font-family: 'Outfit', sans-serif;">
          {{ $rev->body }}
        </p>

        @if($rev->product)
          <div style="margin-top: 14px; padding-top: 10px; border-top: 1px dashed rgba(255,255,255,0.1); font-size: 11.5px; color: #8fa59b;">
            📦 Produk: <strong style="color: #f59e0b;">{{ $rev->product->name }}</strong>
          </div>
        @endif
      </div>
    @endforeach
  </div>
</section>
@endif

<!-- FAQ Section (Hal Yang Sering Ditanyakan) -->
<section style="background: #f8faf9; padding: 70px 20px; color: #0f172a; font-family: 'Outfit', sans-serif;">
  <div style="max-width: 1100px; margin: 0 auto; text-align: center;">
    <div style="text-align: center; margin-bottom: 40px; width: 100%;">
      <h2 style="font-family: 'Outfit', sans-serif; font-size: clamp(24px, 3.5vw, 32px); font-weight: 800; color: #0f172a; text-align: center; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 auto; display: inline-block; position: relative;">
        HAL YANG SERING DITANYAKAN
        <span style="display: block; width: 60px; height: 3px; background: linear-gradient(90deg, #d97706, #f59e0b); margin: 12px auto 0 auto; border-radius: 2px;"></span>
      </h2>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px;">
      {{-- FAQ Item 1 --}}
      <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); text-align: left;">
        <h3 style="font-size: 15.5px; font-weight: 700; color: #0f172a; margin: 0 0 10px 0; line-height: 1.4;">
          Apa saja yang ada dalam hampers?
        </h3>
        <p style="font-size: 13.5px; color: #475569; line-height: 1.6; margin: 0;">
          Setiap hampers berisi pilihan produk premium eksklusif, seperti cookies gourmet, artisan treats, sajadah/merchandise, parfum mewah, dan item pilihan berkualitas tinggi lainnya. Untuk melihat secara detail, Anda bisa menekan tombol “Lihat Rincian Isi Hampers” atau “More Details”.
        </p>
      </div>

      {{-- FAQ Item 2 --}}
      <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); text-align: left;">
        <h3 style="font-size: 15.5px; font-weight: 700; color: #0f172a; margin: 0 0 10px 0; line-height: 1.4;">
          Apakah bisa custom isi hampers?
        </h3>
        <p style="font-size: 13.5px; color: #475569; line-height: 1.6; margin: 0;">
          Tentu! Kami menyediakan opsi custom hampers sesuai dengan kebutuhan dan budget Anda. Hubungi WhatsApp pada website untuk konsultasi lebih lanjut.
        </p>
      </div>

      {{-- FAQ Item 3 --}}
      <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); text-align: left;">
        <h3 style="font-size: 15.5px; font-weight: 700; color: #0f172a; margin: 0 0 10px 0; line-height: 1.4;">
          Berapa lama proses pengiriman?
        </h3>
        <p style="font-size: 13.5px; color: #475569; line-height: 1.6; margin: 0;">
          Pengiriman dalam kota biasanya memakan waktu 1–3 hari kerja, sedangkan luar kota mengikuti estimasi ekspedisi yang dipilih.
        </p>
      </div>

      {{-- FAQ Item 4 --}}
      <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); text-align: left;">
        <h3 style="font-size: 15.5px; font-weight: 700; color: #0f172a; margin: 0 0 10px 0; line-height: 1.4;">
          Apakah bisa pre-order untuk hampers?
        </h3>
        <p style="font-size: 13.5px; color: #475569; line-height: 1.6; margin: 0;">
          Tentu! Dengan melakukan pre-order, Anda dapat memastikan ketersediaan hampers terbaik sekaligus memilih jadwal pengiriman sesuai keinginan Anda.
        </p>
      </div>

      {{-- FAQ Item 5 --}}
      <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); text-align: left;">
        <h3 style="font-size: 15.5px; font-weight: 700; color: #0f172a; margin: 0 0 10px 0; line-height: 1.4;">
          Apakah tersedia kartu ucapan dalam hampers?
        </h3>
        <p style="font-size: 13.5px; color: #475569; line-height: 1.6; margin: 0;">
          Ya! Kami menyediakan kartu ucapan gratis yang bisa disesuaikan dengan permintaan Anda.
        </p>
      </div>

      {{-- FAQ Item 6 --}}
      <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); text-align: left;">
        <h3 style="font-size: 15.5px; font-weight: 700; color: #0f172a; margin: 0 0 10px 0; line-height: 1.4;">
          Bagaimana cara pemesanan?
        </h3>
        <p style="font-size: 13.5px; color: #475569; line-height: 1.6; margin: 0;">
          Anda bisa memesan langsung melalui WhatsApp pada website dan kami akan siap membantu Anda.
        </p>
      </div>

      {{-- FAQ Item 7 --}}
      <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); text-align: left;">
        <h3 style="font-size: 15.5px; font-weight: 700; color: #0f172a; margin: 0 0 10px 0; line-height: 1.4;">
          Apakah ada minimal pemesanan untuk hampers corporate?
        </h3>
        <p style="font-size: 13.5px; color: #475569; line-height: 1.6; margin: 0;">
          Pesan hampers corporate tanpa batasan jumlah. Hubungi kami untuk detail dan penawaran spesial.
        </p>
      </div>

      {{-- FAQ Item 8 --}}
      <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); text-align: left;">
        <h3 style="font-size: 15.5px; font-weight: 700; color: #0f172a; margin: 0 0 10px 0; line-height: 1.4;">
          Bisakah pengiriman langsung ke alamat penerima?
        </h3>
        <p style="font-size: 13.5px; color: #475569; line-height: 1.6; margin: 0;">
          Tentu! Kami bisa mengirimkan hampers langsung ke alamat penerima dengan pengemasan aman dan rapi.
        </p>
      </div>
    </div>
  </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const videos = document.querySelectorAll('video');
    videos.forEach(v => {
      v.play().catch(e => console.log('Autoplay handled', e));
    });

    // Auto-sliding 4-Image Feature Banner Carousel
    const track = document.getElementById('feature-slider-track');
    const slides = document.querySelectorAll('.feature-slide');
    const dots = document.querySelectorAll('.feature-slider-dots .dot');
    const prevBtn = document.getElementById('feature-prev-btn');
    const nextBtn = document.getElementById('feature-next-btn');

    if (track && slides.length > 0) {
      let currentIndex = 0;
      const totalSlides = slides.length;
      let slideTimer = null;

      function goToSlide(index) {
        currentIndex = (index + totalSlides) % totalSlides;
        track.style.transform = `translateX(-${currentIndex * 100}%)`;

        dots.forEach((dot, idx) => {
          if (idx === currentIndex) {
            dot.classList.add('active');
          } else {
            dot.classList.remove('active');
          }
        });
      }

      function nextSlide() {
        goToSlide(currentIndex + 1);
      }

      function prevSlide() {
        goToSlide(currentIndex - 1);
      }

      function startTimer() {
        stopTimer();
        slideTimer = setInterval(nextSlide, 3500); // Shift right every 3.5s
      }

      function stopTimer() {
        if (slideTimer) clearInterval(slideTimer);
      }

      if (nextBtn) {
        nextBtn.addEventListener('click', function (e) {
          e.preventDefault();
          nextSlide();
          startTimer();
        });
      }

      if (prevBtn) {
        prevBtn.addEventListener('click', function (e) {
          e.preventDefault();
          prevSlide();
          startTimer();
        });
      }

      dots.forEach(dot => {
        dot.addEventListener('click', function () {
          const idx = parseInt(this.getAttribute('data-index'));
          goToSlide(idx);
          startTimer();
        });
      });

      const container = document.querySelector('.feature-slider-container');
      if (container) {
        container.addEventListener('mouseenter', stopTimer);
        container.addEventListener('mouseleave', startTimer);
      }

      startTimer();
    }
  });
</script>
@endsection
