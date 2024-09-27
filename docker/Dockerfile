# Use a imagem base PHP 8.2 slim
FROM dunglas/frankenphp

RUN cp $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

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


ENTRYPOINT ["php", "artisan", "octane:frankenphp"]
