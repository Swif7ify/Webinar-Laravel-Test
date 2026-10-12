#!/bin/bash
set -e

PORT="${PORT:-80}"
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

if [ "$DB_CONNECTION" = "sqlite" ] && [ ! -f "/var/www/html/database/database.sqlite" ]; then
    touch /var/www/html/database/database.sqlite
    chown www-data:www-data /var/www/html/database/database.sqlite
    chmod 664 /var/www/html/database/database.sqlite
fi

php artisan storage:link --force || true
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force
fi

if [ "$RUN_SEED" = "true" ]; then
    echo "Seeding database..."
    php artisan db:seed --force
fi

exec apache2-foreground
