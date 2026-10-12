@extends('layouts.app')

@section('title', 'Сторінку не знайдено')

@section('content')
    <section class="status-page">
        <div class="status-page__signal" aria-hidden="true">
            <span>4</span><i></i><span>4</span>
        </div>
        <div>
            <p class="eyebrow">
                <span aria-hidden="true"></span> Error / route_not_found
            </p>
            <h1 class="status-page__title">З’єднання зі сторінкою втрачено.</h1>
            <p class="status-page__text">
                Адреса не відповідає жодному товару або розділу каталогу.
            </p>
            <a class="button button--cyan" href="{{ route('home') }}">
                Повернутися на головну
            </a>
        </div>
    </section>
@endsection