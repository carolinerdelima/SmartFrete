#!/bin/sh

echo "🛠️ Corrigindo permissões do Laravel..."

chown -R www-data:www-data /var/www/smartfrete/storage /var/www/smartfrete/bootstrap/cache
chmod -R 775 /var/www/smartfrete/storage /var/www/smartfrete/bootstrap/cache

echo "🚀 Iniciando php-fpm..."

exec "$@"
