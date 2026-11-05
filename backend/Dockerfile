# Dockerfile: Aswaja CyberShield - Laravel REST API Backend
# PHP 8.4 FPM Alpine Lightweight Container

FROM php:8.4-cli-alpine

# Set working directory
WORKDIR /var/www/html

# Install system dependencies & build tools
RUN apk add --no-cache \
    curl \
    git \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    sqlite-dev

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_sqlite \
    mbstring \
    xml \
    bcmath \
    intl \
    zip \
    fileinfo

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application source code
COPY . /var/www/html

# Ensure storage and bootstrap/cache permissions
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose Laravel default port
EXPOSE 8000

# Start Laravel development server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

