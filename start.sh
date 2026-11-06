#!/bin/sh
set -e

echo "==> [Railway] Booting Qavix Backend..."

# Ensure storage and bootstrap directories exist
mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

# Generate APP_KEY if not provided
if [ -z "$APP_KEY" ]; then
    echo "==> [Railway] Generating APP_KEY..."
    php artisan key:generate --force
fi

# Link storage for uploaded files and media
php artisan storage:link --no-interaction || true

# Run database migrations
echo "==> [Railway] Running migrations..."
php artisan migrate --force --no-interaction || echo "==> [Railway] Migration warning: check database connection"

# Seed default admin & initial content
echo "==> [Railway] Seeding database..."
php artisan db:seed --force --no-interaction || echo "==> [Railway] Seeding notice: already seeded or DB not ready"

# Optimize configuration and route caching
echo "==> [Railway] Caching configuration & routes..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

PORT_TO_USE="${PORT:-8080}"
echo "==> [Railway] Application ready! Serving on http://0.0.0.0:${PORT_TO_USE}"
exec php artisan serve --host=0.0.0.0 --port="${PORT_TO_USE}"

