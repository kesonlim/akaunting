#!/bin/sh
set -e

# Set port if provided by Railway
PORT="${PORT:-80}"
sed -i "s/listen 80;/listen $PORT;/g" /etc/nginx/http.d/default.conf 2>/dev/null || true
sed -i "s/listen \[::\]:80;/listen [::]:$PORT;/g" /etc/nginx/http.d/default.conf 2>/dev/null || true

# Generate app key if missing
if [ -z "$APP_KEY" ]; then
    echo "Generating Application Key..."
    php artisan key:generate --force || true
fi

# Run database migrations if DB is reachable
if [ -n "$DB_HOST" ]; then
    echo "Running database migrations..."
    php artisan migrate --force || true
fi

# Clear and optimize cache
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Start PHP-FPM daemon
echo "Starting PHP-FPM..."
php-fpm -D

# Start Nginx
echo "Starting Nginx on port $PORT..."
exec nginx -g "daemon off;"
