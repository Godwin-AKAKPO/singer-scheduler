#!/bin/sh
set -e

cd /var/www/html

# echo "==> Génération de la clé app..."
# php artisan key:generate --force

echo "==> Cache config + routes..."
timeout 60 php artisan config:cache || echo "config:cache failed or timed out"
timeout 60 php artisan route:cache || echo "route:cache failed or timed out"
timeout 60 php artisan view:cache || echo "view:cache failed or timed out"

echo "==> Migrations..."
timeout 90 php artisan migrate --force --no-interaction || echo "migration failed or timed out, starting services anyway"

echo "==> Démarrage des services..."
exec /usr/bin/supervisord -c /etc/supervisord.conf