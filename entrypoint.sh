#!/bin/sh

echo "🛠️ Corrigindo permissões do Laravel..."

# Verifica se o diretório de armazenamento já possui as permissões corretas antes de modificá-las
if [ ! -d "/var/www/smartfrete/storage" ]; then
    echo "🎯 Criando diretórios de armazenamento..."
    mkdir -p /var/www/smartfrete/storage /var/www/smartfrete/bootstrap/cache
    chown -R www-data:www-data /var/www/smartfrete/storage /var/www/smartfrete/bootstrap/cache
    chmod -R 775 /var/www/smartfrete/storage /var/www/smartfrete/bootstrap/cache
else
    echo "🔒 Diretórios já existem. Corrigindo permissões..."
    chown -R www-data:www-data /var/www/smartfrete/storage /var/www/smartfrete/bootstrap/cache
    chmod -R 775 /var/www/smartfrete/storage /var/www/smartfrete/bootstrap/cache
fi

# Verifica se a chave APP_KEY está presente no .env e gera uma chave se não estiver
if [ ! -f /var/www/smartfrete/.env ]; then
    echo "🔑 Arquivo .env não encontrado. Criando a chave APP_KEY..."
    cp .env.example .env
    php artisan key:generate
else
    echo "🔑 Arquivo .env encontrado. Verificando a chave APP_KEY..."
    if ! grep -q "APP_KEY=" /var/www/smartfrete/.env; then
        php artisan key:generate
    fi
fi

echo "🚀 Iniciando PHP-FPM..."
exec "$@"