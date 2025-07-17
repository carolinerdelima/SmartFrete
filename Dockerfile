FROM php:8.2-fpm-alpine3.18

# Instala dependências e extensões PHP necessárias para MySQL
RUN apk add --no-cache \
    bash \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    oniguruma-dev \
    mysql-client \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mbstring \
        zip \
        bcmath

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define diretório de trabalho
WORKDIR /var/www

# Criação de pastas do Laravel
RUN mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache
