# 🚀 Manual Hostinger Deployment Commands

## Step 1: Connect to SSH
```bash
ssh -p 65002 u137616334@147.93.101.176
# Password: Aaradhya@9920
```

## Step 2: Navigate and Clean public_html
```bash
cd public_html
ls -la
rm -rf *
rm -rf .*
ls -la  # Should be empty now
```

## Step 3: Clone Laravel Repository
```bash
git clone https://github.com/hardik-gajera/embroidery-laravel.git .
ls -la  # Verify files are there
```

## Step 4: Install Dependencies (Check if Composer exists)
```bash
which composer
# If composer exists:
composer install --no-dev --optimize-autoloader

# If composer doesn't exist, we'll use alternative method
```

## Step 5: Set Up Environment
```bash
cp .env.example .env
nano .env
```

### Update .env with these values:
```env
APP_NAME="Aaradhya Design Gallery"
APP_ENV=production
APP_KEY=base64:OcuvLgzgfM6VIBy6opJPrTgvztYpbDF1jGk+olbdRts=
APP_DEBUG=false
APP_URL=https://u137616334.ct.ws

# Database - YOU NEED TO CREATE DATABASE FIRST IN HPANEL
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u137616334_embroidery
DB_USERNAME=u137616334_embroidery
DB_PASSWORD=YOUR_DATABASE_PASSWORD

# SMS API (Keep existing)
SMS_API_URL=https://api.dovesoft.io/api/json/sendsms/
SMS_API_KEY=1c4d918ee4XX
SMS_SENDER_ID=ADGAPP
SMS_ENTITY_ID=1701177339250153619
SMS_TEMPLATE_ID=1707177389597272140

# Razorpay (Keep existing)
RAZORPAY_KEY=rzp_test_SsJ8IQqMM5tB3T
RAZORPAY_SECRET=cPshk9G0GaIaLueMGuRBiklb

# Odoo Database (Keep existing)
ODOO_DB_HOST=206.81.15.227
ODOO_DB_PORT=5432
ODOO_DB_DATABASE=test_v12
ODOO_DB_USERNAME=root
ODOO_DB_PASSWORD=root

SANCTUM_STATEFUL_DOMAINS=u137616334.ct.ws,aaradhadesigngallery.com
```

## Step 6: Set Permissions
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env
```

## Step 7: Laravel Setup Commands
```bash
# Generate application key
php artisan key:generate

# Create storage symlink
php artisan storage:link

# Run database migrations (after creating database)
php artisan migrate --force

# Create admin user
php artisan db:seed --class=AdminSeeder

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Step 8: Set Up Production .htaccess
```bash
cp public/.htaccess.production public/.htaccess
```

## 🗄️ IMPORTANT: Create Database First

Before running migrations, create database in hPanel:
1. Go to hPanel → Databases → MySQL Databases
2. Create database: u137616334_embroidery
3. Username: u137616334_embroidery
4. Generate strong password
5. Update .env file with database details

## 🌐 Test URLs

After deployment, test:
- Website: https://u137616334.ct.ws
- Admin: https://u137616334.ct.ws/admin
- API: https://u137616334.ct.ws/api/mobile/categories

## 🔧 Troubleshooting Commands

```bash
# Check Laravel status
php artisan about

# View logs
tail -f storage/logs/laravel.log

# Check database connection
php artisan migrate:status

# Clear caches if needed
php artisan config:clear
php artisan cache:clear
```

## 📱 Update Postman Collection

Change base URL to: https://u137616334.ct.ws/api/mobile