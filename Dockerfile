# -----------------------------
# Phase 1 : Build (Composer + Yarn pour les assets)
# -----------------------------
FROM php:8.2-fpm AS build

WORKDIR /var/www/html

# Dépendances système + Node.js + Yarn
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libonig-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev libpq-dev nodejs npm \
    --no-install-recommends && rm -rf /var/lib/apt/lists/*

# Extensions PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql mbstring gd zip

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Copier composer files et installer dépendances PHP (sans scripts pour éviter erreur artisan)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --prefer-dist --no-progress --no-interaction --no-scripts

# Copier package.json et installer Yarn + dépendances frontend
COPY package.json package-lock.json ./
RUN npm install -g yarn \
    && yarn config set network-timeout 600000 -g \
    && yarn install --frozen-lockfile

# Copier tout le code source Laravel
COPY . .

# Builder les assets (Vite ou Laravel Mix)
RUN yarn build

# Régénérer l'autoload Composer
RUN composer dump-autoload --optimize --classmap-authoritative

# -----------------------------
# Phase 2 : Production (PHP-FPM + Nginx + Supervisor)
# -----------------------------
FROM php:8.2-fpm

WORKDIR /var/www/html

# Dépendances runtime (Nginx + Supervisor)
RUN apt-get update && apt-get install -y \
    nginx supervisor libzip-dev libonig-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev libpq-dev \
    --no-install-recommends && rm -rf /var/lib/apt/lists/*

# Extensions PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql mbstring gd zip

# Forcer PHP-FPM à écouter sur le port 9000
RUN sed -i 's|listen = /run/php/php8.2-fpm.sock|listen = 9000|g' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's|;listen.owner = www-data|listen.owner = www-data|g' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's|;listen.group = www-data|listen.group = www-data|g' /usr/local/etc/php-fpm.d/www.conf

# Copier tout depuis la phase build
COPY --from=build /var/www/html /var/www/html

# Permissions Laravel
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Copier les configurations
COPY nginx.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Créer le script de déploiement (migrations + admin)
# Remplacer toute l'ancienne section de RUN echo '... /deploy.sh ...'
# ... par ceci :

# Créer le script de déploiement (migrations + admin)
RUN echo '#!/bin/bash\n\
echo "Exécution des migrations..."\n\
php artisan migrate --force\n\
echo "Création/mise à jour de l administrateur..."\n\
php artisan tinker --execute="\
# (Le code PHP tinker est le même)\
# ...
"' > /deploy.sh && chmod +x /deploy.sh

# --- NOUVEAU ENTRYPOINT PLUS SIMPLE ---
COPY --chmod=0755 <<'EOF' /entrypoint.sh
#!/bin/bash

# Exécuter les tâches de déploiement (migrations, admin)
/deploy.sh

# Démarrer Supervisor en mode non-daemon (premier plan)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf -n
EOF

ENTRYPOINT ["/entrypoint.sh"]
# Supprimez le `wait` et la logique `nc -z` (Supervisor gère déjà le démarrage des processus)