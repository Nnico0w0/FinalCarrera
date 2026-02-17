#!/bin/sh

set -e

echo "Waiting for database to be ready..."
until php artisan db:show 2>/dev/null; do
  echo "Database is unavailable - waiting..."
  sleep 2
done

echo "Database is ready!"

# Clear configuration cache
php artisan config:clear
php artisan cache:clear

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Cache configuration for better performance
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start the application
echo "Starting Laravel server..."
exec php artisan serve --host=0.0.0.0 --port=8000
