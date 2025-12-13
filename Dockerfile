# -------- Phase 1 : Build (Inchagée, elle est très bien) --------
FROM php:8.2-fpm AS build

WORKDIR /var/www/html

# ... (Le contenu de la Phase 1 reste identique) ...

# -------- Phase 2 : Production (Adaptée pour Nginx/Supervisor) --------
FROM php:8.2-fpm

WORKDIR /var/www/html

# Installer les outils requis
RUN apt-get update && apt-get install -y \
    nginx supervisor git libzip-dev libonig-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    --no-install-recommends && rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP de production
RUN docker-php-ext-install pdo pdo_mysql mbstring gd zip

# Copier le code + vendor + assets depuis build
COPY --from=build /var/www/html /var/www/html

# Configurer PHP-FPM pour écouter via un socket pour Nginx (par défaut pour php-fpm)
RUN sed -i 's/listen = 127.0.0.1:9000/listen = \/var\/run\/php\/php8.2-fpm.sock/g' /etc/php/8.2/fpm/pool.d/www.conf 
RUN mkdir -p /var/run/php/

# Config Nginx + Supervisor
COPY nginx.conf /etc/nginx/sites-available/default
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Permissions (Très important pour Laravel)
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Exposer le port que Nginx écoute
EXPOSE 8000 

# Commande de Démarrage
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]