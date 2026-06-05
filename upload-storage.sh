#!/bin/bash
# Upload Storage Files to Server
# This script creates compressed archives of all design, category, and package images
# for uploading to the production server.

STORAGE_PATH="storage/app/public"

echo "📦 Packaging storage files for server upload..."
echo ""

# Package design images
echo "🖼️  Packaging design images ($(ls $STORAGE_PATH/designs/images/ | wc -l) files)..."
tar -czf storage_design_images.tar.gz -C $STORAGE_PATH/designs images/
echo "✅ Created: storage_design_images.tar.gz"

# Package design EMB files
echo "📄 Packaging design EMB files ($(ls $STORAGE_PATH/designs/files/ | wc -l) files)..."
tar -czf storage_design_files.tar.gz -C $STORAGE_PATH/designs files/
echo "✅ Created: storage_design_files.tar.gz"

# Package category images
echo "🏷️  Packaging category images ($(ls $STORAGE_PATH/categories/ | wc -l) files)..."
tar -czf storage_categories.tar.gz -C $STORAGE_PATH categories/
echo "✅ Created: storage_categories.tar.gz"

# Package package images
echo "📦 Packaging package images ($(ls $STORAGE_PATH/packages/ | wc -l) files)..."
tar -czf storage_packages.tar.gz -C $STORAGE_PATH packages/
echo "✅ Created: storage_packages.tar.gz"

echo ""
echo "📊 Archive sizes:"
ls -lh storage_design_images.tar.gz storage_design_files.tar.gz storage_categories.tar.gz storage_packages.tar.gz
echo ""
echo "=========================================="
echo "🚀 SERVER UPLOAD INSTRUCTIONS"
echo "=========================================="
echo ""
echo "1. Upload these 4 files to your server's project root"
echo ""
echo "2. SSH into your server and run:"
echo ""
echo "   cd /path/to/your/laravel/project"
echo ""
echo "   # Create directories"
echo "   mkdir -p storage/app/public/designs"
echo "   mkdir -p storage/app/public/categories"
echo "   mkdir -p storage/app/public/packages"
echo ""
echo "   # Extract files"
echo "   tar -xzf storage_design_images.tar.gz -C storage/app/public/designs/"
echo "   tar -xzf storage_design_files.tar.gz -C storage/app/public/designs/"
echo "   tar -xzf storage_categories.tar.gz -C storage/app/public/"
echo "   tar -xzf storage_packages.tar.gz -C storage/app/public/"
echo ""
echo "   # Create storage symlink"
echo "   php artisan storage:link"
echo ""
echo "   # Set permissions"
echo "   chmod -R 775 storage/app/public/"
echo ""
echo "   # Cleanup archives"
echo "   rm -f storage_design_images.tar.gz storage_design_files.tar.gz storage_categories.tar.gz storage_packages.tar.gz"
echo ""
echo "✅ Done! Your images will be accessible at:"
echo "   - {APP_URL}/storage/designs/images/{filename}"
echo "   - {APP_URL}/storage/designs/files/{filename}"
echo "   - {APP_URL}/storage/categories/{filename}"
echo "   - {APP_URL}/storage/packages/{filename}"
