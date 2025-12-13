# -----------------------------
# Phase 1 : Build (Composer + NPM)
# -----------------------------
FROM php:8.2-fpm AS build

WORKDIR /var/www/html

# Installer dépendances système + libpq-dev pour PostgreSQL
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libonig-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev libpq-dev nodejs npm \
    --no-install-recommends && rm -rf /var/lib/apt/lists/*

# Extensions PHP nécessaires (PostgreSQL + Laravel)
RUN docker-php-ext-install pdo pdo_pgsql mbstring gd zip

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Copier fichiers Composer et installer dépendances PHP
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Copier tout le projet Laravel
COPY . .

# Installer les dépendances Node et builder les assets front-end
COPY package.json package-lock.json ./
RUN npm install && npm run build

# -----------------------------
# Phase 2 : Production
# -----------------------------
FROM php:8.2-fpm

WORKDIR /var/www/html

# Installer dépendances système + Nginx + Supervisor + libpq-dev
RUN apt-get update && apt-get install -y \
    nginx supervisor git libzip-dev libonig-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev libpq-dev nodejs npm \
    --no-install-recommends && rm -rf /var/lib/apt/lists/*

# Extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_pgsql mbstring gd zip

# Copier le projet buildé depuis l'étape 1
COPY --from=build /var/www/html /var/www/html

# Permissions Laravel
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Configuration PHP-FPM pour socket Unix
RUN sed -i 's|listen = 127.0.0.1:9000|listen = /var/run/php/php8.2-fpm.sock|g' /usr/local/etc/php-fpm.d/www.conf
RUN mkdir -p /var/run/php/

# Copier config Nginx et Supervisor
COPY nginx.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Exposer le port
EXPOSE 8000

# Commande de démarrage : Supervisor lance PHP-FPM + Nginx
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
