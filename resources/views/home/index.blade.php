@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <section class="hero">
        <div class="hero__slider">
            @if (!empty($sliders))
                @foreach ($sliders as $index => $slider)
                    <div class="hero__slide {{ $index === 0 ? 'hero__slide--active' : '' }}">
                        <img src="{{ asset($slider->image) }}"
                            srcset="{{ asset(str_replace('.', '-320w.', $slider->image)) }} 320w,
                                      {{ asset(str_replace('.', '-640w.', $slider->image)) }} 640w,
                                      {{ asset(str_replace('.', '-1024w.', $slider->image)) }} 1024w"
                            sizes="(max-width: 768px) 100vw, 1024px" alt="Slider image" class="hero__image">
                    </div>
                @endforeach
            @else
                <div class="hero__slide hero__slide--active">
                    <img src="{{ asset('images/default-slider.jpg') }}" alt="Welcome to BeautyLatory" class="hero__image">
                </div>
            @endif
        </div>
        <div class="hero__nav">
            @for ($i = 0; $i < count($sliders); $i++)
                <span class="hero__dot {{ $i === 0 ? 'hero__dot--active' : '' }}" data-index="{{ $i }}"></span>
            @endfor
        </div>
    </section>

    <section class="featured-products">
        <div class="container">
            <h2 class="featured-products__title">Featured Products</h2>
            <div class="featured-products__grid">
                @forelse($products->take(8) as $product)
                    <div class="product-card">
                        <a href="{{ route('products.show', $product->id) }}" class="product-card__link">
                            <div class="product-card__image-container">
                                @if (!empty($product->image))
                                    <img src="{{ asset($product->image) }}"
                                        srcset="{{ asset(str_replace('.', '-268w.', $product->image)) }} 268w,
                                              {{ asset(str_replace('.', '-380w.', $product->image)) }} 380w,
                                              {{ asset(str_replace('.', '-512w.', $product->image)) }} 512w"
                                        sizes="(max-width: 768px) 50vw, (max-width: 1024px) 33vw, 25vw"
                                        alt="{{ $product->name }}" class="product-card__image">
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
                    <p class="featured-products__empty">No products available at the moment.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
