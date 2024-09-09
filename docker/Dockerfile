# Use a imagem base PHP 8.2 slim
FROM php:8.2-slim

# Defina variáveis de ambiente
ENV ACCEPT_EULA=Y

# Instale dependências necessárias
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    zip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    --no-install-recommends && \
    rm -rf /var/lib/apt/lists/*

# Instale o FrankenPHP
RUN curl -sSL https://github.com/frankenphp/frankenphp/releases/download/v0.4.0/frankenphp-linux-x86_64.tar.gz | tar -xz -C /usr/local/bin

# Instale extensões PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl

# Configure OPcache
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=4000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.revalidate_freq=2" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.save_comments=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.enable_cli=1" >> /usr/local/etc/php/conf.d/opcache.ini

# Configure o diretório de trabalho
WORKDIR /var/www/html

# Copie o código da aplicação para o contêiner
COPY . .

# Instale dependências do Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install --optimize-autoloader --no-dev

# Configure permissões
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Exponha a porta 9000
EXPOSE 9000

# Defina o comando de inicialização
CMD ["frankenphp", "php-fpm"]
