# backend/Dockerfile
FROM php:8.4-fpm

# Install necessary PHP extensions
RUN docker-php-ext-install pdo pdo_mysql && docker-php-ext-enable pdo

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install dependencies required by Composer
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    curl

# Set the working directory
WORKDIR /var/www/html

# Copy PHP application files
COPY . .

# Set permissions for the PHP application
RUN chown -R www-data:www-data /var/www/html

# Install NGINX
RUN apt-get update && apt-get install -y nginx && apt-get clean

# Copy NGINX configuration
COPY nginx.conf /etc/nginx/nginx.conf

# Expose NGINX and PHP-FPM ports
EXPOSE 80

# Start both PHP-FPM and NGINX
CMD ["sh", "-c", "composer install && php-fpm -D && nginx -g 'daemon off;'"]
