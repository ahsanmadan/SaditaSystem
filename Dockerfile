# Standard PHP 8.3 + Apache image — proven, no entrypoint conflicts
FROM php:8.3-apache

# Enable Apache mod_rewrite for Laravel
RUN a2enmod rewrite

# Install system dependencies + all required PHP extensions
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    unzip \
    curl \
    git \
    --no-install-recommends \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        bcmath \
        intl \
        zip \
        gd \
        pcntl \
        opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install Node.js 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs --no-install-recommends \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Set Apache document root to Laravel public/
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

# Install PHP dependencies
COPY composer.json composer.lock ./
RUN composer install --optimize-autoloader --no-dev --no-interaction --no-scripts

# Install Node dependencies
COPY package.json package-lock.json ./
RUN npm ci

# Copy full application
COPY . .

# Finalize
RUN composer dump-autoload --optimize \
    && npm run build

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache \
    && mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs

EXPOSE 8080

# Startup: configure Apache port dynamically, run artisan, start Apache
CMD sh -c "\
    export PORT=${PORT:-8080}; \
    sed -i \"s/Listen 80/Listen \$PORT/\" /etc/apache2/ports.conf; \
    sed -i \"s/<VirtualHost \*:80>/<VirtualHost *:\$PORT>/\" /etc/apache2/sites-available/000-default.conf; \
    echo '==> Migrate...' && php artisan migrate --force || echo '[WARN] migrate failed'; \
    echo '==> Seed...' && php artisan db:seed --force || echo '[WARN] seed failed'; \
    php artisan storage:link || true; \
    php artisan config:cache || echo '[WARN] config:cache failed'; \
    php artisan route:cache || true; \
    php artisan view:cache || true; \
    echo '==> Starting Apache on port '\$PORT'...'; \
    apache2-foreground \
"
