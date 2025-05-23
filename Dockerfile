FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    curl \
    libpng \
    libpng-dev \
    libjpeg-turbo \
    libjpeg-turbo-dev \
    freetype \
    freetype-dev \
    zip \
    unzip \
    oniguruma \
    oniguruma-dev \
    bash \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install gd

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www

EXPOSE 9000
CMD ["php-fpm"]