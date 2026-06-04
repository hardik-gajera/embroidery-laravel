# Embroidery Android API Setup

## 🚀 Quick Start

The Android API for the Embroidery app is now ready! Here's what was implemented:

### ✅ What's Included

1. **Complete API Controllers** (`app/Http/Controllers/Api/`)
   - `AuthController` - Registration, login, logout, profile
   - `CategoryController` - Category listing and design browsing  
   - `DesignController` - Design search, details, download
   - `CartController` - Cart management
   - `OrderController` - Purchase flow and order history
   - `PackageController` - Package management and purchases

2. **Authentication System**
   - Laravel Sanctum token-based authentication
   - Login required at app startup (not just for purchases)
   - Secure API token management

3. **API Routes** (`routes/api.php`)
   - Prefix: `/api/mobile/`
   - Public routes for browsing (categories, designs, packages)
   - Protected routes for user actions (cart, purchases, downloads)

4. **Postman Collection**
   - Complete testing collection with all endpoints
   - Environment variables for easy switching between dev/prod
   - Auto-token extraction from login/register responses

## 📋 Testing

### Option 1: Use Postman (Recommended)
1. Import `Embroidery_Android_API.postman_collection.json`
2. Import `Embroidery_Android.postman_environment.json`  
3. Update `base_url` in environment to your domain
4. Test all endpoints starting with Authentication folder

### Option 2: Use Test Script
```bash
# Make sure your Laravel app is running
php artisan serve

# Run the test script
./test_api.sh
```

## 🔧 Configuration

### 1. Environment Variables
Add to your `.env` file:
```env
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1,your-domain.com
```

### 2. CORS (if needed)
Update `config/cors.php` for Android app domain:
```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_origins' => ['*'], // Configure for production
```

## 📱 Android Integration

### Base URL
```
https://your-domain.com/api/mobile/
```

### Authentication Flow
1. App startup → Check stored token → Call `/profile` to validate
2. If no token/invalid → Show login screen
3. After login → Store token securely
4. Include in all API calls: `Authorization: Bearer {token}`

### Key Endpoints
- `POST /register` - Create account
- `POST /login` - Get auth token  
- `GET /profile` - Validate token + get user info
- `GET /categories` - Browse categories
- `GET /designs/featured` - Home screen designs
- `POST /buy-now` - Check purchase options
- `POST /claim-design` - Free download via package
- `GET /designs/{id}/download` - Download file

## 🔄 Differences from Website

| Website | Android API |
|---------|-------------|
| Session-based auth | Token-based auth |
| Login only for purchase | Login at app start |
| HTML responses | JSON responses |
| Redirect after actions | Status codes + messages |
| File downloads via browser | Direct file response |

## 📊 API Response Format

**Success:**
```json
{
    "success": true,
    "message": "Operation successful", 
    "data": {...}
}
```

**Error:**
```json
{
    "success": false,
    "message": "Error description",
    "errors": {...} // For validation errors
}
```

## 🛡️ Security Features

- ✅ Token-based authentication with Sanctum
- ✅ Input validation on all endpoints  
- ✅ Proper error handling and status codes
- ✅ User isolation (customers can only access their own data)
- ✅ File access control for downloads

## 📈 Next Steps

1. **Test all endpoints** using Postman collection
2. **Configure production domain** in environment and CORS
3. **Set up proper error logging** for API monitoring
4. **Implement rate limiting** if needed
5. **Add API documentation** to your project wiki

## 🐛 Troubleshooting

**Token issues?**
- Check Sanctum configuration  
- Verify middleware is applied
- Ensure proper Authorization header format

**File downloads not working?**
- Check storage symlink: `php artisan storage:link`
- Verify file paths in database
- Check file permissions

**CORS errors?**
- Update `config/cors.php`
- Add your domain to allowed origins
- Check preflight OPTIONS requests

---

Your Android API is now ready for development! 🎉

For detailed endpoint documentation, see `API_DOCUMENTATION.md`.