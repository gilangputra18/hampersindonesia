<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function home()
    {
        $treats = Product::with('category')->where('is_treat', true)->take(8)->get();
        if ($treats->count() < 5) {
            $treatCategory = Category::where('slug', 'light-bites')->first();
            if ($treatCategory) {
                $treats = $treatCategory->products()->take(8)->get();
            }
        }

        // Fetch best sellers first, and expand with diverse luxury items to give a rich variety of at least 8 items
        $best = Product::with('category')->where('is_best_seller', true)->get();
        if ($best->count() < 8) {
            $existingIds = $best->pluck('id')->toArray();
            $supplement = Product::with('category')
                ->whereNotIn('id', $existingIds)
                ->whereIn('availability', ['in_stock', 'pre_order'])
                ->orderBy('display_order', 'asc')
                ->take(8 - $best->count())
                ->get();
            $best = $best->concat($supplement);
        }

        if ($best->isEmpty()) {
            $best = Product::with('category')->latest()->take(8)->get();
        }

        try {
            $reviews = \App\Models\ProductReview::with('product')
                ->where('is_approved', true)
                ->where('is_featured', true)
                ->latest()
                ->take(6)
                ->get();
        } catch (\Throwable $e) {
            $reviews = collect();
        }

        return view('home', [
            'gifts'   => ['hampers', 'cakes', 'cookies', 'breads'],
            'treats'  => $treats,
            'best'    => $best,
            'reviews' => $reviews,
        ]);
    }

    public function category(Request $request, ?string $slug = 'hampers')
    {
        $slug = $slug ?: 'hampers';
        $cat = Category::where('slug', $slug)->first() ?? Category::first();

        if (!$cat) {
            abort(404);
        }

        // Base query for category products (with eager-loaded category relation)
        $baseQuery = Product::with('category')->where('category_id', $cat->id);

        // Auto-heal: If category currently has no products, populate default products so the page is never blank!
        if ($baseQuery->count() === 0) {
            $this->ensureCategoryHasProducts($cat);
            $baseQuery = Product::with('category')->where('category_id', $cat->id);
        }

        // Aggregate filter options based on all products in this category
        $allCategoryProducts = (clone $baseQuery)->get();

        $types = $allCategoryProducts->pluck('type')->filter()->unique()->values();
        $flavors = $allCategoryProducts->pluck('flavor')->filter()->unique()->values();
        $sizes = $allCategoryProducts->pluck('size')->filter()->unique()->values();
        $availabilities = $allCategoryProducts->pluck('availability')->filter()->unique()->values();

        $minPriceLimit = $allCategoryProducts->min('price') ?? 0;
        $maxPriceLimit = $allCategoryProducts->max('price') ?? 10000000;

        // Apply active filters
        $filters = [
            'category' => $slug,
            'types' => (array) $request->get('types', []),
            'flavors' => (array) $request->get('flavors', []),
            'sizes' => (array) $request->get('sizes', []),
            'availabilities' => (array) $request->get('availabilities', []),
            'min_price' => $request->get('min_price', $minPriceLimit),
            'max_price' => $request->get('max_price', $maxPriceLimit),
            'sort' => $request->get('sort', 'latest'),
        ];

        $filteredProducts = (clone $baseQuery)->filter($filters)->get();

        // AJAX response for live filter updates
        if ($request->ajax() || $request->wantsJson()) {
            $gridHtml = view('partials.product_grid', ['products' => $filteredProducts])->render();
            return response()->json([
                'html' => $gridHtml,
                'total' => $filteredProducts->count(),
            ]);
        }

        return view('category', [
            'slug' => $slug,
            'cat' => $cat,
            'products' => $filteredProducts,
            'types' => $types,
            'flavors' => $flavors,
            'sizes' => $sizes,
            'availabilities' => $availabilities,
            'minPriceLimit' => $minPriceLimit,
            'maxPriceLimit' => $maxPriceLimit,
            'filters' => $filters,
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
}
