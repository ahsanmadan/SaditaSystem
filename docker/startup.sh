#!/bin/bash

PORT="${PORT:-8080}"

echo "==> [SaditaSystem] Starting on port $PORT"

echo "==> [DB] Running migrations..."
php artisan migrate --force && echo "[OK] Migrate done" || echo "[WARN] Migrate failed"

echo "==> [DB] Running seeders..."
php artisan db:seed --force && echo "[OK] Seed done" || echo "[WARN] Seed failed"

echo "==> [Laravel] Storage link..."
php artisan storage:link 2>/dev/null || true

echo "==> [Laravel] Caching..."
php artisan config:cache && echo "[OK] Config cached" || echo "[WARN] Config cache failed"
php artisan route:cache  && echo "[OK] Route cached"  || echo "[WARN] Route cache failed"
php artisan view:cache   && echo "[OK] View cached"   || echo "[WARN] View cache failed"

echo "==> [Server] Starting php artisan serve on 0.0.0.0:$PORT ..."
exec php artisan serve --host=0.0.0.0 --port="$PORT"
