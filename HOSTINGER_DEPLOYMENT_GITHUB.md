# 🚀 Hostinger Deployment Guide - GitHub Method

## Step 1: Login to Hostinger hPanel

1. Go to [hpanel.hostinger.com](https://hpanel.hostinger.com)
2. Login with: **gdsavaliya9920@gmail.com** / **Gaurang9920**

## Step 2: Set Up Hosting & Database

### A. Create Database
1. In hPanel, go to **"Databases"** → **"MySQL Databases"**
2. Click **"Create Database"**
3. **Database Name**: `u123456789_embroidery` (Hostinger will add prefix)
4. **Username**: Same as database name
5. **Password**: Generate strong password (save it!)

### B. Enable SSH Access
1. Go to **"Hosting"** → **"Manage"**  
2. Find **"SSH Access"** section
3. **Enable SSH** if not already enabled
4. Note down **SSH details** (hostname, port, username)

## Step 3: Deploy from GitHub via SSH

### A. Connect to SSH
```bash
ssh your-username@your-hostinger-server
# Use the SSH details from hPanel
```

### B. Clone Repository
```bash
# Navigate to public_html
cd public_html

# Remove default files
rm -rf *

# Clone your GitHub repository
git clone https://github.com/hardik-gajera/embroidery-laravel.git .

# Install dependencies
composer install --no-dev --optimize-autoloader
```

### C. Set Permissions
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env
```

## Step 4: Configure Environment

### A. Create .env file
```bash
cp .env.example .env
nano .env
```

### B. Update .env with your Hostinger details:
```env
APP_NAME="Aaradhya Design Gallery"
APP_ENV=production
APP_KEY=base64:OcuvLgzgfM6VIBy6opJPrTgvztYpbDF1jGk+olbdRts=
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database (Update with your Hostinger database details)
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u123456789_embroidery
DB_USERNAME=u123456789_embroidery
DB_PASSWORD=your_database_password

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
```

## Step 5: Run Setup Commands

```bash
# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate --force

# Create admin user
php artisan db:seed --class=AdminSeeder

# Create storage symlink
php artisan storage:link

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Step 6: Configure Web Server

### A. Copy .htaccess for production
```bash
cp public/.htaccess.production public/.htaccess
```

### B. Update PHP version (if needed)
1. In hPanel → **"Hosting"** → **"Manage"**
2. Go to **"PHP Version"**
3. Select **PHP 8.1** or higher

## Step 7: Set Up Domain

### A. If using existing domain (aaradhadesigngallery.com)
1. Go to **"Domains"** in hPanel
2. **Add domain** → **"Use existing domain"**
3. Enter: `aaradhadesigngallery.com`

### B. Get nameservers/IP for DNS change
1. In **"Hosting"** → **"Manage"** 
2. Note the **Server IP address**

## Step 8: Test Installation

### A. Test via Hostinger subdomain first
```
http://your-hostinger-subdomain.hostinger.com
```

### B. Test API endpoints
```
http://your-hostinger-subdomain.hostinger.com/api/mobile/categories
```

### C. Test admin panel
```
http://your-hostinger-subdomain.hostinger.com/admin
```

## Step 9: Domain DNS Configuration

### Option 1: Change DNS (Recommended)
1. Login to your domain registrar (where aaradhadesigngallery.com is registered)
2. Go to DNS Management
3. Update A record: `@ → [Hostinger IP Address]`
4. Update A record: `www → [Hostinger IP Address]`

### Option 2: Change Nameservers
Use Hostinger nameservers:
- `ns1.dns-parking.com`
- `ns2.dns-parking.com`

## Step 10: Enable SSL

1. In hPanel → **"Security"** → **"SSL/TLS"**
2. **Enable Force HTTPS**
3. Certificate will auto-generate

## 🔧 Troubleshooting Commands

```bash
# Check Laravel status
php artisan about

# Check database connection
php artisan migrate:status

# View logs
tail -f storage/logs/laravel.log

# Reset caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## 📱 Test Mobile API

Update Postman collection base URL to:
```
https://your-domain.com/api/mobile
```

Test OTP flow:
1. Send OTP to real mobile number
2. Verify OTP received via SMS
3. Test registration/login flow

## 🎯 Final Checklist

- ✅ Database created and migrated
- ✅ .env file configured
- ✅ Storage permissions set
- ✅ Admin user created
- ✅ SSL certificate enabled
- ✅ Domain pointing to Hostinger
- ✅ API endpoints responding
- ✅ OTP SMS working
- ✅ File downloads working

Your Laravel embroidery application is now live! 🎉