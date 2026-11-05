FROM php:8.4-fpm-alpine

RUN apk add --no-cache icu-dev libzip-dev oniguruma-dev sqlite-dev \
    && docker-php-ext-install pdo_mysql pdo_sqlite mbstring intl zip pcntl bcmath

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
CMD ["php-fpm"]
