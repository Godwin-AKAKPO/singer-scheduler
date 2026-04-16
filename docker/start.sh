#!/bin/sh
set -e

cd /var/www/html

# echo "==> Génération de la clé app..."
# php artisan key:generate --force

echo "==> Cache config + routes..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Migrations..."
php artisan migrate --force --no-interaction

echo "==> Démarrage des services..."
exec /usr/bin/supervisord -c /etc/supervisord.conf