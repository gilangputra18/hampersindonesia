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
}
