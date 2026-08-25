FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    $PHPIZE_DEPS \
    linux-headers \
    build-base \
    zip \
    libzip-dev \
    unzip \
    git \
    curl \
    mariadb-client \
    oniguruma-dev

RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN pecl install redis && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
