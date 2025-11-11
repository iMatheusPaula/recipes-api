#!/usr/bin/env sh

set -eu

cd /var/www/html

echo "Running Laravel pre-flight tasks..."

# Ensure cache directories are writable
chown -R www-data:www-data storage bootstrap/cache

php artisan config:clear >/dev/null
php artisan route:clear  >/dev/null
php artisan view:clear   >/dev/null

php artisan storage:link >/dev/null 2>&1 || true

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    php artisan migrate --force
fi

php artisan optimize

echo "Starting process manager..."

exec "$@"