#!/usr/bin/env sh

set -eu

cd /var/www/html

# Detect if running in Lambda (read-only filesystem except /tmp)
if [ -n "${LAMBDA_TASK_ROOT:-}" ]; then
    echo "Running in Lambda environment..."
    
    # Lambda: Use /tmp for cache directories
    export CACHE_DRIVER=array
    export SESSION_DRIVER=array
    export VIEW_COMPILED_PATH=/tmp/storage/framework/views
    
    # Skip directory creation and permissions in Lambda
    echo "Skipping file system setup (Lambda read-only filesystem)"
else
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
fi

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    php artisan migrate --force
fi

# If running in Lambda, execute command directly
# Otherwise, start process manager
# Lambda: always receives command as single string argument
echo "Starting process manager..."
if [ -n "${LAMBDA_TASK_ROOT:-}" ]; then
    exec sh -c "$1"
else
    exec "$@"
fi