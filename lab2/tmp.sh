sedi() { sed -i "" "$@"; }
REPO="${REPO_NAME:-CHANGE_ME}"
set -e
[ -f artisan ] || { echo "Запускайте з кореня Laravel-проєкту (lab2)"; exit 1; }
mkdir -p deploy public/js resources/views

# ---------- routes ----------
cat > routes/web.php <<'EOF'
<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{slug}', [CatalogController::class, 'show'])->name('catalog.show');
EOF

# ---------- HomeController + view home.blade.php ----------
cat > app/Http/Controllers/HomeController.php <<'EOF'
<?php

namespace App\Http\Controllers;

use App\Services\CatalogService;

class HomeController extends Controller
{
    public function __construct(
        protected CatalogService $catalogService
    ) {}

    public function index()
    {
        return view('home', [
            'featuredItems' => $this->catalogService->featured(3),
        ]);
    }
}
EOF
if [ -f resources/views/catalog/home.blade.php ]; then
  mv resources/views/catalog/home.blade.php resources/views/home.blade.php
fi

# ---------- CatalogService: дати без поточного часу ----------
sedi "s|dateTimeBetween('-30 days', 'now')|dateTimeBetween('2026-01-01', '2026-03-31')|" app/Services/CatalogService.php

# ---------- .env.example (без БД-драйверів) ----------
cat > .env.example <<'EOF'
APP_NAME="Tech Showcase"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

APP_LOCALE=uk
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=uk_UA

APP_MAINTENANCE_DRIVER=file
BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=debug

SESSION_DRIVER=file
SESSION_LIFETIME=120
CACHE_STORE=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=local

CATALOG_VARIANT=9
CATALOG_THEME=tech-showcase
EOF

# локальний .env (не в Git): ті самі драйвери
if [ -f .env ]; then
  sedi -E 's/^SESSION_DRIVER=.*/SESSION_DRIVER=file/; s/^CACHE_STORE=.*/CACHE_STORE=file/; s/^QUEUE_CONNECTION=.*/QUEUE_CONNECTION=sync/' .env
  grep -q '^CATALOG_VARIANT=' .env || echo 'CATALOG_VARIANT=9' >> .env
  grep -q '^CATALOG_THEME=' .env || echo 'CATALOG_THEME=tech-showcase' >> .env
fi

# ---------- layout ----------
cat > resources/views/layouts/app.blade.php <<'EOF'
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
EOF

# ---------- 404: посилання на головну, без дубля в title ----------
sedi "s|route('catalog.index')|route('home')|; s|Повернутися до каталогу|Повернутися на головну|; s| — Tech Showcase'|'|" resources/views/errors/404.blade.php

# ---------- чесна нотатка про фільтри ----------
sedi "s|Фільтри демонструють стан інтерфейсу. Їхню динамічну логіку реалізовано через параметри форми.|Фільтри й пошук — елементи оформлення теми, у цій роботі вони не змінюють вибірку.|" resources/views/catalog/index.blade.php

# ---------- сповіщення: бейдж = кількість непрочитаних ----------
sedi 's|aria-label="2 нові сповіщення"|aria-label="1 нове сповіщення"|; s|^            2$|            1|' resources/views/components/notification-center.blade.php

cat > public/js/notifications.js <<'EOF'
document.querySelectorAll('[data-component="notification-center"]').forEach((root) => {
  const badge = root.querySelector("[data-notification-count]");
  const empty = root.querySelector("[data-notification-empty]");

  const refresh = () => {
    const unread = root.querySelectorAll(".notification-menu__item--unread").length;
    badge.textContent = unread;
    badge.style.display = unread ? "" : "none";
    badge.setAttribute("aria-label", unread + " нових сповіщень");
    empty.hidden = unread > 0;
  };

  root.addEventListener("click", (event) => {
    const button = event.target.closest("[data-action]");
    if (!button) return;

    if (button.dataset.action === "mark-read") {
      button.closest("[data-notification-id]").classList.remove("notification-menu__item--unread");
    }
    if (button.dataset.action === "mark-all-read") {
      root.querySelectorAll(".notification-menu__item--unread")
        .forEach((item) => item.classList.remove("notification-menu__item--unread"));
    }
    refresh();
  });

  refresh();
});
EOF

# ---------- nginx ----------
cat > deploy/nginx.conf <<'EOF'
server {
    listen 80;
    server_name _;
    root /var/www/__REPO__/lab2/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF
sedi "s|__REPO__|$REPO|g" deploy/nginx.conf

# ---------- README ----------
cat > README.md <<'EOF'
# Лабораторна робота №2. Розробка вебзастосунків з використанням фреймворків

- **Студент:** Пригарін Богдан
- **Група:** 6.1224
- **Варіант:** 9 — «Продаж комп’ютерної техніки»
- **Тема оформлення:** `tech-showcase` (модульна вітрина з технічними характеристиками)

## 1. Обґрунтування вибору теми

У комп’ютерній техніці покупець порівнює характеристики: процесор, RAM, SSD, діагональ, матрицю, стан і гарантію.
Тема `tech-showcase` показує їх прямо в картці каталогу (блок `.tech-card__specs`), тож моделі
можна порівнювати, не відкриваючи кожну сторінку. Бейджі (`.tech-card__label`) виділяють
призначення пристрою, а стримана палітра (cyan, indigo, violet) підкреслює технологічність.

## 2. Ланцюжок «маршрут → контролер → сервіс → Blade»

1. `routes/web.php` зіставляє `/`, `/catalog`, `/catalog/{slug}` з методами контролерів (іменовані маршрути).
2. `HomeController` і `CatalogController` отримують `CatalogService` через Dependency Injection
   (контейнер служб сам створює сервіс за типом параметра конструктора). Контролер лише обирає метод
   сервісу, передає дані у view або викликає `abort(404)`.
3. `CatalogService` генерує 12 оголошень через Faker (`uk_UA`) із фіксованим seed з `config/catalog.php`
   (варіант 9 → seed 2109). Той самий seed дає ті самі дані після перезапуску. Slug будується
   через `Str::slug()` з назви, а при збігу додається номер.
4. Blade: `layouts/app.blade.php` (шапка, навігація, підвал), компоненти `catalog-card` та `notification-center`,
   сторінки `home`, `catalog/index`, `catalog/show`, `errors/404`.

## 3. BEM і спільний інтерфейс

Блок → елемент → модифікатор: `site-header` → `site-header__brand`, `catalog-card`/`catalog-card--featured`,
`notification-menu__item--unread`, `button--cyan`.
Однаковий data-інтерфейс для всіх тем: кнопка заявки має `data-action="request"`,
`data-endpoint="/api/requests"`, `data-item`; сповіщення — `data-component="notification-center"`,
`data-endpoint="/api/notifications"`, `data-notification-list`, дії `mark-read` / `mark-all-read`.
У цій роботі мережевих запитів немає, а кнопки сповіщень працюють локально (`public/js/notifications.js`).

## 4. Локальний запуск

```bash
git clone <repository-url>
cd __REPO__/lab2
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

Перевірки:

```bash
php artisan route:list
curl -i http://127.0.0.1:8000/
curl -i http://127.0.0.1:8000/catalog
curl -i http://127.0.0.1:8000/catalog/<known-slug>
curl -i http://127.0.0.1:8000/catalog/missing-item
curl -i http://127.0.0.1:8000/missing/page
curl -i http://127.0.0.1:8000/css/styles.css
```

## 5. Розгортання на Amazon EC2 (Ubuntu 24.04, t3.micro)

Security group: SSH (22) лише з вашої IP, HTTP (80) з будь-якої IPv4.

```bash
ssh -i <key.pem> ubuntu@<PUBLIC_IPV4>

sudo apt update
sudo apt install -y nginx git unzip composer php8.3-fpm php8.3-cli \
  php8.3-curl php8.3-mbstring php8.3-xml php8.3-zip

cd /var/www
sudo git clone <repository-url> __REPO__
sudo chown -R ubuntu:www-data __REPO__
cd __REPO__/lab2
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
nano .env   # APP_ENV=production, APP_DEBUG=false, APP_URL=http://<PUBLIC_IPV4>,
            # CATALOG_VARIANT=9, CATALOG_THEME=tech-showcase

sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R ug+rwX storage bootstrap/cache

sudo cp deploy/nginx.conf /etc/nginx/sites-available/lab2
sudo ln -sf /etc/nginx/sites-available/lab2 /etc/nginx/sites-enabled/lab2
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx
php artisan optimize
```

Після перевірки: Terminate instance у консолі EC2, видалити security group.
EOF
sedi "s|__REPO__|$REPO|g" README.md

# ---------- перевірка ----------
[ -f public/images/catalog-atlas.png ] || echo "УВАГА: немає public/images/catalog-atlas.png — скопіюйте зі стартера!"
[ "$REPO" = "CHANGE_ME" ] && echo "УВАГА: задайте REPO_NAME=... (назва каталогу репозиторію) і запустіть ще раз"
php artisan optimize:clear
php artisan route:list
php artisan test || true
echo "Готово. Далі: git add -A && git commit -m 'Fix lab2' && git push"
