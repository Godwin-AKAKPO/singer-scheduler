# -------- 1. Image de base PHP --------
FROM php:8.3-cli

# -------- 2. Installer dépendances système --------
RUN apt-get update && apt-get install -y \
    git unzip curl libpq-dev libzip-dev zip nodejs npm \
    && docker-php-ext-install pdo pdo_pgsql zip

# -------- 3. Installer Composer --------
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# -------- 4. Définir le dossier de travail --------
WORKDIR /app

# -------- 5. Copier le projet --------
COPY . .

# -------- 6. Installer dépendances PHP --------
RUN composer install --no-dev --optimize-autoloader

# -------- 7. Installer dépendances JS + build --------
RUN npm install && npm run build

# -------- 8. Optimisations Laravel --------
RUN php artisan config:cache \
 && php artisan route:cache \
 && php artisan view:cache

# -------- 9. Exposer le port --------
EXPOSE 8000

# -------- 10. Commande de démarrage --------
CMD php artisan config:clear && php artisan serve --host=0.0.0.0 --port=8000