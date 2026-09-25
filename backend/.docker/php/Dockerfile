FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
        libxml2-dev \
        libzip-dev \
        oniguruma-dev \
        sqlite-dev \
        icu-dev \
        git \
        unzip \
    && docker-php-ext-install \
        bcmath \
        intl \
        mbstring \
        opcache \
        pdo_mysql \
        pdo_sqlite \
        xml \
        zip \
    && rm -rf /var/cache/apk/*

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock* ./

RUN composer install \
    --no-interaction \
    --prefer-dist \
    --no-scripts

COPY . .

USER www-data

EXPOSE 9000
