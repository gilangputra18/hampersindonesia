<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminContactController;
use App\Http\Controllers\Admin\AdminCouponReviewController;
use App\Http\Controllers\Admin\AdminMenuPdfController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminShippingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\ReviewCouponController;
use App\Http\Controllers\SiteController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

// Public Website Routes
Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/shop/{slug}', [SiteController::class, 'category'])->name('category');
Route::get('/reservations', [SiteController::class, 'reservations'])->name('reservations');
Route::get('/menu-pdf/{slug?}', [SiteController::class, 'menuPdf'])->name('menu.pdf');
Route::get('/contact', [SiteController::class, 'contact'])->name('contact');
Route::post('/contact', [SiteController::class, 'sendContact'])->name('contact.send');
Route::get('/track-order', [SiteController::class, 'track'])->name('track');

// Customer Auth Routes
Route::get('/login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [CustomerAuthController::class, 'login']);
Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');
Route::get('/auth/google', [CustomerAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [CustomerAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::post('/auth/google/callback', [CustomerAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback.post');

// Public Live Search API
Route::get('/api/public/search', [AdminProductController::class, 'autocomplete'])->name('public.search');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

// Checkout & Order Routes
Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/order/{invoice}', [CheckoutController::class, 'showOrder'])->name('order.show');
Route::post('/order/{invoice}/pay-midtrans', [CheckoutController::class, 'payMidtrans'])->name('order.pay_midtrans');

// Review & Coupon Public Routes
Route::post('/products/{product}/review', [ReviewCouponController::class, 'submitReview'])->name('review.submit');
Route::post('/coupon/apply', [ReviewCouponController::class, 'applyCoupon'])->name('coupon.apply');
Route::post('/coupon/remove', [ReviewCouponController::class, 'removeCoupon'])->name('coupon.remove');

// Customer Account (Auth Required)
Route::middleware('auth')->group(function () {
    Route::get('/my-orders', [CheckoutController::class, 'myOrders'])->name('my.orders');
});

// Admin Authentication Routes (Dedicated URL)
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Protected Admin Panel Routes
Route::middleware([AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminProductController::class, 'dashboard']);
    Route::get('/dashboard', [AdminProductController::class, 'dashboard'])->name('dashboard');
    
    // Sales Analytics Exports
    Route::get('/export/excel', [AdminProductController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/pdf', [AdminProductController::class, 'exportPdf'])->name('export.pdf');

    // Product Management (CRUD)
    Route::get('/api/products/autocomplete', [AdminProductController::class, 'autocomplete'])->name('products.autocomplete');
    Route::resource('products', AdminProductController::class);

    // Order Management
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('/api/orders/notifications', [AdminOrderController::class, 'getNotifications'])->name('orders.notifications');

    // Admin Profile & Password Management
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');

    // Payment Methods Management
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments/update', [AdminPaymentController::class, 'update'])->name('payments.update');

    // Shipping & Courier Management
    Route::get('/shipping', [AdminShippingController::class, 'index'])->name('shipping.index');
    Route::post('/shipping/update', [AdminShippingController::class, 'update'])->name('shipping.update');

    // Menu PDF & Reservation Management
    Route::get('/menu-pdf', [AdminMenuPdfController::class, 'index'])->name('menu_pdf.index');
    Route::post('/menu-pdf/upload', [AdminMenuPdfController::class, 'uploadGlobalPdf'])->name('menu_pdf.upload');
    Route::delete('/menu-pdf', [AdminMenuPdfController::class, 'destroyGlobalPdf'])->name('menu_pdf.destroy');

    Route::post('/menu-pdf/item/{id}/upload-pdf', [AdminMenuPdfController::class, 'uploadItemPdf'])->name('menu_pdf.item_upload_pdf');
    Route::delete('/menu-pdf/item/{id}/destroy-pdf', [AdminMenuPdfController::class, 'destroyItemPdf'])->name('menu_pdf.item_destroy_pdf');
    Route::post('/menu-pdf/item/store', [AdminMenuPdfController::class, 'storeReservation'])->name('menu_pdf.store_reservation');
    Route::post('/menu-pdf/item/{id}/update', [AdminMenuPdfController::class, 'updateReservation'])->name('menu_pdf.update_reservation');
    Route::delete('/menu-pdf/item/{id}/destroy', [AdminMenuPdfController::class, 'destroyReservation'])->name('menu_pdf.destroy_reservation');

    // Contact & Concierge Messages Management
    Route::get('/contact-messages', [AdminContactController::class, 'index'])->name('contact.index');
    Route::post('/contact-messages/mark-all-read', [AdminContactController::class, 'markAllAsRead'])->name('contact.mark_all_read');
    Route::get('/contact-messages/{id}', [AdminContactController::class, 'show'])->name('contact.show');
    Route::post('/contact-messages/{id}/toggle-read', [AdminContactController::class, 'toggleRead'])->name('contact.toggle_read');
    Route::post('/contact-messages/{id}/reply', [AdminContactController::class, 'reply'])->name('contact.reply');
    Route::delete('/contact-messages/{id}', [AdminContactController::class, 'destroy'])->name('contact.destroy');

    // Coupon Management
    Route::get('/coupons', [AdminCouponReviewController::class, 'couponsIndex'])->name('coupons.index');
    Route::post('/coupons', [AdminCouponReviewController::class, 'couponStore'])->name('coupons.store');
    Route::post('/coupons/{coupon}/toggle', [AdminCouponReviewController::class, 'couponToggle'])->name('coupons.toggle');
    Route::delete('/coupons/{coupon}', [AdminCouponReviewController::class, 'couponDestroy'])->name('coupons.destroy');

    // Review / Ulasan Management
    Route::get('/reviews', [AdminCouponReviewController::class, 'reviewsIndex'])->name('reviews.index');
    Route::post('/reviews', [AdminCouponReviewController::class, 'reviewStore'])->name('reviews.store');
    Route::post('/reviews/{review}/approve', [AdminCouponReviewController::class, 'reviewApprove'])->name('reviews.approve');
    Route::post('/reviews/{review}/featured', [AdminCouponReviewController::class, 'reviewToggleFeatured'])->name('reviews.featured');
    Route::delete('/reviews/{review}', [AdminCouponReviewController::class, 'reviewDestroy'])->name('reviews.destroy');
});
