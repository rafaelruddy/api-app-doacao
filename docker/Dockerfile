FROM php:8.2-fpm

# Instalar dependências e pacotes necessários
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

# Instalar extensões PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && docker-php-ext-install intl \
    && docker-php-ext-enable opcache




# Configuração do OPcache
COPY ./docker/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Copiar Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Configuração do diretório de trabalho
WORKDIR /var/www/html

# Copiar o código da aplicação
COPY . .

# Instalar dependências do Composer
RUN composer install --optimize-autoloader --no-dev

# Otimizar o cache do Laravel
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

# Ajustar permissões
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Expor a porta
EXPOSE 9000

# Comando de inicialização
CMD ["php-fpm"]
