<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', config('site.brand'))</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Work+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/site.css') }}">
</head>
<body>
<div class="top">
  <nav>
    <a href="{{ route('reservations') }}">Reservations</a>
    <a href="{{ route('contact') }}">Contact</a>
    <a href="{{ route('track') }}">Track your order</a>
  </nav>
</div>
<header class="head">
  <a href="{{ route('home') }}" class="logo">{{ config('site.brand') }}<small>{{ config('site.since') }}</small></a>
  <nav class="main">
    @foreach (config('site.categories') as $slug => $c)
      <a href="{{ route('category', $slug) }}">{{ $c['title'] }}</a>
    @endforeach
    <a href="{{ route('reservations') }}">Restaurant Menu</a>
  </nav>
</header>
<main>@yield('content')</main>
<footer class="foot">
  <div>
    <h4>Reservations</h4>
    <p>A dining hall, cake shop, and bakery under one roof. For reservation inquiries, call {{ config('site.phone') }} or WhatsApp {{ config('site.wa') }}.</p>
  </div>
  <div>
    <h4>Find us</h4>
    <p>{{ config('site.address') }}<br>P {{ config('site.phone') }}<br>WA {{ config('site.wa') }}</p>
    <p><a href="mailto:{{ config('site.email') }}">{{ config('site.email') }}</a></p>
    <p><b>Opening hours</b><br>{{ config('site.hours') }}</p>
  </div>
  <div>
    <h4>Info</h4>
    <p><a href="{{ route('contact') }}">Contact</a></p>
  </div>
</footer>
<p class="copy">© {{ date('Y') }} {{ config('site.brand') }}</p>
<script src="{{ asset('js/site.js') }}" defer></script>
</body>
</html>
