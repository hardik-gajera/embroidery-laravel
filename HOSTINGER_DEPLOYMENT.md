# 🚀 Hostinger Deployment Guide

## Part 1: Prepare for Deployment

### 1. Run Preparation Script
```bash
./deploy-prepare.sh
```
This creates `laravel-deployment.tar.gz` ready for upload.

## Part 2: Hostinger Setup

### 1. Create Database
1. Login to Hostinger hPanel
2. Go to **Databases** → **MySQL Databases**
3. Create new database (note the details):
   - Database Name: `u123456789_embroidery`
   - Username: `u123456789_embroidery`
   - Password: `[generate strong password]`

### 2. Upload Application
1. Go to **File Manager** in hPanel
2. Navigate to `public_html` folder
3. Upload `laravel-deployment.tar.gz`
4. Extract the archive
5. Move all files from extracted folder to `public_html` root

### 3. Configure Environment
1. Rename `.env.production` to `.env`
2. Update database credentials in `.env`:
   ```env
   DB_DATABASE=u123456789_embroidery
   DB_USERNAME=u123456789_embroidery
   DB_PASSWORD=your_generated_password
   APP_URL=https://your-domain.com
   ```
3. Replace `public/.htaccess` with `public/.htaccess.production`

### 4. Set Permissions
Set these folder permissions via File Manager:
- `storage/` → 755 (recursive)
- `bootstrap/cache/` → 755 (recursive)
- `.env` → 644

### 5. Run Migrations (via Terminal/SSH if available)
```bash
php artisan migrate --force
php artisan storage:link
```

If no SSH access, use Hostinger's **Terminal** in hPanel.

## Part 3: Domain Transfer

### Option A: Change DNS (Recommended - No Downtime)

1. **Keep Domain at Current Registrar**
2. **Update DNS Records:**
   - Login to your domain registrar (where domain is registered)
   - Go to DNS Management
   - Update these records:
     ```
     A Record: @ → [Hostinger IP Address]
     A Record: www → [Hostinger IP Address]
     ```
   - Get Hostinger IP from hPanel → **Hosting** → **Manage**

3. **DNS Propagation:** Takes 24-48 hours globally

### Option B: Transfer Domain Registration

1. **Get Transfer Code** from current registrar
2. **Initiate Transfer** at Hostinger:
   - Go to **Domains** → **Transfer Domain**
   - Enter domain and transfer code
   - Pay transfer fee (~$10-15)
3. **Wait for Completion:** 5-7 days

## Part 4: Post-Deployment Setup

### 1. SSL Certificate
1. In hPanel → **Security** → **SSL/TLS**
2. Enable **Force HTTPS**
3. Certificate auto-generates (Let's Encrypt)

### 2. Email Setup (Optional)
1. **Create Email Account:**
   - hPanel → **Email** → **Email Accounts**
   - Create: `noreply@your-domain.com`
2. **Update .env:**
   ```env
   MAIL_HOST=smtp.hostinger.com
   MAIL_USERNAME=noreply@your-domain.com
   MAIL_PASSWORD=email_account_password
   ```

### 3. Cron Jobs (For Laravel Scheduler)
1. hPanel → **Advanced** → **Cron Jobs**
2. Add job:
   ```
   * * * * * cd /home/u123456789/public_html && php artisan schedule:run >> /dev/null 2>&1
   ```

## Part 5: Testing

### 1. Website Access
- Visit `https://your-domain.com`
- Check home page loads correctly

### 2. API Testing
- Update Postman base URL to `https://your-domain.com/api/mobile`
- Test OTP flow with real mobile number
- Verify all endpoints work

### 3. Admin Panel
- Visit `https://your-domain.com/admin`
- Test login functionality

## Part 6: Production Optimizations

### 1. Enable OPcache (if available)
Add to `.htaccess` or ask Hostinger support:
```
php_value opcache.enable 1
php_value opcache.memory_consumption 128
```

### 2. Backup Strategy
1. **Automatic Backups:** Enable in hPanel
2. **Database Exports:** Schedule regular exports
3. **File Backups:** Use hPanel backup feature

## Troubleshooting

### Common Issues:

1. **500 Error:**
   - Check `.env` file exists and has correct permissions
   - Verify database credentials
   - Check `storage/logs/laravel.log`

2. **Storage Issues:**
   - Run `php artisan storage:link`
   - Set proper permissions on storage folders

3. **API Not Working:**
   - Verify `.htaccess` is correct
   - Check if `mod_rewrite` is enabled

4. **SMS/Email Issues:**
   - Verify API keys in `.env`
   - Check firewall allows outbound connections

### Getting Help:
- **Hostinger Support:** 24/7 live chat
- **Laravel Logs:** `storage/logs/laravel.log`
- **Server Logs:** Available in hPanel

---

## Quick Reference

**Important Files:**
- `.env` → Environment configuration
- `public/.htaccess` → Web server rules
- `storage/logs/` → Application logs

**Important URLs:**
- Website: `https://your-domain.com`
- Admin: `https://your-domain.com/admin`  
- API: `https://your-domain.com/api/mobile`

**Hostinger Support:**
- hPanel: [hostinger.com/cpanel-login](https://hostinger.com/cpanel-login)
- Tutorials: [hostinger.com/tutorials](https://hostinger.com/tutorials)

Your Laravel application should now be live! 🎉