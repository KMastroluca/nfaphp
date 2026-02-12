# Use the official PHP image with Apache
FROM php:8.2-apache

# 1. Install system dependencies for ZIP and Composer
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install zip

# 2. Enable Apache rewrite module (essential for many PHP apps/APIs)
RUN a2enmod rewrite

# 3. Set the working directory to the web root
WORKDIR /var/www/html

# 4. Copy composer files first to leverage Docker cache
# This prevents re-installing vendors if only your code changed
COPY composer.json composer.lock* ./

# 5. Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 6. Install dependencies
# --no-dev optimizes for production
RUN composer install --no-scripts --no-autoloader --no-dev

# 7. Copy the rest of your application code
# This includes your root files and the local vendor folder if it exists
COPY . .

# 8. Finalize Composer autoloader
RUN composer dump-autoload --optimize --no-dev

# 9. Set permissions for Apache
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80
