#!/bin/bash

# Embroidery Android API Test Script (OTP Based)
# Run this script to test all API endpoints

BASE_URL="http://localhost:8000/api/mobile"
TOKEN=""

echo "🧵 Embroidery Android API Test Script (OTP Based)"
echo "================================================="

# Test public endpoints first
echo ""
echo "📋 Testing Public Endpoints..."

echo "1. Getting categories..."
curl -s -X GET "$BASE_URL/categories" | jq '.'

echo ""
echo "2. Getting featured designs..."
curl -s -X GET "$BASE_URL/designs/featured" | jq '.'

echo ""
echo "3. Getting packages..."
curl -s -X GET "$BASE_URL/packages" | jq '.'

# Test OTP authentication
echo ""
echo "🔐 Testing OTP Authentication..."

echo "4. Sending OTP to test number..."
SEND_OTP_RESPONSE=$(curl -s -X POST "$BASE_URL/send-otp" \
  -H "Content-Type: application/json" \
  -d '{
    "mobile_no": "9876543210"
  }')

echo $SEND_OTP_RESPONSE | jq '.'

# For testing, we'll use a dummy OTP verification
echo ""
echo "5. Verifying OTP (use actual OTP from SMS for real testing)..."
echo "⚠️  For testing: Check your SMS for the actual OTP, or use cache to get OTP"
echo "📱 Replace '123456' with the actual OTP received"

VERIFY_RESPONSE=$(curl -s -X POST "$BASE_URL/verify-otp" \
  -H "Content-Type: application/json" \
  -d '{
    "mobile_no": "9876543210",
    "otp": "123456"
  }')

echo $VERIFY_RESPONSE | jq '.'

# Extract token or check if new user
TOKEN=$(echo $VERIFY_RESPONSE | jq -r '.data.token // empty')
IS_NEW_USER=$(echo $VERIFY_RESPONSE | jq -r '.data.is_new_user // false')

if [ "$IS_NEW_USER" = "true" ]; then
    echo ""
    echo "6. New user detected, completing registration..."
    REGISTER_RESPONSE=$(curl -s -X POST "$BASE_URL/register" \
      -H "Content-Type: application/json" \
      -d '{
        "name": "Test User",
        "email": "test@example.com",
        "mobile_no": "9876543210",
        "password": "password123",
        "password_confirmation": "password123"
      }')
    
    echo $REGISTER_RESPONSE | jq '.'
    TOKEN=$(echo $REGISTER_RESPONSE | jq -r '.data.token // empty')
fi

if [ -z "$TOKEN" ]; then
    echo "❌ OTP Authentication failed. Please check your setup or use actual OTP."
    echo "💡 To test manually:"
    echo "   1. Call /send-otp with a real mobile number"
    echo "   2. Use the OTP received via SMS"
    echo "   3. Call /verify-otp with the correct OTP"
    exit 1
fi

echo "✅ Authentication successful. Token: ${TOKEN:0:20}..."

# Test authenticated endpoints
echo ""
echo "🔒 Testing Protected Endpoints..."

echo "7. Getting profile..."
curl -s -X GET "$BASE_URL/profile" \
  -H "Authorization: Bearer $TOKEN" | jq '.'

echo ""
echo "8. Getting cart..."
curl -s -X GET "$BASE_URL/cart" \
  -H "Authorization: Bearer $TOKEN" | jq '.'

echo ""
echo "9. Getting my designs..."
curl -s -X GET "$BASE_URL/my-designs" \
  -H "Authorization: Bearer $TOKEN" | jq '.'

echo ""
echo "10. Getting my packages..."
curl -s -X GET "$BASE_URL/my-packages" \
  -H "Authorization: Bearer $TOKEN" | jq '.'

# If designs exist, test adding to cart
echo ""
echo "11. Getting first design for cart test..."
FIRST_DESIGN=$(curl -s -X GET "$BASE_URL/designs?page=1" | jq -r '.data.data[0].id // empty')

if [ ! -z "$FIRST_DESIGN" ]; then
    echo "Found design ID: $FIRST_DESIGN"
    
    echo "12. Adding design to cart..."
    curl -s -X POST "$BASE_URL/cart/add" \
      -H "Authorization: Bearer $TOKEN" \
      -H "Content-Type: application/json" \
      -d "{\"design_id\": $FIRST_DESIGN}" | jq '.'
    
    echo ""
    echo "13. Checking buy-now options for design..."
    curl -s -X POST "$BASE_URL/buy-now" \
      -H "Authorization: Bearer $TOKEN" \
      -H "Content-Type: application/json" \
      -d "{\"design_id\": $FIRST_DESIGN}" | jq '.'
else
    echo "⚠️  No designs found to test cart functionality"
fi

echo ""
echo "✅ OTP API testing completed!"
echo ""
echo "📝 Summary:"
echo "- All public endpoints should return data"
echo "- OTP authentication should work with real mobile numbers and OTP"
echo "- Protected endpoints should return user-specific data"
echo "- Cart and purchase functionality should be accessible"
echo ""
echo "📱 Android App Flow:"
echo "1. User enters mobile number → Send OTP"
echo "2. User enters OTP → Verify (returns token if existing user)"
echo "3. If new user → Complete registration with name/email"
echo "4. Use token for all subsequent API calls"
echo ""
echo "🚀 Your OTP-based Android API is ready for development!"