#!/bin/bash
# Run this script ON THE SERVER after uploading the tar.gz files
# Usage: bash server-extract-storage.sh

echo "🔧 Extracting storage files on server..."

# Create directories
mkdir -p storage/app/public/designs
mkdir -p storage/app/public/categories
mkdir -p storage/app/public/packages

# Extract design images
if [ -f storage_design_images.tar.gz ]; then
    echo "📦 Extracting design images..."
    tar -xzf storage_design_images.tar.gz -C storage/app/public/designs/
    echo "✅ Design images extracted"
fi

# Extract design EMB files
if [ -f storage_design_files.tar.gz ]; then
    echo "📦 Extracting design files..."
    tar -xzf storage_design_files.tar.gz -C storage/app/public/designs/
    echo "✅ Design files extracted"
fi

# Extract category images
if [ -f storage_categories.tar.gz ]; then
    echo "📦 Extracting category images..."
    tar -xzf storage_categories.tar.gz -C storage/app/public/
    echo "✅ Category images extracted"
fi

# Extract package images
if [ -f storage_packages.tar.gz ]; then
    echo "📦 Extracting package images..."
    tar -xzf storage_packages.tar.gz -C storage/app/public/
    echo "✅ Package images extracted"
fi

# Create storage symlink
php artisan storage:link 2>/dev/null || ln -sf $(pwd)/storage/app/public $(pwd)/public/storage

# Set permissions
chmod -R 775 storage/app/public/

# Cleanup
rm -f storage_design_images.tar.gz storage_design_files.tar.gz storage_categories.tar.gz storage_packages.tar.gz

echo ""
echo "✅ All storage files extracted and ready!"
echo "📊 File counts:"
echo "   Design images: $(ls storage/app/public/designs/images/ 2>/dev/null | wc -l)"
echo "   Design files:  $(ls storage/app/public/designs/files/ 2>/dev/null | wc -l)"
echo "   Categories:    $(ls storage/app/public/categories/ 2>/dev/null | wc -l)"
echo "   Packages:      $(ls storage/app/public/packages/ 2>/dev/null | wc -l)"
