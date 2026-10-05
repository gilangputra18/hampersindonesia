@extends('layouts.app')
@section('title', 'Contact')
@section('content')
<h1 class="pt">Contact</h1>
<div class="prose center">
  <p>We look forward to hearing from you! Email <a href="mailto:{{ config('site.email') }}">{{ config('site.email') }}</a> or WhatsApp {{ config('site.wa') }}.</p>
  @if (session('ok')) <p class="ok">{{ session('ok') }}</p> @endif
  <form method="post" action="{{ route('contact.send') }}" class="form">
    @csrf
    <input name="name" placeholder="Name" value="{{ old('name') }}" required>
    <input name="email" type="email" placeholder="E-mail" value="{{ old('email') }}" required>
    <textarea name="message" placeholder="Message" rows="4" required>{{ old('message') }}</textarea>
    @if ($errors->any()) <p class="err">{{ $errors->first() }}</p> @endif
    <button class="btn sand">Send message</button>
  </form>
</div>
@endsection
