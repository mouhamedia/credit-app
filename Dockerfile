FROM php:8.2-fpm

WORKDIR /var/www/html

# Dépendances système pour PostgreSQL et PHP
RUN apt-get update && apt-get install -y \
    libpq-dev postgresql-client git unzip curl libzip-dev libonig-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev nodejs npm \
    --no-install-recommends && rm -rf /var/lib/apt/lists/*

# Extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_pgsql mbstring gd zip

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Copier projet Laravel
COPY . .

# Installer dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Builder assets front-end
RUN npm install && npm run build

# Permissions Laravel
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8000

CMD ["php-fpm"]
