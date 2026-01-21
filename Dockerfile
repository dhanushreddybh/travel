FROM php:8.2-apache

# Install MySQL extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Completely remove conflicting MPM modules
RUN a2dismod mpm_event mpm_worker 2>/dev/null || true && \
    a2enmod mpm_prefork && \
    # Remove the actual module files to prevent loading
    rm -f /etc/apache2/mods-enabled/mpm_event.* && \
    rm -f /etc/apache2/mods-enabled/mpm_worker.* && \
    # Also check mods-available
    rm -f /etc/apache2/mods-available/mpm_event.load && \
    rm -f /etc/apache2/mods-available/mpm_worker.load

# Copy project files
COPY . /var/www/html/

# Fix permissions
RUN chown -R www-data:www-data /var/www/html

# Expose HTTP port
EXPOSE 80
