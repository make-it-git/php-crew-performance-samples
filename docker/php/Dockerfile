FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    icu-dev \
    libzip-dev \
    postgresql-dev \
    git \
    unzip

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_pgsql \
    zip \
    intl \
    opcache

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configure PHP
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/custom.conf

# Set working directory
WORKDIR /var/www/html

# Copy composer files
COPY composer.json composer.lock ./

# Install dependencies and optimize autoloader
RUN composer install --no-dev --no-scripts --no-autoloader \
    && composer dump-autoload --optimize --no-dev --classmap-authoritative

# Copy application files
COPY . .

# Set permissions
RUN chown -R www-data:www-data var 