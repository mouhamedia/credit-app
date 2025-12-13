# -------- Phase 1 : Build (Construction de l'Application) --------
FROM php:8.2-fpm AS build

WORKDIR /var/www/html

# Dépendances système pour le build (Git, Unzip, outils pour extensions PHP, Node/NPM)
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libonig-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev curl nodejs npm \
    --no-install-recommends && rm -rf /var/lib/apt/lists/*

# Extensions PHP nécessaires (IMPORTANT : pdo_pgsql pour PostgreSQL)
RUN docker-php-ext-install pdo pdo_pgsql mbstring gd zip

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Copier fichiers Composer + installer les dépendances (sans dev pour la production)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Copier le projet Laravel
COPY . .

# Installer npm + builder assets (pour les assets front-end)
COPY package.json package-lock.json ./
RUN npm install && npm run build


# -------- Phase 2 : Production (Image Légère pour le Déploiement) --------
FROM php:8.2-fpm

WORKDIR /var/www/html

# Installer Nginx, Supervisor et les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    nginx supervisor git libzip-dev libonig-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    --no-install-recommends && rm -rf /var/lib/apt/lists/*

# Extensions PHP de production (IMPORTANT : pdo_pgsql pour PostgreSQL)
RUN docker-php-ext-install pdo pdo_pgsql mbstring gd zip

# Copier le code + vendor + assets depuis l'image de build
COPY --from=build /var/www/html /var/www/html

# CONFIGURATION CRITIQUE : PHP-FPM et Nginx
# CORRECTION DU CHEMIN PHP-FPM : Utilisation du chemin standard /usr/local/etc/php-fpm.d/www.conf
# Ceci permet à Nginx et PHP-FPM de communiquer via un socket local.
RUN sed -i 's/listen = 127.0.0.1:9000/listen = \/var\/run\/php\/php8.2-fpm.sock/g' /usr/local/etc/php-fpm.d/www.conf
RUN mkdir -p /var/run/php/

# Config Nginx + Supervisor
# Copier les configurations Nginx et Supervisor (que vous devez fournir)
COPY nginx.conf /etc/nginx/sites-available/default
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Permissions (Très important pour Laravel)
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Exposer le port que Nginx écoute
EXPOSE 8000 

# Commande de Démarrage (Supervisor gère Nginx et PHP-FPM)
# Ceci démarre Supervisor qui lance Nginx et PHP-FPM en parallèle.
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]