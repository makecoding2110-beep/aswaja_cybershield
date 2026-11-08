# FrankenPHP Production Container
FROM dunglas/frankenphp:1-php8.4

WORKDIR /app

# PHP Extensions
RUN install-php-extensions \
    pdo_pgsql \
    pgsql \
    pdo_mysql \
    gd \
    intl \
    zip \
    opcache

# Composer Binary
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Source Code
COPY . /app

# Backend Dependencies
RUN cd /app/backend && composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-scripts

# Storage Permissions
RUN mkdir -p /tmp/storage/app /tmp/storage/framework/cache/data /tmp/storage/framework/sessions /tmp/storage/framework/views /tmp/storage/logs /tmp/storage/views \
    && chmod -R 777 /tmp/storage /app/backend/storage /app/backend/bootstrap/cache

# Caddy Configuration
COPY Caddyfile /etc/caddy/Caddyfile
COPY Caddyfile /etc/frankenphp/Caddyfile

ENV PORT=80
ENV APP_STORAGE=/tmp/storage
EXPOSE 80

CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]

