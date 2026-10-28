<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BakerySystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create standard test category and product
        $category = Category::create([
            'title' => 'Luxury Hampers',
            'slug' => 'hampers',
            'display_order' => 1,
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Royal Emerald Hamper',
            'slug' => 'royal-emerald-hamper',
            'price' => 750000,
            'availability' => 'in_stock',
            'is_best_seller' => true,
            'is_treat' => true,
        ]);
    }

    public function test_homepage_loads_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Royal Emerald Hamper');
        $response->assertSee("Have a bite, you can't stop!", false);
        $response->assertSee('header-menu-wrap-btn');
        $response->assertSee('menu-btn-text');
        $response->assertSee('Menu');
        $response->assertSee('Produk Berkualitas');
        $response->assertSee('Jaminan Kualitas');
        $response->assertSee('Kategori Produk');
        $response->assertSee('Big Bites');
        $response->assertSee('Loyang Mini');
        $response->assertSee('Other');
        $response->assertSee('luxury-brand-stack');
        $response->assertDontSee('GABUNG VIP');
        $response->assertDontSee('Berlangganan Penawaran Eksklusif');
        $response->assertSee('logo-title-majestic');
        $response->assertSee('CERITA RASA KAMI');
        $response->assertSee('Pure Wijsman Butter');
        $response->assertSee('100% Halal');
        $response->assertSee('0% Pengawet');
        $response->assertSee('Lihat Semua Pilihan Hampers');
        $response->assertDontSee('id="open-search-btn"', false);
        $response->assertDontSee('wa.me');
    }

    public function test_shop_page_loads_with_products(): void
    {
        $response = $this->get('/shop');
        $response->assertStatus(200);
        $response->assertSee('Royal Emerald Hamper');
        $response->assertSee('PRODUK');
        $response->assertDontSee('shop-acc-item'); // Confirms old sidebar filters removed
        $response->assertDontSee('wa.me');
    }

    public function test_category_page_displays_clean_layout_without_sidebar_filters(): void
    {
        $response = $this->get('/shop/hampers');
        $response->assertStatus(200);
        $response->assertSee('Royal Emerald Hamper');
        $response->assertDontSee('shop-acc-item');
    }

    public function test_product_detail_page_loads_matching_reference_layout(): void
    {
        $product = Product::first();
        $response = $this->get('/product/' . $product->slug);
        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee('Add to cart');
        $response->assertSee('Informasi Produk');
        $response->assertSee('Komposisi (Bahan-bahan)');
        $response->assertSee('Informasi Penyimpanan');
        $response->assertSee('You also like');
        $response->assertSee('shopping-cart-drawer');
        $response->assertDontSee('wa.me');
    }

    public function test_cart_can_add_item_and_retrieve_session(): void
    {
        $product = Product::first();

        $response = $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response->assertRedirect('/cart');
        $this->assertEquals(2, session('cart')[$product->id]['quantity']);
    }

    public function test_coupon_application_validates_server_side(): void
    {
        $product = Product::first();

        // Add to cart first so session has a valid subtotal of Rp 750.000
        $this->withSession([
            'cart' => [
                $product->id => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => 1,
                ]
            ]
        ]);

        Coupon::create([
            'code' => 'DISC10',
            'type' => 'percent',
            'value' => 10,
            'min_purchase' => 100000,
            'is_active' => true,
        ]);

        $response = $this->postJson('/coupon/apply', [
            'code' => 'DISC10',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'discount' => 75000,
        ]);
    }

    public function test_coupon_rejects_empty_cart(): void
    {
        $response = $this->postJson('/coupon/apply', [
            'code' => 'DISC10',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
    }

    public function test_admin_login_authentication_and_protection(): void
    {
        // Create an admin user
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('secret123'),
        ]);
        $admin->is_admin = true;
        $admin->save();

        // 1. Regular user cannot access admin dashboard
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/admin/login');

        // 2. Admin can log in successfully
        $loginResponse = $this->post('/admin/login', [
            'email' => 'admin@test.com',
            'password' => 'secret123',
        ]);
        $loginResponse->assertRedirect('/admin/dashboard');

        // 3. Clear cache route is protected inside admin
        $this->actingAs($admin);
        $cacheResponse = $this->get('/admin/clear-cache');
        $cacheResponse->assertStatus(200);
        $cacheResponse->assertSee('Cache & View Berhasil Dibersihkan', false);

        // 4. Hero Banner Slider management is accessible to admin
        $bannerResponse = $this->get('/admin/hero-banner');
        $bannerResponse->assertStatus(200);
        $bannerResponse->assertSee('Hero Banner Slider');

        // 5. Hero Banner Slider settings can be saved (default 4 detik)
        $settingPost = $this->post('/admin/hero-banner/settings', [
            'interval' => 4000,
            'autoplay' => 1,
            'direction' => 'right',
        ]);
        $settingPost->assertRedirect('/admin/hero-banner');
        $settingPost->assertSessionHas('success');
    }

    public function test_tracking_page_masks_customer_phone_for_public(): void
    {
        $order = Order::create([
            'invoice_number' => 'INV-TEST-9999',
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '081234567890',
            'delivery_option' => 'delivery',
            'delivery_date' => now()->addDays(2),
            'address' => 'Jl. Kebon Jeruk No. 15, Jakarta Barat',
            'subtotal' => 750000,
            'total_amount' => 750000,
            'payment_status' => 'paid',
            'order_status' => 'processing',
        ]);

        $response = $this->get('/track-order?invoice=INV-TEST-9999');
        $response->assertStatus(200);
        // Ensure sensitive phone number is masked for guests
        $response->assertSee('0812••••890');
        $response->assertDontSee('081234567890');
    }

    public function test_mobile_bottom_navigation_is_rendered(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('mobile-bottom-nav-bar');
        $response->assertSee('bottomNavCartBadge');
        $response->assertSee('Shop');
        $response->assertSee('Account');
        $response->assertSee('Cart');
    }

    public function test_order_invoice_pdf_view_renders_correctly(): void
    {
        $order = Order::create([
            'invoice_number' => 'INV-TEST-PDF-001',
            'customer_name' => 'Siti Nurhaliza',
            'customer_phone' => '081987654321',
            'customer_email' => 'siti@example.com',
            'delivery_option' => 'delivery',
            'delivery_date' => now()->addDays(1),
            'address' => 'Jl. Sudirman Kav 25, Jakarta Selatan',
            'subtotal' => 750000,
            'total_amount' => 750000,
            'payment_status' => 'paid',
            'payment_method' => 'BCA Virtual Account',
            'order_status' => 'processing',
        ]);

        $order->items()->create([
            'product_name' => 'Royal Emerald Hamper',
            'quantity' => 1,
            'price' => 750000,
            'subtotal' => 750000,
        ]);

        $response = $this->get('/order/INV-TEST-PDF-001/invoice-pdf');
        $response->assertStatus(200);
        $response->assertSee('INV-TEST-PDF-001');
        $response->assertSee('Siti Nurhaliza');
        $response->assertSee('Jl. Sudirman Kav 25, Jakarta Selatan');
        $response->assertSee('FAKTUR PENGIRIMAN &amp; INVOICE', false);
        $response->assertSee('Royal Emerald Hamper');
        $response->assertSee('window.print()');
    }

    public function test_admin_can_delete_completed_or_test_order(): void
    {
        $admin = User::create([
            'name' => 'Admin Super',
            'email' => 'admin-delete@test.com',
            'password' => Hash::make('secret123'),
        ]);
        $admin->is_admin = true;
        $admin->save();

        $order = Order::create([
            'invoice_number' => 'INV-TEST-DELETE',
            'customer_name' => 'Hapus Saya',
            'customer_phone' => '081233333333',
            'delivery_option' => 'pickup',
            'delivery_date' => now(),
            'subtotal' => 100000,
            'total_amount' => 100000,
            'payment_status' => 'paid',
            'order_status' => 'completed',
        ]);

        $order->items()->create([
            'product_name' => 'Test Item',
            'quantity' => 1,
            'price' => 100000,
            'subtotal' => 100000,
        ]);

        // Unauthenticated user cannot delete
        $guestDelete = $this->delete('/admin/orders/' . $order->id);
        $guestDelete->assertRedirect('/admin/login');
        $this->assertDatabaseHas('orders', ['id' => $order->id]);

        // Authenticated admin can delete
        $this->actingAs($admin);
        $adminDelete = $this->delete('/admin/orders/' . $order->id);
        $adminDelete->assertRedirect(route('admin.orders.index'));
        $adminDelete->assertSessionHas('success');
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }

    public function test_admin_pricelist_pdf_page_has_no_reservation_package(): void
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin-pdf@test.com',
            'password' => Hash::make('secret123'),
        ]);
        $admin->is_admin = true;
        $admin->save();

        $this->actingAs($admin);
        $response = $this->get('/admin/menu-pdf');
        $response->assertStatus(200);
        $response->assertSee('Kelola Berkas Pricelist &amp; Katalog PDF Toko', false);
        $response->assertDontSee('Daftar Paket Reservasi Toko');
        $response->assertDontSee('Tambah Paket Reservasi Baru');
        $response->assertDontSee('Outside Catering');
        $response->assertDontSee('Dine In');
    }

    public function test_homepage_has_interactive_gallery_and_lightbox(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('gallery-filter-tabs');
        $response->assertSee('galleryLightboxModal');
        $response->assertSee('Hampers Rose Velvet Box');
        $response->assertSee('Royal Heritage Hamper Basket');
        $response->assertSee('Pure Wijsman Butter Cookies');
        $response->assertSee('Basque Burnt Cheesecake');
    }

    public function test_homepage_has_reviews_auto_slider_with_multiple_reviews(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('reviewsSliderContainer');
        $response->assertSee('reviewsSliderTrack');
        $response->assertSee('Sefsaya');
        $response->assertSee('Adeline Wijaya');
        $response->assertSee('Clarissa Putri');
        $response->assertSee('revPrevBtn');
        $response->assertSee('revNextBtn');
    }

    public function test_checkout_page_renders_with_greeting_cards_and_trust_badges(): void
    {
        $product = Product::first();
        $this->withSession([
            'cart' => [
                $product->id => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => 1,
                ]
            ]
        ]);

        $response = $this->get('/checkout');
        $response->assertStatus(200);
        $response->assertSee('DETAIL PEMESAN &amp; PENGIRIMAN', false);
        $response->assertSee('Kartu Ucapan Spesial Hantaran');
        $response->assertSee('Kirim sebagai Hadiah / Kado');
        $response->assertSee('Transfer Bank BCA');
        $response->assertSee('QRIS Instant Payment');
        $response->assertSee('Enkripsi SSL 256-Bit');
        $response->assertSee('Garansi Kemasan Aman');
        $response->assertDontSee('wa.me');
    }

    public function test_checkout_processing_saves_greeting_card_and_gift_recipient_in_order_note(): void
    {
        $product = Product::first();
        $this->withSession([
            'cart' => [
                $product->id => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => 1,
                ]
            ]
        ]);

        $postData = [
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '081234567890',
            'customer_email' => 'budi@test.com',
            'delivery_option' => 'delivery',
            'courier_name' => 'Kurir Toko (Dedicated Bakery Delivery)',
            'delivery_date' => now()->addDays(2)->format('Y-m-d'),
            'address' => 'Jl. Kemang Raya No. 10',
            'city' => 'Jakarta Selatan',
            'postal_code' => '12730',
            'is_gift' => '1',
            'recipient_name' => 'Ibu Rina Suryani',
            'recipient_phone' => '081987654321',
            'card_to' => 'Ibu Rina & Keluarga',
            'card_from' => 'Keluarga Budi Santoso',
            'card_message' => 'Selamat Hari Raya Idul Fitri 1447 H. Mohon maaf lahir batin.',
            'payment_method' => 'Transfer Bank BCA',
            'terms' => '1',
        ];

        $response = $this->post(route('checkout.process'), $postData);
        $response->assertRedirect();

        $order = Order::where('customer_name', 'Budi Santoso')->latest()->first();
        $this->assertNotNull($order);
        $this->assertStringContainsString('Jakarta Selatan', $order->address);
        $this->assertStringContainsString('12730', $order->address);
        $this->assertStringContainsString('[KARTU UCAPAN HAMPERS]', $order->order_note);
        $this->assertStringContainsString('Ibu Rina & Keluarga', $order->order_note);
        $this->assertStringContainsString('Selamat Hari Raya Idul Fitri', $order->order_note);
        $this->assertStringContainsString('[KIRIM SEBAGAI HADIAH', $order->order_note);
        $this->assertStringContainsString('Ibu Rina Suryani', $order->order_note);
    }

    public function test_homepage_is_styled_in_burgundy_white_theme(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('theme-burgundy-white');
        $response->assertDontSee('siteThemeBar');
        $response->assertDontSee('floatingThemeToggle');
    }

    public function test_product_without_gallery_strictly_displays_only_uploaded_image(): void
    {
        $category = Category::first();
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Custom Hamper Single Photo',
            'slug' => 'custom-hamper-single-photo',
            'price' => 500000,
            'image' => 'custom-hamper-single.jpg',
            'gallery' => [],
            'availability' => 'in_stock',
        ]);

        // 1. Gallery URLs must contain ONLY 1 image (no extra stock photos)
        $this->assertCount(1, $product->gallery_urls);
        $this->assertEquals($product->image_url, $product->secondary_image_url);

        // 2. Product detail page must NOT inject extra gallery images
        $response = $this->get('/product/' . $product->slug);
        $response->assertStatus(200);
        $response->assertDontSee('cat-cookies.jpg');
        $response->assertDontSee('Thumbnail 2');
    }

    public function test_admin_panel_is_styled_in_burgundy_and_gold_theme(): void
    {
        // 1. Admin Login page has burgundy palette
        $loginRes = $this->get('/admin/login');
        $loginRes->assertStatus(200);
        $loginRes->assertSee('--bg-dark: #22040b', false);
        $loginRes->assertSee('--panel-bg: #320712', false);
        $loginRes->assertDontSee('#0f172a');

        // 2. Authenticated Admin Dashboard has burgundy palette
        $user = User::factory()->create([
            'is_admin' => true,
        ]);
        $dashRes = $this->actingAs($user)->get('/admin/dashboard');
        $dashRes->assertStatus(200);
        $dashRes->assertSee('--bg-dark: #22040b', false);
        $dashRes->assertSee('--panel-bg: #320712', false);
        $dashRes->assertDontSee('#0f172a');
        $dashRes->assertDontSee('#13221b');
    }

    public function test_google_auth_flow_and_account_chooser(): void
    {
        // 1. Direct /auth/google redirects to Google accounts OAuth with select_account prompt
        $response = $this->get('/auth/google');
        $this->assertTrue($response->isRedirect());
        $targetUrl = $response->headers->get('Location');
        $this->assertStringContainsString('accounts.google.com', $targetUrl);
        $this->assertStringContainsString('prompt=select_account', $targetUrl);

        // 2. /auth/google?chooser=1 renders the account chooser UI with accounts and direct button
        $chooserRes = $this->get('/auth/google?chooser=1');
        $chooserRes->assertStatus(200);
        $chooserRes->assertSee('CHOOSE AN ACCOUNT');
        $chooserRes->assertSee('Buka Pilihan Akun Google Resmi');
        $chooserRes->assertSee('deviceAccountsContainer');
        $chooserRes->assertSee('Gunakan akun Google lain');

        // 3. User can login with Google account callback
        $callbackRes = $this->post('/auth/google/callback', [
            'email' => 'pelanggan.test@gmail.com',
            'name' => 'Pelanggan Google Test',
        ]);
        $callbackRes->assertRedirect(route('home'));
        $this->assertAuthenticated();
        $this->assertEquals('pelanggan.test@gmail.com', auth()->user()->email);
    }

    public function test_customer_registration_page_and_processing(): void
    {
        // 1. Register page loads properly with tabs and form
        $res = $this->get('/register');
        $res->assertStatus(200);
        $res->assertSee('DAFTAR AKUN BARU');
        $res->assertSee('Masuk Akun');
        $res->assertSee('Daftar Akun Baru');
        $res->assertSee('Daftar Cepat dengan Akun Google');
        $res->assertSee('DAFTAR SEKARANG');

        // 2. Validation fails on missing or mismatched password
        $failRes = $this->post('/register', [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => '123456',
            'password_confirmation' => 'mismatch',
        ]);
        $failRes->assertSessionHasErrors('password');

        // 3. Successful registration creates user, logs them in, and redirects to home
        $successRes = $this->post('/register', [
            'name' => 'Siti Nurhaliza',
            'email' => 'siti.nurhaliza@example.com',
            'phone' => '081298765432',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ]);
        $successRes->assertRedirect(route('home'));
        $this->assertAuthenticated();
        $this->assertEquals('siti.nurhaliza@example.com', auth()->user()->email);
        $this->assertEquals('081298765432', auth()->user()->phone);

        // 4. Duplicate email registration is prevented
        auth()->logout();
        $dupRes = $this->post('/register', [
            'name' => 'Siti Kloning',
            'email' => 'siti.nurhaliza@example.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ]);
        $dupRes->assertSessionHasErrors('email');
    }

    public function test_pricelist_page_renders_luxury_concept_matching_reference(): void
    {
        // Both /pricelist and /menu-pdf load successfully
        $res1 = $this->get('/pricelist');
        $res1->assertStatus(200);
        $res1->assertSee('Pricelist');
        $res1->assertSee('Pusat Hampers Indonesia');
        $res1->assertSee('HALAL INDONESIA');
        $res1->assertSee('Kaasstengels Toples');
        $res1->assertSee('160K');
        $res1->assertSee('Nastar Toples');
        $res1->assertSee('180K');
        $res1->assertSee('Lapis Loyang Mini Original');
        $res1->assertSee('260K');
        $res1->assertSee('Lapis Plum Big');
        $res1->assertSee('Risoles');
        $res1->assertSee('Risoles Beef Mayo');
        $res1->assertSee('Order on Tokopedia');
        $res1->assertSee('Order on Website');
        $res1->assertSee('Cetak / Download PDF');

        $res2 = $this->get('/menu-pdf');
        $res2->assertStatus(200);
        $res2->assertSee('Pricelist');
        $res2->assertSee('Order on Tokopedia');
        $res2->assertSee('Order on Website');
    }

    public function test_terms_and_conditions_page_renders_matching_reference_concept(): void
    {
        $res1 = $this->get('/terms');
        $res1->assertStatus(200);
        $res1->assertSee('Syarat &amp; Ketentuan', false);
        $res1->assertSee('Ketentuan Umum');
        $res1->assertSee('Produk &amp; Kualitas', false);
        $res1->assertSee('Pemesanan');
        $res1->assertSee('Ketersediaan Produk');
        $res1->assertSee('Custom Order &amp; Corporate Order', false);
        $res1->assertSee('Harga &amp; Pembayaran', false);
        $res1->assertSee('Pengiriman');
        $res1->assertSee('Tanggung Jawab Pengiriman');
        $res1->assertSee('Kebijakan Refund &amp; Komplain', false);
        $res1->assertSee('Operasional Toko');
        $res1->assertSee('Seasonal &amp; Special Edition', false);
        $res1->assertSee('Hak Kekayaan Intelektual');
        $res1->assertSee('Persetujuan');
        $res1->assertSee('terms-toggle-btn');
        $res1->assertSee('toggleTermsCard');
        $res1->assertSee('toggleAllTerms');
        $res1->assertDontSee('The Golden Bites');

        $res2 = $this->get('/syarat-ketentuan');
        $res2->assertStatus(200);
        $res2->assertSee('Syarat &amp; Ketentuan', false);
        $res2->assertDontSee('The Golden Bites');
    }

    public function test_payment_and_shipping_page_renders_matching_reference_concept(): void
    {
        $res1 = $this->get('/pembayaran-pengiriman');
        $res1->assertStatus(200);
        $res1->assertSee('Pembayaran &amp; Pengiriman', false);
        $res1->assertSee('Metode Pembayaran (Melalui Xendit)');
        $res1->assertSee('Virtual Account (Bank Transfer)');
        $res1->assertSee('E-Wallet');
        $res1->assertSee('QRIS');
        $res1->assertSee('Pengiriman Domestik (Indonesia)');
        $res1->assertDontSee('Pale Baithip');
        $res1->assertDontSee('Pengiriman Internasional');
        $res1->assertDontSee('Pajak impor dan bea cukai');

        $res2 = $this->get('/payment-shipping');
        $res2->assertStatus(200);
        $res2->assertSee('Pembayaran &amp; Pengiriman', false);
        $res2->assertDontSee('Pale Baithip');
    }

    public function test_homepage_has_best_seller_kategori_produk_auto_slider(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('cat-eyebrow-ref');
        $response->assertSee('Best Seller');
        $response->assertSee('Kategori Produk');
        $response->assertSee('catSliderTrack');
        $response->assertSee('catSliderPrev');
        $response->assertSee('catSliderNext');
        $response->assertSee('slideNextCat');
        $response->assertSee('startCatAutoplay');
    }

    public function test_gallery_page_renders_matching_reference_grid_and_drawer_link(): void
    {
        // 1. Gallery page loads with 3-column photo grid & lightbox
        $res = $this->get('/gallery');
        $res->assertStatus(200);
        $res->assertSee('Gallery');
        $res->assertSee('gallery-cards-grid');
        $res->assertSee('gallery-card-item');
        $res->assertSee('Lapis Legit Original');
        $res->assertSee('Once You Bite, You Can\'t Stop!', false);
        $res->assertSee('HALAL INDONESIA');
        $res->assertSee('lightboxModal');

        // 2. Navigation drawer links to /gallery
        $homeRes = $this->get('/');
        $homeRes->assertStatus(200);
        $homeRes->assertSee(route('gallery'));
    }

    public function test_back_navigation_feature_renders_across_pages(): void
    {
        // 1. Homepage does not render the back button
        $home = $this->get('/');
        $home->assertStatus(200);
        $home->assertDontSee('class="global-page-back-bar"', false);
        $home->assertDontSee('class="floating-back-pill"', false);

        // 2. Non-home pages have both sticky back bar and floating back pill
        $pages = ['/shop', '/gallery', '/terms', '/pembayaran-pengiriman', '/pricelist', '/cart', '/track-order'];
        foreach ($pages as $p) {
            $res = $this->get($p);
            $res->assertStatus(200);
            $res->assertSee('class="global-page-back-bar"', false);
            $res->assertSee('class="floating-back-pill"', false);
            $res->assertSee('Kembali');
        }
    }
}
