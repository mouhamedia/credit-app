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
    nodejs \
    npm \
    supervisor \
    nginx \
    --no-install-recommends \
    && rm -rf /var/lib/apt/lists/*

# 3. Installer les extensions PHP
RUN docker-php-ext-install pdo pdo_mysql gd mbstring zip

# 4. Installer Composer (depuis l'image Composer)
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# 5. Copier les fichiers Composer pour la mise en cache
COPY composer.json composer.lock ./

# 6. Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# 7. Installer les dépendances JS et Builder (si nécessaire)
COPY package.json package-lock.json ./
RUN npm install
COPY resources/js/ app/ database/ public/ routes/ .env.example ./ # Copier le reste du projet
RUN npm run build

## Phase 2 : Finale (Copier uniquement ce qui est nécessaire)
FROM php:8.2-fpm 

# 1. Installer Nginx et Supervisor dans l'image finale
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    --no-install-recommends \
    && rm -rf /var/lib/apt/lists/*

# 2. Définir le dossier de travail et copier le code depuis l'image de construction
WORKDIR /var/www/html
COPY --from=build /usr/local/bin/composer /usr/local/bin/composer
COPY --from=build /var/www/html /var/www/html

# 3. Ajouter les configurations Nginx et Supervisor
# Assurez-vous d'avoir ces fichiers : nginx.conf et supervisord.conf
COPY docker/nginx/default.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default \
    && rm -rf /etc/nginx/sites-enabled/default
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# 4. Définir les permissions (TRÈS IMPORTANT pour l'erreur 500)
RUN chown -R www-data:www-data /var/www/html/storage \
    && chown -R www-data:www-data /var/www/html/bootstrap/cache

# 5. Exposer le port par défaut (Nginx)
EXPOSE 80

# 6. Commande de lancement (lance PHP-FPM et Nginx via Supervisor)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]