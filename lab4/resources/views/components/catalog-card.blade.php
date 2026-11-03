@props(['product'])

<article class="catalog-card tech-card {{ $product->is_featured ? 'catalog-card--featured' : '' }}">
    <a class="tech-card__media atlas-frame" href="{{ route('catalog.show', $product->slug) }}">
        <img
            class="catalog-card__image atlas-frame__image {{ $product->image_class }}"
            src="{{ asset($product->image) }}"
            alt="{{ $product->title }}"
            width="1774"
            height="887"
            loading="lazy"
        />
        @if($product->badge)
            <span class="tech-card__label {{ $product->badge_class }}">
                {{ $product->badge }}
            </span>
        @endif
    </a>
    <div class="tech-card__body">
        <div class="tech-card__meta">
            <p class="catalog-card__eyebrow">
                {{ $product->category->name }} · {{ $product->operation }}
            </p>
            <span>{{ $product->status }}</span>
        </div>

        <h2 class="catalog-card__title">{{ $product->title }}</h2>

        <p class="catalog-card__description">
            {{ $product->short_description }}
        </p>

        @if(!empty($product->specs))
            <dl class="tech-card__specs">
                @foreach($product->specs as $specName => $specValue)
                    <div>
                        <dt>{{ $specName }}</dt>
                        <dd>{{ $specValue }}</dd>
                    </div>
                @endforeach
            </dl>
        @endif

        <div class="tech-card__footer">
            <strong class="catalog-card__price">
                {{ number_format((float) $product->price, 0, ',', ' ') }} {{ $product->currency }}
            </strong>
            <a class="text-link" href="{{ route('catalog.show', $product->slug) }}">
                Переглянути →
            </a>
        </div>
    </div>
</article>
