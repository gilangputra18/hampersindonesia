@extends('layouts.app')
@section('title', $cat['title'].' | '.config('site.brand'))
@section('content')
<div class="crumb"><a href="{{ route('home') }}">Home</a> / Shop / {{ $cat['title'] }}</div>
<h1 class="pt">{{ $cat['title'] }}</h1>
<div class="bar">{{ count($cat['items']) }} products</div>
<div class="grid4 listing">
  @foreach ($cat['items'] as $item) @include('partials.card', compact('item', 'slug')) @endforeach
</div>
@endsection
