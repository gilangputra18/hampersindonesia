@extends('layouts.app')

@section('title', 'Reservations | ' . config('site.brand'))

@section('content')
<style>
  .reservations-page {
    background-color: #f8faf9;
    padding: 60px 20px 100px 20px;
    color: #122019;
    font-family: 'Outfit', sans-serif;
  }
  .reservations-container {
    max-width: 960px;
    margin: 0 auto;
  }
  .res-header-title {
    text-align: center;
    margin-bottom: 60px;
  }
  .res-header-title h1 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 40px;
    letter-spacing: 5px;
    font-weight: 700;
    text-transform: uppercase;
    color: #122019;
    margin-bottom: 8px;
  }
  .res-header-title h1::after {
    content: '';
    display: block;
    width: 50px;
    height: 2px;
    background: linear-gradient(90deg, #d97706, transparent);
    margin: 10px auto 0 auto;
  }
  .res-header-title p {
    font-size: 12px;
    letter-spacing: 3.5px;
    text-transform: uppercase;
    color: #854d0e;
    font-weight: 700;
  }

  .res-section-card {
    margin-bottom: 80px;
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
  }
  .res-img-wrapper {
    width: 100%;
    aspect-ratio: 16/9;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 28px;
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(217, 119, 6, 0.2);
  }
  .res-img-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.4s ease;
  }
  .res-img-wrapper:hover img {
    transform: scale(1.04);
  }

  .res-title-sub {
    font-family: 'Cormorant Garamond', serif;
    font-size: 26px;
    letter-spacing: 2.5px;
    font-weight: 700;
    text-transform: uppercase;
    color: #122019;
    margin-bottom: 6px;
  }
  .res-topic-title {
    font-size: 11.5px;
    letter-spacing: 3px;
    text-transform: uppercase;
    font-weight: 700;
    color: #b45309;
    margin-bottom: 18px;
  }
  .res-description {
    font-size: 14px;
    line-height: 1.85;
    color: #4a5c53;
    margin-bottom: 28px;
  }
  .res-description ul {
    margin-left: 20px;
    margin-top: 10px;
  }
  .res-btn-group {
    display: flex;
    gap: 18px;
  }
  .res-btn {
    padding: 14px 32px;
    font-size: 11px;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    font-weight: 700;
    cursor: pointer;
    border-radius: 6px;
    text-decoration: none;
    display: inline-block;
    transition: all 0.25s ease;
  }
  .res-btn-outline {
    background: transparent;
    border: 1px solid #122019;
    color: #122019;
  }
  .res-btn-outline:hover {
    border-color: #d97706;
    background: #122019;
    color: #fef08a;
  }
  .res-btn-sand {
    background: linear-gradient(135deg, #b45309 0%, #d97706 100%);
    border: none;
    color: #fff;
    box-shadow: 0 4px 15px rgba(180,83,9,0.35);
  }
  .res-btn-sand:hover {
    background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
    color: #0d1713;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(245,158,11,0.45);
  }
</style>

<div class="reservations-page">
  <div class="reservations-container">
    <div class="res-header-title">
      <h1>RESERVASI & ACARA PRIVAT</h1>
      <p>RESERVASI TOKO & KATERING ACARA</p>
    </div>

    @foreach($reservations as $res)
      <div class="res-section-card">
        <div class="res-img-wrapper">
          <img src="{{ $res->image_url }}" alt="{{ $res->title }}">
        </div>
        <h2 class="res-title-sub">{{ $res->title }}</h2>
        @if($res->subtitle)
          <div class="res-topic-title">{{ $res->subtitle }}</div>
        @endif
        <div class="res-description">
          {!! $res->description !!}
        </div>
        <div class="res-btn-group">
          <a href="{{ route('menu.pdf', $res->slug) }}" target="_blank" class="res-btn res-btn-outline">LIHAT MENU PDF</a>
          <a href="https://wa.me/{{ $res->whatsapp_number }}?text={{ urlencode($res->whatsapp_text ?? 'Halo PUSAT HAMPERS INDONESIA, saya ingin pemesanan ' . $res->title) }}" target="_blank" class="res-btn res-btn-sand">PESAN HAMPERS SEKARANG</a>
        </div>
      </div>
    @endforeach
  </div>
</div>
@endsection
