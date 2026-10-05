@extends('layouts.app')
@section('content')
<section class="hero">
  <video id="hv" autoplay muted loop playsinline poster="{{ asset('images/hero-poster.jpg') }}">
    <source src="{{ config('site.hero_video') }}" type="video/mp4">
  </video>
  <button id="mute" type="button" aria-pressed="false">Sound off</button>
</section>
<p class="banner">{{ config('site.tagline') }}</p>

<a class="feature ph" href="{{ route('category', 'hampers') }}"><img src="{{ asset('images/feature.jpg') }}" alt="" onerror="this.remove()"><b>Shop now</b></a>

<section class="band">
  <h2>The perfect gift for every occasion</h2>
  <div class="grid4">
    @foreach ($gifts as $g)
      <a class="tile" href="{{ route('category', $g) }}"><span class="ph"><img src="{{ asset('images/cat-'.$g.'.jpg') }}" alt="" onerror="this.remove()"></span><span class="n">{{ config("site.categories.$g.title") }}</span></a>
    @endforeach
  </div>
</section>

<section class="sec">
  <h2>Time for a treat</h2>
  <p class="lead">Celebrate every moment with irresistible bites, perfect for sharing, snacking, or simply indulging.</p>
  <div class="grid5">
    @foreach ($treats as $t) @include('partials.card', ['item' => $t, 'slug' => 'light-bites', 'from' => true]) @endforeach
  </div>
  <a class="btn" href="{{ route('category', 'light-bites') }}">Shop food hall</a>
</section>

<section class="split">
  <div class="ph"><img src="{{ asset('images/events.jpg') }}" alt="" onerror="this.remove()"></div>
  <div class="navy">
    <h2>Are you looking for a place to celebrate your private events?</h2>
    <p><b>We are ready to serve you!</b></p>
    <p>Gather with your loved ones in a space made for family, friends, and every special occasion.</p>
    <a class="btn ghost" href="{{ route('reservations') }}">Book now</a>
  </div>
</section>

<section class="sec grey">
  <p class="lead">Explore the most loved items in our store</p>
  <h2>Best sellers</h2>
  <div class="grid5">
    @foreach ($best as $b) @include('partials.card', ['item' => $b, 'slug' => 'cakes', 'from' => true]) @endforeach
  </div>
  <a class="btn dark" href="{{ route('category', 'cakes') }}">View all</a>
</section>
@endsection
