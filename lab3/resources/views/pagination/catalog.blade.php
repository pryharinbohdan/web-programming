@if ($paginator->hasPages())
    <nav class="pagination" aria-label="Навігація сторінками">
        <ul class="pagination__list">
            @if ($paginator->onFirstPage())
                <li class="pagination__item pagination__item--disabled">
                    <span class="pagination__link">← Назад</span>
                </li>
            @else
                <li class="pagination__item">
                    <a class="pagination__link" href="{{ $paginator->previousPageUrl() }}" rel="prev">← Назад</a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="pagination__item pagination__item--gap">
                        <span class="pagination__link">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="pagination__item pagination__item--active">
                                <span class="pagination__link" aria-current="page">{{ $page }}</span>
                            </li>
                        @else
                            <li class="pagination__item">
                                <a class="pagination__link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="pagination__item">
                    <a class="pagination__link" href="{{ $paginator->nextPageUrl() }}" rel="next">Далі →</a>
                </li>
            @else
                <li class="pagination__item pagination__item--disabled">
                    <span class="pagination__link">Далі →</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
