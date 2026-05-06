# Standard PHP 8.3 CLI — clean, minimal, no entrypoint conflicts
FROM php:8.3-cli

# Install system deps + all PHP extensions needed by Laravel/Filament
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
RUN chmod -R 775 storage bootstrap/cache \
    && mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs

EXPOSE 8080

# Startup script
COPY docker/startup.sh /startup.sh
RUN chmod +x /startup.sh

CMD ["/startup.sh"]
