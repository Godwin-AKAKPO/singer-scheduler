FROM php:8.3-fpm-alpine

# Extensions système
RUN apk add --no-cache \
    nginx \
    nodejs \
    npm \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    unzip \
    git \
    curl \
    supervisor

# Extensions PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql pgsql gd opcache pcntl

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copier les fichiers de dépendances en premier (cache Docker)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY package.json package-lock.json ./
RUN npm ci

# Copier tout le projet
COPY . .

# Finaliser composer + build assets
RUN composer dump-autoload --optimize \
    && npm run build

# Permissions storage + cache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Config Nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Config PHP-FPM
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

# Config PHP
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini

# Config Supervisor (nginx + php-fpm ensemble)
COPY docker/supervisord.conf /etc/supervisord.conf

# Script de démarrage
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8080

CMD ["/start.sh"]