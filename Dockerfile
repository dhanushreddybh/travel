FROM php:8.2-apache

# Set Apache document root explicitly
ENV APACHE_DOCUMENT_ROOT=/var/www/html

# Update Apache config to use the new document root
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set DirectoryIndex to prioritize login.php
RUN echo "DirectoryIndex login.php index.php index.html" >> /etc/apache2/apache2.conf

# Install MySQL extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Create entrypoint script to fix MPM at runtime
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
# Remove all MPM module symlinks\n\
rm -f /etc/apache2/mods-enabled/mpm_event.* 2>/dev/null || true\n\
rm -f /etc/apache2/mods-enabled/mpm_worker.* 2>/dev/null || true\n\
\n\
# Ensure only mpm_prefork is enabled\n\
if [ ! -f /etc/apache2/mods-enabled/mpm_prefork.load ]; then\n\
    ln -s /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load\n\
fi\n\
if [ ! -f /etc/apache2/mods-enabled/mpm_prefork.conf ]; then\n\
    ln -s /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf\n\
fi\n\
\n\
# Start Apache\n\
exec apache2-foreground "$@"' > /usr/local/bin/docker-entrypoint.sh

RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Copy application files
COPY . /var/www/html/

# Fix permissions
RUN chown -R www-data:www-data /var/www/html

# Expose HTTP port
EXPOSE 80

# Use custom entrypoint
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
