FROM php-crew-php-base:latest

# Set working directory
WORKDIR /var/www/html

# Copy composer files
COPY composer.json ./
COPY config/preload.php /var/www/html/config/preload.php

# Install dependencies and optimize autoloader
RUN --mount=type=cache,target=/root/.composer/ composer install --no-dev --no-scripts --no-autoloader \
    && composer dump-autoload --optimize --no-dev --classmap-authoritative

# Copy application files
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www/html
