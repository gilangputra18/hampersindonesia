@extends('layouts.app')
@section('title', 'Track your order')
@section('content')
<h1 class="pt">Check your order</h1>
<form method="get" class="form box">
  <h2>Order details</h2>
  <label for="inv">Invoice no.</label>
  <input id="inv" name="invoice" placeholder="example: INV-2309200021" value="{{ $invoice }}" required>
  <button class="btn dark">Check my order</button>
  @if ($invoice) <p class="err">No order found for {{ $invoice }}. (Sambungkan ke database di SiteController@track.)</p> @endif
</form>
@endsection
