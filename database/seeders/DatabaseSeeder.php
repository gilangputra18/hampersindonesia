<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@bakery.com'],
            [
                'name' => 'Bakery Administrator',
                'password' => Hash::make('password123'),
                'is_admin' => true,
            ]
        );

        // 2. Initial Categories & Products with Attributes
        $catalog = [
            'mooncake' => [
                'title' => 'Mooncake',
                'items' => [
                    ['name' => 'Box of 6', 'price' => 1288000, 'type' => 'Gift Box', 'flavor' => 'Lotus & Egg Yolk', 'size' => 'Box of 6', 'availability' => 'in_stock'],
                    ['name' => 'Box of 4', 'price' => 988000, 'type' => 'Gift Box', 'flavor' => 'Assorted Lotus', 'size' => 'Box of 4', 'availability' => 'in_stock'],
                ]
            ],
            'hampers' => [
                'title' => 'Hampers',
                'items' => [
                    ['name' => 'Rose', 'price' => 2950000, 'type' => 'Luxury Gift Set', 'flavor' => 'Assorted Premium', 'size' => 'Large Box', 'availability' => 'in_stock'],
                    ['name' => 'Lily', 'price' => 2450000, 'type' => 'Luxury Gift Set', 'flavor' => 'Assorted Premium', 'size' => 'Medium Box', 'availability' => 'in_stock'],
                    ['name' => 'Tulip', 'price' => 1750000, 'type' => 'Gift Box', 'flavor' => 'Cookies & Tea', 'size' => 'Medium Box', 'availability' => 'in_stock'],
                    ['name' => 'Calla', 'price' => 1200000, 'type' => 'Gift Box', 'flavor' => 'Butter Cookies', 'size' => 'Small Box', 'availability' => 'in_stock'],
                    ['name' => 'Iris', 'price' => 850000, 'type' => 'Gift Box', 'flavor' => 'Pastry Set', 'size' => 'Small Box', 'availability' => 'pre_order'],
                ]
            ],
            'cakes' => [
                'title' => 'Cakes',
                'items' => [
                    ['name' => 'Basque Burnt Cheesecake', 'price' => 375000, 'type' => 'Whole Cake', 'flavor' => 'Cheese', 'size' => 'Whole 18cm', 'availability' => 'in_stock'],
                    ['name' => 'Berry Tart', 'price' => 295000, 'type' => 'Tart', 'flavor' => 'Berry', 'size' => 'Whole 16cm', 'availability' => 'in_stock'],
                    ['name' => 'Black Forest', 'price' => 595000, 'type' => 'Whole Cake', 'flavor' => 'Chocolate Cherry', 'size' => 'Whole 20cm', 'availability' => 'in_stock'],
                    ['name' => 'Tiramisu', 'price' => 525000, 'type' => 'Whole Cake', 'flavor' => 'Coffee Mascarpone', 'size' => 'Whole 18cm', 'availability' => 'in_stock'],
                    ['name' => 'Chocolate Hazelnut', 'price' => 450000, 'type' => 'Whole Cake', 'flavor' => 'Chocolate', 'size' => 'Whole 18cm', 'availability' => 'in_stock'],
                ]
            ],
            'breads' => [
                'title' => 'Breads',
                'items' => [
                    ['name' => 'Sourdough Loaf', 'price' => 65000, 'type' => 'Artisan Bread', 'flavor' => 'Original Sourdough', 'size' => 'Loaf (500g)', 'availability' => 'in_stock'],
                    ['name' => 'Milk Bun', 'price' => 25000, 'type' => 'Soft Bread', 'flavor' => 'Hokkaido Milk', 'size' => 'Pack of 6', 'availability' => 'in_stock'],
                    ['name' => 'Croissant', 'price' => 32000, 'type' => 'Pastry', 'flavor' => 'French Butter', 'size' => 'Single Piece', 'availability' => 'in_stock'],
                ]
            ],
            'cookies' => [
                'title' => 'Cookies',
                'items' => [
                    ['name' => 'Butter Cookies Jar', 'price' => 145000, 'type' => 'Dry Cookies', 'flavor' => 'French Butter', 'size' => 'Jar 350g', 'availability' => 'in_stock'],
                    ['name' => 'Nastar Pineapple', 'price' => 295000, 'type' => 'Traditional Cookie', 'flavor' => 'Pineapple Jam', 'size' => 'Jar 450g', 'availability' => 'in_stock'],
                    ['name' => 'Kaastengel', 'price' => 275000, 'type' => 'Savory Cookie', 'flavor' => 'Edam Cheese', 'size' => 'Jar 400g', 'availability' => 'in_stock'],
                ]
            ],
            'light-bites' => [
                'title' => 'Light Bites',
                'items' => [
                    ['name' => 'Arem-Arem', 'price' => 45000, 'type' => 'Rice Snack', 'flavor' => 'Spicy Chicken', 'size' => 'Single Piece', 'availability' => 'in_stock'],
                    ['name' => 'Bacang', 'price' => 55000, 'type' => 'Savory Rice', 'flavor' => 'Braised Beef', 'size' => 'Single Piece', 'availability' => 'in_stock'],
                    ['name' => 'Mini Bacang', 'price' => 30000, 'type' => 'Savory Rice', 'flavor' => 'Braised Beef', 'size' => 'Single Piece', 'availability' => 'in_stock'],
                    ['name' => 'Chicken Lemper', 'price' => 25000, 'type' => 'Rice Snack', 'flavor' => 'Shredded Chicken', 'size' => 'Single Piece', 'availability' => 'in_stock'],
                    ['name' => 'Onigiri', 'price' => 40000, 'type' => 'Rice Wrap', 'flavor' => 'Tuna Mayo', 'size' => 'Single Piece', 'availability' => 'in_stock'],
                    ['name' => 'Focaccia Chicken Pesto Sandwich', 'price' => 80000, 'type' => 'Sandwich', 'flavor' => 'Chicken Pesto', 'size' => 'Single Serving', 'availability' => 'in_stock'],
                    ['name' => 'Mini Burger', 'price' => 80000, 'type' => 'Sandwich', 'flavor' => 'Beef Cheese', 'size' => 'Set of 3', 'availability' => 'in_stock'],
                    ['name' => 'Tortilla', 'price' => 51000, 'type' => 'Wrap', 'flavor' => 'Chicken Veggie', 'size' => 'Single Roll', 'availability' => 'in_stock'],
                    ['name' => 'Bitterballen', 'price' => 45000, 'type' => 'Savory Bite', 'flavor' => 'Beef Cheese', 'size' => 'Portion (6 pcs)', 'availability' => 'in_stock'],
                    ['name' => 'Rice Dumpling', 'price' => 55000, 'type' => 'Savory Rice', 'flavor' => 'Chicken Mushroom', 'size' => 'Single Piece', 'availability' => 'in_stock'],
                ]
            ]
        ];

        $catOrder = 1;
        foreach ($catalog as $slug => $data) {
            $category = Category::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $data['title'],
                    'display_order' => $catOrder++,
                ]
            );

            foreach ($data['items'] as $itemIndex => $item) {
                $name = $item['name'];
                $price = $item['price'];
                $productSlug = Str::slug($name);

                $isBestSeller = ($slug === 'cakes' && $itemIndex < 5);
                $isTreat = ($slug === 'light-bites');

                Product::updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'name' => $name,
                    ],
                    [
                        'slug' => $productSlug,
                        'price' => $price,
                        'image' => $productSlug . '.jpg',
                        'type' => $item['type'] ?? 'Bakery Item',
                        'flavor' => $item['flavor'] ?? 'Original',
                        'size' => $item['size'] ?? 'Standard',
                        'availability' => $item['availability'] ?? 'in_stock',
                        'is_best_seller' => $isBestSeller,
                        'is_treat' => $isTreat,
                        'display_order' => $itemIndex + 1,
                    ]
                );
            }
        }

        // 3. Seed Realistic Sales Orders for Analytics Dashboard if few exist
        if (\App\Models\Order::count() < 10) {
            $allProducts = Product::all();
            $customers = [
                ['name' => 'Clara Suki', 'email' => 'clara@example.com', 'phone' => '081298765432'],
                ['name' => 'Budi Santoso', 'email' => 'budi@example.com', 'phone' => '081122334455'],
                ['name' => 'Siti Nurhaliza', 'email' => 'siti@example.com', 'phone' => '081377889900'],
                ['name' => 'Dewi Persik', 'email' => 'dewi@example.com', 'phone' => '081566778899'],
                ['name' => 'Raffi Ahmad', 'email' => 'raffi@example.com', 'phone' => '081711223344'],
                ['name' => 'Nagita Slavina', 'email' => 'nagita@example.com', 'phone' => '081822334455'],
                ['name' => 'Reza Rahadian', 'email' => 'reza@example.com', 'phone' => '081933445566'],
            ];

            $statuses = ['paid', 'paid', 'paid', 'paid', 'pending', 'paid'];
            $deliveryOptions = ['delivery', 'pickup'];
            $couriers = ['Kurir Toko (Dedicated Bakery Delivery)', 'JNE Express (Reguler/YES)', 'J&T Express', 'GoSend / GrabExpress (Instant)'];
            $paymentMethods = ['Transfer Bank BCA', 'Transfer Bank Mandiri', 'QRIS Instant Payment', 'COD (Cash on Delivery)'];

            for ($i = 30; $i >= 0; $i--) {
                $ordersCount = rand(1, 3);
                for ($j = 0; $j < $ordersCount; $j++) {
                    $cust = $customers[array_rand($customers)];
                    $date = \Carbon\Carbon::now()->subDays($i)->subHours(rand(1, 10));
                    $invoiceNum = 'INV-' . $date->format('Ymd') . '-' . strtoupper(Str::random(4));
                    $paymentStatus = $statuses[array_rand($statuses)];
                    $orderStatus = ($paymentStatus === 'paid') ? 'completed' : 'processing';
                    $delOption = $deliveryOptions[array_rand($deliveryOptions)];
                    $courier = ($delOption === 'pickup') ? 'Ambil Sendiri di Boutique Store' : $couriers[array_rand($couriers)];
                    $payMethod = $paymentMethods[array_rand($paymentMethods)];
                    $resiPrefix = ($delOption === 'pickup') ? 'PICK' : 'KUR';
                    $trackingNum = $resiPrefix . '-' . $date->format('Ymd') . '-' . rand(1000, 9999);

                    $order = \App\Models\Order::create([
                        'invoice_number' => $invoiceNum,
                        'customer_name' => $cust['name'],
                        'customer_email' => $cust['email'],
                        'customer_phone' => $cust['phone'],
                        'delivery_option' => $delOption,
                        'courier_name' => $courier,
                        'delivery_date' => $date->format('Y-m-d'),
                        'delivery_fee' => ($delOption === 'pickup') ? 0 : 30000,
                        'address' => ($delOption === 'pickup') ? 'Store Pick up (Jl. Cikajang V No. 12)' : 'Jl. Senopati No. ' . rand(10, 99) . ', Jakarta Selatan',
                        'order_note' => 'Mohon sertakan kartu ucapan selamat.',
                        'subtotal' => 0,
                        'total_amount' => 0,
                        'payment_method' => $payMethod,
                        'tracking_number' => $trackingNum,
                        'payment_status' => $paymentStatus,
                        'order_status' => $orderStatus,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);

                    $subtotal = 0;
                    $itemsToTake = rand(1, 3);
                    $randomProducts = $allProducts->random($itemsToTake);

                    foreach ($randomProducts as $prod) {
                        $qty = rand(1, 2);
                        $lineTotal = $prod->price * $qty;
                        $subtotal += $lineTotal;

                        \App\Models\OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $prod->id,
                            'product_name' => $prod->name,
                            'price' => $prod->price,
                            'quantity' => $qty,
                            'subtotal' => $lineTotal,
                            'created_at' => $date,
                            'updated_at' => $date,
                        ]);
                    }

                    $totalAmount = $subtotal + 30000;
                    $order->update([
                        'subtotal' => $subtotal,
                        'total_amount' => $totalAmount,
                    ]);
                }
            }
        }

        // 4. Seed Initial Contact & Concierge Messages
        if (\App\Models\ContactMessage::count() === 0) {
            $sampleMessages = [
                [
                    'name' => 'Adeline Wijaya',
                    'email' => 'adeline.w@luxurycorp.id',
                    'subject' => 'Reservasi Fine Dining Private Room (12 Pax)',
                    'message' => 'Halo Pusat Hampers Indonesia Concierge, saya ingin melakukan pemesanan Private Dining Room untuk acara ulang tahun perusahaan kami pada tanggal 15 bulan depan untuk 12 tamu. Mohon info ketersediaan menu paket set masakan Prancis.',
                    'is_read' => false,
                    'created_at' => now()->subHours(2),
                ],
                [
                    'name' => 'Bambang Soeprapto',
                    'email' => 'bambang.soeprapto@gmail.com',
                    'subject' => 'Pemesanan Custom Wedding Cake 3 Tingkat',
                    'message' => 'Selamat siang, apakah bisa konsultasi mengenai desain custom wedding cake bertema Gold Emerald untuk resepsi pernikahan bulan November mendatang? Terima kasih.',
                    'is_read' => false,
                    'created_at' => now()->subHours(5),
                ],
                [
                    'name' => 'Clarissa Putri',
                    'email' => 'clarissa.putri@gmail.com',
                    'subject' => 'Penawaran Hampers Mewah Corporate Gift (50 Box)',
                    'message' => 'Halo tim Pusat Hampers Indonesia, kami dari PT Permata Harapan bermaksud memesan 50 set Hampers Rose & Lily untuk bingkisan klien VIP akhir tahun. Mohon dikirimkan katalog PDF dan skema diskon korporat.',
                    'is_read' => true,
                    'replied_at' => now()->subHours(1),
                    'created_at' => now()->subDays(1),
                ],
                [
                    'name' => 'Dian Sastrowardoyo',
                    'email' => 'dian.sastro@example.com',
                    'subject' => 'Pendaftaran Klub Privilese VIP',
                    'message' => 'Pendaftaran Newsletter / Privilege VIP Club Pusat Hampers Indonesia.',
                    'is_read' => true,
                    'created_at' => now()->subDays(2),
                ],
            ];

            foreach ($sampleMessages as $msgData) {
                \App\Models\ContactMessage::create($msgData);
            }
        }

        // 5. Seed Default Reservation Items if empty
        if (\App\Models\ReservationItem::count() === 0) {
            \App\Models\ReservationItem::create([
                'slug' => 'dine-in',
                'title' => 'RESERVASI MEJA & FINE DINING',
                'subtitle' => 'Pusat Hampers Indonesia Boutique Restaurant',
                'description' => '<p>Nikmati kehangatan dan kelezatan hidangan artisanal di butik utama kami. Kami menyediakan ruang privat yang elegan untuk makan malam keluarga, perayaan ulang tahun, atau pertemuan bisnis VIP.</p><p>Setiap santapan disajikan dengan bahan-bahan gourmet terbaik yang disiapkan oleh koki ternama kami.</p>',
                'image' => 'reservation-dinein.jpg',
                'whatsapp_number' => '62811152282',
                'whatsapp_text' => 'Halo Pusat Hampers Indonesia, saya ingin reservasi meja VIP Fine Dining.',
                'display_order' => 1,
                'is_active' => true,
            ]);

            \App\Models\ReservationItem::create([
                'slug' => 'catering',
                'title' => 'KATERING & ACARA PRIVAT',
                'subtitle' => 'Layanan Concierge & Katering Eksklusif',
                'description' => '<p>Hadirkan kelezatan kue, pastri, dan hampers mewah Pusat Hampers Indonesia di setiap acara istimewa Anda. Kami melayani katering pernikahan, gathering korporat, dan acara pesta privat dengan pelayanan bintang lima.</p><p>Tim concierge kami siap membantu merancang menu khusus sesuai selera dan kebutuhan tamu Anda.</p>',
                'image' => 'reservation-catering.jpg',
                'whatsapp_number' => '62811152282',
                'whatsapp_text' => 'Halo Pusat Hampers Indonesia, saya ingin berkonsultasi mengenai Katering Acara Privat.',
                'display_order' => 2,
                'is_active' => true,
            ]);
        }
    }
}
