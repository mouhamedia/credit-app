## Phase 1 : Construction (Installation des dépendances)
FROM php:8.2-fpm as build

# 1. Définir le dossier de travail
WORKDIR /var/www/html

# 2. Installer les dépendances système nécessaires (SANS nginx/supervisor)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    curl \
    nodejs npm \
    --no-install-recommends \
    && rm -rf /var/lib/apt/lists/*

# 3. Installer les extensions PHP
RUN docker-php-ext-install pdo pdo_mysql gd mbstring zip

# 4. Installer Composer (depuis l'image Composer officielle)
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# 5. Copier les fichiers Composer pour la mise en cache
COPY composer.json composer.lock ./

# 6. Installer les dépendances PHP (sans dev)
RUN composer install --no-dev --optimize-autoloader

# 7. Installer les dépendances JS
COPY package.json package-lock.json ./
RUN npm install

# 8. Copier tout le code du projet
COPY . /var/www/html

# 9. Builder les assets JS/CSS
RUN npm run build


## Phase 2 : Finale (Copier uniquement ce qui est nécessaire)
FROM php:8.2-fpm

# Installer Nginx et Supervisor
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    --no-install-recommends \
    && rm -rf /var/lib/apt/lists/*

# Définir le dossier de travail
WORKDIR /var/www/html

# Copier le code depuis l'image de build
COPY --from=build /var/www/html /var/www/html

# Ajouter les configurations Nginx et Supervisor
COPY nginx.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Définir les permissions pour éviter l'erreur 500
RUN chown -R www-data:www-data /var/www/html/storage \
    && chown -R www-data:www-data /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Exposer le port HTTP
EXPOSE 80

# Lancer PHP-FPM et Nginx via Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
