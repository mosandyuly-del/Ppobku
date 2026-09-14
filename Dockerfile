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

# Paksa bersihkan sisa vendor & cache internal jika terbawa
RUN rm -rf vendor bootstrap/cache/*.php

ENV COMPOSER_ALLOW_SUPERUSER=1

# Flag --clear-cache memastikan composer tidak mengambil paket dari cache internal container
RUN composer clear-cache && composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs --no-scripts

RUN mkdir -p database storage/framework/views storage/framework/sessions storage/framework/cache storage/logs \
    && touch database/database.sqlite \
    && chmod -R 777 database storage

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
