#!/bin/sh
set -e

# Render assigns the port to listen on via $PORT
PORT="${PORT:-8000}"

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run any pending migrations (safe to run every boot - Laravel skips already-run ones)
php artisan migrate --force

# Make uploaded reference photos viewable (public/storage -> storage/app/public)
php artisan storage:link || true

echo "Starting on port $PORT"
php artisan serve --host=0.0.0.0 --port="$PORT"
