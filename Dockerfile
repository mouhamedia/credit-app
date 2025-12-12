## Phase 1 : Construction (Installation des dépendances)
FROM php:8.2-fpm as build

# 1. Définir le dossier de travail
WORKDIR /var/www/html

# 2. Installer les dépendances système nécessaires
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

# 3. Installer les extensions PHP (pour la Phase 1 uniquement)
RUN docker-php-ext-install pdo pdo_mysql gd mbstring zip

# 4. Installer Composer (depuis l'image Composer officielle)
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# 5. Copier les fichiers Composer pour la mise en cache
COPY composer.json composer.lock ./

# 6. Copier tous les fichiers du projet AVANT d'exécuter composer install.
COPY . /var/www/html

# 7. Installer les dépendances PHP (sans dev)
RUN composer install --no-dev --optimize-autoloader

# 8. Installer les dépendances JS
COPY package.json package-lock.json ./
RUN npm install

# 9. Builder les assets JS/CSS
RUN npm run build


## Phase 2 : Finale (Copier uniquement ce qui est nécessaire)
FROM php:8.2-fpm

# Correction CRUCIALE : Installer les dépendances système nécessaires
# pour les extensions et les outils d'exécution (Nginx, Supervisor)
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    --no-install-recommends \
    && rm -rf /var/lib/apt/lists/*

# Correction CRUCIALE : Installer les extensions PHP (y compris pdo_mysql)
# dans la Phase 2 car c'est l'image qui sert à l'exécution de PHP-FPM.
RUN docker-php-ext-install pdo pdo_mysql gd mbstring zip

# Définir le dossier de travail
WORKDIR /var/www/html

# Copier le code (incluant les vendors installés et assets buildés) depuis l'image de build
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