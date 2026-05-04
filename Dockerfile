FROM php:8.3-cli-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    icu-dev \
    oniguruma-dev \
    libxml2-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    nodejs \
    npm

# Install PHP extensions from pre-compiled source (avoids OOM)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        intl \
        zip \
        tokenizer \
        ctype \
        fileinfo \
        xml

# Get Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Install PHP dependencies
COPY composer.json composer.lock ./
RUN composer install --optimize-autoloader --no-dev --no-interaction --no-scripts

# Install Node and build assets
COPY package.json package-lock.json ./
RUN npm ci

# Copy full application
COPY . .

# Run composer scripts after full copy
RUN composer dump-autoload --optimize

# Build Vite assets
RUN npm run build

# Set permissions
RUN chmod -R 775 storage bootstrap/cache \
    && mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs

EXPOSE 8080

CMD sh -c "php artisan migrate --force && php artisan db:seed --force && php artisan storage:link && php artisan config:cache && php artisan route:cache && php artisan view:cache && php -S 0.0.0.0:${PORT:-8080} -t public"
