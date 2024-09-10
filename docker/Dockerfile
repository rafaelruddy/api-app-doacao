# Use a imagem base PHP 8.2 slim
FROM dunglas/frankenphp

RUN install-php-extensions \
	pdo_mysql \
	gd \
	intl \
    imap \
    bcmath \
    redis \
    curl \
    exif \
    hash \
    iconv \
    json \
    mbstring \
    mysqli \
    mysqlnd \
    pcntl \
    pcre \
    xml \
    libxml \
    zlib \
	zip \
    opcache

COPY . /app

COPY ./opcache.ini /usr/local/etc/php/conf.d/

# Defina o comando de inicialização

ENTRYPOINT ["php", "artisan", "octane:frankenphp"]
