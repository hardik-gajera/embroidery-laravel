# Embroidery Android API Documentation

## Base URL
```
http://your-domain.com/api/mobile
```

## Authentication
The API uses Laravel Sanctum for authentication. After login/register, include the token in the Authorization header:
```
Authorization: Bearer {token}
```

## API Flow for Android App

### 2. App Start Flow (Updated for OTP)
- User opens app
- Check if user has saved token
- If token exists, call `/profile` to validate and get user info
- If no token or token invalid, show mobile number input screen
- User enters mobile number → Send OTP → Verify OTP
- Based on `is_new_user` flag → Either login directly or show registration form

## Authentication Flow (OTP Based)

### 1. Send OTP
```
POST /send-otp
{
    "mobile_no": "9876543210"
}
```

Response:
```json
{
    "success": true,
    "message": "OTP sent successfully",
    "data": {
        "user_exists": false,  // true if user already registered
        "otp_sent": true
    }
}
```

### 2. Verify OTP
```
POST /verify-otp
{
    "mobile_no": "9876543210",
    "otp": "123456"
}
```

**For Existing Users (Login):**
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

### 3. Complete Registration (New Users Only)
```
POST /register
{
    "name": "John Doe",
    "email": "john@example.com",
    "mobile_no": "9876543210",  // Same number used in OTP
    "password": "password123",
    "password_confirmation": "password123"
}
```

Response:
```json
{
    "success": true,
    "message": "Registration completed successfully",
    "data": {
        "customer": {...},
        "token": "1|abc123..."
    }
}
```

### 3. Main App Flow

#### Home Screen Data
```
GET /categories - Get all parent categories
GET /designs/featured - Get featured designs
GET /packages - Get available packages
```

#### Browse Designs
```
GET /categories/{id} - Get category details and children
GET /categories/{id}/designs - Get designs in category
GET /designs?search=term&category_id=1 - Search designs
GET /designs/{id} - Get design details with related designs
```

#### Purchase Flow
1. **Check Purchase Options**
   ```
   POST /buy-now
   {
       "design_id": 1
   }
   ```
   
   Response indicates if user can:
   - Claim free via package (`can_claim_free: true`)
   - Must pay (`can_claim_free: false`)
   - Package exceeded (`package_exceeded: true`)

2. **Free Claim (if has active package)**
   ```
   POST /claim-design
   {
       "design_id": 1
   }
   ```

3. **Payment (if no package or package exceeded)**
   ```
   POST /payment/success
   {
       "design_id": 1,
       "amount": 10.00,
       "razorpay_payment_id": "pay_xxx",
       "razorpay_order_id": "order_xxx"
   }
   ```

#### Download Design
```
GET /designs/{id}/download
```
Returns file download response.

#### Cart Management
```
GET /cart - Get cart items
POST /cart/add - Add design to cart
DELETE /cart/{id} - Remove specific item
DELETE /cart - Clear entire cart
```

#### Package Management
```
GET /packages - Get all packages
GET /packages/{id} - Get package details
POST /package/buy - Get package payment info
POST /package/payment/success - Complete package purchase
GET /my-packages - Get user's package info and history
```

#### User Data
```
GET /profile - Get user profile and package status
GET /my-designs - Get purchased designs
GET /orders - Get all orders history
```

## Error Handling

All API responses follow this format:

**Success Response:**
```json
{
    "success": true,
    "message": "Operation successful",
    "data": {...}
}
```

**Error Response:**
```json
{
    "success": false,
    "message": "Error description",
    "errors": {...} // For validation errors
}
```

## Key Differences from Website

1. **Authentication Required Upfront**: Android app requires login at app start, not just when purchasing
2. **Token-based Authentication**: Use Bearer token for all authenticated requests
3. **JSON Responses**: All responses are in JSON format instead of HTML views
4. **Consolidated Data**: Many endpoints return related data in single response to reduce API calls

## Important Notes

1. Always check `has_active_package` and `remaining_downloads` in profile response
2. Handle token expiration by redirecting to login
3. Cache category and featured design data to improve performance
4. Show download progress for design files
5. Implement proper error handling for network failures

## Postman Collection

Import the `Embroidery_Android_API.postman_collection.json` file into Postman to test all endpoints.

## Sample Usage Sequence

1. App starts → Check token → Call `/profile` if token exists
2. User browses → `/categories` → `/categories/{id}/designs`
3. User selects design → `/designs/{id}` for details
4. User wants to buy → `/buy-now` to check options
5. Based on response → Either `/claim-design` or payment flow
6. After purchase → `/designs/{id}/download` to get file
7. User checks purchases → `/my-designs` and `/my-packages`