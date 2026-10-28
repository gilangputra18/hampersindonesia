<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function home()
    {
        $categories = Category::withCount('products')->orderBy('display_order')->get();

        $treats = Product::with(['category', 'reviews'])->where('is_treat', true)->take(8)->get();
        if ($treats->count() < 4) {
            $treatCategory = Category::where('slug', 'light-bites')->first();
            if ($treatCategory) {
                $treats = $treatCategory->products()->with(['category', 'reviews'])->take(8)->get();
            }
        }

        // Fetch best sellers first, and expand with diverse luxury items to give a rich variety of at least 8 items
        $best = Product::with(['category', 'reviews'])->where('is_best_seller', true)->get();
        if ($best->count() < 8) {
            $existingIds = $best->pluck('id')->toArray();
            $supplement = Product::with(['category', 'reviews'])
                ->whereNotIn('id', $existingIds)
                ->whereIn('availability', ['in_stock', 'pre_order'])
                ->orderBy('display_order', 'asc')
                ->take(8 - $best->count())
                ->get();
            $best = $best->concat($supplement);
        }

        if ($best->isEmpty()) {
            $best = Product::with(['category', 'reviews'])->latest()->take(8)->get();
        }

        // Featured showcase rows by category matching reference
        $bigBites = Product::with(['category', 'reviews'])
            ->whereHas('category', function($q) { $q->whereIn('slug', ['mooncake', 'cookies', 'light-bites']); })
            ->take(8)->get();
        if ($bigBites->isEmpty()) {
            $bigBites = $best->take(8);
        }

        $loyangMini = Product::with(['category', 'reviews'])
            ->whereHas('category', function($q) { $q->whereIn('slug', ['cakes', 'breads']); })
            ->take(8)->get();
        if ($loyangMini->isEmpty()) {
            $loyangMini = $best->skip(2)->take(8);
        }

        $other = Product::with(['category', 'reviews'])
            ->whereHas('category', function($q) { $q->whereIn('slug', ['hampers', 'cookies', 'light-bites']); })
            ->skip(2)->take(8)->get();
        if ($other->isEmpty()) {
            $other = $best->take(8);
        }

        $cakes = $loyangMini;
        $hampers = $other;

        try {
            $dbReviews = \App\Models\ProductReview::with('product')
                ->where('is_approved', true)
                ->orderByDesc('is_featured')
                ->latest()
                ->take(12)
                ->get();
        } catch (\Throwable $e) {
            $dbReviews = collect();
        }

        $reviewsList = [];
        $palette = ['#d97706', '#b45309', '#0284c7', '#059669', '#7c3aed', '#db2777'];
        $pIdx = 0;

        foreach ($dbReviews as $dRev) {
            $words = explode(' ', trim($dRev->reviewer_name));
            $inits = strtoupper(substr($words[0] ?? '', 0, 1) . substr($words[1] ?? '', 0, 1));
            if (empty($inits)) $inits = 'VIP';
            $reviewsList[] = [
                'name' => $dRev->reviewer_name,
                'rating' => $dRev->rating ?? 5,
                'body' => $dRev->body,
                'initials' => $inits,
                'bg_color' => $palette[$pIdx % count($palette)],
            ];
            $pIdx++;
        }

        $curatedReviews = [
            [
                'name' => 'Sefsaya',
                'rating' => 5,
                'body' => 'HAMPERS di sini aman dan terpercaya. Pelayanannya ramah, proses pemesanan mudah, dan sangat membantu. Recommended banget! 💕',
                'initials' => 'SE',
                'bg_color' => '#d97706',
            ],
            [
                'name' => 'Adeline Wijaya',
                'rating' => 5,
                'body' => 'Bahan-bahannya terasa sangat berkualitas tinggi dan butter Wijsman impornya begitu harum. Packaging hampers eksklusif dan kemasan sangat rapi untuk relasi bisnis VIP kami.',
                'initials' => 'AW',
                'bg_color' => '#b45309',
            ],
            [
                'name' => 'Clarissa Putri',
                'rating' => 5,
                'body' => 'Pengiriman sangat aman dan cepat sampai tujuan. Isi hampers lengkap sesuai deskripsi dan pita satin mewahnya bikin penerima sangat terkesan. Pasti langganan di sini!',
                'initials' => 'CP',
                'bg_color' => '#0284c7',
            ],
            [
                'name' => 'Hendra Tanuwidjaja',
                'rating' => 5,
                'body' => 'Pesan 25 paket hampers korporat untuk klien penting perusahaan, semuanya tiba dalam kondisi sempurna dan sangat mewah. CS ramah dan faktur pengiriman langsung rapi.',
                'initials' => 'HT',
                'bg_color' => '#059669',
            ],
            [
                'name' => 'Natasha Lauren',
                'rating' => 5,
                'body' => 'Nastar wisman dan kaastengel-nya luar biasa renyah dan gurih! Kualitas kue kering premium bintang lima. Box beludru merahnya sangat berkelas untuk hadiah Idul Fitri & Natal.',
                'initials' => 'NL',
                'bg_color' => '#7c3aed',
            ],
            [
                'name' => 'Dimas Prasetyo',
                'rating' => 5,
                'body' => 'Sudah langganan kirim hampers di sini setiap momen perayaan. Kualitas rasa konsisten, kue selalu fresh from the oven, dan pelayanannya tidak pernah mengecewakan.',
                'initials' => 'DP',
                'bg_color' => '#db2777',
            ],
        ];

        foreach ($curatedReviews as $cRev) {
            $exists = false;
            foreach ($reviewsList as $existing) {
                if (strtolower($existing['name']) === strtolower($cRev['name'])) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                $reviewsList[] = $cRev;
            }
        }

        $reviews = $reviewsList;

        $heroSlides = \App\Http\Controllers\Admin\AdminHeroBannerController::getSlides();
        $heroSettings = \App\Http\Controllers\Admin\AdminHeroBannerController::getSettings();
        $activeSlides = array_values(array_filter($heroSlides, function($s) {
            return ($s['is_active'] ?? true);
        }));
        if (empty($activeSlides)) {
            $activeSlides = $heroSlides;
        }

        return view('home', [
            'heroSlides'   => $activeSlides,
            'heroSettings' => $heroSettings,
            'categories'   => $categories,
            'gifts'        => ['hampers', 'cakes', 'cookies', 'breads', 'light-bites', 'mooncake'],
            'treats'       => $treats,
            'best'         => $best,
            'bigBites'     => $bigBites,
            'loyangMini'   => $loyangMini,
            'cakes'        => $cakes,
            'hampers'      => $hampers,
            'other'        => $other,
            'reviews'      => $reviews,
        ]);
    }

    public function product(string $slug)
    {
        $product = Product::with(['category', 'reviews' => function($q) {
            $q->where('is_approved', true)->latest();
        }])->where('slug', $slug)->first();

        if (!$product && is_numeric($slug)) {
            $product = Product::with(['category', 'reviews' => function($q) {
                $q->where('is_approved', true)->latest();
            }])->find($slug);
        }

        if (!$product) {
            // Try by fuzzy match or fallback to first product
            $product = Product::with(['category', 'reviews'])->first();
            if (!$product) {
                abort(404, 'Produk tidak ditemukan');
            }
        }

        // Get 4 related products for "You also like" row (matching reference)
        $related = Product::with(['category', 'reviews'])
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->take(4)
            ->get();

        if ($related->count() < 4) {
            $more = Product::with(['category', 'reviews'])
                ->where('id', '!=', $product->id)
                ->whereNotIn('id', $related->pluck('id'))
                ->take(4 - $related->count())
                ->get();
            $related = $related->concat($more);
        }

        // Only use the product's actual uploaded images (do not inject extra fake stock photos)
        $gallery = $product->gallery_urls;

        return view('product-detail', compact('product', 'related', 'gallery'));
    }

    public function category(Request $request, ?string $slug = null)
    {
        $categories = Category::withCount('products')->orderBy('display_order')->get();
        $isAllProducts = empty($slug) || $slug === 'all';

        if ($isAllProducts) {
            $cat = null;
            $baseQuery = Product::with(['category', 'reviews']);
        } else {
            $cat = Category::where('slug', $slug)->first() ?? Category::first();
            if ($cat) {
                $baseQuery = Product::with(['category', 'reviews'])->where('category_id', $cat->id);
                // Auto-heal: If category currently has no products, populate default products
                if ($baseQuery->count() === 0) {
                    $this->ensureCategoryHasProducts($cat);
                    $baseQuery = Product::with(['category', 'reviews'])->where('category_id', $cat->id);
                }
            } else {
                $baseQuery = Product::with(['category', 'reviews']);
            }
        }

        if ($search = $request->get('q')) {
            $baseQuery->where('name', 'like', "%{$search}%");
        }

        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $baseQuery->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $baseQuery->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $baseQuery->orderBy('name', 'asc');
                break;
            case 'latest':
            default:
                $baseQuery->latest();
                break;
        }

        $filteredProducts = $baseQuery->get();

        // AJAX response for live filter updates
        if ($request->ajax() || $request->wantsJson()) {
            $gridHtml = view('partials.product_grid', ['products' => $filteredProducts])->render();
            return response()->json([
                'html' => $gridHtml,
                'total' => $filteredProducts->count(),
            ]);
        }

        return view('category', [
            'slug'          => $slug ?: 'all',
            'cat'           => $cat,
            'isAllProducts' => $isAllProducts,
            'categories'    => $categories,
            'products'      => $filteredProducts,
            'sort'          => $sort,
            'types'         => [],
            'flavors'       => [],
            'sizes'         => [],
            'availabilities'=> [],
            'minPriceLimit' => 0,
            'maxPriceLimit' => 10000000,
            'filters'       => ['sort' => $sort],
        ]);
    }

    public function reservations()
    {
        $reservations = \App\Models\ReservationItem::where('is_active', true)->orderBy('display_order')->get();

        if ($reservations->isEmpty()) {
            try {
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

                $reservations = \App\Models\ReservationItem::where('is_active', true)->orderBy('display_order')->get();
            } catch (\Throwable $e) {
                // Ignore if table issues arise
            }
        }

        return view('reservations', compact('reservations'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function sendContact(Request $r)
    {
        $r->validate([
            'name' => 'nullable|max:80',
            'email' => 'required|email',
            'message' => 'nullable|max:2000',
        ]);

        $name = $r->input('name') ?: 'Pelanggan VIP';
        $message = $r->input('message') ?: 'Pendaftaran Newsletter / Privilege VIP Club Pusat Hampers Indonesia.';
        $subject = $r->input('subject') ?: 'Pesan Kontak & Concierge';

        \App\Models\ContactMessage::create([
            'name' => $name,
            'email' => $r->input('email'),
            'subject' => $subject,
            'message' => $message,
            'is_read' => false,
        ]);

        return back()->with('ok', 'Terima kasih! Pesan Anda telah kami terima dan tim Concierge kami akan segera menghubungi Anda.');
    }

    public function track(Request $r)
    {
        $invoice = trim($r->query('invoice'));
        $order = null;
        $searched = false;

        if ($invoice) {
            $searched = true;
            $order = \App\Models\Order::with('items.product')
                ->where('invoice_number', $invoice)
                ->orWhere('tracking_number', $invoice)
                ->first();
        }

        return view('track', compact('invoice', 'order', 'searched'));
    }

    public function menuPdf($slug = null)
    {
        if ($slug) {
            $resItem = \App\Models\ReservationItem::where('slug', $slug)->first();
            if ($resItem && $resItem->pdf_path && \Illuminate\Support\Facades\File::exists(public_path($resItem->pdf_path))) {
                return response()->file(public_path($resItem->pdf_path), [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="Pusat_Hampers_Indonesia_' . $resItem->slug . '_Menu.pdf"'
                ]);
            }
        }

        $customPdfPath = public_path('uploads/menu_catalog.pdf');
        if (\Illuminate\Support\Facades\File::exists($customPdfPath)) {
            return response()->file($customPdfPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Pusat_Hampers_Indonesia_Menu_Catalog.pdf"'
            ]);
        }

        $categories = Category::with('products')->get();
        return view('menu_pdf', compact('categories'));
    }

    protected function ensureCategoryHasProducts(Category $cat): void
    {
        $catalog = [
            'breads' => [
                ['name' => 'Croissant', 'price' => 32000, 'type' => 'Pastry', 'flavor' => 'French Butter', 'size' => 'Single Piece', 'image' => 'croissant.jpg', 'availability' => 'in_stock'],
                ['name' => 'Sourdough Loaf', 'price' => 65000, 'type' => 'Artisan Bread', 'flavor' => 'Original Sourdough', 'size' => 'Loaf (500g)', 'image' => 'sourdough-loaf.jpg', 'availability' => 'in_stock'],
                ['name' => 'Milk Bun', 'price' => 25000, 'type' => 'Soft Bread', 'flavor' => 'Hokkaido Milk', 'size' => 'Pack of 6', 'image' => 'milk-bun.jpg', 'availability' => 'in_stock'],
                ['name' => 'Focaccia Chicken Pesto Sandwich', 'price' => 80000, 'type' => 'Sandwich', 'flavor' => 'Chicken Pesto', 'size' => 'Single Serving', 'image' => 'focaccia-chicken-pesto-sandwich.jpg', 'availability' => 'in_stock'],
            ],
            'mooncake' => [
                ['name' => 'Moon Hampers', 'price' => 800000, 'type' => 'Luxury Lantern Gift Set', 'flavor' => 'Assorted Mooncake & Tea', 'size' => 'Imperial Box', 'image' => 'cat-mooncake.jpg', 'availability' => 'in_stock', 'is_best_seller' => true],
                ['name' => 'Hampers Kue Bulan', 'price' => 1300000, 'type' => 'Royal Festival Box', 'flavor' => 'Premium Lotus Mooncake', 'size' => 'Exclusive Hardbox', 'image' => 'box-of-6.jpg', 'availability' => 'in_stock', 'is_best_seller' => true],
                ['name' => 'Hampers Kue Bulan Yue tipe B', 'price' => 1300000, 'type' => 'Royal Festival Box', 'flavor' => 'Double Yolk Assortment', 'size' => 'Yue Hardbox B', 'image' => 'box-of-4.jpg', 'availability' => 'in_stock', 'is_best_seller' => true],
                ['name' => 'Box of 6', 'price' => 1288000, 'type' => 'Gift Box', 'flavor' => 'Lotus & Egg Yolk', 'size' => 'Box of 6', 'image' => 'box-of-6.jpg', 'availability' => 'in_stock', 'is_best_seller' => true],
                ['name' => 'Box of 4', 'price' => 988000, 'type' => 'Gift Box', 'flavor' => 'Assorted Lotus', 'size' => 'Box of 4', 'image' => 'box-of-4.jpg', 'availability' => 'in_stock', 'is_best_seller' => false],
            ],
            'hampers' => [
                ['name' => 'Rose', 'price' => 2950000, 'type' => 'Luxury Gift Set', 'flavor' => 'Assorted Premium', 'size' => 'Large Box', 'image' => 'rose.jpg', 'availability' => 'in_stock', 'is_best_seller' => true],
                ['name' => 'Lily', 'price' => 2450000, 'type' => 'Luxury Gift Set', 'flavor' => 'Assorted Premium', 'size' => 'Medium Box', 'image' => 'lily.jpg', 'availability' => 'in_stock', 'is_best_seller' => true],
                ['name' => 'Tulip', 'price' => 1750000, 'type' => 'Gift Box', 'flavor' => 'Cookies & Tea', 'size' => 'Medium Box', 'image' => 'tulip.jpg', 'availability' => 'in_stock', 'is_best_seller' => false],
                ['name' => 'Calla', 'price' => 1200000, 'type' => 'Gift Box', 'flavor' => 'Butter Cookies', 'size' => 'Small Box', 'image' => 'calla.jpg', 'availability' => 'in_stock', 'is_best_seller' => false],
                ['name' => 'Iris', 'price' => 850000, 'type' => 'Gift Box', 'flavor' => 'Pastry Set', 'size' => 'Small Box', 'image' => 'iris.jpg', 'availability' => 'pre_order', 'is_best_seller' => false],
            ],
            'cakes' => [
                ['name' => 'Basque Burnt Cheesecake', 'price' => 375000, 'type' => 'Whole Cake', 'flavor' => 'Cheese', 'size' => 'Whole 18cm', 'image' => 'basque-burnt-cheesecake.jpg', 'availability' => 'in_stock', 'is_best_seller' => true],
                ['name' => 'Berry Tart', 'price' => 295000, 'type' => 'Tart', 'flavor' => 'Berry', 'size' => 'Whole 16cm', 'image' => 'berry-tart.jpg', 'availability' => 'in_stock', 'is_best_seller' => true],
                ['name' => 'Black Forest', 'price' => 595000, 'type' => 'Whole Cake', 'flavor' => 'Chocolate Cherry', 'size' => 'Whole 20cm', 'image' => 'black-forest.jpg', 'availability' => 'in_stock', 'is_best_seller' => true],
                ['name' => 'Tiramisu', 'price' => 525000, 'type' => 'Whole Cake', 'flavor' => 'Coffee Mascarpone', 'size' => 'Whole 18cm', 'image' => 'tiramisu.jpg', 'availability' => 'in_stock', 'is_best_seller' => true],
                ['name' => 'Chocolate Hazelnut', 'price' => 450000, 'type' => 'Whole Cake', 'flavor' => 'Chocolate', 'size' => 'Whole 18cm', 'image' => 'chocolate-hazelnut.jpg', 'availability' => 'in_stock', 'is_best_seller' => false],
            ],
            'cookies' => [
                ['name' => 'Butter Cookies Jar', 'price' => 145000, 'type' => 'Dry Cookies', 'flavor' => 'French Butter', 'size' => 'Jar 350g', 'image' => 'butter-cookies-jar.jpg', 'availability' => 'in_stock'],
                ['name' => 'Nastar Pineapple', 'price' => 295000, 'type' => 'Traditional Cookie', 'flavor' => 'Pineapple Jam', 'size' => 'Jar 450g', 'image' => 'nastar-pineapple.jpg', 'availability' => 'in_stock'],
                ['name' => 'Kaastengel', 'price' => 275000, 'type' => 'Savory Cookie', 'flavor' => 'Edam Cheese', 'size' => 'Jar 400g', 'image' => 'kaastengel.jpg', 'availability' => 'in_stock'],
            ],
            'light-bites' => [
                ['name' => 'Arem-Arem', 'price' => 45000, 'type' => 'Rice Snack', 'flavor' => 'Spicy Chicken', 'size' => 'Single Piece', 'image' => 'arem-arem.jpg', 'availability' => 'in_stock'],
                ['name' => 'Bacang', 'price' => 55000, 'type' => 'Savory Rice', 'flavor' => 'Braised Beef', 'size' => 'Single Piece', 'image' => 'bacang.jpg', 'availability' => 'in_stock'],
                ['name' => 'Mini Bacang', 'price' => 30000, 'type' => 'Savory Rice', 'flavor' => 'Braised Beef', 'size' => 'Single Piece', 'image' => 'mini-bacang.jpg', 'availability' => 'in_stock'],
                ['name' => 'Chicken Lemper', 'price' => 25000, 'type' => 'Rice Snack', 'flavor' => 'Shredded Chicken', 'size' => 'Single Piece', 'image' => 'chicken-lemper.jpg', 'availability' => 'in_stock'],
                ['name' => 'Onigiri', 'price' => 40000, 'type' => 'Rice Wrap', 'flavor' => 'Tuna Mayo', 'size' => 'Single Piece', 'image' => 'onigiri.jpg', 'availability' => 'in_stock'],
                ['name' => 'Mini Burger', 'price' => 80000, 'type' => 'Sandwich', 'flavor' => 'Beef Cheese', 'size' => 'Set of 3', 'image' => 'mini-burger.jpg', 'availability' => 'in_stock'],
                ['name' => 'Tortilla', 'price' => 51000, 'type' => 'Wrap', 'flavor' => 'Chicken Veggie', 'size' => 'Single Roll', 'image' => 'tortilla.jpg', 'availability' => 'in_stock'],
                ['name' => 'Bitterballen', 'price' => 45000, 'type' => 'Savory Bite', 'flavor' => 'Beef Cheese', 'size' => 'Portion (6 pcs)', 'image' => 'bitterballen.jpg', 'availability' => 'in_stock'],
                ['name' => 'Rice Dumpling', 'price' => 55000, 'type' => 'Savory Rice', 'flavor' => 'Chicken Mushroom', 'size' => 'Single Piece', 'image' => 'rice-dumpling.jpg', 'availability' => 'in_stock'],
            ],
        ];

        $items = $catalog[$cat->slug] ?? null;
        if (!$items) {
            foreach ($catalog as $key => $itemList) {
                if (str_contains($cat->slug, $key) || str_contains($key, $cat->slug)) {
                    $items = $itemList;
                    break;
                }
            }
        }

        if ($items) {
            foreach ($items as $idx => $item) {
                Product::updateOrCreate(
                    [
                        'name' => $item['name'],
                    ],
                    [
                        'category_id' => $cat->id,
                        'slug' => \Illuminate\Support\Str::slug($item['name']),
                        'price' => $item['price'],
                        'type' => $item['type'] ?? 'Artisan Item',
                        'flavor' => $item['flavor'] ?? 'Original',
                        'size' => $item['size'] ?? 'Standard',
                        'availability' => $item['availability'] ?? 'in_stock',
                        'image' => $item['image'] ?? (\Illuminate\Support\Str::slug($item['name']) . '.jpg'),
                        'is_best_seller' => $item['is_best_seller'] ?? false,
                        'display_order' => $idx + 1,
                    ]
                );
            }
        }
    }

    public static function getActiveTheme(): string
    {
        if (session()->has('site_theme')) {
            return session('site_theme');
        }

        $path = storage_path('app/theme.json');
        if (file_exists($path)) {
            $data = json_decode(file_get_contents($path), true);
            if (!empty($data['theme'])) {
                return $data['theme'];
            }
        }

        return 'burgundy-white'; // Default tema: burgundy terang kombinasi putih
    }

    public function setSiteTheme(Request $request)
    {
        $theme = $request->input('theme', 'burgundy-white');
        if (!in_array($theme, ['burgundy-white', 'wine-dark', 'emerald'])) {
            $theme = 'burgundy-white';
        }

        session(['site_theme' => $theme]);

        try {
            $dir = storage_path('app');
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }
            file_put_contents(storage_path('app/theme.json'), json_encode(['theme' => $theme]));
        } catch (\Throwable $e) {
            // Ignore file write error, session already set
        }

        return response()->json([
            'success' => true,
            'theme' => $theme,
            'label' => $theme === 'burgundy-white' ? 'Burgundy Terang & Putih' : ($theme === 'emerald' ? 'Royal Emerald & Gold' : 'Burgundy Dark & Emas')
        ]);
    }
}
