FROM php:8.2-apache

# Set Apache document root explicitly
ENV APACHE_DOCUMENT_ROOT=/var/www/html

# Update Apache config to use the new document root
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Disable conflicting MPMs (prefork already enabled in php image)
RUN a2dismod mpm_event mpm_worker || true

# Ensure index files are recognised
RUN echo "DirectoryIndex index.php index.html" >> /etc/apache2/apache2.conf

# Install MySQL extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy application files
COPY . /var/www/html/

# Fix permissions
RUN chown -R www-data:www-data /var/www/html

# Expose HTTP port
EXPOSE 80
