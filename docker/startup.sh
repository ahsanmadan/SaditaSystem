#!/bin/bash

PORT="${PORT:-8080}"

echo "==> [SaditaSystem] Starting on port $PORT"

echo "==> [DB] Running migrations..."
php artisan migrate --force && echo "[OK] Migrate done" || echo "[WARN] Migrate failed"

# NOTE: Seeder removed from startup - data already in DB and seeder hangs on duplicate inserts
# To re-seed manually: php artisan db:seed --force

echo "==> [Laravel] Storage link..."
php artisan storage:link 2>/dev/null || true

echo "==> [Laravel] Caching..."
php artisan config:cache && echo "[OK] Config cached" || echo "[WARN] Config cache failed"
php artisan route:cache  && echo "[OK] Route cached"  || echo "[WARN] Route cache failed"
php artisan view:cache   && echo "[OK] View cached"   || echo "[WARN] View cache failed"

echo "==> [Server] Starting php artisan serve on 0.0.0.0:$PORT ..."
# Try artisan serve first, fall back to php built-in server
php artisan serve --host=0.0.0.0 --port="$PORT" 2>&1 &
SERVE_PID=$!

# Give it 3 seconds to start
sleep 3

# Check if it's still running
if kill -0 $SERVE_PID 2>/dev/null; then
    echo "[OK] php artisan serve is running (PID: $SERVE_PID)"
    wait $SERVE_PID
else
    echo "[WARN] artisan serve crashed, falling back to php -S"
    exec php -S 0.0.0.0:"$PORT" -t public
fi
