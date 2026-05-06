#!/bin/bash
set -e

PORT="${PORT:-8080}"

echo "==> [SaditaSystem] Starting on port $PORT"

# Reconfigure Apache to listen on the PORT provided by Railway
echo "Listen $PORT" > /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:$PORT>/" /etc/apache2/sites-available/000-default.conf

echo "==> [DB] Running migrations..."
php artisan migrate --force || echo "[WARN] Migration failed or nothing to migrate"

echo "==> [DB] Running seeders..."
php artisan db:seed --force || echo "[WARN] Seeder failed"

echo "==> [Laravel] Linking storage..."
php artisan storage:link || true

echo "==> [Laravel] Caching config, routes, views..."
php artisan config:cache || echo "[WARN] config:cache failed"
php artisan route:cache || echo "[WARN] route:cache failed"
php artisan view:cache  || echo "[WARN] view:cache failed"

echo "==> [Apache] Starting on port $PORT..."
exec apache2-foreground
