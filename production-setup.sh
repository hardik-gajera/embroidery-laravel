#!/bin/bash

# Production Database Setup Script
echo "🗄️  Setting up production database..."

# 1. Run migrations
echo "Running migrations..."
php artisan migrate --force

# 2. Create storage symlink
echo "Creating storage symlink..."
php artisan storage:link

# 3. Create admin user
echo "Creating admin user..."
php artisan db:seed --class=AdminSeeder

# 4. Cache configuration for production
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Set proper permissions
echo "Setting permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

echo "✅ Production database setup complete!"
echo ""
echo "📋 Next steps:"
echo "1. Test admin login at /admin"
echo "2. Import designs from Odoo if needed"
echo "3. Test mobile API endpoints"