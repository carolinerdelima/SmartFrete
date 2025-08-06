#!/bin/sh
set -e

APP_DIR=/var/www/smartfrete

echo "🛠️ Corrigindo permissões do Laravel..."

if [ ! -d "$APP_DIR/storage" ]; then
    echo "🎯 Criando diretórios de armazenamento..."
    mkdir -p "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"
    chown -R www-data:www-data "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"
    chmod -R 775 "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"
else
    echo "🔒 Diretórios já existem. Corrigindo permissões..."
    chown -R www-data:www-data "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"
    chmod -R 775 "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"
fi

if [ ! -f "$APP_DIR/.env" ]; then
    echo "🔑 Arquivo .env não encontrado. Criando a chave APP_KEY..."
    cp "$APP_DIR/.env.example" "$APP_DIR/.env"
    php artisan key:generate
else
    echo "🔑 Arquivo .env encontrado. Verificando a chave APP_KEY..."
    if ! grep -q "APP_KEY=" "$APP_DIR/.env"; then
        php artisan key:generate
    fi
fi

echo "📚 Gerando documentação Swagger..."
php artisan l5-swagger:generate

echo "🚀 Iniciando PHP-FPM..."
exec "$@"
