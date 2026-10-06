<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function home()
    {
        $treats = Product::where('is_treat', true)->take(5)->get();
        if ($treats->isEmpty()) {
            $treatCategory = Category::where('slug', 'light-bites')->first();
            $treats = $treatCategory ? $treatCategory->products()->take(5)->get() : collect();
        }

        $best = Product::where('is_best_seller', true)->take(5)->get();
        if ($best->isEmpty()) {
            $best = Product::latest()->take(5)->get();
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

    public function category(Request $request, string $slug)
    {
        $cat = Category::where('slug', $slug)->first();

        if (!$cat) {
            abort(404);
        }

        // Base query for category products
        $baseQuery = Product::where('category_id', $cat->id);

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
}
