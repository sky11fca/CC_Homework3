FROM php:8.2-apache

# Enable Apache mod_rewrite for API routing
RUN a2enmod rewrite

# Suppress the AH00558 ServerName warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf


# Install required system packages and PHP extensions (PDO and PDO_MySQL)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_mysql

# Install Composer directly from the official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files into the container
COPY . .

# Set proper permissions for the Apache user
RUN chown -R www-data:www-data /var/www/html