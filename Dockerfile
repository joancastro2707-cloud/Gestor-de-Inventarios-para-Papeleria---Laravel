# ==========================================
# STAGE 1: Compilación de assets Frontend
# ==========================================
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY resources ./resources
COPY vite.config.js tailwind.config.js postcss.config.js ./
RUN npm run build

# ==========================================
# STAGE 2: Dependencias PHP con Composer
# ==========================================
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader

# ==========================================
# STAGE 3: Imagen final mínima (Alpine + No-Root)
# ==========================================
FROM php:8.3-fpm-alpine

# Instalar dependencias del sistema y extensiones PHP necesarias
RUN apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    $PHPIZE_DEPS \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql mbstring zip gd intl opcache \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del $PHPIZE_DEPS

# Crear grupo y usuario no-root
RUN addgroup -g 1000 -S appgroup \
    && adduser -u 1000 -S appuser -G appgroup

WORKDIR /var/www/html

# Copiar aplicación, dependencias compiladas y assets
COPY --chown=appuser:appgroup . .
COPY --from=vendor --chown=appuser:appgroup /app/vendor ./vendor
COPY --from=frontend --chown=appuser:appgroup /app/public/build ./public/build

# Asegurar permisos de escritura en storage y cache
RUN mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
    && chown -R appuser:appgroup storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Ejecutar contenedor como usuario no-root
USER appuser

EXPOSE 9000
CMD ["php-fpm"]