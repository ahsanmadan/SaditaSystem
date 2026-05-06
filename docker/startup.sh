#!/bin/bash

PORT="${PORT:-8080}"
echo "==> [SaditaSystem] Booting on port $PORT"

# 1. Run migrations only (safe to re-run, idempotent)
echo "==> [DB] Migrating..."
php artisan migrate --force
echo "==> [DB] Migration done."

# 2. Storage symlink (suppress error if already exists)
php artisan storage:link 2>/dev/null || true

# 3. Clear any stale cache (prevents corrupted cache issues on restart)
php artisan config:clear 2>/dev/null || true
php artisan cache:clear  2>/dev/null || true

# 4. Cache config only (safe, fast, no Blade compilation)
php artisan config:cache || echo "[WARN] config:cache failed, using env directly"

# 5. Start server
echo "==> [Server] Starting on 0.0.0.0:$PORT"
exec php artisan serve --host=0.0.0.0 --port="$PORT"
