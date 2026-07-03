# Android Razorpay Payment Integration Guide

## Base URL
```
https://your-domain.com/api/mobile
```

## Authentication Header (required for all payment APIs)
```
Authorization: Bearer {token}
Content-Type: application/json
```

---

## 1. Setup

### build.gradle (app)
```gradle
implementation 'com.razorpay:checkout:1.6.33'
implementation 'com.squareup.retrofit2:retrofit:2.9.0'
implementation 'com.squareup.retrofit2:converter-gson:2.9.0'
```

### AndroidManifest.xml
```xml
<uses-permission android:name="android.permission.INTERNET" />

<activity
    android:name="com.razorpay.CheckoutActivity"
    android:theme="@style/Theme.AppCompat.Light.NoActionBar" />
```

---

## 2. Payment Flows

There are **3 payment scenarios** in this app:

| Scenario | API Endpoint |
|---|---|
| Buy single design | `POST /payment/success` |
| Buy multiple designs (cart) | `POST /payment/bulk-success` |
| Buy a package | `POST /package/payment/success` |

---

## 3. Single Design Payment

### Step 1 — Check if design needs payment
```
POST /buy-now
Body: { "design_id": 1 }
```

Response:
```json
{
  "success": true,
  "data": {
    "can_claim_free": false,
    "package_exceeded": false,
    "design": { "id": 1, "design_name": "Rose", "design_price": 50.00 },
    "amount": 50.00
  }
}
```

- If `can_claim_free: true` → call `/claim-design` (no payment needed)
- If `can_claim_free: false` → proceed with Razorpay

### Step 2 — Open Razorpay Checkout
```kotlin
class DesignDetailActivity : AppCompatActivity(), PaymentResultListener {

    private var designId: Int = 0
    private var amount: Double = 0.0

    private fun startRazorpayPayment(designId: Int, amountInRupees: Double) {
        this.designId = designId
        this.amount = amountInRupees

        Checkout.preload(applicationContext)
        val checkout = Checkout()
        checkout.setKeyID("rzp_live_YOUR_KEY_ID") // Replace with your Razorpay key

        val options = JSONObject().apply {
            put("name", "Aaradhya Design Gallery")
            put("description", "Design Purchase")
            put("currency", "INR")
            put("amount", (amountInRupees * 100).toInt()) // Razorpay needs paise
            put("prefill", JSONObject().apply {
                put("contact", userMobile)  // from saved profile
                put("email", userEmail)
            })
        }

        checkout.open(this, options)
    }

    override fun onPaymentSuccess(razorpayPaymentId: String) {
        confirmDesignPayment(designId, amount, razorpayPaymentId)
    }

    override fun onPaymentError(code: Int, response: String?) {
        Toast.makeText(this, "Payment failed: $response", Toast.LENGTH_SHORT).show()
    }
}
```

### Step 3 — Confirm payment with API
```
POST /payment/success
```

```kotlin
data class DesignPaymentRequest(
    val design_id: Int,
    val amount: Double,
    val razorpay_payment_id: String,
    val razorpay_order_id: String? = null
)

fun confirmDesignPayment(designId: Int, amount: Double, paymentId: String) {
    val request = DesignPaymentRequest(
        design_id = designId,
        amount = amount,
        razorpay_payment_id = paymentId
    )

    apiService.designPaymentSuccess("Bearer $token", request)
        .enqueue(object : Callback<ApiResponse> {
            override fun onResponse(call: Call<ApiResponse>, response: Response<ApiResponse>) {
                if (response.body()?.success == true) {
                    // Payment confirmed — allow download
                    downloadDesign(designId)
                }
            }
            override fun onFailure(call: Call<ApiResponse>, t: Throwable) {
                Toast.makeText(context, "Network error", Toast.LENGTH_SHORT).show()
            }
        })
}
```

---

## 4. Cart / Bulk Design Payment

### Step 1 — Get cart checkout summary
```
POST /cart/checkout
Body: { "design_ids": [1, 2, 3] }
```

Response:
```json
{
  "success": true,
  "data": {
    "can_claim_all_free": false,
    "free_designs": [{ "id": 1 }],
    "paid_designs": [{ "id": 2 }, { "id": 3 }],
    "total_amount": 100.00,
    "remaining_downloads": 1
  }
}
```

### Step 2 — Open Razorpay for total_amount (same as single design)

### Step 3 — Confirm bulk payment
```
POST /payment/bulk-success
```

```kotlin
data class BulkPaymentRequest(
    val design_ids: List<Int>,          // paid designs
    val free_design_ids: List<Int>,     // free via package
    val amount: Double,
    val razorpay_payment_id: String,
    val razorpay_order_id: String? = null
)

fun confirmBulkPayment(
    paidIds: List<Int>,
    freeIds: List<Int>,
    amount: Double,
    paymentId: String
) {
    val request = BulkPaymentRequest(
        design_ids = paidIds,
        free_design_ids = freeIds,
        amount = amount,
        razorpay_payment_id = paymentId
    )

    apiService.bulkPaymentSuccess("Bearer $token", request)
        .enqueue(object : Callback<ApiResponse> {
            override fun onResponse(call: Call<ApiResponse>, response: Response<ApiResponse>) {
                if (response.body()?.success == true) {
                    // All designs unlocked
                }
            }
            override fun onFailure(call: Call<ApiResponse>, t: Throwable) { }
        })
}
```

---

## 5. Package Purchase Payment

### Step 1 — Get package price
```
POST /package/buy
Body: { "package_id": 1 }
```

Response:
```json
{
  "success": true,
  "data": {
    "package": { "id": 1, "name": "Basic", "price": 499.00, "number_of_design": 20, "time_period": 1 },
    "amount": 499.00
  }
}
```

### Step 2 — Open Razorpay (same as above with `amount`)

### Step 3 — Confirm package payment
```
POST /package/payment/success
```

```kotlin
data class PackagePaymentRequest(
    val package_id: Int,
    val amount: Double,
    val razorpay_payment_id: String,
    val razorpay_order_id: String? = null
)

fun confirmPackagePayment(packageId: Int, amount: Double, paymentId: String) {
    val request = PackagePaymentRequest(
        package_id = packageId,
        amount = amount,
        razorpay_payment_id = paymentId
    )

    apiService.packagePaymentSuccess("Bearer $token", request)
        .enqueue(object : Callback<ApiResponse> {
            override fun onResponse(call: Call<ApiResponse>, response: Response<ApiResponse>) {
                if (response.body()?.success == true) {
                    // Package activated — refresh profile
                }
            }
            override fun onFailure(call: Call<ApiResponse>, t: Throwable) { }
        })
}
```

---

## 6. Retrofit API Interface
```kotlin
interface ApiService {

    @POST("mobile/payment/success")
    fun designPaymentSuccess(
        @Header("Authorization") token: String,
        @Body request: DesignPaymentRequest
    ): Call<ApiResponse>

    @POST("mobile/payment/bulk-success")
    fun bulkPaymentSuccess(
        @Header("Authorization") token: String,
        @Body request: BulkPaymentRequest
    ): Call<ApiResponse>

    @POST("mobile/package/payment/success")
    fun packagePaymentSuccess(
        @Header("Authorization") token: String,
        @Body request: PackagePaymentRequest
    ): Call<ApiResponse>

    @POST("mobile/cart/checkout")
    fun cartCheckout(
        @Header("Authorization") token: String,
        @Body body: Map<String, List<Int>>
    ): Call<ApiResponse>

    @POST("mobile/buy-now")
    fun buyNow(
        @Header("Authorization") token: String,
        @Body body: Map<String, Int>
    ): Call<ApiResponse>
}
```

---

## 7. Complete Payment Flow (Decision Logic)

```kotlin
fun handleBuyNow(designId: Int) {
    apiService.buyNow("Bearer $token", mapOf("design_id" to designId))
        .enqueue(object : Callback<ApiResponse> {
            override fun onResponse(call: Call<ApiResponse>, response: Response<ApiResponse>) {
                val data = response.body()?.data ?: return

                if (data.can_claim_free) {
                    // Free via package — no payment
                    claimDesignFree(designId)
                } else {
                    // Open Razorpay
                    startRazorpayPayment(designId, data.amount)
                }
            }
            override fun onFailure(call: Call<ApiResponse>, t: Throwable) { }
        })
}
```

---

## 8. API Response Models
```kotlin
data class ApiResponse(
    val success: Boolean,
    val message: String?,
    val data: ResponseData?
)

data class ResponseData(
    val can_claim_free: Boolean = false,
    val package_exceeded: Boolean = false,
    val amount: Double = 0.0,
    val design: Design? = null,
    val designs: List<Design>? = null,
    val free_designs: List<Design>? = null,
    val paid_designs: List<Design>? = null,
    val total_amount: Double = 0.0,
    val remaining_downloads: Int = 0,
    val package: Package? = null
)
```

---

## 9. Error Handling

All API errors follow this format:
```json
{ "success": false, "message": "Error description" }
```

```kotlin
fun handleApiError(response: Response<ApiResponse>) {
    val errorBody = response.errorBody()?.string()
    val error = Gson().fromJson(errorBody, ApiResponse::class.java)
    Toast.makeText(context, error.message ?: "Something went wrong", Toast.LENGTH_SHORT).show()
}
```

Common HTTP codes:
| Code | Meaning |
|---|---|
| 400 | Already purchased / package exceeded |
| 401 | Token expired — redirect to login |
| 422 | Validation error |

---

## 10. Quick Summary

```
Buy Design:
  buyNow → can_claim_free?
    YES → POST /claim-design
    NO  → Razorpay → POST /payment/success

Buy Cart:
  cartCheckout → total_amount > 0?
    YES → Razorpay → POST /payment/bulk-success (with free_design_ids too)
    NO  → POST /claim-designs-bulk

Buy Package:
  packageBuy → Razorpay → POST /package/payment/success
```
