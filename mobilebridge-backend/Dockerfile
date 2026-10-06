# ==============================================================================
# Multi-Stage Dockerfile para MobileBridge Backend (Laravel 12 + PHP 8.2 + Render)
# ==============================================================================

# ------------------------------------------------------------------------------
# Etapa 1: Compilación de Assets Frontend con Node.js & Vite
# ------------------------------------------------------------------------------
FROM node:20-alpine AS node_builder

WORKDIR /app

# Copiar manifiestos de dependencias
COPY package.json package-lock.json* ./

# Instalar dependencias npm
RUN npm install --no-audit --prefer-offline || npm install

# Copiar fuentes requeridas para la compilación de Vite
COPY vite.config.js ./
COPY resources/ ./resources/

# Compilar assets en public/build (asegura directorio aunque no existan assets complejos)
RUN mkdir -p public/build && (npm run build || true)

# ------------------------------------------------------------------------------
# Etapa 2: Imagen de Producción PHP 8.2 con Apache
# ------------------------------------------------------------------------------
FROM php:8.2-apache

# Metadatos del contenedor
LABEL maintainer="MobileBridge Team"
LABEL description="MobileBridge Backend API & Dashboard en Render"

# Instalar dependencias del sistema y librerías requeridas por PHP y PostgreSQL
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    ca-certificates \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_pgsql \
        pgsql \
        zip \
        bcmath \
        opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Copiar Composer oficial desde la imagen de Composer
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

# Habilitar módulos de Apache requeridos por Laravel
RUN a2enmod rewrite headers

# Configurar VirtualHost de Apache con DocumentRoot en /var/www/html/public
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Configuración recomendada de PHP para producción
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=10000'; \
    echo 'opcache.revalidate_freq=2'; \
    echo 'opcache.fast_shutdown=1'; \
    echo 'upload_max_filesize=64M'; \
    echo 'post_max_size=64M'; \
    echo 'memory_limit=256M'; \
} > /usr/local/etc/php/conf.d/production.ini

# Establecer directorio de trabajo de la aplicación
WORKDIR /var/www/html

# Copiar composer.json y composer.lock primero para aprovechar el cache de capas Docker
COPY composer.json composer.lock* ./

# Instalar dependencias de producción de PHP sin ejecutar scripts aún
RUN composer install --no-dev --no-scripts --no-interaction --prefer-dist --optimize-autoloader

# Copiar el código fuente completo del proyecto (respetando .dockerignore)
COPY . .

# Copiar los assets compilados desde la etapa de Node.js
COPY --from=node_builder /app/public/build ./public/build

# Regenerar autoloader de Composer optimizado con todas las clases
RUN composer dump-autoload --optimize --no-dev

# Crear directorios requeridos por Laravel y ajustar permisos
RUN mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Instalar y otorgar permisos de ejecución al entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Puerto de escucha predeterminado (Render sobreescribirá la variable PORT en runtime)
EXPOSE 8080

# Definir el script de arranque como ENTRYPOINT
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
