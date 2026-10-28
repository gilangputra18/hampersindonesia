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
        $response->assertSee('+ Tambah ke Keranjang');
        $response->assertSee('Informasi Produk');
        $response->assertSee('Komposisi (Bahan-bahan)');
        $response->assertSee('Informasi Penyimpanan');
        $response->assertSee('Produk Pilihan Lainnya');
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

    public function test_cart_can_remove_item_via_remove_action(): void
    {
        $product = Product::first();

        $this->withSession([
            'cart' => [
                $product->id => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => 2,
                ]
            ]
        ]);

        $response = $this->postJson('/cart/update', [
            'product_id' => $product->id,
            'action' => 'remove',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'action' => 'remove',
            'is_empty' => true,
        ]);
        $this->assertArrayNotHasKey($product->id, session('cart', []));
    }

    public function test_cart_can_clear_entire_cart(): void
    {
        $product = Product::first();

        $this->withSession([
            'cart' => [
                $product->id => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => 3,
                ]
            ]
        ]);

        $response = $this->postJson('/cart/clear');
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'is_empty' => true,
        ]);
        $this->assertEmpty(session('cart', []));
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
        $response->assertSee('Belanja');
        $response->assertSee('Akun');
        $response->assertSee('Keranjang');
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
        $response->assertSee('Apa Kata Pelanggan Kami');
        $response->assertDontSee('What Our Customers Say');
        $response->assertSee('reviewsSliderContainer');
        $response->assertSee('reviewsSliderTrack');
        $response->assertSee('Sefsaya');
        $response->assertSee('Adeline Wijaya');
        $response->assertSee('Clarissa Putri');
        $response->assertSee('revPrevBtn');
        $response->assertSee('revNextBtn');
    }

    public function test_unauthenticated_guest_is_redirected_to_login_when_accessing_checkout(): void
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
        $response->assertRedirect('/login');
    }

    public function test_cart_page_renders_delete_feature_and_guest_login_prompt(): void
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

        $response = $this->get('/cart');
        $response->assertStatus(200);
        $response->assertSee('cart-btn-delete');
        $response->assertSee('Kosongkan Keranjang');
        $response->assertSee('Wajib Masuk / Memiliki Akun untuk Belanja');
    }

    public function test_checkout_page_renders_with_greeting_cards_and_trust_badges(): void
    {
        $user = User::factory()->create();
        $product = Product::first();
        $this->actingAs($user)->withSession([
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
        $response->assertSee('Titik Lokasi Peta &amp; Koordinat GPS Presisi', false);
        $response->assertSee('Gunakan Lokasi Saat Ini (GPS)');
        $response->assertSee('Kota Praya / Lombok Tengah (NTB)');
        $response->assertSee('checkout_leaflet_map');
        $response->assertSee('leaflet.js');
        $response->assertDontSee('wa.me');
    }

    public function test_checkout_processing_saves_greeting_card_and_gift_recipient_in_order_note(): void
    {
        $user = User::factory()->create();
        $product = Product::first();
        $this->actingAs($user)->withSession([
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

    public function test_google_auth_callback_with_authorization_code_and_native_http(): void
    {
        \Illuminate\Support\Facades\Http::fake([
            'https://oauth2.googleapis.com/token' => \Illuminate\Support\Facades\Http::response([
                'access_token' => 'mock_access_token_123',
                'id_token' => 'mock_id_token',
                'expires_in' => 3600,
            ], 200),
            'https://www.googleapis.com/oauth2/v3/userinfo' => \Illuminate\Support\Facades\Http::response([
                'sub' => 'google_sub_998877',
                'name' => 'Dr. Hendra Gunawan',
                'email' => 'hendra.gunawan@gmail.com',
                'picture' => 'https://lh3.googleusercontent.com/avatar.jpg',
            ], 200),
        ]);

        $response = $this->get('/auth/google/callback?code=mock_auth_code_from_google');
        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();

        $user = User::where('email', 'hendra.gunawan@gmail.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Dr. Hendra Gunawan', $user->name);
        $this->assertEquals('google_sub_998877', $user->google_id);
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
        $res1->assertSee('Pesan di Tokopedia');
        $res1->assertSee('Pesan di Website');
        $res1->assertSee('Cetak / Unduh PDF');

        $res2 = $this->get('/menu-pdf');
        $res2->assertStatus(200);
        $res2->assertSee('Pricelist');
        $res2->assertSee('Pesan di Tokopedia');
        $res2->assertSee('Pesan di Website');
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
        $response->assertSee('Produk Terlaris');
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
        $res->assertSee('Galeri');
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

        // 2. Non-home pages have exactly ONE single back button (global sticky subheader bar, no duplicate floating pill or breadcrumb buttons)
        $pages = ['/shop', '/gallery', '/terms', '/pembayaran-pengiriman', '/pricelist', '/cart', '/track-order'];
        foreach ($pages as $p) {
            $res = $this->get($p);
            $res->assertStatus(200);
            $res->assertSee('class="global-page-back-bar"', false);
            $res->assertDontSee('class="floating-back-pill"', false);
            $res->assertDontSee('class="btn-crumb-back"', false);
            $res->assertSee('Kembali');
        }
    }

    public function test_admin_can_manage_gallery_photos(): void
    {
        // 1. Guest is redirected
        $guestRes = $this->get('/admin/gallery');
        $this->assertTrue($guestRes->isRedirect());

        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        // 2. Admin can access gallery management index
        $indexRes = $this->actingAs($admin)->get('/admin/gallery');
        $indexRes->assertStatus(200);
        $indexRes->assertSee('Kelola Galeri Foto');
        $indexRes->assertSee('Lapis Legit Original');
        $indexRes->assertSee('addGalleryModal');
        $indexRes->assertSee('editGalleryModal');

        // 3. Admin sidebar contains the link
        $dashRes = $this->actingAs($admin)->get('/admin/dashboard');
        $dashRes->assertStatus(200);
        $dashRes->assertSee(route('admin.gallery.index'));
        $dashRes->assertSee('Kelola Galeri Foto');

        // 4. Admin can store a new gallery photo
        $storeRes = $this->actingAs($admin)->post('/admin/gallery', [
            'title' => 'Lapis Legit Prunes Exclusive Golden Box',
            'card_name' => 'Lapis Prunes VIP',
            'tagline' => 'Special Golden Edition',
            'category' => 'lapis',
            'desc' => 'Dibuat dengan butter premium dan buah prunes segar import.',
            'display_order' => 1,
            'image_url' => '/images/cat-cakes.jpg',
        ]);
        $storeRes->assertRedirect(route('admin.gallery.index'));
        $storeRes->assertSessionHas('success');

        // 5. Newly added photo appears on public gallery
        $publicRes = $this->get('/gallery');
        $publicRes->assertStatus(200);
        $publicRes->assertSee('Lapis Prunes VIP');
        $publicRes->assertSee('Special Golden Edition');

        // Retrieve items to find the newly created item's ID
        $items = \App\Http\Controllers\Admin\AdminGalleryController::getItems();
        $createdItem = null;
        foreach ($items as $it) {
            if ($it['title'] === 'Lapis Legit Prunes Exclusive Golden Box') {
                $createdItem = $it;
                break;
            }
        }
        $this->assertNotNull($createdItem);
        $newId = $createdItem['id'];

        // 6. Admin can update the photo
        $updateRes = $this->actingAs($admin)->put('/admin/gallery/' . $newId, [
            'title' => 'Lapis Legit Prunes Ultra Luxury',
            'card_name' => 'Lapis Prunes Royal',
            'tagline' => 'Royal Heritage Edition',
            'category' => 'lapis',
            'desc' => 'Deskripsi diperbarui.',
            'display_order' => 1,
            'image_url' => '/images/cat-cakes.jpg',
        ]);
        $updateRes->assertRedirect(route('admin.gallery.index'));
        $updateRes->assertSessionHas('success');

        // Verify update in public gallery
        $publicRes2 = $this->get('/gallery');
        $publicRes2->assertSee('Lapis Prunes Royal');
        $publicRes2->assertSee('Royal Heritage Edition');

        // 7. Admin can toggle active status
        $toggleRes = $this->actingAs($admin)->post('/admin/gallery/' . $newId . '/toggle');
        $toggleRes->assertRedirect(route('admin.gallery.index'));

        // Once hidden, public gallery should not display it
        $publicRes3 = $this->get('/gallery');
        $publicRes3->assertDontSee('Lapis Prunes Royal');

        // 8. Admin can delete the photo
        $deleteRes = $this->actingAs($admin)->delete('/admin/gallery/' . $newId);
        $deleteRes->assertRedirect(route('admin.gallery.index'));
        $deleteRes->assertSessionHas('success');

        // Clean up test file if created in test
        if (\Illuminate\Support\Facades\File::exists(\App\Http\Controllers\Admin\AdminGalleryController::getGalleryPath())) {
            \Illuminate\Support\Facades\File::delete(\App\Http\Controllers\Admin\AdminGalleryController::getGalleryPath());
        }
    }

    public function test_admin_can_update_category_cover_image_and_name(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $category = Category::first();
        $this->assertNotNull($category);

        $updateRes = $this->actingAs($admin)->put('/admin/categories/' . $category->id, [
            'title' => 'Mooncake Royal Imperial',
            'slug' => 'mooncake-royal',
            'image_url' => '/images/cat-mooncake.jpg',
        ]);

        $updateRes->assertRedirect(route('admin.products.index'));
        $updateRes->assertSessionHas('success');

        $category->refresh();
        $this->assertEquals('Mooncake Royal Imperial', $category->title);
        $this->assertEquals('/images/cat-mooncake.jpg', $category->image);
        $this->assertEquals(asset('images/cat-mooncake.jpg'), $category->image_url);

        // Verify public shop page displays the updated category title and image
        $shopRes = $this->get('/shop');
        $shopRes->assertStatus(200);
        $shopRes->assertSee('Mooncake Royal Imperial');
        $shopRes->assertSee(asset('images/cat-mooncake.jpg'));
    }

    public function test_admin_can_manage_greeting_cards_and_checkout_reflects_changes(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        // 1. Admin views greeting cards index
        $indexRes = $this->actingAs($admin)->get('/admin/greeting-cards');
        $indexRes->assertStatus(200);
        $indexRes->assertSee('Kelola Template Kartu Ucapan');
        $indexRes->assertSee('Natal &amp; Tahun Baru', false);
        $indexRes->assertSee('Tahun Baru Imlek');

        // 2. Public checkout shows Natal and Imlek
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
        $checkoutRes = $this->get('/checkout');
        $checkoutRes->assertStatus(200);
        $checkoutRes->assertSee('Natal &amp; Tahun Baru', false);
        $checkoutRes->assertSee('Tahun Baru Imlek');

        // 3. Admin creates a new template
        $storeRes = $this->actingAs($admin)->post('/admin/greeting-cards', [
            'title' => '🌟 Syukuran Rumah Baru',
            'message' => 'Selamat menempati rumah baru yang penuh keberkahan dan kebahagiaan!',
            'display_order' => 1,
        ]);
        $storeRes->assertRedirect(route('admin.greeting_cards.index'));
        $storeRes->assertSessionHas('success');

        // Checkout now reflects new template
        $checkoutRes2 = $this->get('/checkout');
        $checkoutRes2->assertSee('Syukuran Rumah Baru');

        // 4. Admin updates the template
        $updateRes = $this->actingAs($admin)->put('/admin/greeting-cards/syukuran-rumah-baru', [
            'title' => '🏡 Grand Opening & Syukuran',
            'message' => 'Sukses selalu untuk babak baru ini!',
            'display_order' => 1,
        ]);
        $updateRes->assertRedirect(route('admin.greeting_cards.index'));

        // 5. Admin toggles active state
        $toggleRes = $this->actingAs($admin)->post('/admin/greeting-cards/syukuran-rumah-baru/toggle');
        $toggleRes->assertRedirect(route('admin.greeting_cards.index'));

        // Since it's deactivated, checkout should not show it
        $checkoutRes3 = $this->get('/checkout');
        $checkoutRes3->assertDontSee('Grand Opening &amp; Syukuran', false);

        // 6. Admin deletes template
        $deleteRes = $this->actingAs($admin)->delete('/admin/greeting-cards/syukuran-rumah-baru');
        $deleteRes->assertRedirect(route('admin.greeting_cards.index'));

        // 7. Admin resets to defaults
        $resetRes = $this->actingAs($admin)->post('/admin/greeting-cards/reset');
        $resetRes->assertRedirect(route('admin.greeting_cards.index'));
        $resetRes->assertSessionHas('success');
    }

    public function test_payment_page_has_no_whatsapp_admin_button_and_footer_is_clean(): void
    {
        $order = Order::create([
            'invoice_number' => 'INV-TEST-NOWA-001',
            'customer_name' => 'Maya Anggraini',
            'customer_phone' => '081234567890',
            'customer_email' => 'maya@example.com',
            'delivery_option' => 'delivery',
            'delivery_date' => now()->addDays(2)->format('Y-m-d'),
            'address' => 'Jl. Kebon Jeruk No 15, Jakarta',
            'subtotal' => 500000,
            'total_amount' => 520000,
            'payment_status' => 'unpaid',
            'payment_method' => 'Transfer Bank BCA',
            'order_status' => 'pending',
        ]);

        $response = $this->get('/order/INV-TEST-NOWA-001');
        $response->assertStatus(200);

        // Verify "Hubungi admin via wa" button is removed from payment page
        $response->assertDontSee('Hubungi Admin via WhatsApp');
        $response->assertDontSee('btn-wa-confirm');

        // Verify footer renders clean Butik & Menu Utama without redundant contact channels
        $response->assertSee('BUTIK & LOKASI', false);
        $response->assertSee('MENU UTAMA & LAYANAN', false);
        $response->assertDontSee('HUBUNGI KAMI');
        $response->assertDontSee('social-icons-row');
    }

    public function test_checkout_processing_saves_coordinates_and_renders_on_order_and_admin(): void
    {
        $user = User::factory()->create();
        $product = Product::first();
        $this->actingAs($user)->withSession([
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
            'customer_name' => 'Ahmad Praya Customer',
            'customer_phone' => '087812345678',
            'customer_email' => 'ahmad@praya.test',
            'delivery_option' => 'delivery',
            'courier_name' => 'Kurir Toko (Dedicated Bakery Delivery)',
            'delivery_date' => now()->addDays(2)->format('Y-m-d'),
            'address' => 'Kampung Pengendong, Kelurahan Praya',
            'city' => 'Kota Praya / Lombok Tengah',
            'postal_code' => '83511',
            'latitude' => -8.7064272,
            'longitude' => 116.2694240,
            'payment_method' => 'Transfer Bank BCA',
            'terms' => '1',
        ];

        $response = $this->post(route('checkout.process'), $postData);
        $response->assertRedirect();

        $order = Order::where('customer_name', 'Ahmad Praya Customer')->latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals(-8.7064272, (float)$order->latitude);
        $this->assertEquals(116.2694240, (float)$order->longitude);
        $this->assertStringContainsString('https://www.google.com/maps?q=-8.7064272,116.269424', $order->google_maps_url);
        $this->assertStringContainsString('[TITIK LOKASI MAPS / GPS]', $order->order_note);

        // Check customer order detail view
        $orderRes = $this->get('/order/' . $order->invoice_number);
        $orderRes->assertStatus(200);
        $orderRes->assertSee('Koordinat Titik Lokasi (Maps)');
        $orderRes->assertSee('-8.7064272');
        $orderRes->assertSee('116.269424');
        $orderRes->assertSee('Buka di Google Maps / Navigasi Kurir');

        // Check invoice PDF
        $pdfRes = $this->get('/order/' . $order->invoice_number . '/invoice-pdf');
        $pdfRes->assertStatus(200);
        $pdfRes->assertSee('Titik Maps (GPS)');
        $pdfRes->assertSee('-8.7064272');

        // Check admin orders list
        $admin = User::factory()->create([
            'is_admin' => 1,
            'email' => 'admin_maps@test.com',
            'password' => bcrypt('password123'),
        ]);
        $adminRes = $this->actingAs($admin)->get(route('admin.orders.index'));
        $adminRes->assertStatus(200);
        $adminRes->assertSee('Maps (-8.7064, 116.2694)');
    }

    public function test_geocode_api_search_and_reverse_endpoints(): void
    {
        // 1. Search with short/empty query
        $resEmpty = $this->getJson(route('geocode.search', ['q' => 'a']));
        $resEmpty->assertStatus(200);
        $resEmpty->assertJson(['status' => 'empty', 'results' => []]);

        // 2. Search with Http fake for predictable testing
        \Illuminate\Support\Facades\Http::fake([
            'https://nominatim.openstreetmap.org/search*' => \Illuminate\Support\Facades\Http::response([
                [
                    'place_id' => 12345,
                    'lat' => '-8.7064272',
                    'lon' => '116.2694240',
                    'name' => 'Praya',
                    'display_name' => 'Praya, Lombok Tengah, Nusa Tenggara Barat, 83511, Indonesia',
                    'address' => [
                        'city' => 'Praya',
                        'county' => 'Lombok Tengah',
                        'state' => 'Nusa Tenggara Barat',
                        'postcode' => '83511'
                    ]
                ]
            ], 200),
            'https://nominatim.openstreetmap.org/reverse*' => \Illuminate\Support\Facades\Http::response([
                'place_id' => 67890,
                'lat' => '-8.7064272',
                'lon' => '116.2694240',
                'display_name' => 'Kampung Pengendong, Praya, Lombok Tengah, Nusa Tenggara Barat, Indonesia',
                'address' => [
                    'road' => 'Kampung Pengendong',
                    'city' => 'Praya',
                    'county' => 'Lombok Tengah',
                    'state' => 'Nusa Tenggara Barat',
                    'postcode' => '83511'
                ]
            ], 200),
        ]);

        $searchRes = $this->getJson(route('geocode.search', ['q' => 'kota praya kampung pengendong']));
        $searchRes->assertStatus(200);
        $searchRes->assertJsonFragment(['name' => 'Praya']);
        $searchRes->assertJsonFragment(['lat' => -8.7064272]);

        $revRes = $this->getJson(route('geocode.reverse', ['lat' => -8.7064272, 'lon' => 116.2694240]));
        $revRes->assertStatus(200);
        $revRes->assertJsonFragment(['status' => 'success']);
        $revRes->assertJsonFragment(['city' => 'Praya']);
        $revRes->assertJsonFragment(['postal_code' => '83511']);
    }
}
