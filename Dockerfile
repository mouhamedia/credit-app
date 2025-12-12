# -------- Phase 1 : Build --------
FROM php:8.2-fpm AS build

WORKDIR /var/www/html

# Dépendances système
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libonig-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev curl nodejs npm \
    --no-install-recommends && rm -rf /var/lib/apt/lists/*

# Extensions PHP
RUN docker-php-ext-install pdo pdo_mysql mbstring gd zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Copier fichiers Composer + installer les dépendances
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Copier le projet
COPY . .

# Installer npm + builder assets
COPY package.json package-lock.json ./
RUN npm install && npm run build

# -------- Phase 2 : Production --------
FROM php:8.2-fpm

WORKDIR /var/www/html

# Installer PHP-FPM, Nginx, Supervisor
RUN apt-get update && apt-get install -y \
    nginx supervisor git unzip libzip-dev libonig-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    --no-install-recommends && rm -rf /var/lib/apt/lists/*

# Extensions PHP
RUN docker-php-ext-install pdo pdo_mysql mbstring gd zip

# Copier le code + vendor + assets depuis build
COPY --from=build /var/www/html /var/www/html

# Config Nginx + Supervisor
COPY nginx.conf /etc/nginx/sites-available/default
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
