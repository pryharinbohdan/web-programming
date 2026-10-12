@extends('layouts.app')

@section('title', 'Каталог комп’ютерної техніки')

@section('content')
    <section class="catalog-heading" aria-labelledby="catalog-title">
        <div>
            <p class="eyebrow">
                <span aria-hidden="true"></span> {{ count($items) }} моделей у каталозі
            </p>
            <h1 class="catalog-heading__title" id="catalog-title">
                Каталог техніки
            </h1>
        </div>
        <p>
            Порівнюйте характеристики, формат отримання й стан пристроїв.
            Кожна картка веде до детальної сторінки комп’ютерної техніки.
        </p>
    </section>

    <div class="catalog-layout">
        <form class="tech-filter-panel" action="{{ route('catalog.index') }}" method="get">
            <div class="tech-filter-panel__heading">
                <strong>Фільтри</strong>
                <button class="tech-filter-panel__reset" type="reset">Очистити</button>
            </div>
            <div class="tech-filter-panel__fields">
                <fieldset class="tech-filter-panel__operations">
                    <legend>Операція</legend>
                    <label class="tech-filter-panel__option">
                        <input type="radio" name="operation" value="" checked />
                        <span>Усі</span>
                    </label>
                    <label class="tech-filter-panel__option">
                        <input type="radio" name="operation" value="sale" />
                        <span>Продаж</span>
                    </label>
                </fieldset>

                <label class="tech-filter-panel__field">
                    <span>Категорія</span>
                    <select name="category">
                        <option value="">Уся техніка</option>
                        <option value="Ноутбук">Ноутбуки</option>
                        <option value="Монітор">Монітори</option>
                        <option value="Комплектуючі">Комплектуючі</option>
                    </select>
                </label>

                <label class="tech-filter-panel__field">
                    <span>Сортування</span>
                    <select name="sort">
                        <option value="popular">За популярністю</option>
                        <option value="price-asc">Спочатку дешевші</option>
                        <option value="price-desc">Спочатку дорожчі</option>
                    </select>
                </label>

                <label class="tech-filter-panel__check">
                    <input type="checkbox" name="available" checked />
                    <span>Лише в наявності</span>
                </label>

                <button class="button button--cyan" type="submit">Застосувати</button>
            </div>
            <p class="tech-filter-panel__note">
                Фільтри й пошук — елементи оформлення теми, у цій роботі вони не змінюють вибірку.
            </p>
        </form>

        <section class="catalog-results" aria-label="Результати каталогу">
            <div class="catalog-toolbar">
                <p class="catalog-toolbar__count"><strong>{{ count($items) }}</strong> моделей</p>
                <label class="catalog-search">
                    <span class="catalog-search__label">Пошук у каталозі</span>
                    <input
                        class="catalog-search__input"
                        type="search"
                        name="search"
                        placeholder="Наприклад, ноутбук…"
                        autocomplete="off"
                    />
                </label>
            </div>

            <div class="tech-grid" aria-label="Комп’ютерна техніка">
                @foreach($items as $item)
                    <x-catalog-card :item="$item" />
                @endforeach
            </div>
        </section>
    </div>
@endsection
