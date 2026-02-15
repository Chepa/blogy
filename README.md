# Blogy — простой блог на PHP

Блог с категориями и статьями. PHP 8.1+, Smarty, MySQL, DI, FastRoute.

## Требования

- PHP 8.1+
- MySQL 8.0+
- Composer

## Установка

```bash
composer install
php scripts/build-scss.php   # Компиляция SCSS
```

## База данных

Создайте БД и выполните схему:

```bash
php scripts/schema.php
# или с указанием файла:
php scripts/schema.php database/schema.sql
```

Либо через mysql:

```bash
mysql -u root -p < database/schema.sql
```

Либо настройте подключение через переменные окружения:

- `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`
- `BASE_URL` — базовый URL (например, `http://localhost:8080` для Open Graph)

## Сидер

Заполнение тестовыми категориями и статьями:

```bash
php scripts/seed.php
```

## Запуск (локально)

Встроенный сервер PHP:

```bash
php -S localhost:8080 -t public
```

Откройте http://localhost:8080

## Docker

```bash
docker-compose up -d
```

- Web: http://localhost:8080
- MySQL: localhost:3306 (blogy / blogy)

После запуска выполните схему и сидер внутри контейнера:

```bash
docker-compose exec web php scripts/schema.php
docker-compose exec web php scripts/seed.php
```

## Разработка

```bash
composer test       # PHPUnit
composer phpstan    # Статический анализ
```

## Структура проекта

```
config/          — конфигурация, DI, роуты
database/        — схема БД
public/          — Front Controller (index.php), CSS, статика
scss/            — исходники стилей
scripts/         — сидер, сборка SCSS
src/             — PHP-классы (Actions, Repositories, UrlHelper, SmartyFactory)
templates/       — шаблоны Smarty
tests/           — PHPUnit тесты
```

## URL

- `/` — главная
- `/article/{id}` — статья
- `/category/{id}` — категория (?sort=date|views&dir=asc|desc&page=N)
- `/sitemap.xml` — sitemap
# blogy
