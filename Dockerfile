FROM php:8.2-apache

# Remove ALL MPMs completely
RUN rm -f /etc/apache2/mods-enabled/mpm_* \
    && rm -f /etc/apache2/mods-available/mpm_*

# Reinstall and enable ONLY prefork MPM
RUN apt-get update \
    && apt-get install -y apache2 \
    && a2enmod mpm_prefork

# Install PHP MySQL extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy application files
COPY . /var/www/html/

# Fix permissions
RUN chown -R www-data:www-data /var/www/html

# Expose HTTP port
EXPOSE 80

# Force Apache to run in foreground
CMD ["apache2ctl", "-D", "FOREGROUND"]
