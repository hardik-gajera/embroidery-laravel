<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DesignController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PackageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API routes
Route::middleware(['api', \App\Http\Middleware\ForceJsonResponse::class])->prefix('mobile')->group(function () {
    // OTP Authentication
    Route::post('/send-otp', [AuthController::class, 'sendOtp']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/register', [AuthController::class, 'register']);
    
    // Public data
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::get('/categories/{id}/designs', [CategoryController::class, 'designs']);
    Route::get('/designs', [DesignController::class, 'index']);
    Route::get('/designs/featured', [DesignController::class, 'featured']);
    Route::get('/designs/{id}', [DesignController::class, 'show']);
    Route::get('/packages', [PackageController::class, 'index']);
    Route::get('/packages/{id}', [PackageController::class, 'show']);
});

// Protected API routes
Route::middleware(['auth:sanctum', \App\Http\Middleware\ForceJsonResponse::class])->prefix('mobile')->group(function () {
    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::get('/check-session', [AuthController::class, 'checkSession']);
    
    // Cart management
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::delete('/cart/{id}', [CartController::class, 'remove']);
    Route::delete('/cart', [CartController::class, 'clear']);
    
    // Orders & Purchases
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/my-designs', [OrderController::class, 'myDesigns']);
    Route::post('/buy-now', [OrderController::class, 'buyNow']);
    Route::post('/claim-design', [OrderController::class, 'claimDesign']);
    Route::post('/claim-designs-bulk', [OrderController::class, 'claimDesignsBulk']);
    Route::post('/cart/checkout', [OrderController::class, 'cartCheckout']);
    Route::post('/payment/success', [OrderController::class, 'paymentSuccess']);
    Route::post('/payment/bulk-success', [OrderController::class, 'bulkPaymentSuccess']);
    
    // Design downloads
    Route::get('/designs/{id}/download', [DesignController::class, 'download']);
    
    // Packages
    Route::get('/my-packages', [PackageController::class, 'myPackages']);
    Route::post('/package/buy', [PackageController::class, 'buy']);
    Route::post('/package/payment/success', [PackageController::class, 'paymentSuccess']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
