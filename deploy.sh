#!/bin/bash

# Stop script on error
set -e

echo "🚀 Starting deployment..."

# 1. Enable Maintenance Mode
php artisan down || true
echo "🔒 Application is now in maintenance mode."

# 2. Update Codebase
git pull origin main
echo "📥 Codebase updated."

# 3. Install Dependencies
# Ensure we are using the correct PHP version if multiple are installed
# Update this line if you need a specific path, e.g., /usr/bin/php8.3
composer install --no-dev --optimize-autoloader
echo "📦 Dependencies installed."

# 4. Update Database
# Force is used to run migrations in production
php artisan migrate --force
echo "🗄️  Database migrated."

# 5. Clear Caches
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "🧹 Caches cleared and rebuilt."

# 6. Build Assets (Optional - if you build on server)
# npm install && npm run build
# echo "🎨 Assets built."

# 7. Restart Queues
php artisan queue:restart
echo "lu  Queue workers restarted."

# 8. Disable Maintenance Mode
php artisan up
echo "✅ Application is live!"
