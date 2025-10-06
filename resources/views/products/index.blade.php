@extends('layouts.app')

@section('title', 'All Products')

@section('content')
    <div class="products-page">
        <section class="page-header">
            <div class="container">
                <h1 class="page-header__title">All Products</h1>
            </div>
        </section>

        <!-- Category Filter Section -->
        <section class="category-filter-section">
            <div class="container">
                <div class="category-filter-buttons">
                    <a href="{{ route('products.index') }}"
                        class="category-btn {{ request()->get('category') ? '' : 'active' }}">All Products</a>
                    @foreach ($categories as $category)
                        <a href="{{ route('products.index', ['category' => $category->id]) }}"
                            class="category-btn {{ request()->get('category') == $category->id ? 'active' : '' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="products-grid-section">
            <div class="container">
                <div class="products-grid">
                    @forelse($products as $product)
                        <div class="product-card">
                            <a href="{{ route('products.show', $product->id) }}" class="product-card__link">
                                <div class="product-card__image-container">
                                    @if (!empty($product->image))
                                        <img src="{{ asset($product->image) }}"
                                             loading="lazy"
                                             alt="{{ $product->name }}"
                                             class="product-card__image"
                                             width="380" height="380">
                                    @else
                                        <div class="product-card__no-image">No Image</div>
                                    @endif
                                </div>
                                <div class="product-card__info">
                                    <h3 class="product-card__name">{{ $product->name }}</h3>
                                    <p class="product-card__price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                </div>
                            </a>
                        </div>
                    @empty
                        <p class="products-grid__empty">No products found.</p>
                    @endforelse
                </div>

            </div>
        </section>
    </div>
@endsection
