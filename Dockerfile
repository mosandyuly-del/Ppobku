FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    sqlite3 \
    libsqlite3-dev \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_sqlite mbstring gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs --no-scripts

RUN mkdir -p database storage/framework/views storage/framework/sessions storage/framework/cache storage/logs \
    && touch database/database.sqlite \
    && chmod -R 777 database storage

EXPOSE 8080

CMD php artisan serve --host=0.0.0.0 --port=8080
