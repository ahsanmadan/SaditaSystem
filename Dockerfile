# Use serversideup/php — pre-built for Laravel, all extensions included (no OOM!)
FROM serversideup/php:8.3-cli

# Switch to root to install packages
USER root

# Install Node.js 20 + intl PHP extension (only missing one from serversideup base image)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs libicu-dev --no-install-recommends \
    && docker-php-ext-install intl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Install PHP dependencies (layered for Docker cache)
COPY composer.json composer.lock ./
RUN composer install --optimize-autoloader --no-dev --no-interaction --no-scripts

# Install Node dependencies
COPY package.json package-lock.json ./
RUN npm ci

# Copy full application
COPY . .

# Finalize composer and build assets
RUN composer dump-autoload --optimize \
    && npm run build

# Set permissions
RUN chmod -R 775 storage bootstrap/cache \
    && mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs

EXPOSE 8080

CMD sh -c "\
    echo '==> Running migrations...' && php artisan migrate --force || echo '[WARN] migrate failed'; \
    echo '==> Running seeders...' && php artisan db:seed --force || echo '[WARN] seed failed'; \
    echo '==> Storage link...' && php artisan storage:link || true; \
    echo '==> Caching config/routes/views...' && php artisan config:cache && php artisan route:cache && php artisan view:cache || echo '[WARN] cache failed'; \
    echo '==> Starting PHP server on port ${PORT:-8080}...'; \
    php -S 0.0.0.0:${PORT:-8080} -t public \
"
