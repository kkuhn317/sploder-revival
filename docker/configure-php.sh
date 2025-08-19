#!/bin/bash

# Configure PHP based on environment variables
# Default to development settings if not specified

if [ "${PHP_ENVIRONMENT:-development}" = "production" ]; then
    echo "Configuring PHP for production environment..."
    echo "display_errors = Off" >> /usr/local/etc/php/php.ini
    echo "display_startup_errors = Off" >> /usr/local/etc/php/php.ini
    echo "error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT" >> /usr/local/etc/php/php.ini
else
    echo "Configuring PHP for development environment..."
    echo "display_errors = On" >> /usr/local/etc/php/php.ini
    echo "display_startup_errors = On" >> /usr/local/etc/php/php.ini
    echo "error_reporting = E_ALL" >> /usr/local/etc/php/php.ini
fi

echo "Starting cron daemon..."
cron

echo "Starting Apache..."
exec apache2-foreground
