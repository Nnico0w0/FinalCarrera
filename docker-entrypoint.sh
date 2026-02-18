#!/bin/bash

set -e

echo "Waiting for database to be ready..."

# Use psql to check PostgreSQL availability with environment variables
DB_HOST="${DB_HOST:-db}"
DB_PORT="${DB_PORT:-5432}"
DB_USERNAME="${DB_USERNAME:-postgres}"
DB_DATABASE="${DB_DATABASE:-finalcarrera}"
DB_PASSWORD="${DB_PASSWORD:-secret}"

until PGPASSWORD="${DB_PASSWORD}" psql -h "${DB_HOST}" -p "${DB_PORT}" -U "${DB_USERNAME}" -d "${DB_DATABASE}" -c '\q' 2>/dev/null; do
  echo "Database is unavailable - waiting..."
  sleep 2
done

echo "Database is ready!"

# Clear configuration cache
php artisan config:clear
php artisan cache:clear

# Run migrations optionally (default: skip, setup script handles it)
if [ "${RUN_MIGRATIONS_ON_START:-false}" = "true" ]; then
  echo "Running migrations..."
  php artisan migrate --force
else
  echo "Skipping automatic migrations (controlled by setup script)"
fi

# Cache configuration for better performance (only in production)
if [ "$APP_ENV" = "production" ]; then
  echo "Caching configuration for production..."
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
fi

# Start the application
echo "Starting Laravel server..."
exec php artisan serve --host=0.0.0.0 --port=8000
