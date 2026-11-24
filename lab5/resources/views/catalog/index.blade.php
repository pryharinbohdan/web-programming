@extends('layouts.app')

@section('title', 'Каталог комп’ютерної техніки')

@section('content')
    <section class="catalog-heading" aria-labelledby="catalog-title">
        <div>
            <p class="eyebrow">
                <span aria-hidden="true"></span> {{ $products->total() }} моделей у каталозі
            </p>
            <h1 class="catalog-heading__title" id="catalog-title">
                Каталог техніки
            </h1>
        </div>
        <p>
            Порівнюйте характеристики, стан і гарантію пристроїв. Дані читаються з бази MySQL;
            ті самі фільтри доступні в JSON API за адресою /api/catalog.
        </p>
    </section>

    <div class="catalog-layout">
        <form class="tech-filter-panel" action="{{ route('catalog.index') }}" method="get">
            <div class="tech-filter-panel__heading">
                <strong>Фільтри</strong>
                <a class="tech-filter-panel__reset" href="{{ route('catalog.index') }}">Скинути фільтри</a>
            </div>
            <div class="tech-filter-panel__fields">
                <label class="tech-filter-panel__field">
                    <span>Пошук</span>
                    <input
                        type="search"
                        name="q"
                        value="{{ $filters->q }}"
                        placeholder="Наприклад, ноутбук…"
                        autocomplete="off"
                    />
                    @error('q')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </label>

                <label class="tech-filter-panel__field">
                    <span>Категорія</span>
                    <select name="category">
                        <option value="">Усі категорії</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->name }}" @selected($filters->category === $category->name)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </label>

                <label class="tech-filter-panel__field">
                    <span>Спосіб угоди</span>
                    <select name="operation">
                        <option value="">Усі способи</option>
                        @foreach($operations as $operation)
                            <option value="{{ $operation }}" @selected($filters->operation === $operation)>
                                {{ $operation }}
                            </option>
                        @endforeach
                    </select>
                    @error('operation')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </label>

                <label class="tech-filter-panel__field">
                    <span>Сортування</span>
                    <select name="sort">
                        @foreach(\App\Data\CatalogFilterData::SORTS as $value => $label)
                            <option value="{{ $value }}" @selected($filters->sort === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('sort')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </label>

                <label class="tech-filter-panel__field">
                    <span>Продуктів на сторінці</span>
                    <select name="per_page">
                        @foreach(\App\Data\CatalogFilterData::PER_PAGE_OPTIONS as $size)
                            <option value="{{ $size }}" @selected($filters->perPage === $size)>{{ $size }}</option>
                        @endforeach
                    </select>
                    @error('per_page')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </label>

                @error('page')
                    <small class="field-error">{{ $message }}</small>
                @enderror

                <button class="button button--cyan" type="submit">Знайти</button>
            </div>
            <p class="tech-filter-panel__note">
                Пошук, фільтри, сортування й пагінація працюють однаково для HTML та API.
            </p>
        </form>

        <section class="catalog-results" aria-label="Результати каталогу">
            <div class="catalog-toolbar">
                <p class="catalog-toolbar__count"><strong>{{ $products->total() }}</strong> моделей знайдено</p>
            </div>

            @if($products->isEmpty())
                <div class="catalog-empty">
                    <h2 class="catalog-empty__title">Нічого не знайдено</h2>
                    <p class="catalog-empty__text">
                        За вибраними умовами продуктів немає. Змініть пошуковий запит або фільтри.
                    </p>
                    <a class="button button--cyan" href="{{ route('catalog.index') }}">Скинути фільтри</a>
                </div>
            @else
                <div class="tech-grid" aria-label="Комп’ютерна техніка">
                    @foreach($products as $product)
                        <x-catalog-card :product="$product" />
                    @endforeach
                </div>

                {{ $products->links('pagination.catalog') }}
            @endif
        </section>
    </div>
@endsection
