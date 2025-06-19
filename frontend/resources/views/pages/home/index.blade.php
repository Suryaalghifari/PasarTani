@extends('layouts.home.app')

@section('title', 'TaniDirect - Pasar Tani Digital')

@section('content')
    @include('layouts.home.partials.hero')
    @include('layouts.home.partials.categories')
    @include('layouts.home.partials.fresh-banner')
   @include('layouts.home.partials.products', ['products' => $products])
    @include('layouts.home.partials.farmer-banner')
@endsection
