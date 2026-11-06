#!/bin/sh
# Docker-only installation: creates the missing Laravel framework skeleton
# without overwriting DealBD source files in this starter directory.
set -eu
cd /var/www/html

if [ ! -f artisan ] || [ ! -f composer.json ]; then
  echo 'Creating the Laravel 13 base application (no local PHP/Composer needed)...'
  rm -rf /tmp/dealbd-laravel-base
  composer create-project laravel/laravel /tmp/dealbd-laravel-base '^13.0' \
    --no-interaction --no-install --no-scripts
  # Copy only missing files: the DealBD overlay takes precedence.
  # Alpine's `cp -n` skips an existing destination directory instead of
  # merging into it, so create directories and copy files individually.
  find /tmp/dealbd-laravel-base -type d | while IFS= read -r source; do
    relative=${source#/tmp/dealbd-laravel-base}
    mkdir -p /var/www/html${relative}
  done
  find /tmp/dealbd-laravel-base -type f | while IFS= read -r source; do
    relative=${source#/tmp/dealbd-laravel-base}
    if [ ! -e /var/www/html${relative} ]; then
      cp -p $source /var/www/html${relative}
    fi
  done
fi

if [ ! -f .env ]; then
  echo 'Missing .env. Copy .env.docker.example to .env and configure passwords first.' >&2
  exit 1
fi

if grep -q 'REPLACE_WITH_' .env; then
  echo 'Edit .env and replace the example DB passwords with your own strong values.' >&2
  exit 1
fi

if [ ! -f artisan ] || [ ! -f composer.json ]; then
  echo 'Laravel skeleton was not installed correctly.' >&2
  exit 1
fi

composer install --no-interaction --prefer-dist --optimize-autoloader

# PHP-FPM runs as www-data and must write logs, sessions, and compiled views.
chmod -R a+rwX storage bootstrap/cache

if ! grep -q '^APP_KEY=base64:' .env; then
  php artisan key:generate --force
fi

echo 'Bootstrap complete. Run docker compose up -d, then migrate the database.'
