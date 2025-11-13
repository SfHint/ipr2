# Categories RESTful API (PHP + MySQL)

### Описание
Простой RESTful API на PHP для управления сущностью `categories` (name, slug, parent_id).
Защищено с помощью API-ключа (X-API-Key). API-ключи хранятся в таблице `api_keys` в виде хеша (password_hash()).

### Структура проекта
- index.php — единая точка входа и маршрутизация
- .htaccess — перенаправление всех запросов на index.php
- config/Database.php — класс подключения к MySQL через PDO
- config/config.php — параметры подключения (настройте)
- config/Auth.php — проверка API-ключа
- models/CategoryModel.php — работа с таблицей categories
- controllers/CategoryController.php — обработка запросов CRUD
- sql/init.sql — SQL для создания базы, таблиц и пример seed-скрипт для API-ключа

### Быстрый старт
1. Скопируйте проект в папку веб-сервера (например, /var/www/html/categories_api_php).
2. Настройте `config/config.php` (DB_HOST, DB_NAME, DB_USER, DB_PASS).
3. Импортируйте `sql/init.sql` в MySQL или выполните через `mysql`:
   ```
   mysql -u root -p < sql/init.sql
   ```
4. Сгенерируйте API-ключ и добавьте его в таблицу `api_keys` с помощью PHP-скрипта:
   ```
   php sql/seed_api_key.php
   ```
   Скрипт выведет сгенерированный plain API key — сохраните его. Скрипт сам положит хеш в базу (если config верно настроен).
5. Тестируйте endpoints (Postman/Insomnia):
   - GET /api/categories
   - GET /api/categories/{id}
   - POST /api/categories
   - PUT /api/categories/{id}
   - DELETE /api/categories/{id}

Заголовок: `X-API-Key: <your-key>`

Ответы в JSON, статусы HTTP соответствуют результатам.
