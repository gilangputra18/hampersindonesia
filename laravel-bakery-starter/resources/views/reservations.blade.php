@extends('layouts.app')
@section('title', 'Reservations')
@section('content')
<h1 class="pt">Reservations</h1>
<div class="prose">
  <h3>Dine in &amp; group bookings</h3>
  <p><b>Opening hours:</b> {{ config('site.hours') }}</p>
  <ul>
    <li>No outside food or drinks are permitted.</li>
    <li>Smoking is permitted in designated areas only.</li>
    <li>For parties of 8 or more, we recommend making a reservation.</li>
  </ul>
  <a class="btn" href="https://wa.me/{{ preg_replace('/\D/', '', config('site.wa')) }}">Book now</a>

  <h3>Outside catering</h3>
  <p>Buffet style menus, or work with our team to customize a menu.</p>
  <ul><li>Custom menu planning</li><li>Professional on-site staff</li><li>Seamless setup and cleanup</li></ul>
  <a class="btn" href="https://wa.me/{{ preg_replace('/\D/', '', config('site.wa')) }}">Book now</a>
</div>
@endsection
