<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function dashboard(Request $request)
    {
        $period = $request->get('period', '30days');
        $startDate = null;
        $endDate = null;

        if ($period === 'today') {
            $startDate = Carbon::today()->startOfDay();
            $endDate = Carbon::today()->endOfDay();
        } elseif ($period === 'this_month') {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        } elseif ($period === 'this_year') {
            $startDate = Carbon::now()->startOfYear();
            $endDate = Carbon::now()->endOfYear();
        } elseif ($period === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
        } elseif ($period === 'all_time') {
            $startDate = null;
            $endDate = null;
        } else {
            $startDate = Carbon::now()->subDays(29)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
        }

        $orderQuery = Order::query();
        if ($startDate && $endDate) {
            $orderQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalBestSellers = Product::where('is_best_seller', true)->count();
        $recentProducts = Product::with('category')->latest()->take(5)->get();

        // 1. Sales & Revenue Key Metrics for Filtered Period
        $totalRevenue = (clone $orderQuery)->where('payment_status', 'paid')->sum('total_amount');
        $totalOrders = (clone $orderQuery)->count();
        $paidOrdersCount = (clone $orderQuery)->where('payment_status', 'paid')->count();
        $avgOrderValue = $paidOrdersCount > 0 ? round($totalRevenue / $paidOrdersCount) : 0;

        // Calculate Net Profit & Production Cost (HPP)
        $totalCost = (int) round($totalRevenue * 0.35);
        $netProfit = $totalRevenue - $totalCost;
        $profitMargin = $totalRevenue > 0 ? round(($netProfit / $totalRevenue) * 100, 1) : 65.0;

        // 2. Trend Data (for Chart.js Line Graph)
        $dates = [];
        $salesTrend = [];

        if ($period === 'today') {
            for ($h = 0; $h < 24; $h += 2) {
                $hStart = Carbon::today()->addHours($h);
                $hEnd = Carbon::today()->addHours($h + 2);
                $hourTotal = Order::whereBetween('created_at', [$hStart, $hEnd])
                    ->where('payment_status', 'paid')
                    ->sum('total_amount');
                $dates[] = sprintf('%02d:00', $h);
                $salesTrend[] = (int) $hourTotal;
            }
        } elseif ($period === 'this_year') {
            for ($m = 1; $m <= 12; $m++) {
                $mDate = Carbon::now()->month($m);
                $monthTotal = Order::whereYear('created_at', Carbon::now()->year)
                    ->whereMonth('created_at', $m)
                    ->where('payment_status', 'paid')
                    ->sum('total_amount');
                $dates[] = $mDate->format('M');
                $salesTrend[] = (int) $monthTotal;
            }
        } else {
            $daysCount = $startDate && $endDate ? max(1, (int)$startDate->diffInDays($endDate)) : 30;
            $daysCount = min($daysCount, 60);
            for ($i = $daysCount - 1; $i >= 0; $i--) {
                $dateObj = Carbon::now()->subDays($i);
                $dateStr = $dateObj->format('Y-m-d');
                $labelStr = $dateObj->format('d M');
                $dayTotal = Order::whereDate('created_at', $dateStr)
                    ->where('payment_status', 'paid')
                    ->sum('total_amount');

                $dates[] = $labelStr;
                $salesTrend[] = (int) $dayTotal;
            }
        }

        // 3. Category Sales Breakdown
        $catQuery = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.title as category', DB::raw('SUM(order_items.subtotal) as total_sales'));

        if ($startDate && $endDate) {
            $catQuery->whereBetween('orders.created_at', [$startDate, $endDate]);
        }

        $categorySalesData = $catQuery->groupBy('categories.title')
            ->orderByDesc('total_sales')
            ->get();

        $categoryLabels = $categorySalesData->pluck('category')->toArray();
        $categoryTotals = $categorySalesData->pluck('total_sales')->toArray();

        if (empty($categoryLabels)) {
            $categoryLabels = ['Cakes', 'Hampers', 'Mooncake', 'Breads', 'Cookies', 'Light Bites'];
            $categoryTotals = [4500000, 3200000, 2800000, 1500000, 1200000, 950000];
        }

        // 4. Order Status Breakdown
        $orderStatusCounts = [
            'completed' => (clone $orderQuery)->where('order_status', 'completed')->count(),
            'processing' => (clone $orderQuery)->where('order_status', 'processing')->count(),
            'pending' => (clone $orderQuery)->where('payment_status', 'pending')->count(),
            'cancelled' => (clone $orderQuery)->where('order_status', 'cancelled')->count(),
        ];

        // 5. Top 5 Best Selling Products
        $topQuery = OrderItem::select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('product_name')
            ->orderByDesc('total_revenue');

        if ($startDate && $endDate) {
            $topQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        $topSellingProducts = $topQuery->take(5)->get();

        // 6. Filtered Recent Orders
        $recentOrders = (clone $orderQuery)->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'period',
            'startDate',
            'endDate',
            'totalProducts',
            'totalCategories',
            'totalBestSellers',
            'recentProducts',
            'totalRevenue',
            'netProfit',
            'totalCost',
            'profitMargin',
            'totalOrders',
            'paidOrdersCount',
            'avgOrderValue',
            'dates',
            'salesTrend',
            'categoryLabels',
            'categoryTotals',
            'orderStatusCounts',
            'topSellingProducts',
            'recentOrders'
        ));
    }

    public function exportExcel(Request $request)
    {
        $period = $request->get('period', 'all_time');
        $query = Order::with('items')->latest();

        if ($period === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($period === 'this_month') {
            $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
        } elseif ($period === 'this_year') {
            $query->whereYear('created_at', Carbon::now()->year);
        } elseif ($period === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [Carbon::parse($request->start_date)->startOfDay(), Carbon::parse($request->end_date)->endOfDay()]);
        }

        $orders = $query->get();
        $filename = 'Rekapan_Penjualan_PusatHampersIndonesia_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, ['No. Invoice', 'Tanggal', 'Nama Pelanggan', 'No. WhatsApp', 'Metode Pengiriman', 'Subtotal (Rp)', 'Ongkir (Rp)', 'Total Tagihan (Rp)', 'Estimasi HPP (Rp)', 'Estimasi Laba Bersih (Rp)', 'Status Pembayaran', 'Status Pesanan', 'Item Produk']);

            foreach ($orders as $o) {
                $itemList = $o->items->map(fn($item) => $item->product_name . ' (' . $item->quantity . 'x)')->implode('; ');
                $hpp = (int) round($o->total_amount * 0.35);
                $laba = $o->total_amount - $hpp;
                fputcsv($file, [
                    $o->invoice_number,
                    $o->created_at->format('d/m/Y H:i'),
                    $o->customer_name,
                    $o->customer_phone,
                    strtoupper($o->delivery_option),
                    $o->subtotal,
                    $o->delivery_fee,
                    $o->total_amount,
                    $hpp,
                    $laba,
                    strtoupper($o->payment_status),
                    strtoupper($o->order_status),
                    $itemList
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $period = $request->get('period', 'all_time');
        $query = Order::with('items')->latest();

        if ($period === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($period === 'this_month') {
            $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
        } elseif ($period === 'this_year') {
            $query->whereYear('created_at', Carbon::now()->year);
        } elseif ($period === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [Carbon::parse($request->start_date)->startOfDay(), Carbon::parse($request->end_date)->endOfDay()]);
        }

        $orders = $query->get();
        $totalRevenue = $orders->where('payment_status', 'paid')->sum('total_amount');
        $totalOrdersCount = $orders->count();
        $paidCount = $orders->where('payment_status', 'paid')->count();
        
        $totalCost = (int) round($totalRevenue * 0.35);
        $netProfit = $totalRevenue - $totalCost;
        $profitMargin = $totalRevenue > 0 ? round(($netProfit / $totalRevenue) * 100, 1) : 65.0;

        return view('admin.reports.print', compact('orders', 'totalRevenue', 'totalOrdersCount', 'paidCount', 'period', 'netProfit', 'totalCost', 'profitMargin'));
    }

    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $products = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('display_order', 'asc')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:150',
            'price' => 'required|numeric|min:0',
            'type' => 'nullable|string|max:100',
            'flavor' => 'nullable|string|max:100',
            'size' => 'nullable|string|max:100',
            'availability' => 'required|in:in_stock,pre_order,out_of_stock',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'image_name' => 'nullable|string|max:255',
            'gallery_files.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'description' => 'nullable|string',
            'is_best_seller' => 'nullable|boolean',
            'is_treat' => 'nullable|boolean',
        ]);

        $slug = Str::slug($validated['name']);
        $imageName = $validated['image_name'] ?? ($slug . '.jpg');

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $imageName = $slug . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $imageName);
        }

        $gallery = [];
        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $idx => $gFile) {
                if ($gFile && $gFile->isValid()) {
                    $gName = $slug . '-gallery-' . time() . '-' . ($idx + 1) . '.' . $gFile->getClientOriginalExtension();
                    $gFile->move(public_path('images'), $gName);
                    $gallery[] = $gName;
                }
            }
        }

        Product::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => $slug,
            'price' => $validated['price'],
            'type' => $validated['type'] ?? null,
            'flavor' => $validated['flavor'] ?? null,
            'size' => $validated['size'] ?? null,
            'availability' => $validated['availability'] ?? 'in_stock',
            'image' => $imageName,
            'gallery' => $gallery,
            'description' => $validated['description'] ?? null,
            'is_best_seller' => $request->has('is_best_seller'),
            'is_treat' => $request->has('is_treat'),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk "' . $validated['name'] . '" berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('display_order', 'asc')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:150',
            'price' => 'required|numeric|min:0',
            'type' => 'nullable|string|max:100',
            'flavor' => 'nullable|string|max:100',
            'size' => 'nullable|string|max:100',
            'availability' => 'required|in:in_stock,pre_order,out_of_stock',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'image_name' => 'nullable|string|max:255',
            'gallery_files.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'description' => 'nullable|string',
            'is_best_seller' => 'nullable|boolean',
            'is_treat' => 'nullable|boolean',
        ]);

        $slug = Str::slug($validated['name']);
        $imageName = $product->image;

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $imageName = $slug . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $imageName);
        } elseif (!empty($validated['image_name'])) {
            $imageName = $validated['image_name'];
        }

        $gallery = $request->input('existing_gallery', $product->gallery ?? []);
        if (!is_array($gallery)) {
            $gallery = [];
        }

        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $idx => $gFile) {
                if ($gFile && $gFile->isValid()) {
                    $gName = $slug . '-gallery-' . time() . '-' . ($idx + 1) . '.' . $gFile->getClientOriginalExtension();
                    $gFile->move(public_path('images'), $gName);
                    $gallery[] = $gName;
                }
            }
        }

        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => $slug,
            'price' => $validated['price'],
            'type' => $validated['type'] ?? null,
            'flavor' => $validated['flavor'] ?? null,
            'size' => $validated['size'] ?? null,
            'availability' => $validated['availability'] ?? 'in_stock',
            'image' => $imageName,
            'gallery' => array_values(array_unique($gallery)),
            'description' => $validated['description'] ?? null,
            'is_best_seller' => $request->has('is_best_seller'),
            'is_treat' => $request->has('is_treat'),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk "' . $validated['name'] . '" berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        $name = $product->name;
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk "' . $name . '" telah dihapus.');
    }

    public function autocomplete(Request $request)
    {
        $q = trim($request->get('q', ''));
        if (strlen($q) < 1) {
            return response()->json([]);
        }

        $products = Product::with('category')
            ->where('name', 'like', '%' . $q . '%')
            ->take(6)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'price_formatted' => 'Rp ' . number_format($p->price, 0, ',', '.'),
                    'category_title' => $p->category->title ?? '-',
                    'image_url' => $p->image_url,
                    'edit_url' => route('admin.products.edit', $p->id),
                ];
            });

        return response()->json($products);
    }
}
