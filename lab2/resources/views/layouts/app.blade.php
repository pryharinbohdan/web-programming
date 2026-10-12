<!doctype html>
<html lang="uk">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="theme-color" content="#f3f5f8" />
    <title>@yield('title', 'Комп’ютерна техніка') — Tech Showcase</title>

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
    <script src="{{ asset('js/partners.js') }}" defer></script>
    <script src="{{ asset('js/notifications.js') }}" defer></script>
  </head>
  <body class="page">
    <a class="skip-link" href="#main-content">Перейти до вмісту</a>

    <header class="site-header">
      <a class="site-header__brand" href="{{ route('home') }}">
        <span class="site-header__logo" aria-hidden="true">&gt;_</span>
        <span class="site-header__brand-copy">
          Tech Showcase
          <small class="site-header__subtitle">computer store</small>
        </span>
      </a>

      <nav class="site-nav" aria-label="Головна навігація">
        <a
          class="site-nav__link {{ request()->routeIs('home') ? 'site-nav__link--active' : '' }}"
          href="{{ route('home') }}"
          @if(request()->routeIs('home')) aria-current="page" @endif
        >Головна</a>

        <a
          class="site-nav__link {{ request()->routeIs('catalog.*') ? 'site-nav__link--active' : '' }}"
          href="{{ route('catalog.index') }}"
          @if(request()->routeIs('catalog.*')) aria-current="page" @endif
        >Каталог</a>
      </nav>

      <div class="site-header__tools">
        <a class="site-header__action" href="mailto:author@example.com">
          Допомога з вибором
        </a>

        <x-notification-center />
      </div>
    </header>

    <main class="page__main" id="main-content">
      @yield('content')
    </main>

    <footer class="site-footer">
      <p class="site-footer__text">Пригарін Богдан · група 6.1224</p>
      <p class="site-footer__meta">Варіант {{ config('catalog.variant') }} · тема {{ config('catalog.theme') }}</p>
    </footer>
  </body>
</html>
