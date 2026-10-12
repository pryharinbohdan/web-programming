@props(['item'])

<article class="catalog-card tech-card {{ !empty($item['featured']) ? 'catalog-card--featured' : '' }}">
    <a class="tech-card__media atlas-frame" href="{{ route('catalog.show', $item['slug']) }}">
        <img
            class="catalog-card__image atlas-frame__image {{ $item['image_class'] ?? 'atlas-frame__image--first' }}"
            src="{{ asset('images/catalog-atlas.png') }}"
            alt="{{ $item['title'] }}"
            width="1774"
            height="887"
            loading="lazy"
        />
        @if(!empty($item['badge']))
            <span class="tech-card__label {{ $item['badge_class'] ?? '' }}">
                {{ $item['badge'] }}
            </span>
        @endif
    </a>
    <div class="tech-card__body">
        <div class="tech-card__meta">
            <p class="catalog-card__eyebrow">
                {{ $item['category'] }} · {{ $item['operation'] }}
            </p>
            <span>{{ $item['status'] }}</span>
        </div>
        
        <h2 class="catalog-card__title">{{ $item['title'] }}</h2>
        
        <p class="catalog-card__description">
            {{ $item['short_description'] ?? $item['description'] }}
        </p>

        @if(!empty($item['specs']) && is_array($item['specs']))
            <dl class="tech-card__specs">
                @foreach($item['specs'] as $specName => $specValue)
                    <div>
                        <dt>{{ $specName }}</dt>
                        <dd>{{ $specValue }}</dd>
                    </div>
                @endforeach
            </dl>
        @endif

        <div class="tech-card__footer">
            <strong class="catalog-card__price">
                {{ number_format($item['price'], 0, ',', ' ') }} {{ $item['currency'] ?? '₴' }}
            </strong>
            <a class="text-link" href="{{ route('catalog.show', $item['slug']) }}">
                Переглянути →
            </a>
        </div>
    </div>
</article>
