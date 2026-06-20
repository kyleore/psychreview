#!/bin/sh
set -e

cd /app

# Ensure SQLite database file exists (disk is ephemeral on free tier)
mkdir -p database
[ -f database/database.sqlite ] || touch database/database.sqlite

# Clear any stale cached config from the build
php artisan config:clear || true

# Cache config for performance (routes are NOT cached because of closures)
php artisan config:cache || true

# Build/seed the database on boot
php artisan migrate --force
php artisan db:seed --force || true

# Start the web server on the port Render provides
php artisan serve --host 0.0.0.0 --port "${PORT:-8080}"
