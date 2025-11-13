#!/usr/bin/env sh

set -eu

cd /var/www/html

echo "Running Laravel pre-flight tasks..."

# Ensure required directories exist and are writable
mkdir -p \
    storage/framework/cache \
    storage/framework/data \
    storage/framework/sessions \
    storage/framework/views \
    bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache

php artisan storage:link || true

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    php artisan migrate --force
fi

echo "Starting process manager..."

exec "$@"