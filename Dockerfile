FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    sqlite3 \
    libsqlite3-dev \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl

RUN docker-php-ext-install pdo pdo_sqlite mbstring gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader

# Buat direktori dan atur permission untuk SQLite & Storage
RUN mkdir -p database storage/framework/views storage/framework/sessions storage/framework/cache storage/logs
RUN touch database/database.sqlite
RUN chmod -R 777 database storage

EXPOSE 8080

# Jalankan migrasi dan server secara otomatis saat container dinyalakan
CMD php artisan migrate:force --seed && php artisan serve --host=0.0.0.0 --port=8080
