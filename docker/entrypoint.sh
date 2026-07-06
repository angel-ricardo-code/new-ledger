#!/bin/sh
set -e

PORT=${PORT:-3000}

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    APP_KEY=$(php /var/www/artisan key:generate --show 2>/dev/null)
fi
export APP_KEY

# Laravel cache for production
if [ "$APP_ENV" = "production" ]; then
    php /var/www/artisan config:cache
    php /var/www/artisan route:cache
    php /var/www/artisan view:cache
fi

# Patch nginx to listen on the desired PORT
sed -i "s/listen 80;/listen ${PORT};/" /etc/nginx/http.d/default.conf

exec supervisord -c /etc/supervisord.conf
