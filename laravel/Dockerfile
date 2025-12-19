FROM php:8.1-fpm-alpine

RUN apk update && apk add --no-cache \
    bash \
    curl \
    git \
    openssl \
    bash \
    zlib \
    libmemcached-dev \
    libzip-dev \
    libpq-dev \
    pcre-dev \
    && apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    curl-dev \
    postgresql-dev \
    openssl-dev \
    zlib-dev \
    libzip-dev \
    pcre-dev \
    && \
    pecl install redis && \
    docker-php-ext-enable redis && \
    pecl install swoole && \
    docker-php-ext-enable swoole && \
    docker-php-ext-install pdo_mysql && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    apk del .build-deps

WORKDIR /var/www

COPY . /var/www

RUN chown -R www-data:www-data /var/www && chmod -R 777 /var/www/storage /var/www/bootstrap/cache

RUN composer install --no-dev --optimize-autoloader

# EXPOSE 9000

# CMD ["php-fpm"]
