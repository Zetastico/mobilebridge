#!/bin/sh
set -e

# Asignar puerto predeterminado si Render o el entorno no lo provee
export PORT="${PORT:-8080}"

echo "=================================================="
echo "Iniciando MobileBridge Backend en Render..."
echo "Puerto configurado: ${PORT}"
echo "=================================================="

# Asegurar directorios de almacenamiento y permisos de Laravel
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configurar Apache para escuchar en 0.0.0.0:$PORT
echo "Listen ${PORT}" > /etc/apache2/ports.conf

# Limpiar caches previas para leer variables de entorno dinámicas de Render
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Ejecutar migraciones si se especificó AUTO_MIGRATE=true
if [ "$AUTO_MIGRATE" = "true" ]; then
    echo "Ejecutando migraciones de base de datos contra Supabase..."
    php artisan migrate --force --no-interaction
    echo "Migraciones completadas correctamente."
else
    echo "AUTO_MIGRATE no está habilitado. Se omiten las migraciones."
fi

# Optimizar rutas y vistas para producción si existe APP_KEY
if [ -n "$APP_KEY" ]; then
    echo "Optimizando vistas y rutas de Laravel..."
    php artisan route:cache || true
    php artisan view:cache || true
fi

echo "MobileBridge Backend listo. Arrancando Apache en 0.0.0.0:${PORT}..."
exec apache2-foreground