<!-- resources/views/home.blade.php -->
@extends('layouts.main')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<section class="hero bg-primary text-white text-center py-5">
    <div class="hero-content container">
        <h1 class="display-4">Welcome to Our E-Commerce Site</h1>
        <p class="lead">Discover the finest products tailored just for you</p>
        <a href="{{ url('/shop') }}" class="btn btn-light btn-lg">Shop Now</a>
    </div>
</section>

<!-- Latest Products Section -->
<section class="latest-products py-5">
    <div class="container">
        <h2 class="text-center mb-4">Latest Products</h2>
        <div class="row">
            @foreach($latestProducts as $product)
            <div class="col-md-4 mb-4">
                <div class="card product-card border-0 shadow-sm">
                    <img src="{{ asset('storage/images/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height:250px">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text text-muted">{{ $product->description }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-primary">{{ number_format($product->price, 2) }} MAD</h6>
                            <a href="{{ url('/product/' . $product->id) }}" class="btn btn-primary">View Product</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Optional: Add Testimonials or Featured Categories -->
<section class="testimonials py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">What Our Customers Say</h2>
        <div class="row">
            <!-- Add testimonials here -->
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
    .hero {
        background: url('{{ asset('images/hero-bg.jpg') }}') no-repeat center center;
        background-size: cover;
    }
    .hero-content {
        padding: 100px 0;
    }
    .product-card {
        transition: transform 0.3s ease-in-out;
    }
    .product-card:hover {
        transform: scale(1.05);
    }
</style>
@endpush
