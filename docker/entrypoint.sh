#!/bin/sh
set -e

# =============================================================================
# Presensi Skansaba Web Entrypoint
# =============================================================================
# Environment variables for database operations:
#   RUN_MIGRATE_FRESH=true  - Run migrate:fresh (WARNING: destroys data)
#   RUN_SEEDER=true         - Run database seeders
# =============================================================================

echo "🚀 Starting Presensi Skansaba Web..."

# Create required directories
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs

# Storage symlink
if [ ! -e /var/www/html/public/storage ]; then
  echo "📁 Creating storage symlink..."
  php artisan storage:link
fi

# Database migrations
if [ "$RUN_MIGRATE_FRESH" = "true" ]; then
  echo "⚠️  WARNING: Running migrate:fresh - this will destroy all data!"
  php artisan migrate:fresh --force
  if [ "$RUN_SEEDER" = "true" ]; then
    echo "🌱 Running database seeders..."
    php artisan db:seed --force
  fi
else
  echo "📦 Running database migrations..."
  php artisan migrate --force
  if [ "$RUN_SEEDER" = "true" ]; then
    echo "🌱 Running database seeders..."
    php artisan db:seed --force
  fi
fi

# Optimize Laravel
echo "⚡ Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "✅ Starting supervisord..."
exec "$@"
