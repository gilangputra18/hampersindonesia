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
}
