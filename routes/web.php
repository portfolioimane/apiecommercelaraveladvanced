<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;
use App\Http\Controllers\Frontend\HomeController;

use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\ProductController as BackendProductController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\DashboardController;

// Authentication routes
Auth::routes();

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// View single product
Route::get('/product/{id}', [FrontendProductController::class, 'show'])->name('product.show');

// View all products
Route::get('/products', [FrontendProductController::class, 'index'])->name('products.index');

// Cart routes


Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');



// Checkout routes
Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
Route::post('/process-payment', [CheckoutController::class, 'processPayment'])->name('process.payment');
Route::get('/payment-return', [CheckoutController::class, 'handlePaymentReturn'])->name('payment.return');
Route::get('/success/{orderId}', [CheckoutController::class, 'success'])->name('success');
Route::get('/cancel', [CheckoutController::class, 'cancel'])->name('cancel');

// Route for cash on delivery
Route::post('/checkout/cash-on-delivery', [CheckoutController::class, 'handleCashOnDelivery'])->name('checkout.cashOnDelivery');


// PayPal routes
Route::get('/paypalsuccess', [CheckoutController::class, 'paypalsuccess'])->name('paypalsuccess');
Route::post('/paypal/create', [CheckoutController::class, 'createPayment'])->name('paypal.create');

// Backend Routes (Admin Panel)
Route::middleware(['auth', 'admin'])->group(function() {

        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    // User management
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/{id}', [UserController::class, 'show'])->name('admin.users.show');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');


    // categories
     Route::get('/admin/categories', [BackendProductController::class, 'fetchCategories'])->name('admin.categories.index');
    // Product management
 
    Route::get('/admin/products', [BackendProductController::class, 'index'])->name('admin.products.index');  
    Route::get('/admin/products/{id}', [BackendProductController::class, 'show'])->name('admin.products.show');
    Route::post('/admin/products', [BackendProductController::class, 'store'])->name('admin.products.store');
    Route::put('/admin/products/{id}', [BackendProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/admin/products/{id}', [BackendProductController::class, 'destroy'])->name('admin.products.destroy');

    // Order management
  
    Route::get('/admin/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/admin/orders/{id}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::put('/admin/orders/{id}', [OrderController::class, 'update'])->name('admin.orders.update');
    Route::delete('/admin/orders/{id}', [OrderController::class, 'destroy'])->name('admin.orders.destroy');

    // OrderItem management
    Route::get('/admin/order-items', [OrderController::class, 'indexItem'])->name('admin.orderItems.index');
    Route::delete('/admin/order-items/{id}', [OrderController::class, 'destroyItem'])->name('admin.orderItems.destroy');
});