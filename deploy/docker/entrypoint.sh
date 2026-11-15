#!/bin/sh
set -eu

php artisan migrate --force
php artisan app:bootstrap-admin-central

exec "$@"
