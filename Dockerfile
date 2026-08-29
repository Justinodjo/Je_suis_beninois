# =========================
# ETAPE 1 : Node / Vite
# =========================
FROM node:24 AS node_builder

WORKDIR /app

COPY package*.json ./

RUN npm install

COPY . .

RUN npm run build


# =========================
# ETAPE 2 : PHP / Laravel
# =========================
FROM php:8.3-cli

WORKDIR /var/www/html


# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install \
    pdo_pgsql \
    zip \
    && rm -rf /var/lib/apt/lists/*


# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


# Copier les fichiers du projet
COPY . .


# Installer les dépendances PHP
RUN composer install \
    --optimize-autoloader \
    --no-interaction


# Copier les fichiers Vite générés
COPY --from=node_builder /app/public/build ./public/build


# Permissions Laravel
RUN chmod -R 775 storage bootstrap/cache


# Port Render
EXPOSE 10000


# Démarrage Laravel
CMD php artisan migrate --force && \
    php artisan db:seed --force && \
    php artisan storage:link || true && \
    php artisan serve --host=0.0.0.0 --port=$PORT