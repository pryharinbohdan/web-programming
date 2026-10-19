@extends('layouts.app')

@section('title', $product->title . ' — Деталі пристрою')

@section('content')
    <a class="back-link" href="{{ route('catalog.index') }}">← До каталогу</a>

    <article class="product">
        <div class="product__visual atlas-frame">
            <img
                class="product__image atlas-frame__image {{ $product->image_class }}"
                src="{{ asset($product->image) }}"
                alt="{{ $product->title }}"
                width="1774"
                height="887"
                fetchpriority="high"
            />
            <span class="product__code">Артикул: {{ $product->slug }}</span>
        </div>

        <div class="product__content">
            <div class="product__status">
                <span></span> {{ $product->status }}
            </div>

            <p class="product__eyebrow">
                {{ $product->category->name }} · {{ $product->operation }}
            </p>

            <h1 class="product__title">{{ $product->title }}</h1>
            <p class="product__lead">{{ $product->description }}</p>

            <strong class="product__price">
                {{ number_format((float) $product->price, 0, ',', ' ') }} {{ $product->currency }}
            </strong>

            <dl class="attribute-list">
                @foreach($product->characteristics as $attrName => $attrValue)
                    <div class="attribute-list__item">
                        <dt class="attribute-list__name">{{ $attrName }}</dt>
                        <dd class="attribute-list__value">{{ $attrValue }}</dd>
                    </div>
                @endforeach

                <div class="attribute-list__item">
                    <dt class="attribute-list__name">Контактна особа</dt>
                    <dd class="attribute-list__value">{{ $product->contact_name }}</dd>
                </div>
                <div class="attribute-list__item">
                    <dt class="attribute-list__name">Телефон</dt>
                    <dd class="attribute-list__value">{{ $product->contact_phone }}</dd>
                </div>
                <div class="attribute-list__item">
                    <dt class="attribute-list__name">Email</dt>
                    <dd class="attribute-list__value">{{ $product->contact_email }}</dd>
                </div>
                <div class="attribute-list__item">
                    <dt class="attribute-list__name">Опубліковано</dt>
                    <dd class="attribute-list__value">{{ $product->published_at->format('Y-m-d') }}</dd>
                </div>
            </dl>

            <div class="product__actions" id="contact">
                <a
                    class="button button--cyan"
                    href="mailto:{{ $product->contact_email }}?subject={{ urlencode('Замовлення: ' . $product->title) }}"
                    data-action="request"
                    data-endpoint="/api/requests"
                    data-item="{{ $product->slug }}"
                >
                    Домовитися про отримання
                </a>
                <span>Гарантія: {{ $product->characteristics['Гарантія'] ?? '12 місяців' }}</span>
            </div>
        </div>
    </article>
@endsection
