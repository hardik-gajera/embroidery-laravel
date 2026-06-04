# Embroidery Android API - OTP Based Authentication

## 🚀 OTP Authentication Flow

The Android API now uses OTP-based authentication instead of email/password. Here's the complete flow:

### 📱 User Experience Flow

1. **App Launch** → User enters mobile number
2. **Send OTP** → System sends 6-digit OTP via SMS
3. **Verify OTP** → Two possible outcomes:
   - **Existing User**: Direct login with token
   - **New User**: Complete registration (name + email)
4. **App Access** → Use token for all API calls

### 🔧 Technical Implementation

#### 1. Send OTP Endpoint
```http
POST /api/mobile/send-otp
Content-Type: application/json

{
    "mobile_no": "9876543210"
}
```

**Response:**
```json
{
    "success": true,
    "message": "OTP sent successfully",
    "data": {
        "user_exists": false,  // true if already registered
        "otp_sent": true
    }
}
```

#### 2. Verify OTP Endpoint
```http
POST /api/mobile/verify-otp
Content-Type: application/json

{
    "mobile_no": "9876543210",
    "otp": "123456"
}
```

**For Existing Users (Auto Login):**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "customer": {...},
        "token": "1|abc123...",
        "is_new_user": false
    }
}
```

**For New Users (Need Registration):**
```json
{
    "success": true,
    "message": "OTP verified. Please complete registration.",
    "data": {
        "otp_verified": true,
        "is_new_user": true,
        "mobile_no": "9876543210"
    }
}
```

#### 3. Complete Registration (New Users Only)
```http
POST /api/mobile/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com", 
    "mobile_no": "9876543210",  // Same number from OTP
    "password": "password123",
    "password_confirmation": "password123"
}
```

## 🛠️ SMS Configuration

Update the SMS API credentials in `AuthController.php`:

```php
private function sendOtpSms($mobile, $otp)
{
    $url = "https://api.dovesoft.io/api/json/sendsms/";
    $headers = [
        "Content-Type" => "application/json",
        "key" => "YOUR_ACTUAL_API_KEY"  // Replace this
    ];
    
    // SMS content and payload configuration
}
```

### SMS Template Variables:
- **API Key**: `1c4d918ee4XX` (replace with actual)
- **Sender ID**: `ADGAPP`
- **Entity ID**: `1701177339250153619`
- **Template ID**: `1707177389597272140`

## 📋 Testing the OTP Flow

### Option 1: Postman Collection
1. Import `Embroidery_OTP_API.postman_collection.json`
2. Follow the numbered requests in "OTP Authentication" folder
3. Use real mobile number to receive actual OTP

### Option 2: Test Script
```bash
# Run OTP test script
./test_otp_api.sh

# Note: Replace dummy OTP with actual SMS OTP for real testing
```

## 🔐 Security Features

- **OTP Expiration**: 10 minutes (stored in Laravel Cache)
- **One-time Use**: OTP is deleted after successful verification
- **Rate Limiting**: Consider adding rate limits for OTP requests
- **Mobile Validation**: Number format validation and uniqueness

## 📱 Android Integration Points

### Key Differences from Website:
| Feature | Website | Android App |
|---------|---------|-------------|
| Authentication | Email/Password | Mobile OTP |
| Login Trigger | Before Purchase | At App Launch |
| User Identification | Email | Mobile Number |
| Registration | Full form upfront | Mobile first, then details |

### Recommended Android Flow:

1. **Splash Screen** → Check stored token
2. **Token Valid** → Go to Home Screen
3. **No Token** → Show Mobile Input Screen
4. **Enter Mobile** → Call `/send-otp`
5. **Enter OTP** → Call `/verify-otp`
6. **If New User** → Show Registration Form (Name, Email, Password) → Call `/register`
7. **Store Token** → Navigate to Home Screen

## 🎯 Implementation Checklist

- ✅ OTP generation and SMS sending
- ✅ OTP verification and token generation  
- ✅ User existence checking
- ✅ Seamless login for existing users
- ✅ Registration completion for new users
- ✅ Token-based API authentication
- ✅ Complete Postman collection
- ✅ Test scripts and documentation

## 🚨 Important Notes

1. **Real SMS Testing**: Use actual mobile numbers and SMS API credentials
2. **OTP Storage**: Uses Laravel Cache (Redis/Database recommended for production)
3. **Error Handling**: Proper validation and error responses
4. **Security**: OTP is 6-digit random number, expires in 10 minutes
5. **User Experience**: Existing users get instant login, new users complete registration

## 🔄 Migration from Email/Password

If you have existing customers with email/password authentication:
1. They can still use the website normally
2. For mobile app, they'll go through OTP flow
3. System will recognize them by mobile number
4. No data loss or migration needed

---

Your OTP-based Android API is now ready! 🎉

**Files to use:**
- `Embroidery_OTP_API.postman_collection.json` - Updated Postman collection
- `test_otp_api.sh` - OTP testing script  
- `API_DOCUMENTATION.md` - Updated documentation

Remember to configure your SMS API credentials before testing!