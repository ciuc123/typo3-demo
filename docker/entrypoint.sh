#!/bin/sh
# Entrypoint script for TYPO3 application initialization

set -e

echo "Starting TYPO3 application setup..."

# Wait for database to be ready
while ! nc -z database 5432; do
    echo "Waiting for database to be ready..."
    sleep 1
done

echo "Database is ready!"

# Set proper permissions
if [ -d "/app/fileadmin" ]; then
    chmod -R 0755 /app/fileadmin
fi

if [ -d "/app/typo3temp" ]; then
    chmod -R 0755 /app/typo3temp
fi

# Install TYPO3 if not already installed
if [ ! -f "/app/public/index.php" ]; then
    echo "Installing TYPO3..."
    cd /app

    # Install composer dependencies if not present
    if [ ! -d "/app/vendor" ]; then
        composer install --no-dev --optimize-autoloader
    fi

    # Run TYPO3 setup command if available
    if command -v php &> /dev/null; then
        # Create initial directories
        mkdir -p public fileadmin typo3conf var/cache var/log var/lock

        echo "TYPO3 directory structure created"
    fi
fi

# Clear cache to ensure fresh start
if [ -d "/app/var/cache" ]; then
    rm -rf /app/var/cache/* || true
fi

echo "Setup complete. Starting PHP-FPM..."

# Execute the main process (PHP-FPM)
exec "$@"

