# 🧵 Aaradhya Design Gallery - Laravel App

A comprehensive embroidery design marketplace built with Laravel, featuring both web interface and mobile API with OTP authentication.

## 🚀 Features

### 🌐 Web Application
- **Admin Panel**: Complete design and category management
- **Customer Portal**: Browse, purchase, and download designs
- **Package System**: Subscription-based design downloads
- **Payment Integration**: Razorpay payment gateway
- **File Management**: Automatic design file handling

### 📱 Mobile API 
- **OTP Authentication**: SMS-based login system
- **REST API**: Complete mobile app backend
- **Token Authentication**: Laravel Sanctum security
- **Package Integration**: Free downloads for subscribers
- **File Downloads**: Direct design file delivery

## 🛠️ Technology Stack

- **Framework**: Laravel 10
- **Database**: MySQL with Odoo integration
- **Authentication**: Laravel Sanctum (API) + Session (Web)
- **SMS Service**: Dovesoft API integration
- **Payment**: Razorpay Gateway
- **File Storage**: Laravel Storage with symlinks

## 📋 Requirements

- PHP 8.1+
- MySQL 5.7+
- Composer
- Node.js (for assets)

## 🔧 Installation

### 1. Clone Repository
```bash
git clone https://github.com/yourusername/embroidery-laravel.git
cd embroidery-laravel
```

### 2. Install Dependencies
```bash
composer install
npm install && npm run build
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Configuration
Update `.env` with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=embroidery
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. SMS API Configuration
```env
SMS_API_URL=https://api.dovesoft.io/api/json/sendsms/
SMS_API_KEY=your_sms_api_key
SMS_SENDER_ID=ADGAPP
SMS_ENTITY_ID=1701177339250153619
SMS_TEMPLATE_ID=1707177389597272140
```

### 6. Run Migrations
```bash
php artisan migrate
php artisan db:seed --class=AdminSeeder
php artisan storage:link
```

## 🔗 API Documentation

### Base URLs
- **Web**: `https://your-domain.com`
- **Mobile API**: `https://your-domain.com/api/mobile`

### Authentication Flow
1. **Send OTP**: `POST /api/mobile/send-otp`
2. **Verify OTP**: `POST /api/mobile/verify-otp`  
3. **Register** (if new): `POST /api/mobile/register`

### Key Endpoints
- `GET /api/mobile/categories` - Browse categories
- `GET /api/mobile/designs/featured` - Featured designs
- `POST /api/mobile/buy-now` - Check purchase options
- `POST /api/mobile/claim-design` - Free download via package
- `GET /api/mobile/designs/{id}/download` - Download file

## 📚 Documentation Files

- `API_DOCUMENTATION.md` - Complete API reference
- `OTP_IMPLEMENTATION_GUIDE.md` - OTP setup guide  
- `HOSTINGER_DEPLOYMENT.md` - Production deployment
- `ANDROID_API_README.md` - Mobile development guide

## 🧪 Testing

### Postman Collections
- `Embroidery_OTP_API.postman_collection.json` - Complete API testing
- `Embroidery_Android.postman_environment.json` - Environment variables

### Test Scripts
```bash
# Test all endpoints
./test_otp_api.sh

# Prepare for deployment
./deploy-prepare.sh
```

## 📱 Mobile App Integration

### Android Flow
1. **App Start** → Check stored token
2. **No Token** → Mobile input → Send OTP → Verify OTP
3. **New User** → Registration form → Get token
4. **Existing User** → Direct login with token
5. **Use Token** → All API calls

### Package Logic
- **Has Package + Downloads Left** → Free claim
- **Package Exceeded** → Payment required  
- **No Package** → Payment required

## 🏗️ Project Structure

```
├── app/Http/Controllers/Api/     # Mobile API controllers
├── app/Http/Controllers/         # Web controllers
├── app/Models/                   # Eloquent models
├── database/migrations/          # Database schema
├── resources/views/              # Blade templates
├── routes/api.php               # API routes
├── routes/web.php               # Web routes
├── config/sms.php               # SMS configuration
└── public/storage/              # Design files
```

## 🔐 Security Features

- **Token Authentication**: Sanctum-based API security
- **Input Validation**: Comprehensive request validation
- **SQL Injection Protection**: Eloquent ORM
- **XSS Protection**: Blade template escaping
- **CSRF Protection**: Laravel middleware
- **Rate Limiting**: API request throttling

## 🚢 Deployment

### Production Setup
1. **Run deployment script**: `./deploy-prepare.sh`
2. **Upload to server**: Upload generated tar.gz
3. **Configure environment**: Update .env for production
4. **Run setup**: `./production-setup.sh`

### Hostinger Deployment
Complete guide available in `HOSTINGER_DEPLOYMENT.md`

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

## 📄 License

This project is licensed under the MIT License.

## 📞 Support

- **Documentation**: Check the `/docs` folder
- **Issues**: Create GitHub issues for bugs
- **API Testing**: Use provided Postman collections

## 🎯 Key Features Summary

- ✅ OTP-based mobile authentication
- ✅ Complete design marketplace
- ✅ Package subscription system
- ✅ Odoo ERP integration
- ✅ Razorpay payment gateway
- ✅ File download management
- ✅ Admin dashboard
- ✅ Mobile-first API design
- ✅ Production-ready deployment
- ✅ Comprehensive documentation

---

**Built with ❤️ for Aaradhya Design Gallery**