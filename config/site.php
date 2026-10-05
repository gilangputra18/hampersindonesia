<?php

// Ganti semua isi di sini dengan brand, kontak, dan produk milikmu.
return [
    'brand'      => env('BRAND_NAME', 'PUSAT HAMPERS INDONESIA'),
    'since'      => '2026',
    'hero_video' => env('HERO_VIDEO_URL', '/videos/hero.mp4'),
    'tagline'    => 'Buat perayaan Anda tak terlupakan bersama kami.',
    'address'    => 'Jl. Contoh No. 1, Jakarta Selatan',
    'phone'      => '(021) 000 00 00',
    'wa'         => '+62 800-0000-000',
    'email'      => 'hello@example.com',
    'hours'      => 'Senin - Minggu, 07.00 - 22.00 WIB',

    // Harga dalam Rupiah. Gambar produk: public/images/{slug-nama}.jpg
    'categories' => [
        'mooncake' => ['title' => 'Kue Bulan', 'items' => [
            ['Box of 6', 1288000], ['Box of 4', 988000],
        ]],
        'hampers' => ['title' => 'Parsel & Hampers', 'items' => [
            ['Rose', 2950000], ['Lily', 2450000], ['Tulip', 1750000], ['Calla', 1200000], ['Iris', 850000],
        ]],
        'cakes' => ['title' => 'Kue & Tart', 'items' => [
            ['Basque Burnt Cheesecake', 375000], ['Berry Tart', 295000], ['Black Forest', 595000],
            ['Tiramisu', 525000], ['Chocolate Hazelnut', 450000],
        ]],
        'breads' => ['title' => 'Roti Artisanal', 'items' => [
            ['Sourdough Loaf', 65000], ['Milk Bun', 25000], ['Croissant', 32000],
        ]],
        'cookies' => ['title' => 'Kue Kering', 'items' => [
            ['Butter Cookies Jar', 145000], ['Nastar Pineapple', 295000], ['Kaastengel', 275000],
        ]],
        'light-bites' => ['title' => 'Hidangan Ringan', 'items' => [
            ['Chicken Lemper', 25000], ['Mini Bacang', 30000], ['Onigiri', 40000], ['Rice Dumpling', 55000], ['Arem-Arem', 45000],
        ]],
    ],
];
