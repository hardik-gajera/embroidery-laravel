<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\DesignPackageController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\OrderController;

// Frontend routes
Route::get('/clear-cache', function() {
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    return 'All caches cleared!';
});
Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/all-designs', [FrontendController::class, 'allDesigns'])->name('frontend.all-designs');
Route::get('/category/{id}', [FrontendController::class, 'categories'])->name('frontend.categories');
Route::get('/designs/{id}', [FrontendController::class, 'designs'])->name('frontend.designs');
Route::get('/design/download/{id}', [FrontendController::class, 'downloadDesign'])->name('frontend.design.download');
Route::get('/design/{id}', [FrontendController::class, 'designDetail'])->name('frontend.design.detail');
Route::get('/customer/login', [FrontendController::class, 'showLogin'])->name('frontend.login');
Route::post('/customer/login', [FrontendController::class, 'login'])->name('frontend.login.post');
Route::post('/customer/register', [FrontendController::class, 'register'])->name('frontend.register.post');
Route::get('/customer/forgot-password', [FrontendController::class, 'showForgotPassword'])->name('frontend.forgot-password');
Route::post('/customer/forgot-password', [FrontendController::class, 'forgotPassword'])->name('frontend.forgot-password.post');
Route::get('/customer/reset-password', [FrontendController::class, 'showResetPassword'])->name('frontend.reset-password');
Route::post('/customer/reset-password', [FrontendController::class, 'resetPassword'])->name('frontend.reset-password.post');
Route::post('/customer/logout', [FrontendController::class, 'logout'])->name('frontend.logout');
Route::get('/cart', [FrontendController::class, 'cart'])->name('frontend.cart');
Route::post('/cart/add', [FrontendController::class, 'addToCart'])->name('frontend.cart.add');
Route::delete('/cart/{id}', [FrontendController::class, 'removeFromCart'])->name('frontend.cart.remove');
Route::post('/buy-now', [FrontendController::class, 'buyNow'])->name('frontend.buy');
Route::post('/claim-design', [FrontendController::class, 'claimDesign'])->name('frontend.claim');
Route::post('/payment/success', [FrontendController::class, 'paymentSuccess'])->name('frontend.payment.success');
Route::get('/my-designs', [FrontendController::class, 'myDesigns'])->name('frontend.my-designs');
Route::get('/my-packages', [FrontendController::class, 'myPackages'])->name('frontend.my-packages');
Route::get('/packages', [FrontendController::class, 'packages'])->name('frontend.packages');
Route::get('/about', [FrontendController::class, 'about'])->name('frontend.about');
Route::get('/contact', [FrontendController::class, 'contact'])->name('frontend.contact');
Route::post('/contact', [FrontendController::class, 'contactSend'])->name('frontend.contact.send');
Route::get('/package/{id}', [FrontendController::class, 'packageDetail'])->name('frontend.package.detail');
Route::post('/package/buy', [FrontendController::class, 'buyPackage'])->name('frontend.package.buy');
Route::post('/package/payment/success', [FrontendController::class, 'packagePaymentSuccess'])->name('frontend.package.payment.success');

// Admin Auth routes
Route::middleware('guest')->prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
});

// Admin Authenticated routes
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/', fn() => redirect('/admin/dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('customers', CustomerController::class);
    Route::resource('categories', CategoryController::class)->except('show');
    Route::resource('designs', DesignController::class);
    Route::get('/designs/{design}/download', [DesignController::class, 'download'])->name('designs.download');
    Route::resource('packages', DesignPackageController::class);
    Route::get('/package-history', [DashboardController::class, 'packageHistory'])->name('package-history');
    Route::resource('contact-messages', ContactMessageController::class)->only(['index', 'show', 'destroy']);
    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
