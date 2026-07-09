#!/bin/sh
set -e

php artisan config:clear
php artisan migrate --seed --force
php artisan config:cache
php artisan route:cache

exec php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
