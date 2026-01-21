FROM php:8.2-apache

# Disable conflicting MPMs (php image already uses prefork)
RUN a2dismod mpm_event mpm_worker || true

# Install MySQL extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy project files
COPY . /var/www/html/

# Fix permissions
RUN chown -R www-data:www-data /var/www/html

# Expose HTTP port
EXPOSE 80
