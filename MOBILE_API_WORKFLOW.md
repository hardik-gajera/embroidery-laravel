# 📱 Mobile API Workflow - Step by Step

**Base URL:** `https://your-domain.com/api/mobile`

---

## 🔐 FLOW 1: Authentication (App Launch)

```
┌─────────────────────────────────────────────────────┐
│  APP OPEN → Check if auth_token exists in storage   │
│                                                     │
│  YES → Go to Home Screen (Flow 2)                   │
│  NO  → Go to Login Screen (Step 1.1)                │
└─────────────────────────────────────────────────────┘
```

### Step 1.1: Send OTP
```
POST /api/mobile/send-otp
Body: { "mobile_no": "9876543210" }

Response:
✅ { "success": true, "data": { "user_exists": true/false } }
```
→ Navigate to OTP Input Screen

### Step 1.2: Verify OTP
```
POST /api/mobile/verify-otp
Body: { "mobile_no": "9876543210", "otp": "123456" }

Response (Existing User):
✅ { "success": true, "data": { "token": "abc123...", "user": {...} } }
   → Store token → Go to Home Screen (Flow 2)

Response (New User):
✅ { "success": true, "data": { "is_new_user": true, "mobile_no": "9876543210" } }
   → Go to Registration Screen (Step 1.3)
```

### Step 1.3: Register (Only for New Users)
```
POST /api/mobile/register
Body: {
    "name": "John Doe",
    "email": "john@example.com",
    "mobile_no": "9876543210",
    "password": "password123",
    "password_confirmation": "password123"
}

Response:
✅ { "success": true, "data": { "token": "abc123...", "user": {...} } }
   → Store token → Go to Home Screen (Flow 2)
```

---

## 🏠 FLOW 2: Home Screen (Browse Designs)

```
┌──────────────────────────────────────┐
│  HOME SCREEN LOADS                   │
│  Call these APIs simultaneously:     │
│  • Get Categories (2.1)              │
│  • Get Featured Designs (2.2)        │
│  • Get Profile (2.3) ⭐             │
└──────────────────────────────────────┘
```

### Step 2.1: Get Categories
```
GET /api/mobile/categories
Header: (none required)

Response:
✅ { "success": true, "data": [ { "id": 1, "name": "Flowers", "image": "..." }, ... ] }
```

### Step 2.2: Get Featured Designs
```
GET /api/mobile/designs/featured
Header: (none required)

Response:
✅ { "success": true, "data": [ { "id": 1, "name": "Rose", "price": 10, "image": "..." }, ... ] }
```

### Step 2.3: Get Designs by Category (when user taps category)
```
GET /api/mobile/categories/{id}/designs
Header: (none required)
```

### Step 2.4: Search / Filter Designs
```
GET /api/mobile/designs?search=rose&category_id=1
Header: (none required)
```

### Step 2.5: Get Design Details (when user taps a design)
```
GET /api/mobile/designs/{id}
Header: (none required)
```

---

## 📦 FLOW 3: Check Package Status (⭐ IMPORTANT - Where Package Info Comes From)

```
┌────────────────────────────────────────────────────────────────────────┐
│  WHERE TO CHECK IF USER HAS ACTIVE PACKAGE:                            │
│                                                                        │
│  Option A: GET /api/mobile/profile (returns has_active_package)        │
│  Option B: GET /api/mobile/my-packages (full package details)          │
│  Option C: POST /api/mobile/cart/checkout (auto-checks at checkout)    │
│  Option D: POST /api/mobile/buy-now (auto-checks for single design)   │
│                                                                        │
│  ⚡ Best Practice: Call Profile API after login & store package info   │
│     locally. The checkout/buy-now APIs will ALSO check automatically.  │
└────────────────────────────────────────────────────────────────────────┘
```

### Step 3.1: Get Profile (⭐ Call after login — tells package status)
```
GET /api/mobile/profile
Header: Authorization: Bearer {token}

Response:
✅ {
    "success": true,
    "data": {
        "customer": { "id": 1, "name": "John", "package_id": 2, ... },
        "has_active_package": true,          ← ⭐ PACKAGE ACTIVE OR NOT
        "remaining_downloads": 8             ← ⭐ HOW MANY FREE LEFT
    }
}
```
→ Store `has_active_package` and `remaining_downloads` locally in app

### Step 3.2: Get Full Package Details (My Packages screen)
```
GET /api/mobile/my-packages
Header: Authorization: Bearer {token}

Response:
✅ {
    "success": true,
    "data": {
        "current_package": { "id": 2, "name": "Gold", "number_of_design": 50, ... },
        "has_active_package": true,          ← ⭐ ACTIVE OR NOT
        "package_start_date": "2024-01-01",
        "package_end_date": "2024-07-01",
        "total_design": 50,                  ← total allowed downloads
        "downloaded_design": 42,             ← already used
        "remaining_downloads": 8,            ← ⭐ FREE DOWNLOADS LEFT
        "package_history": [...]
    }
}
```

**⚠️ If `has_active_package` = false or `remaining_downloads` = 0 → User must PAY for designs**

---

## 🛒 FLOW 4: Cart & Checkout (Main Purchase Flow)

```
┌─────────────────────────────────────────────────────────────────────┐
│                                                                     │
│  User browses designs → Adds to Cart → Views Cart → Checkout       │
│                                                                     │
│  Checkout API automatically checks package status & returns:        │
│  ├── ALL FREE (package covers all) → Claim Bulk                    │
│  ├── SOME FREE + SOME PAID → Razorpay for paid ones                │
│  └── ALL PAID (no package) → Razorpay for all                      │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

### Step 4.1: Add Design to Cart
```
POST /api/mobile/cart/add
Header: Authorization: Bearer {token}
Body: { "design_id": 1 }

Response:
✅ { "success": true, "message": "Design added to cart" }
```
→ Show cart badge count update

### Step 4.2: View Cart
```
GET /api/mobile/cart
Header: Authorization: Bearer {token}

Response:
✅ { "success": true, "data": {
    "items": [ { "id": 1, "design_id": 1, "design": { "name": "Rose", "price": 10, ... } } ],
    "total": 30.00
}}
```

### Step 4.3: Remove from Cart (optional - swipe to delete)
```
DELETE /api/mobile/cart/{cart_item_id}
Header: Authorization: Bearer {token}
```

### Step 4.4: Checkout Cart (⭐ This API auto-checks package status)
```
POST /api/mobile/cart/checkout
Header: Authorization: Bearer {token}
Body: { "design_ids": [1, 2, 3] }
```

**Response Case 1 — User HAS active package, enough remaining:**
```json
{
    "success": true,
    "data": {
        "can_claim_all_free": true,           ← ⭐ ALL FREE!
        "designs": [...],
        "remaining_downloads": 8
    }
}
```

**Response Case 2 — User HAS package but NOT enough remaining:**
```json
{
    "success": true,
    "data": {
        "can_claim_all_free": false,
        "free_designs": [{"id":1,...}, {"id":2,...}],   ← covered by package
        "paid_designs": [{"id":3,...}],                 ← need payment
        "total_amount": 10.00,                          ← charge this amount
        "remaining_downloads": 2
    }
}
```

**Response Case 3 — User has NO package OR package expired:**
```json
{
    "success": true,
    "data": {
        "can_claim_all_free": false,
        "free_designs": [],                             ← nothing free
        "paid_designs": [{"id":1,...}, {"id":2,...}, {"id":3,...}],
        "total_amount": 30.00,                          ← pay for all
        "remaining_downloads": 0
    }
}
```

**Now decide based on `can_claim_all_free`:**

---

### Step 4.5A: ALL FREE → Claim Designs Bulk
**Condition:** `can_claim_all_free` == true

```
POST /api/mobile/claim-designs-bulk
Header: Authorization: Bearer {token}
Body: { "design_ids": [1, 2, 3] }

Response:
✅ {
    "success": true,
    "message": "3 design(s) claimed successfully",
    "data": {
        "claimed_designs": [...],
        "remaining_downloads": 5         ← updated remaining
    }
}
```
→ Clear cart → Navigate to "My Designs" or show success screen

---

### Step 4.5B: PAYMENT NEEDED → Razorpay Flow
**Condition:** `can_claim_all_free` == false AND `total_amount` > 0

```
┌─────────────────────────────────────────────┐
│  1. Open Razorpay SDK with total_amount     │
│  2. User completes payment                  │
│  3. Get razorpay_payment_id & order_id      │
│  4. Call bulk-payment-success API           │
└─────────────────────────────────────────────┘
```

```
POST /api/mobile/payment/bulk-success
Header: Authorization: Bearer {token}
Body: {
    "design_ids": [3],                    ← only PAID design IDs
    "amount": 10.00,
    "razorpay_payment_id": "pay_xxxxx",
    "razorpay_order_id": "order_xxxxx",
    "free_design_ids": [1, 2]             ← FREE designs to claim via package
}

Response:
✅ {
    "success": true,
    "message": "Payment successful. 3 design(s) purchased.",
    "data": {
        "designs": [...],
        "remaining_downloads": 6
    }
}
```
→ Clear cart → Navigate to "My Designs" or show success screen

---

## 🛍️ FLOW 5: Single Design Purchase (Buy Now - Without Cart)

```
┌───────────────────────────────────────────────────────────┐
│  User taps "Buy Now" on a design                          │
│  ⭐ buy-now API auto-checks package status                │
│  ├── FREE (has package + remaining) → Claim Design        │
│  ├── PAID (package exceeded) → Razorpay                   │
│  └── PAID (no package) → Razorpay                         │
└───────────────────────────────────────────────────────────┘
```

### Step 5.1: Check Price (⭐ This API auto-checks package)
```
POST /api/mobile/buy-now
Header: Authorization: Bearer {token}
Body: { "design_id": 1 }
```

**Response Case 1 — Has package + remaining downloads:**
```json
{
    "success": true,
    "data": {
        "can_claim_free": true,              ← ⭐ FREE!
        "design": {...},
        "remaining_downloads": 8
    }
}
```

**Response Case 2 — Has package but limit exceeded:**
```json
{
    "success": true,
    "data": {
        "can_claim_free": false,
        "package_exceeded": true,            ← package used up
        "design": {...},
        "amount": 10.00                      ← must pay this
    }
}
```

**Response Case 3 — No package at all:**
```json
{
    "success": true,
    "data": {
        "can_claim_free": false,
        "package_exceeded": false,           ← no package exists
        "design": {...},
        "amount": 10.00                      ← must pay this
    }
}
```

### Step 5.2A: If FREE (`can_claim_free` == true) → Claim Design
```
POST /api/mobile/claim-design
Header: Authorization: Bearer {token}
Body: { "design_id": 1 }
```
→ Show success → Enable download

### Step 5.2B: If PAID (`can_claim_free` == false) → Razorpay → Payment Success
```
POST /api/mobile/payment/success
Header: Authorization: Bearer {token}
Body: {
    "design_id": 1,
    "amount": 10.00,
    "razorpay_payment_id": "pay_xxxxx",
    "razorpay_order_id": "order_xxxxx"
}
```
→ Show success → Enable download

---

## 📥 FLOW 6: My Designs & Download

### Step 6.1: Get My Purchased Designs
```
GET /api/mobile/my-designs
Header: Authorization: Bearer {token}

Response:
✅ { "success": true, "data": [ { "id": 1, "name": "Rose", "download_url": "..." } ] }
```

### Step 6.2: Download Design File
```
GET /api/mobile/designs/{id}/download
Header: Authorization: Bearer {token}

Response: File download (binary)
```

---

## 💎 FLOW 7: Packages (Subscription)

```
┌───────────────────────────────────────────────────────┐
│  Browse Packages → Buy Package → Razorpay → Success   │
│  Now user gets FREE downloads up to package limit     │
└───────────────────────────────────────────────────────┘
```

### Step 7.1: View Available Packages
```
GET /api/mobile/packages
Header: (none required)
```

### Step 7.2: View My Active Packages
```
GET /api/mobile/my-packages
Header: Authorization: Bearer {token}
```

### Step 7.3: Buy Package (Initiate)
```
POST /api/mobile/package/buy
Header: Authorization: Bearer {token}
Body: { "package_id": 1 }
```
→ Open Razorpay with package amount

### Step 7.4: Package Payment Success
```
POST /api/mobile/package/payment/success
Header: Authorization: Bearer {token}
Body: {
    "package_id": 1,
    "amount": 100.00,
    "razorpay_payment_id": "pay_xxxxx",
    "razorpay_order_id": "order_xxxxx"
}
```

---

## 👤 FLOW 8: Profile & Session

### Get Profile
```
GET /api/mobile/profile
Header: Authorization: Bearer {token}
```

### Check Session (validate token is still active)
```
GET /api/mobile/check-session
Header: Authorization: Bearer {token}
```

### Logout
```
POST /api/mobile/logout
Header: Authorization: Bearer {token}
```
→ Clear stored token → Go to Login Screen

---

## 🔑 Important Notes for Mobile Developer

### Headers
| Endpoint Type | Headers Required |
|---|---|
| Public (categories, designs, packages) | `Content-Type: application/json` |
| Protected (cart, orders, profile) | `Authorization: Bearer {token}` + `Content-Type: application/json` |

### Token Storage
- Store token in **SharedPreferences** (Android) or **Keychain** (iOS)
- Send token in every protected API call
- If API returns **401**, token expired → redirect to Login Screen

### Error Handling
```json
{
    "success": false,
    "message": "Error description here"
}
```
- **401** → Token expired, re-login
- **422** → Validation error (show field errors)
- **404** → Resource not found
- **500** → Server error (show generic error)

---

## 🧠 Decision Logic (Pseudocode for Mobile Developer)

### Cart Checkout Logic
```
function onCheckoutPressed():
    design_ids = getCartDesignIds()
    response = API.call(POST /cart/checkout, { design_ids: design_ids })
    
    if response.can_claim_all_free == true:
        // ✅ User has active package with enough remaining
        API.call(POST /claim-designs-bulk, { design_ids: design_ids })
        showSuccess()
        clearCart()
    else:
        // 💳 Payment needed (some or all)
        free_ids = response.free_designs.map(d => d.id)    // may be empty []
        paid_ids = response.paid_designs.map(d => d.id)
        
        openRazorpay(amount: response.total_amount)
        onPaymentSuccess(payment_id, order_id):
            API.call(POST /payment/bulk-success, {
                design_ids: paid_ids,
                amount: response.total_amount,
                razorpay_payment_id: payment_id,
                razorpay_order_id: order_id,
                free_design_ids: free_ids      // also claim free ones
            })
            showSuccess()
            clearCart()
```

### Single Buy Logic
```
function onBuyNowPressed(design_id):
    response = API.call(POST /buy-now, { design_id: design_id })
    
    if response.can_claim_free == true:
        // ✅ Package covers this design
        API.call(POST /claim-design, { design_id: design_id })
        showSuccess()
    else:
        // 💳 Must pay (no package OR package exceeded)
        openRazorpay(amount: response.amount)
        onPaymentSuccess(payment_id, order_id):
            API.call(POST /payment/success, {
                design_id: design_id,
                amount: response.amount,
                razorpay_payment_id: payment_id,
                razorpay_order_id: order_id
            })
            showSuccess()
```

---

## 📊 Visual Flow Summary

```
APP OPEN
  │
  ├── No Token → FLOW 1 (Login/Register)
  │                │
  │                ▼
  └── Has Token → FLOW 2 (Home Screen)
                    │
                    ├── Profile API → FLOW 3 (⭐ Get package status)
                    │     • has_active_package: true/false
                    │     • remaining_downloads: N
                    │
                    ├── Browse Designs
                    │     │
                    │     ├── Add to Cart → FLOW 4 (Cart Checkout)
                    │     │     └── checkout API auto-checks package
                    │     │         ├── can_claim_all_free=true → claim-bulk
                    │     │         └── can_claim_all_free=false → razorpay
                    │     │
                    │     └── Buy Now → FLOW 5 (Single Purchase)
                    │           └── buy-now API auto-checks package
                    │               ├── can_claim_free=true → claim-design
                    │               └── can_claim_free=false → razorpay
                    │
                    ├── My Designs → FLOW 6 (Download)
                    │
                    ├── Packages → FLOW 7 (Buy/Renew Subscription)
                    │
                    └── Profile → FLOW 8 (Logout)
```

---

## ⭐ PACKAGE STATUS SUMMARY

| Scenario | Where it's checked | What happens |
|---|---|---|
| User has active package + remaining downloads | Profile API, buy-now API, cart/checkout API | `can_claim_free = true` → Design is FREE |
| User has active package but limit exceeded | Profile API, buy-now API, cart/checkout API | `can_claim_free = false`, `package_exceeded = true` → Must PAY |
| User has NO package | Profile API, buy-now API, cart/checkout API | `can_claim_free = false`, `package_exceeded = false` → Must PAY |
| User buys a new package | After package/payment/success | Refresh profile → `has_active_package` becomes true |

**The mobile app does NOT need to manually check package status before checkout. The `buy-now` and `cart/checkout` APIs automatically check and return the correct pricing.**

---

**Built for Aaradhya Design Gallery Mobile App**






USER TAPS "ADD TO CART" ON A DESIGN
         │
         ▼
┌─────────────────────────────────────────────────────────┐
│ STEP 1: POST /api/mobile/cart/add                       │
│ Body: { "design_id": 5 }                                │
│                                                         │
│ Response: { "cart_item": {...}, "cart_count": 3 }       │
│ → Update cart badge with cart_count                     │
└─────────────────────────────────────────────────────────┘
         │
         ▼ (User opens cart screen)
┌─────────────────────────────────────────────────────────┐
│ STEP 2: GET /api/mobile/cart                            │
│                                                         │
│ Response: {                                             │
│   "cart_items": [ {design_id:1,...}, {design_id:5,...}] │
│   "total_amount": 30.00,                                │
│   "item_count": 3                                       │
│ }                                                       │
│ → Show list of designs with prices                      │
│ → User can swipe to remove (DELETE /cart/{id})           │
└─────────────────────────────────────────────────────────┘
         │
         ▼ (User taps "Checkout" button)
┌─────────────────────────────────────────────────────────┐
│ STEP 3: POST /api/mobile/cart/checkout                  │
│ Body: { "design_ids": [1, 2, 5] }  ← all cart item IDs │
│                                                         │
│ ⭐ This API checks package status automatically        │
└─────────────────────────────────────────────────────────┘
         │
         ├── can_claim_all_free = TRUE
         │         │
         │         ▼
         │   ┌─────────────────────────────────────────┐
         │   │ STEP 4A: POST /api/mobile/claim-designs-bulk
         │   │ Body: { "design_ids": [1, 2, 5] }      │
         │   │                                         │
         │   │ Response: {                             │
         │   │   "message": "3 design(s) claimed",     │
         │   │   "remaining_downloads": 5              │
         │   │ }                                       │
         │   │ → Show success ✅                       │
         │   │ → Cart is auto-cleared                  │
         │   │ → Go to "My Designs" screen             │
         │   └─────────────────────────────────────────┘
         │
         ├── can_claim_all_free = FALSE (some free + some paid)
         │         │
         │         ▼
         │   ┌─────────────────────────────────────────┐
         │   │ Response shows:                         │
         │   │   free_designs: [1, 2]  (covered by pkg)│
         │   │   paid_designs: [5]     (need payment)  │
         │   │   total_amount: 10.00                   │
         │   │                                         │
         │   │ → Show breakdown to user:               │
         │   │   "2 designs FREE (package)"            │
         │   │   "1 design ₹10.00"                     │
         │   │   "Total to pay: ₹10.00"                │
         │   └─────────────────────────────────────────┘
         │         │
         │         ▼ (User taps "Pay ₹10.00")
         │   ┌─────────────────────────────────────────┐
         │   │ Open Razorpay SDK                       │
         │   │ amount: 10.00                           │
         │   │                                         │
         │   │ On payment success, get:                │
         │   │ • razorpay_payment_id                   │
         │   │ • razorpay_order_id                     │
         │   └─────────────────────────────────────────┘
         │         │
         │         ▼
         │   ┌─────────────────────────────────────────┐
         │   │ STEP 4B: POST /api/mobile/payment/bulk-success
         │   │ Body: {                                 │
         │   │   "design_ids": [5],        ← paid     │
         │   │   "amount": 10.00,                      │
         │   │   "razorpay_payment_id": "pay_xxx",     │
         │   │   "razorpay_order_id": "order_xxx",     │
         │   │   "free_design_ids": [1, 2] ← free     │
         │   │ }                                       │
         │   │                                         │
         │   │ Response: {                             │
         │   │   "message": "3 design(s) purchased",   │
         │   │   "remaining_downloads": 6              │
         │   │ }                                       │
         │   │ → Show success ✅                       │
         │   │ → Cart is auto-cleared                  │
         │   │ → Go to "My Designs" screen             │
         │   └─────────────────────────────────────────┘
         │
         └── can_claim_all_free = FALSE (no package at all)
                   │
                   ▼
             ┌─────────────────────────────────────────┐
             │ Response shows:                         │
             │   free_designs: []                      │
             │   paid_designs: [1, 2, 5]               │
             │   total_amount: 30.00                   │
             │                                         │
             │ → Show: "Total to pay: ₹30.00"          │
             └─────────────────────────────────────────┘
                   │
                   ▼ (User taps "Pay ₹30.00")
             ┌─────────────────────────────────────────┐
             │ Open Razorpay SDK                       │
             │ amount: 30.00                           │
             └─────────────────────────────────────────┘
                   │
                   ▼
             ┌─────────────────────────────────────────┐
             │ STEP 4B: POST /api/mobile/payment/bulk-success
             │ Body: {                                 │
             │   "design_ids": [1, 2, 5],              │
             │   "amount": 30.00,                      │
             │   "razorpay_payment_id": "pay_xxx",     │
             │   "razorpay_order_id": "order_xxx",     │
             │   "free_design_ids": []                 │
             │ }                                       │
             │ → Show success ✅                       │
             │ → Cart is auto-cleared                  │
             │ → Go to "My Designs" screen             │
             └─────────────────────────────────────────┘
