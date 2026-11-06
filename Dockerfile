FROM php:8.2-cli-alpine

# Install system dependencies
RUN apk add --no-cache \
    bash \
    curl \
    git \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    libxml2-dev \
    mysql-client \
    postgresql-client

# Install PHP extensions required by Laravel
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    zip \
    gd \
    bcmath \
    xml

# Install Composer from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer definitions first for caching
COPY composer.json composer.lock ./

# Install dependencies without scripts and dev
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copy the rest of the application
COPY . .

# Generate optimized autoloader
RUN composer dump-autoload --optimize

# Ensure storage directories exist with write permissions
RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache \
    && chmod +x start.sh

# Dynamic port handled by Railway
EXPOSE 8080

# Execute entrypoint
CMD ["sh", "start.sh"]
