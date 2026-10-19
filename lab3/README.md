# Лабораторна робота №3. Робота з базою даних у Laravel

- **Студент:** Пригарін Богдан
- **Група:** 6.1224
- **Предметний варіант:** №9 — «Продаж комп’ютерної техніки»
- **Тема оформлення:** `tech-showcase` (модульна вітрина з технічними характеристиками)
- **Репозиторій:** <https://github.com/pryharinbohdan/web-programming> (каталог `lab3`)

Застосунок лабораторної №2 перенесено в `lab3`: дані тепер зберігаються в **MySQL 8.4**, читаються через
**Eloquent**, а ті самі фільтри працюють у HTML-сторінках і в **JSON API**. Конфігурація `config/catalog.php`
та змінні `CATALOG_VARIANT`/`CATALOG_THEME` прибрані — варіант і тема зафіксовані в цьому README та в підвалі сайту.

## 1. Зв’язок Product і Category

`products.category_id` → `categories.id` (зв’язок «один до багатьох»).
`Category::products(): HasMany`, `Product::category(): BelongsTo`.
Міграція `categories` виконується раніше за `products`; зовнішній ключ має `restrictOnDelete()`,
тому категорію з продуктами видалити не можна. `slug` у обох таблицях унікальний.

## 2. Спільна обробка для HTML і API

```
GET /catalog      ┐                                    ┌→ Blade (catalog.index, пагінація)
                  ├→ CatalogFilterRequest → CatalogFilterData → CatalogService → Eloquent (Product + Category)
GET /api/catalog  ┘                                    └→ ProductResource::collection() → JSON (data, links, meta)
```

- `CatalogFilterRequest` — валідація (`q`, `category`, `operation`, `sort`, `page`, `per_page`), повідомлення українською.
- `CatalogFilterData` — типізований `readonly` DTO; створюється з `$request->validated()`.
- `CatalogService` — єдина точка вибірки: пошук, фільтри, сортування (з додатковим ключем `id`), пагінація, `with('category')`.
- Faker використовується **лише в сидерах**; під час HTTP-запитів дані читаються з бази.

| GET-запит | Відповідь |
|---|---|
| `/` | HTML, три рекомендовані продукти, 200 |
| `/catalog` | HTML, фільтри та пагінація, 200 |
| `/catalog/{slug}` | HTML продукту, 200 |
| `/api/catalog` | JSON: `data`, `links`, `meta`, 200 |
| `/api/catalog/{slug}` | JSON: `data`, 200 |
| невідомий HTML-шлях або slug | оформлена сторінка, 404 |
| невідомий API-шлях або slug | JSON з `message`, 404 |
| некоректні API-параметри | JSON з `message` та `errors`, 422 |

## 3. Запуск у Docker

Команди Docker — у терміналі основної системи з каталогу `lab3`.

```bash
cp .env.example .env
docker compose config --quiet
docker compose up -d
docker compose logs mysql        # дочекайтеся "ready for connections" на порті 3306
docker compose exec --user application app sh
```

Усередині контейнера (каталог `/app`):

```bash
composer install                 # якщо конфлікт PHP-вимог: rm -f composer.lock && composer update
php artisan key:generate
php artisan config:clear
php artisan migrate --seed
php artisan migrate:status
php artisan db:show --counts     # 4 категорії і 12 продуктів
php artisan route:list
```

Сайт: <http://localhost:8080>. Якщо порт зайнятий — змініть `APP_PORT` і `APP_URL` у `.env` узгоджено.
Повторне наповнення (`php artisan db:seed`) не додає дублікатів і не переписує продукти.

Тести (потрібен `pdo_sqlite` у контейнері):

```bash
php artisan test
```

Зупинка: `docker compose stop` (дані MySQL лишаються в volume `mysql-data`).
`docker compose down -v` видаляє volume разом з даними.

## 4. Postman

1. Імпортуйте `postman/lab3.postman_collection.json` (Import → File).
2. У змінних колекції `base_url` = `http://localhost:8080` (без кінцевого слеша).
   Для вебверсії Postman з `localhost` оберіть запущений Desktop Agent.
3. Запустіть **Run collection** (Collection Runner). Перший запит зберігає `known_slug`, `category`,
   `operation`, `search`, які використовують наступні.
4. Усі 15 запитів повинні пройти (200 / 422 / 404 з перевіркою вмісту).
