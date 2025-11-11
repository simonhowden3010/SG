#!/usr/bin/env sh

# ignore this file - from previous project, used for convenience for others running the project
set -e
cd /var/www/html/app

# caches
mkdir -p \
  storage/framework/cache \
  storage/framework/sessions \
  storage/framework/views \
  storage/framework/testing \
  bootstrap/cache
chmod -R 777 storage bootstrap/cache || true

# deps
if [ ! -d vendor ] || [ -z "$(ls -A vendor 2>/dev/null)" ]; then
  echo ">>> Installing Composer dependencies…"
  composer install --no-interaction --prefer-dist
fi

# env
if [ ! -f .env ]; then
  if [ -f .env.example ]; then
    cp .env.example .env
  else
    touch .env
  fi
fi
if ! grep -q '^APP_KEY=' .env || grep -q '^APP_KEY=$' .env; then
  echo ">>> Generating APP_KEY…"
  php artisan key:generate --force || true
fi

echo ">>> Starting Laravel dev server on :8000"
exec php artisan serve --host=0.0.0.0 --port=8000
