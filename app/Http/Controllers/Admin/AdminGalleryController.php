<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AdminGalleryController extends Controller
{
    public static function getGalleryPath(): string
    {
        return storage_path('app/gallery.json');
    }

    public static function getCategories(): array
    {
        return [
            'lapis'   => '🥮 Lapis & Cakes',
            'bites'   => '🍫 Choco & Bites',
            'hampers' => '🎁 Luxury Hampers',
            'cookies' => '🍪 Artisan Cookies',
        ];
    }

    public static function getDefaultItems(): array
    {
        return [
            [
                'id' => 1,
                'image' => '/images/cat-cakes.jpg',
                'category' => 'lapis',
                'title' => 'Lapis Legit Original Slices',
                'card_name' => 'Lapis Legit Original',
                'tagline' => "Once You Bite, You Can't Stop!",
                'desc' => 'Lapisan butter Wijsman otentik dipanggang perlapis dengan kelembutan sempurna.',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'id' => 2,
                'image' => '/images/black-forest.jpg',
                'category' => 'lapis',
                'title' => 'Lapis Legit Mini Bites',
                'card_name' => 'Mini Bites Platter',
                'tagline' => 'Premium Quality Cakes',
                'desc' => 'Potongan gigitan lapis legit mewah yang disajikan di atas piring saji eksklusif.',
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'id' => 3,
                'image' => '/images/box-of-4.jpg',
                'category' => 'bites',
                'title' => 'Mix Small 4 Rasa (12 Bites)',
                'card_name' => 'Mix Small 4 Rasa',
                'tagline' => '12 Bites Gift Box',
                'desc' => 'Kombinasi 4 varian rasa lapis legit pilihan dalam kemasan gift box elegan.',
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'id' => 4,
                'image' => '/images/tiramisu.jpg',
                'category' => 'lapis',
                'title' => 'Lapis Loyang Mini Plum & Cempedak',
                'card_name' => 'Lapis Plum Square',
                'tagline' => "Once You Bite, You Can't Stop!",
                'desc' => 'Sentuhan buah plum & cempedak manis alami pada adonan lapis legit butter.',
                'display_order' => 4,
                'is_active' => true,
            ],
            [
                'id' => 5,
                'image' => '/images/butter-cookies-jar.jpg',
                'category' => 'lapis',
                'title' => 'Marble Cake Small (12 pcs)',
                'card_name' => 'Marble Cake Small',
                'tagline' => '12 Pcs Slide Box',
                'desc' => 'Bolu marmer klasik dengan tekstur lembut wangi butter dan swirl cokelat premium.',
                'display_order' => 5,
                'is_active' => true,
            ],
            [
                'id' => 6,
                'image' => '/images/basque-burnt-cheesecake.jpg',
                'category' => 'lapis',
                'title' => 'Lapis Legit Pattern Harum',
                'card_name' => 'Lapis Pattern Harum',
                'tagline' => "Once You Bite, You Can't Stop!",
                'desc' => 'Corak pattern atas elegan dengan lapisan bumbu rempah spekkoek wangi semerbak.',
                'display_order' => 6,
                'is_active' => true,
            ],
            [
                'id' => 7,
                'image' => '/images/berry-tart.jpg',
                'category' => 'lapis',
                'title' => 'Tropical Layer Set',
                'card_name' => 'Tropical Layer Set',
                'tagline' => 'Premium Quality Cakes',
                'desc' => 'Edisi koleksi lapis legit tropikal dengan packaging box botanikal mewah.',
                'display_order' => 7,
                'is_active' => true,
            ],
            [
                'id' => 8,
                'image' => '/images/box-of-6.jpg',
                'category' => 'bites',
                'title' => 'Mix Big 4 Rasa (36 Bites)',
                'card_name' => 'Mix Big 4 Rasa',
                'tagline' => '36 Bites Party Tray',
                'desc' => 'Nampan perjamuan eksklusif berisi 36 potong gigitan lapis legit dari 4 varian unggulan.',
                'display_order' => 8,
                'is_active' => true,
            ],
            [
                'id' => 9,
                'image' => '/images/chocolate-hazelnut.jpg',
                'category' => 'bites',
                'title' => 'Choco Bites Gourmet',
                'card_name' => 'Choco Bites',
                'tagline' => "Once You Bite, You Can't Stop!",
                'desc' => 'Truffle cokelat hitam lumer bertabur gold dust mewah, perpaduan manis gurih tak terlupakan.',
                'display_order' => 9,
                'is_active' => true,
            ],
            [
                'id' => 10,
                'image' => '/images/rose.jpg',
                'category' => 'hampers',
                'title' => 'Rose Velvet Hampers Gift Box',
                'card_name' => 'Rose Velvet Box',
                'tagline' => 'Exclusive Gift Hampers',
                'desc' => 'Hampers elegan balutan beludru merah maroon berisikan aneka cookies butter Wijsman & lapis legit.',
                'display_order' => 10,
                'is_active' => true,
            ],
            [
                'id' => 11,
                'image' => '/images/nastar-pineapple.jpg',
                'category' => 'cookies',
                'title' => 'Nastar Pineapple Wijsman',
                'card_name' => 'Nastar Wijsman',
                'tagline' => 'Artisan Recipe Cookies',
                'desc' => 'Kue kering nastar nanas lumer di mulut dengan selai nanas segar resep tradisional turun-temurun.',
                'display_order' => 11,
                'is_active' => true,
            ],
            [
                'id' => 12,
                'image' => '/images/kaastengel.jpg',
                'category' => 'cookies',
                'title' => 'Edam Cheese Kaastengels',
                'card_name' => 'Edam Kaastengels',
                'tagline' => 'Pure Dutch Cheese',
                'desc' => 'Keju tua Edam Belanda melimpah dengan kerenyahan gurih otentik kualitas premium.',
                'display_order' => 12,
                'is_active' => true,
            ],
        ];
    }

    public static function getItems(): array
    {
        $path = self::getGalleryPath();
        if (File::exists($path)) {
            $data = json_decode(File::get($path), true);
            if (is_array($data) && isset($data['items']) && is_array($data['items'])) {
                // Return items sorted by display_order
                $items = $data['items'];
                usort($items, function($a, $b) {
                    $orderA = $a['display_order'] ?? 999;
                    $orderB = $b['display_order'] ?? 999;
                    if ($orderA == $orderB) {
                        return ($a['id'] ?? 0) <=> ($b['id'] ?? 0);
                    }
                    return $orderA <=> $orderB;
                });
                return $items;
            }
        }

        return self::getDefaultItems();
    }

    public static function saveAll(array $items): void
    {
        $dir = storage_path('app');
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        File::put(self::getGalleryPath(), json_encode([
            'items' => array_values($items),
            'updated_at' => now()->toIso8601String(),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    public function index()
    {
        $items = self::getItems();
        $categories = self::getCategories();
        return view('admin.gallery.index', compact('items', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:150',
            'card_name'     => 'nullable|string|max:100',
            'tagline'       => 'nullable|string|max:150',
            'category'      => 'required|string|max:50',
            'desc'          => 'nullable|string|max:600',
            'display_order' => 'nullable|integer|min:0|max:9999',
            'image_file'    => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:8192',
            'image_url'     => 'nullable|string|max:500',
        ]);

        $imagePath = '/images/cat-cakes.jpg';

        if ($request->hasFile('image_file')) {
            $destDir = public_path('uploads/gallery');
            if (!File::isDirectory($destDir)) {
                File::makeDirectory($destDir, 0755, true);
            }
            $filename = 'gallery_' . time() . '_' . Str::random(6) . '.' . $request->file('image_file')->getClientOriginalExtension();
            $request->file('image_file')->move($destDir, $filename);
            $imagePath = '/uploads/gallery/' . $filename;
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->input('image_url');
        }

        $items = self::getItems();
        $newId = count($items) > 0 ? (max(array_column($items, 'id')) + 1) : 1;
        $order = $request->filled('display_order') ? (int) $request->input('display_order') : (count($items) + 1);

        $newItem = [
            'id'            => $newId,
            'image'         => $imagePath,
            'category'      => $request->input('category'),
            'title'         => $request->input('title'),
            'card_name'     => $request->input('card_name') ?: $request->input('title'),
            'tagline'       => $request->input('tagline') ?: "Once You Bite, You Can't Stop!",
            'desc'          => $request->input('desc') ?: '',
            'display_order' => $order,
            'is_active'     => true,
        ];

        $items[] = $newItem;
        self::saveAll($items);

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Foto galeri baru "' . e($newItem['title']) . '" berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'         => 'required|string|max:150',
            'card_name'     => 'nullable|string|max:100',
            'tagline'       => 'nullable|string|max:150',
            'category'      => 'required|string|max:50',
            'desc'          => 'nullable|string|max:600',
            'display_order' => 'nullable|integer|min:0|max:9999',
            'image_file'    => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:8192',
            'image_url'     => 'nullable|string|max:500',
        ]);

        $items = self::getItems();
        $found = false;

        foreach ($items as &$item) {
            if ($item['id'] == $id) {
                $item['title'] = $request->input('title');
                $item['card_name'] = $request->input('card_name') ?: $request->input('title');
                $item['tagline'] = $request->input('tagline') ?: "Once You Bite, You Can't Stop!";
                $item['category'] = $request->input('category');
                $item['desc'] = $request->input('desc') ?: '';
                if ($request->filled('display_order')) {
                    $item['display_order'] = (int) $request->input('display_order');
                }

                if ($request->hasFile('image_file')) {
                    $destDir = public_path('uploads/gallery');
                    if (!File::isDirectory($destDir)) {
                        File::makeDirectory($destDir, 0755, true);
                    }
                    $filename = 'gallery_' . time() . '_' . Str::random(6) . '.' . $request->file('image_file')->getClientOriginalExtension();
                    $request->file('image_file')->move($destDir, $filename);

                    // Delete old upload if custom
                    if (!empty($item['image']) && str_starts_with($item['image'], '/uploads/gallery/')) {
                        $oldFile = public_path(ltrim($item['image'], '/'));
                        if (File::exists($oldFile)) {
                            File::delete($oldFile);
                        }
                    }

                    $item['image'] = '/uploads/gallery/' . $filename;
                } elseif ($request->filled('image_url')) {
                    $item['image'] = $request->input('image_url');
                }

                $found = true;
                break;
            }
        }

        if ($found) {
            self::saveAll($items);
            return redirect()->route('admin.gallery.index')
                ->with('success', 'Foto galeri berhasil diperbarui!');
        }

        return redirect()->route('admin.gallery.index')
            ->with('error', 'Foto galeri dengan ID tersebut tidak ditemukan.');
    }

    public function toggleActive($id)
    {
        $items = self::getItems();
        $newStatus = true;

        foreach ($items as &$item) {
            if ($item['id'] == $id) {
                $item['is_active'] = !($item['is_active'] ?? true);
                $newStatus = $item['is_active'];
                break;
            }
        }

        self::saveAll($items);

        $statusText = $newStatus ? 'diaktifkan (tampil di galeri)' : 'dinonaktifkan (disembunyikan)';
        return redirect()->route('admin.gallery.index')
            ->with('success', 'Status foto galeri berhasil ' . $statusText . '!');
    }

    public function destroy($id)
    {
        $items = self::getItems();
        $targetItem = null;

        foreach ($items as $item) {
            if ($item['id'] == $id) {
                $targetItem = $item;
                break;
            }
        }

        if (!$targetItem) {
            return redirect()->route('admin.gallery.index')
                ->with('error', 'Foto galeri tidak ditemukan.');
        }

        // Delete uploaded file if located in /uploads/gallery/
        if (!empty($targetItem['image']) && str_starts_with($targetItem['image'], '/uploads/gallery/')) {
            $filePath = public_path(ltrim($targetItem['image'], '/'));
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        $items = array_values(array_filter($items, function($it) use ($id) {
            return $it['id'] != $id;
        }));

        self::saveAll($items);

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Foto galeri "' . e($targetItem['title']) . '" berhasil dihapus.');
    }
}
