FROM php:8.2-apache

# Disable other MPMs, enable only prefork (required for PHP)
RUN a2dismod mpm_event mpm_worker || true \
    && a2enmod mpm_prefork

# Install MySQL extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy project files
COPY . /var/www/html/

# Fix permissions
RUN chown -R www-data:www-data /var/www/html

# Expose Apache port
EXPOSE 80
