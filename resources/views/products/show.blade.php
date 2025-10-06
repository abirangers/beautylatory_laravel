@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="product-detail-page">
        <div class="container">
            <div class="product-detail-container">
                {{-- Product Image Column --}}
                <div class="product-detail__image-wrapper">
                    @if (!empty($product->image))
                        <img src="{{ asset($product->image) }}"
                             loading="lazy"
                             alt="{{ $product->name }}" class="product-detail__image">
                    @else
                        <div class="product-detail__no-image">
                            <span>No Image Available</span>
                        </div>
                    @endif
                </div>

                {{-- Product Info Column --}}
                <div class="product-detail__info">
                    <h1 class="product-detail__name">{{ $product->name }}</h1>
                    <p class="product-detail__price">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                    <div class="product-detail__description">
                        <h3 class="description-title">Product Description</h3>
                        <p>{{ $product->description }}</p>
                    </div>
                    <div class="product-detail__actions">
                        <a href="{{ $product->lynk_id_link }}" class="btn btn--primary" target="_blank"
                            rel="noopener noreferrer">
                            Buy Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
