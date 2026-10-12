@extends('layouts.app')

@section('title', $item['title'] . ' — Деталі пристрою')

@section('content')
    <a class="back-link" href="{{ route('catalog.index') }}">← До каталогу</a>

    <article class="product">
        <div class="product__visual atlas-frame">
            <img
                class="product__image atlas-frame__image {{ $item['image_class'] ?? 'atlas-frame__image--first' }}"
                src="{{ asset('images/catalog-atlas.png') }}"
                alt="{{ $item['title'] }}"
                width="1774"
                height="887"
                fetchpriority="high"
            />
            <span class="product__code">Артикул: {{ $item['slug'] }}</span>
        </div>

        <div class="product__content">
            <div class="product__status">
                <span></span> {{ $item['status'] ?? 'В наявності' }}
            </div>

            <p class="product__eyebrow">
                {{ $item['category'] }} · {{ $item['operation'] }}
            </p>

            <h1 class="product__title">{{ $item['title'] }}</h1>
            <p class="product__lead">{{ $item['description'] }}</p>

            <strong class="product__price">
                {{ number_format($item['price'], 0, ',', ' ') }} {{ $item['currency'] ?? '₴' }}
            </strong>

            <dl class="attribute-list">
                @if(!empty($item['attributes']))
                    @foreach($item['attributes'] as $attrName => $attrValue)
                        <div class="attribute-list__item">
                            <dt class="attribute-list__name">{{ $attrName }}</dt>
                            <dd class="attribute-list__value">{{ $attrValue }}</dd>
                        </div>
                    @endforeach
                @endif

                @if(!empty($item['specs']) && is_array($item['specs']))
                    @foreach($item['specs'] as $specName => $specValue)
                        <div class="attribute-list__item">
                            <dt class="attribute-list__name">{{ $specName }}</dt>
                            <dd class="attribute-list__value">{{ $specValue }}</dd>
                        </div>
                    @endforeach
                @endif

                <div class="attribute-list__item">
                    <dt class="attribute-list__name">Контактна особа</dt>
                    <dd class="attribute-list__value">{{ $item['contact_name'] }}</dd>
                </div>
                <div class="attribute-list__item">
                    <dt class="attribute-list__name">Телефон</dt>
                    <dd class="attribute-list__value">{{ $item['contact_phone'] }}</dd>
                </div>
                <div class="attribute-list__item">
                    <dt class="attribute-list__name">Email</dt>
                    <dd class="attribute-list__value">{{ $item['contact_email'] }}</dd>
                </div>
                <div class="attribute-list__item">
                    <dt class="attribute-list__name">Опубліковано</dt>
                    <dd class="attribute-list__value">{{ $item['published_at'] }}</dd>
                </div>
            </dl>

            <div class="product__actions" id="contact">
                <a
                    class="button button--cyan"
                    href="mailto:{{ $item['contact_email'] }}?subject={{ urlencode('Замовлення: ' . $item['title']) }}"
                    data-action="request"
                    data-endpoint="/api/requests"
                    data-item="{{ $item['slug'] }}"
                >
                    Домовитися про отримання
                </a>
                <span>Гарантія: {{ $item['attributes']['Гарантія'] ?? '12 місяців' }}</span>
            </div>
        </div>
    </article>
@endsection
