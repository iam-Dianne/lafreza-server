# Use official PHP image with Apache
FROM php:8.2-apache

# Copy your PHP files into the Apache document root
COPY . /var/www/html/

# Enable CORS (optional)
RUN apt-get update && apt-get install -y \
    libapache2-mod-php \
    && docker-php-ext-install mysqli \
    && a2enmod rewrite headers

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80
