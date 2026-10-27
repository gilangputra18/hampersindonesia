<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminContactController;
use App\Http\Controllers\Admin\AdminCouponReviewController;
use App\Http\Controllers\Admin\AdminHeroBannerController;
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
Route::get('/shop', [SiteController::class, 'category'])->name('shop');
Route::get('/shop/{slug?}', [SiteController::class, 'category'])->name('category');
Route::get('/product/{slug}', [SiteController::class, 'product'])->name('product.detail');
Route::redirect('/reservations', '/shop')->name('reservations');
Route::get('/menu-pdf/{slug?}', [SiteController::class, 'menuPdf'])->name('menu.pdf');
Route::get('/contact', [SiteController::class, 'contact'])->name('contact');
Route::post('/contact', [SiteController::class, 'sendContact'])->middleware('throttle:5,1')->name('contact.send');
Route::get('/track-order', [SiteController::class, 'track'])->name('track');

// Customer Auth Routes
Route::get('/login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [CustomerAuthController::class, 'login'])->middleware('throttle:5,1');
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
Route::post('/checkout', [CheckoutController::class, 'process'])->middleware('throttle:10,1')->name('checkout.process');
Route::get('/order/{invoice}', [CheckoutController::class, 'showOrder'])->name('order.show');
Route::get('/order/{invoice}/invoice-pdf', [CheckoutController::class, 'printInvoice'])->name('order.invoice.pdf');
Route::get('/order/{invoice}/status', [CheckoutController::class, 'checkStatus'])->name('order.status');
Route::post('/order/{invoice}/pay-midtrans', [CheckoutController::class, 'payMidtrans'])->name('order.pay_midtrans');
Route::post('/order/{invoice}/upload-payment-proof', [CheckoutController::class, 'uploadPaymentProof'])->name('order.upload_payment_proof');
Route::post('/api/midtrans/notification', [CheckoutController::class, 'midtransNotification'])->name('midtrans.notification');

// Review & Coupon Public Routes
Route::post('/products/{product}/review', [ReviewCouponController::class, 'submitReview'])->middleware('throttle:5,1')->name('review.submit');
Route::post('/coupon/apply', [ReviewCouponController::class, 'applyCoupon'])->middleware('throttle:15,1')->name('coupon.apply');
Route::post('/coupon/remove', [ReviewCouponController::class, 'removeCoupon'])->name('coupon.remove');

// Customer Account (Auth Required)
Route::middleware('auth')->group(function () {
    Route::get('/my-orders', [CheckoutController::class, 'myOrders'])->name('my.orders');
});

// Admin Authentication Routes (Dedicated URL)
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->middleware('throttle:5,1')->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Protected Admin Panel Routes
Route::middleware([AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    // Protected Utility Route to Clear Cache (Admin Only)
    Route::get('/clear-cache', function() {
        try {
            \Illuminate\Support\Facades\Artisan::call('view:clear');
            \Illuminate\Support\Facades\Artisan::call('cache:clear');
            \Illuminate\Support\Facades\Artisan::call('config:clear');
            \Illuminate\Support\Facades\Artisan::call('route:clear');
            return '<div style="font-family:sans-serif; text-align:center; padding:50px;"><h2>✅ Cache & View Berhasil Dibersihkan!</h2><p>Cache sistem dan view Laravel berhasil diperbarui.</p><p><a href="/admin/dashboard" style="display:inline-block; margin-top:15px; padding:10px 20px; background:#d97706; color:#fff; text-decoration:none; border-radius:6px;">← Kembali ke Dashboard Admin</a></p></div>';
        } catch (\Throwable $e) {
            return 'Clear cache exception: ' . $e->getMessage();
        }
    })->name('clear_cache');

    // Protected Utility Route to Pull Latest Code from GitHub (Admin Only)
    Route::get('/git-pull', function() {
        try {
            $output = [];
            $code = 0;
            exec('git pull origin main 2>&1', $output, $code);
            \Illuminate\Support\Facades\Artisan::call('view:clear');
            \Illuminate\Support\Facades\Artisan::call('cache:clear');
            \Illuminate\Support\Facades\Artisan::call('config:clear');
            \Illuminate\Support\Facades\Artisan::call('route:clear');
            $log = implode("\n", $output);
            return '<div style="font-family:sans-serif; text-align:center; padding:50px;"><h2>🚀 Git Pull & Cache Refresh Berhasil!</h2><p>Kode terbaru ditarik dari GitHub dan cache view Laravel telah dibersihkan.</p><pre style="background:#f1f5f9; padding:20px; text-align:left; max-width:650px; margin:20px auto; border-radius:8px; font-size:13px; font-family:monospace; color:#0f172a;">' . htmlspecialchars($log ?: 'Git command executed successfully.') . '</pre><p><a href="/admin/dashboard" style="display:inline-block; margin-top:15px; padding:10px 20px; background:#d97706; color:#fff; text-decoration:none; border-radius:6px;">← Kembali ke Dashboard Admin</a></p></div>';
        } catch (\Throwable $e) {
            return 'Git pull exception: ' . $e->getMessage();
        }
    })->name('git_pull');

    Route::get('/', [AdminProductController::class, 'dashboard']);
    Route::get('/dashboard', [AdminProductController::class, 'dashboard'])->name('dashboard');
    
    // Sales Analytics Exports
    Route::get('/export/excel', [AdminProductController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/pdf', [AdminProductController::class, 'exportPdf'])->name('export.pdf');

    // Product & Category Management
    Route::get('/api/products/autocomplete', [AdminProductController::class, 'autocomplete'])->name('products.autocomplete');
    Route::post('/categories', [AdminProductController::class, 'categoryStore'])->name('categories.store');
    Route::delete('/categories/{category}', [AdminProductController::class, 'categoryDestroy'])->name('categories.destroy');
    Route::resource('products', AdminProductController::class);

    // Order & Verification Queue Management
    Route::get('/orders/verification-queue', [AdminOrderController::class, 'verificationQueue'])->name('orders.verification');
    Route::post('/orders/{order}/verify-payment', [AdminOrderController::class, 'verifyPayment'])->name('orders.verifyPayment');
    Route::post('/orders/{order}/reject-payment', [AdminOrderController::class, 'rejectPayment'])->name('orders.rejectPayment');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');
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

    // Hero Banner Slider Management (Edit Gambar Bergeser)
    Route::get('/hero-banner', [AdminHeroBannerController::class, 'index'])->name('hero_banner.index');
    Route::post('/hero-banner/slide', [AdminHeroBannerController::class, 'storeSlide'])->name('hero_banner.store');
    Route::post('/hero-banner/slide/{id}/update', [AdminHeroBannerController::class, 'updateSlide'])->name('hero_banner.update');
    Route::post('/hero-banner/slide/{id}/toggle', [AdminHeroBannerController::class, 'toggleSlide'])->name('hero_banner.toggle');
    Route::delete('/hero-banner/slide/{id}', [AdminHeroBannerController::class, 'destroySlide'])->name('hero_banner.destroy');
    Route::post('/hero-banner/settings', [AdminHeroBannerController::class, 'updateSettings'])->name('hero_banner.settings');
});
