#!/bin/sh
set -e
echo "Waiting for MySQL..."
while ! nc -z db 3306; do
    sleep 1
done
echo "MySQL started"
php artisan migrate --force
php -d memory_limit=512M artisan db:seed
# Run migrations
# Clear and cache configurations
echo "Clearing and caching Laravel configurations..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
# Start PHP-FPM in the foreground
echo "Starting PHP-FPM..."
php-fpm
