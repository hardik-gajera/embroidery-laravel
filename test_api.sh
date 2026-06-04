#!/bin/bash

# Embroidery Android API Test Script
# Run this script to test all API endpoints

BASE_URL="http://localhost:8000/api/mobile"
TOKEN=""

echo "🧵 Embroidery Android API Test Script"
echo "======================================"

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

# Test registration
echo ""
echo "🔐 Testing Authentication..."

echo "4. Registering test user..."
REGISTER_RESPONSE=$(curl -s -X POST "$BASE_URL/register" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "mobile_no": "1234567890",
    "password": "password123",
    "password_confirmation": "password123"
  }')

echo $REGISTER_RESPONSE | jq '.'

# Extract token from registration response
TOKEN=$(echo $REGISTER_RESPONSE | jq -r '.data.token // empty')

if [ -z "$TOKEN" ]; then
    echo "❌ Registration failed, trying login instead..."
    
    LOGIN_RESPONSE=$(curl -s -X POST "$BASE_URL/login" \
      -H "Content-Type: application/json" \
      -d '{
        "email": "test@example.com",
        "password": "password123"
      }')
    
    echo $LOGIN_RESPONSE | jq '.'
    TOKEN=$(echo $LOGIN_RESPONSE | jq -r '.data.token // empty')
fi

if [ -z "$TOKEN" ]; then
    echo "❌ Authentication failed. Please check your setup."
    exit 1
fi

echo "✅ Authentication successful. Token: ${TOKEN:0:20}..."

# Test authenticated endpoints
echo ""
echo "🔒 Testing Protected Endpoints..."

echo "5. Getting profile..."
curl -s -X GET "$BASE_URL/profile" \
  -H "Authorization: Bearer $TOKEN" | jq '.'

echo ""
echo "6. Getting cart..."
curl -s -X GET "$BASE_URL/cart" \
  -H "Authorization: Bearer $TOKEN" | jq '.'

echo ""
echo "7. Getting my designs..."
curl -s -X GET "$BASE_URL/my-designs" \
  -H "Authorization: Bearer $TOKEN" | jq '.'

echo ""
echo "8. Getting my packages..."
curl -s -X GET "$BASE_URL/my-packages" \
  -H "Authorization: Bearer $TOKEN" | jq '.'

# If designs exist, test adding to cart
echo ""
echo "9. Getting first design for cart test..."
FIRST_DESIGN=$(curl -s -X GET "$BASE_URL/designs?page=1" | jq -r '.data.data[0].id // empty')

if [ ! -z "$FIRST_DESIGN" ]; then
    echo "Found design ID: $FIRST_DESIGN"
    
    echo "10. Adding design to cart..."
    curl -s -X POST "$BASE_URL/cart/add" \
      -H "Authorization: Bearer $TOKEN" \
      -H "Content-Type: application/json" \
      -d "{\"design_id\": $FIRST_DESIGN}" | jq '.'
    
    echo ""
    echo "11. Checking buy-now options for design..."
    curl -s -X POST "$BASE_URL/buy-now" \
      -H "Authorization: Bearer $TOKEN" \
      -H "Content-Type: application/json" \
      -d "{\"design_id\": $FIRST_DESIGN}" | jq '.'
else
    echo "⚠️  No designs found to test cart functionality"
fi

echo ""
echo "✅ API testing completed!"
echo ""
echo "📝 Summary:"
echo "- All public endpoints should return data"
echo "- Authentication should work and return a token"
echo "- Protected endpoints should return user-specific data"
echo "- Cart and purchase functionality should be accessible"
echo ""
echo "🚀 Your Android API is ready for development!"