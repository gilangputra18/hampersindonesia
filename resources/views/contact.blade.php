@extends('layouts.app')
@section('title', 'Kontak & Concierge')
@section('content')
<h1 class="pt">Kontak & Concierge</h1>
<div class="prose center">
  <p>Kami siap membantu Anda! Email <a href="mailto:{{ config('site.email') }}">{{ config('site.email') }}</a> atau WhatsApp {{ config('site.wa') }}.</p>
  @if (session('ok')) <p class="ok">{{ session('ok') }}</p> @endif
  <form method="post" action="{{ route('contact.send') }}" class="form">
    @csrf
    <input name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
    <input name="email" type="email" placeholder="Alamat Email" value="{{ old('email') }}" required>
    <textarea name="message" placeholder="Tuliskan pesan Anda..." rows="4" required>{{ old('message') }}</textarea>
    @if ($errors->any()) <p class="err">{{ $errors->first() }}</p> @endif
    <button class="btn sand">Kirim Pesan</button>
  </form>
</div>
@endsection
