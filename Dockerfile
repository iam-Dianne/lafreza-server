# Use official PHP image with Apache
FROM php:8.2-apache

# Install dependencies and enable PHP extensions
RUN apt-get update && apt-get install -y \
    libapache2-mod-php \
    && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd mysqli pdo pdo_mysql \
    && a2enmod rewrite headers

# Copy your PHP files into the Apache document root
COPY . /var/www/html/

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80
