@extends('layouts.evente')

@section('title', config('event.titles.main'))

@section('content')

@include('partials.evente.hero')

@include('partials.evente.about')

@include('partials.evente.stats')

@include('partials.evente.schedule')

@include('partials.evente.countdown')

@include('partials.evente.categories')

@include('partials.evente.partners')

@include('partials.evente.text-slider')

@endsection