<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;

// Halaman Public
Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');


// Pengunjung yang belum login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});


// Harus login
Route::middleware('auth')->group(function () {
    
    // Auth Actions
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [PageController::class, 'profile'])->name('profile');

    // Cart Management
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Order & Checkout
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store'); // Checkout Action
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index'); // History List
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show'); // Invoice View
    Route::post('/orders/{order}/payment-success', [OrderController::class, 'paymentSuccess'])->name('orders.payment_success'); // Sukses Dibayar

});

// --- ADMIN PANEL ROUTES ---
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Product Management (Resource includes: index, create, store, edit, update, destroy)
    Route::resource('products', AdminController::class);
    
    // Order Management
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders.index');
    Route::post('/orders/{order}/status', [AdminController::class, 'updateStatus'])->name('orders.updateStatus');

    // Admin Category Routes
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories.index');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::post('/categories/{category}/toggle', [AdminController::class, 'toggleCategory'])->name('categories.toggle');
});

// --- 4. EXCLUDED ROUTES (Midtrans Webhook) ---
// Note: You must exclude this route from CSRF protection in bootstrap/app.php
// or VerifyCsrfToken middleware (depending on your Laravel version)
Route::post('/payment/notification', [PaymentController::class, 'webhook'])->name('payment.webhook');