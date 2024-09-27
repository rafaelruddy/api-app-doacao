FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    zip \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    default-mysql-client \
    libicu-dev \
    --no-install-recommends \
    && rm -r /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && docker-php-ext-install intl \
    && docker-php-ext-enable opcache

COPY ./opcache.ini /usr/local/etc/php/conf.d/opcache.ini

COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .


RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

EXPOSE 9000

CMD ["php-fpm"]
