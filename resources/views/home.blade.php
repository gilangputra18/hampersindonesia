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
</style>

<p class="banner">{{ config('site.tagline') }}</p>

<a class="feature ph" href="{{ route('category', 'hampers') }}"><img src="{{ asset('images/feature.jpg') }}" alt="" onerror="this.remove()"><b>Belanja Sekarang</b></a>

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

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const videos = document.querySelectorAll('video');
    videos.forEach(v => {
      v.play().catch(e => console.log('Autoplay handled', e));
    });
  });
</script>
@endsection
