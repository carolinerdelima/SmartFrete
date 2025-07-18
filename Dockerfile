FROM php:8.2-fpm-alpine3.18

# Instala dependências e extensões necessárias
RUN apk add --no-cache \
    bash \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    oniguruma-dev \
    postgresql-client \
    postgresql-dev \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        mbstring \
        zip \
        bcmath

COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/smartfrete
COPY ./smartfrete /var/www/smartfrete

RUN composer install --no-dev --optimize-autoloader

# Criação de pastas do Laravel
RUN mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# Corrige permissões dos diretórios necessários
RUN chown -R www-data:www-data /var/www/smartfrete/storage /var/www/smartfrete/bootstrap/cache
RUN chmod -R 775 /var/www/smartfrete/storage /var/www/smartfrete/bootstrap/cache

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm"]