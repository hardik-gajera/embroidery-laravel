#!/bin/bash

# Laravel Deployment Preparation Script for Hostinger
echo "🚀 Preparing Laravel app for Hostinger deployment..."

# 1. Optimize for production
echo "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# 2. Clear development caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# 3. Generate optimized autoloader
composer install --optimize-autoloader --no-dev

# 4. Create deployment archive (exclude unnecessary files)
echo "Creating deployment archive..."
tar -czf laravel-deployment.tar.gz \
  --exclude=node_modules \
  --exclude=.git \
  --exclude=tests \
  --exclude=storage/logs/* \
  --exclude=.env \
  --exclude=*.log \
  .

echo "✅ Deployment package ready: laravel-deployment.tar.gz"
echo ""
echo "📋 Next steps:"
echo "1. Upload laravel-deployment.tar.gz to Hostinger"
echo "2. Extract in public_html folder"
echo "3. Configure .env file"
echo "4. Run database migrations"