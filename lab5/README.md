# Лабораторна робота №5. Адміністративний інтерфейс на Blade і React

- **Студент:** Пригарін Богдан Сергійович
- **Група:** 6.1224
- **Предметний варіант:** №9 — «Продаж комп’ютерної техніки»
- **Тема оформлення:** `tech-showcase`

## Порядок запуску

1. Перейдіть до каталогу `lab5` і підготуйте змінні середовища:
   ```bash
   cp .env.example .env
   ```
2. Запустіть Docker-контейнери (проєкт `lab5`, порт `8082`):
   ```bash
   docker compose up -d
   ```
3. Встановіть PHP-залежності, згенеруйте ключ і наповніть базу даних:
   ```bash
   docker compose exec --user application app composer install
   docker compose exec --user application app php artisan key:generate
   docker compose exec --user application app php artisan migrate --seed
   ```
4. Встановіть Node-залежності та зберіть ресурси Vite + React:
   ```bash
   npm install
   npm run build
   ```
5. Після завершення роботи зупиніть контейнери:
   ```bash
   docker compose stop
   ```

## Адреси адмінпанелі та каталогу

- **Огляд каталогу:** <http://localhost:8082/admin>
- **Позиції каталогу:** <http://localhost:8082/admin/products>
- **Категорії:** <http://localhost:8082/admin/categories>
- **Публічний каталог:** <http://localhost:8082/catalog>
- **API позицій і категорій:** `GET /api/products`, `GET /api/categories`