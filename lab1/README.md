# Лабораторна робота №1: Реалізація простих вебзастосунків

**Виконавець:** Пригарін Богдан Сергійович  
**Група:** 6.1224  
**Дисципліна:** Веб-програмування

---

## Опис проєкту

Ця директорія містить дві незалежні реалізації простого багатосторінкового вебзастосунку без використання сторонніх фреймворків чи бібліотек:
1. **Node.js реалізація** (використовує вбудовані модулі `http`, `fs`, `path`).
2. **Python реалізація** (використовує вбудовані модулі `http.server`, `pathlib`, `urllib.parse`).

Обидві реалізації мають однаковий дизайн, структуру сторінок, стилі та навігацію, але відображають інформацію про своє відповідне середовище виконання.

---

## Версії середовищ
- **Node.js:** v26.7.0
- **Python:** 3.14.6
- **curl:** актуальна версія для перевірки HTTP-заголовків.

---

## Структура проєкту

```text
lab1/
├── node/
│   ├── server.mjs
│   └── public/
│       ├── index.html
│       ├── about.html
│       ├── 404.html
│       └── styles.css
├── python/
│   ├── server.py
│   └── public/
│       ├── index.html
│       ├── about.html
│       ├── 404.html
│       └── styles.css
└── README.md
```

---

## Команди запуску та адреси

### 1. Node.js сервер
- **Команда запуску:**
  ```bash
  node node/server.mjs
  ```
- **Адреса:** [http://localhost:3001/](http://localhost:3001/)

### 2. Python сервер
- **Команда запуску:**
  ```bash
  python3 python/server.py
  ```
- **Адреса:** [http://localhost:3002/](http://localhost:3002/)

---

## Перевірка за допомогою curl

Приклади команд для тестування маршрутів:
```bash
# Головна сторінка
curl -i http://localhost:3001/
curl -i http://localhost:3002/

# Сторінка "Про роботу" з параметром
curl -i "http://localhost:3001/about?source=test"
curl -i "http://localhost:3002/about?source=test"

# Файл стилів
curl -i http://localhost:3001/styles.css
curl -i http://localhost:3002/styles.css

# Невідомий шлях (помилка 404)
curl -i http://localhost:3001/missing/page
curl -i http://localhost:3002/missing/page
```
