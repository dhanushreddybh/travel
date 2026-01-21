FROM php:8.2-apache

# Install MySQL extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Create a custom entrypoint script that forcefully fixes MPM issues
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
echo "Fixing Apache MPM configuration..."\n\
\n\
# Remove all MPM module symlinks\n\
rm -f /etc/apache2/mods-enabled/mpm_*.conf\n\
rm -f /etc/apache2/mods-enabled/mpm_*.load\n\
\n\
# Enable only mpm_prefork\n\
ln -sf /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf\n\
ln -sf /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load\n\
\n\
echo "MPM configuration fixed. Starting Apache..."\n\
\n\
# Start Apache in foreground\n\
exec apache2-foreground "$@"' > /usr/local/bin/custom-entrypoint.sh

# Make the script executable
RUN chmod +x /usr/local/bin/custom-entrypoint.sh

# Copy project files
COPY . /var/www/html/

# Fix permissions
RUN chown -R www-data:www-data /var/www/html

# Expose HTTP port
EXPOSE 80

# Use custom entrypoint
ENTRYPOINT ["/usr/local/bin/custom-entrypoint.sh"]
