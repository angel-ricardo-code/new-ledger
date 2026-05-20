#!/bin/sh
set -e

PORT=${PORT:-80}

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    APP_KEY=$(php /var/www/artisan key:generate --show 2>/dev/null)
fi
export APP_KEY

# Patch nginx to listen on Render's PORT
sed -i "s/listen 80;/listen ${PORT};/" /etc/nginx/http.d/default.conf

exec supervisord -c /etc/supervisord.conf
