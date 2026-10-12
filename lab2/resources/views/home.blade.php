@extends('layouts.app')

@section('title', 'Головна — Комп’ютерна техніка')

@section('content')
    <section class="tech-hero">
        <div class="tech-hero__copy">
            <p class="eyebrow">
                <span aria-hidden="true"></span> Техніка для роботи та гри
            </p>
            <h1 class="tech-hero__title">
                Зберіть свій <strong>ідеальний сетап.</strong>
            </h1>
            <p class="tech-hero__lead">
                Обирайте ноутбуки, монітори та комплектуючі з гарантією від 3 до 24 місяців.
            </p>
            <div class="tech-hero__actions">
                <a class="button button--cyan" href="{{ route('catalog.index') }}">Перейти до каталогу</a>
                <a class="text-link" href="mailto:author@example.com">Отримати консультацію →</a>
            </div>
            <div class="tech-hero__metrics">
                <p><strong>12</strong><span>моделей</span></p>
                <p><strong>24 міс.</strong><span>гарантії</span></p>
                <p><strong>1 день</strong><span>на відправлення</span></p>
            </div>
        </div>

        <form class="finder" action="{{ route('catalog.index') }}" method="get">
            <div class="finder__header">
                <p>Підбір конфігурації</p>
                <span>комп’ютерний магазин</span>
            </div>
            <fieldset class="finder__mode">
                <legend class="visually-hidden">Операція</legend>
                <label class="finder__option">
                    <input type="radio" name="operation" value="sale" checked />
                    <span>Продаж</span>
                </label>
                <label class="finder__option">
                    <input type="radio" name="operation" value="rent" />
                    <span>Оренда</span>
                </label>
            </fieldset>
            <label class="field">
                <span class="field__label">Категорія</span>
                <select class="field__control" name="category">
                    <option value="">Усі категорії</option>
                    <option value="Ноутбук">Ноутбуки</option>
                    <option value="Монітор">Монітори</option>
                    <option value="Комплектуючі">Комплектуючі</option>
                </select>
            </label>
            <div class="finder__row">
                <label class="field">
                    <span class="field__label">Бренд</span>
                    <select class="field__control" name="brand">
                        <option value="">Усі бренди</option>
                        <option value="ASUS">ASUS</option>
                        <option value="Dell">Dell</option>
                        <option value="Lenovo">Lenovo</option>
                        <option value="Acer">Acer</option>
                    </select>
                </label>
                <label class="field">
                    <span class="field__label">Бюджет, ₴</span>
                    <input
                        class="field__control"
                        type="number"
                        name="budget"
                        placeholder="до 50 000"
                        autocomplete="off"
                    />
                </label>
            </div>
            <label class="finder__check">
                <input type="checkbox" name="available" checked />
                <span>Лише моделі в наявності</span>
            </label>
            <button class="button button--cyan" type="submit">
                Показати рішення
            </button>
        </form>
    </section>

    @if(!empty($featuredItems))
        <section class="tech-section tech-section--recommendations" aria-labelledby="recommended-tech-title">
            <div class="tech-section__heading">
                <div>
                    <p class="eyebrow">
                        <span aria-hidden="true"></span> Добірка магазину
                    </p>
                    <h2 class="tech-section__title" id="recommended-tech-title">
                        Рекомендована техніка
                    </h2>
                </div>
                <a class="text-link" href="{{ route('catalog.index') }}">Усі 12 моделей →</a>
            </div>
            <div class="tech-recommendation-grid">
                @foreach($featuredItems as $item)
                    <x-catalog-card :item="$item" />
                @endforeach
            </div>
        </section>
    @endif

    <section class="tech-feature" id="featured" aria-labelledby="tech-pick-title">
        <div class="atlas-frame tech-feature__media">
            <img
                class="atlas-frame__image atlas-frame__image--first"
                src="{{ asset('images/catalog-atlas.png') }}"
                alt="Пристрій тижня"
                width="1774"
                height="887"
                loading="lazy"
            />
            <span class="tech-feature__badge">Device / week</span>
        </div>
        <div class="tech-feature__content">
            <p class="eyebrow">Пристрій тижня · продаж</p>
            <h2 class="tech-feature__title" id="tech-pick-title">
                AeroBook 14 Pro
            </h2>
            <p>
                Легкий корпус, яскравий дисплей і продуктивність для щоденних складних задач.
            </p>
            <dl class="spec-strip">
                <div>
                    <dt>CPU</dt>
                    <dd>Core Ultra 7</dd>
                </div>
                <div>
                    <dt>RAM</dt>
                    <dd>32 GB</dd>
                </div>
                <div>
                    <dt>SSD</dt>
                    <dd>1 TB</dd>
                </div>
            </dl>
            <div class="price-row">
                <strong>54 900 ₴</strong>
                <a class="button button--small button--cyan" href="{{ route('catalog.index') }}">
                    Переглянути пристрій
                </a>
            </div>
        </div>
    </section>

    <section class="partners" aria-labelledby="partners-title">
        <div class="partners__heading">
            <div>
                <p class="partners__eyebrow">Співпраця</p>
                <h2 class="partners__title" id="partners-title">Наші партнери</h2>
            </div>
            <p class="partners__text">
                Компанії, які допомагають перевіряти пропозиції, організовувати отримання та підтримувати якість сервісу.
            </p>
        </div>
        <div class="partners__slider" data-partners-slider>
            <button
                class="partners__control"
                type="button"
                data-action="partners-previous"
                aria-label="Попередні"
                disabled
            >
                <svg viewBox="0 0 24 24" width="22" height="22"><path d="m15 5-7 7 7 7" fill="none" stroke="currentColor" stroke-width="2"/></svg>
            </button>
            <div class="partners__viewport" id="partners-viewport" data-partners-viewport tabindex="0">
                <div class="partners__track">
                    <div class="partner-logo">
                        <svg class="partner-logo__icon" width="76" height="76"><use href="{{ asset('images/partner-logos.svg#logo-nova') }}"></use></svg>
                        <span class="partner-logo__name">NOVA</span>
                    </div>
                    <div class="partner-logo">
                        <svg class="partner-logo__icon" width="76" height="76"><use href="{{ asset('images/partner-logos.svg#logo-orbit') }}"></use></svg>
                        <span class="partner-logo__name">ORBIT</span>
                    </div>
                    <div class="partner-logo">
                        <svg class="partner-logo__icon" width="76" height="76"><use href="{{ asset('images/partner-logos.svg#logo-vector') }}"></use></svg>
                        <span class="partner-logo__name">VECTOR</span>
                    </div>
                    <div class="partner-logo">
                        <svg class="partner-logo__icon" width="76" height="76"><use href="{{ asset('images/partner-logos.svg#logo-form') }}"></use></svg>
                        <span class="partner-logo__name">FORMA</span>
                    </div>
                    <div class="partner-logo">
                        <svg class="partner-logo__icon" width="76" height="76"><use href="{{ asset('images/partner-logos.svg#logo-pulse') }}"></use></svg>
                        <span class="partner-logo__name">PULSE</span>
                    </div>
                    <div class="partner-logo">
                        <svg class="partner-logo__icon" width="76" height="76"><use href="{{ asset('images/partner-logos.svg#logo-nexus') }}"></use></svg>
                        <span class="partner-logo__name">NEXUS</span>
                    </div>
                </div>
            </div>
            <button
                class="partners__control"
                type="button"
                data-action="partners-next"
                aria-label="Наступні"
            >
                <svg viewBox="0 0 24 24" width="22" height="22"><path d="m9 5 7 7-7 7" fill="none" stroke="currentColor" stroke-width="2"/></svg>
            </button>
        </div>
    </section>
@endsection
