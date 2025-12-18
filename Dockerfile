# =============================
# Étape 1 : Build (Composer & Assets)
# =============================
FROM php:8.2-fpm AS build

WORKDIR /var/www/html

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libonig-dev libpng-dev \
    libjpeg62-turbo-dev libfreetype6-dev libpq-dev nodejs npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql mbstring gd zip

# Correction de l'erreur Git "dubious ownership"
RUN git config --global --add safe.directory /var/www/html

# Installation de Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier les fichiers de configuration (optimise le cache)
COPY composer.json composer.lock ./

# Installer les dépendances PHP
RUN composer install --no-dev --no-scripts --no-interaction --prefer-dist

# Copier le reste du projet
COPY . .

# Installer Yarn et compiler les assets (Frontend)
RUN npm install -g yarn \
    && yarn install \
    && yarn build

# Générer l'autoloader (CORRECTED TYPO)
RUN composer dump-autoload --no-dev --optimize \
    && php artisan package:discover --ansi

# =============================
# Étape 2 : Production (Image finale)
# =============================
FROM php:8.2-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    libzip-dev libonig-dev libpng-dev \
    libjpeg62-turbo-dev libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=build /var/www/html /var/www/html

# Fixer les permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]