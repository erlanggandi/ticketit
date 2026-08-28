# syntax=docker/dockerfile:1

# Stage 1: Build frontend assets with Node
FROM node:24-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY vite.config.js ./
COPY resources ./resources
COPY public ./public
RUN npm run build

# Stage 2: PHP runtime
FROM php:8.5-fpm

# System deps for pgsql + intl + zip + gd
RUN apt-get update && apt-get install -y --no-install-recommends \
        libpq-dev \
        libicu-dev \
        libzip-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libonig-dev \
        unzip \
        git \
        curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_pgsql \
        pgsql \
        intl \
        bcmath \
        zip \
        gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy app source (respects .dockerignore, .env is excluded)
COPY . .

# Copy built assets from node stage
COPY --from=assets /app/public/build ./public/build

# Install PHP deps (no dev for smaller image; use --no-interaction)
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-progress \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# PHP production-ish defaults (can be overridden via environment or docker/php/php.ini)
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && { \
        echo "opcache.enable=1"; \
        echo "opcache.memory_consumption=128"; \
        echo "opcache.interned_strings_buffer=8"; \
        echo "opcache.max_accelerated_files=4000"; \
        echo "opcache.validate_timestamps=0"; \
    } > /usr/local/etc/php/conf.d/opcache.ini \
    && { \
        echo "upload_max_filesize=10M"; \
        echo "post_max_size=10M"; \
        echo "memory_limit=256M"; \
    } > /usr/local/etc/php/conf.d/custom.ini

EXPOSE 9000
CMD ["php-fpm"]
