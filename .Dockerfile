
FROM php:8.2-apache

# Install MariaDB + utilities
RUN apt-get update && apt-get install -y \
    mariadb-server \
    mariadb-client \
    supervisor \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite
RUN a2enmod rewrite

# Copy app
COPY ./ /var/www/html/

# Copy SQL init file
COPY init.sql /docker-entrypoint-initdb.d/init.sql

# Copy startup script
COPY start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80

CMD ["/start.sh"]