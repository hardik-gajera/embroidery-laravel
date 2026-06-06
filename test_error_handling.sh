#!/bin/bash
# Test Mobile API Error Handling
# This script tests various error scenarios to ensure proper JSON responses

API_BASE="http://localhost/api/mobile"

echo "🧪 Testing Mobile API Error Handling..."
echo "========================================"
echo ""

echo "1. Testing wrong OTP (422 validation error)..."
curl -s -X POST "$API_BASE/verify-otp" \
  -H "Content-Type: application/json" \
  -d '{"mobile_no": "9876543210", "otp": "00000"}' | jq .
echo ""

echo "2. Testing missing required fields (422 validation error)..."
curl -s -X POST "$API_BASE/send-otp" \
  -H "Content-Type: application/json" \
  -d '{}' | jq .
echo ""

echo "3. Testing invalid route (404 error)..."
curl -s -X GET "$API_BASE/invalid-endpoint" \
  -H "Content-Type: application/json" | jq .
echo ""

echo "4. Testing unauthorized access (401 error)..."
curl -s -X GET "$API_BASE/profile" \
  -H "Content-Type: application/json" | jq .
echo ""

echo "5. Testing wrong HTTP method (405 error)..."
curl -s -X DELETE "$API_BASE/send-otp" \
  -H "Content-Type: application/json" | jq .
echo ""

echo "✅ Error handling test completed!"
echo "All responses should be in JSON format with proper error codes"