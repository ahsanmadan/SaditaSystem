#!/bin/bash
set -x  # Debug: print every command to stderr (visible in Railway Deploy Logs)

PORT="${PORT:-8080}"

echo "==> PORT is: $PORT"
echo "==> Working directory: $(pwd)"
echo "==> PHP version: $(php --version | head -1)"

# Only run migrate - it's proven to work
echo "==> Migrating..."
php artisan migrate --force
echo "==> Migration complete."

# Skip ALL other artisan commands (they boot full Laravel + Filament which hangs)
# Skip: config:cache, route:cache, view:cache, cache:clear, storage:link

# Start server with IPv6 support ([::] binds to ALL interfaces including IPv6)
# Railway proxy connects via IPv6 - 0.0.0.0 only is NOT enough
echo "==> Starting PHP server on [::]:$PORT"
exec php -S "[::]:$PORT" -t public
