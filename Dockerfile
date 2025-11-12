FROM composer:2.9 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-scripts \
    --no-interaction \
    --no-progress

COPY . .

RUN composer install \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --no-interaction \
    --no-progress


FROM php:8.4-fpm

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        nginx \
        supervisor \
        libzip-dev \
        unzip \
    && docker-php-ext-install \
        pdo_mysql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=vendor /app /var/www/html

COPY docker/default.conf /etc/nginx/conf.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN rm -rf /etc/nginx/sites-enabled /etc/nginx/sites-available \
    && mkdir -p /run/php \
    && chown -R www-data:www-data /run/php \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && find /var/www/html/storage -type d -exec chmod 775 {} \; \
    && chmod -R ug+rwx /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
