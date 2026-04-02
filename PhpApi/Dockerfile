FROM php:8.2-apache

# Enable Apache mod_rewrite for API routing
RUN a2enmod rewrite

# Enable AllowOverride for .htaccess to process routing rules
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Suppress the AH00558 ServerName warning
# RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf


# Install required system packages and PHP extensions (PDO and PDO_MySQL)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_mysql

# Install Composer directly from the official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configure Apache to listen on the port specified by App Engine (default 8080)
# This is specifically required to prevent 502 Bad Gateway errors in GCP Custom Runtimes
ENV PORT=8080
RUN sed -i "s/80/\${PORT}/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Set working directory
WORKDIR /var/www/html

# Copy project files into the container
COPY . .

# Set proper permissions for the Apache user
RUN chown -R www-data:www-data /var/www/html