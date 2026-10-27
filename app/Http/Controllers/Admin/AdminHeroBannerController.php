<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AdminHeroBannerController extends Controller
{
    public static function getBannerPath()
    {
        return storage_path('app/hero_banner.json');
    }

    public static function getSlides()
    {
        $path = self::getBannerPath();
        if (File::exists($path)) {
            $data = json_decode(File::get($path), true);
            if (is_array($data) && !empty($data['slides'])) {
                return $data['slides'];
            }
        }

        // Default artisanal slides matching reference
        return [
            [
                'id' => 1,
                'image' => '/images/hero-poster.jpg',
                'badge' => '⚜️ PUSAT HAMPERS INDONESIA ⚜️',
                'title' => 'PUSAT HAMPERS INDONESIA',
                'quote' => '"Have a bite, you can\'t stop!"',
                'desc' => 'Cita rasa premium Indonesia untuk setiap momen berharga, dibuat dengan bahan-bahan pilihan berkualitas tinggi berstandar artisanal internasional.',
                'btn_text' => 'JELAJAHI KOLEKSI',
                'btn_url' => '/shop',
                'is_active' => true,
            ],
            [
                'id' => 2,
                'image' => '/images/events.jpg',
                'badge' => '⚜️ EKSKLUSIF & ELEGAN ⚜️',
                'title' => 'PARSEL & HAMPERS MEWAH',
                'quote' => '"Sentuhan Hadiah Istimewa untuk Hari Berharga"',
                'desc' => 'Rangkaian parcel gourmet eksklusif dengan kotak premium hardbox, pita satin mewah, dan pilihan kurasi kue terbaik.',
                'btn_text' => 'PESAN HAMPERS VIP',
                'btn_url' => '/category/hampers',
                'is_active' => true,
            ],
            [
                'id' => 3,
                'image' => '/images/feature.jpg',
                'badge' => '⚜️ ARTISANAL BAKERY ⚜️',
                'title' => 'KUE & TART GOURMET',
                'quote' => '"Dibuat Segar Setiap Hari dengan Pure Butter Impor"',
                'desc' => 'Kelezatan lembut Basque Burnt Cheesecake, Berry Tart, dan Tiramisu autentik berstandar pastry internasional.',
                'btn_text' => 'LIHAT SEMUA KUE',
                'btn_url' => '/category/cakes',
                'is_active' => true,
            ],
            [
                'id' => 4,
                'image' => '/images/cat-cookies.jpg',
                'badge' => '⚜️ KUE KERING TRADISIONAL ⚜️',
                'title' => 'SIGNATURE COOKIES',
                'quote' => '"Renyah, Harum & Meleleh di Setiap Gigitan"',
                'desc' => 'Nastar Nanas Wijsman, Kaastengel Keju Edam tua, dan French Butter Cookies renyah untuk melengkapi hari istimewa.',
                'btn_text' => 'PILIH KUE KERING',
                'btn_url' => '/category/cookies',
                'is_active' => true,
            ],
        ];
    }

    public static function getSettings()
    {
        $path = self::getBannerPath();
        if (File::exists($path)) {
            $data = json_decode(File::get($path), true);
            if (is_array($data) && isset($data['settings'])) {
                return $data['settings'];
            }
        }
        return [
            'autoplay' => true,
            'interval' => 5000, // 5 detik
            'direction' => 'right', // geser ke kanan
        ];
    }

    private static function saveAll(array $slides, array $settings)
    {
        $dir = storage_path('app');
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
        File::put(self::getBannerPath(), json_encode([
            'slides' => array_values($slides),
            'settings' => $settings,
            'updated_at' => now()->toIso8601String(),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    public function index()
    {
        $slides = self::getSlides();
        $settings = self::getSettings();
        return view('admin.hero_banner.index', compact('slides', 'settings'));
    }

    public function storeSlide(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'badge' => 'nullable|string|max:100',
            'quote' => 'nullable|string|max:150',
            'desc' => 'nullable|string|max:500',
            'btn_text' => 'nullable|string|max:50',
            'btn_url' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_url' => 'nullable|string|max:255',
        ]);

        $imagePath = '/images/hero-poster.jpg';
        if ($request->hasFile('image_file')) {
            $destDir = public_path('images/banners');
            if (!File::isDirectory($destDir)) {
                File::makeDirectory($destDir, 0755, true);
            }
            $filename = 'banner_' . time() . '_' . Str::random(6) . '.' . $request->file('image_file')->getClientOriginalExtension();
            $request->file('image_file')->move($destDir, $filename);
            $imagePath = '/images/banners/' . $filename;
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->input('image_url');
        }

        $slides = self::getSlides();
        $settings = self::getSettings();

        $newId = count($slides) > 0 ? (max(array_column($slides, 'id')) + 1) : 1;

        $slides[] = [
            'id' => $newId,
            'image' => $imagePath,
            'badge' => $request->input('badge') ?: '⚜️ PUSAT HAMPERS INDONESIA ⚜️',
            'title' => $request->input('title'),
            'quote' => $request->input('quote') ?: '"Have a bite, you can\'t stop!"',
            'desc' => $request->input('desc') ?: 'Cita rasa premium Indonesia untuk setiap momen berharga.',
            'btn_text' => $request->input('btn_text') ?: 'JELAJAHI KOLEKSI',
            'btn_url' => $request->input('btn_url') ?: '/shop',
            'is_active' => true,
        ];

        self::saveAll($slides, $settings);

        return redirect()->route('admin.hero_banner.index')
            ->with('success', 'Gambar slide hero banner baru berhasil ditambahkan!');
    }

    public function updateSlide(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'badge' => 'nullable|string|max:100',
            'quote' => 'nullable|string|max:150',
            'desc' => 'nullable|string|max:500',
            'btn_text' => 'nullable|string|max:50',
            'btn_url' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_url' => 'nullable|string|max:255',
        ]);

        $slides = self::getSlides();
        $settings = self::getSettings();
        $found = false;

        foreach ($slides as &$slide) {
            if ($slide['id'] == $id) {
                $slide['title'] = $request->input('title');
                $slide['badge'] = $request->input('badge');
                $slide['quote'] = $request->input('quote');
                $slide['desc'] = $request->input('desc');
                $slide['btn_text'] = $request->input('btn_text') ?: 'JELAJAHI KOLEKSI';
                $slide['btn_url'] = $request->input('btn_url') ?: '/shop';

                if ($request->hasFile('image_file')) {
                    $destDir = public_path('images/banners');
                    if (!File::isDirectory($destDir)) {
                        File::makeDirectory($destDir, 0755, true);
                    }
                    $filename = 'banner_' . time() . '_' . Str::random(6) . '.' . $request->file('image_file')->getClientOriginalExtension();
                    $request->file('image_file')->move($destDir, $filename);
                    $slide['image'] = '/images/banners/' . $filename;
                } elseif ($request->filled('image_url')) {
                    $slide['image'] = $request->input('image_url');
                }

                $found = true;
                break;
            }
        }

        if ($found) {
            self::saveAll($slides, $settings);
            return redirect()->route('admin.hero_banner.index')
                ->with('success', 'Slide hero banner berhasil diperbarui!');
        }

        return redirect()->route('admin.hero_banner.index')
            ->with('error', 'Slide tidak ditemukan.');
    }

    public function toggleSlide($id)
    {
        $slides = self::getSlides();
        $settings = self::getSettings();

        foreach ($slides as &$slide) {
            if ($slide['id'] == $id) {
                $slide['is_active'] = !($slide['is_active'] ?? true);
                break;
            }
        }

        self::saveAll($slides, $settings);

        return redirect()->route('admin.hero_banner.index')
            ->with('success', 'Status slide berhasil diubah!');
    }

    public function destroySlide($id)
    {
        $slides = self::getSlides();
        $settings = self::getSettings();

        $slides = array_filter($slides, function ($s) use ($id) {
            return $s['id'] != $id;
        });

        if (empty($slides)) {
            // Jaga agar minimal ada 1 slide
            return redirect()->route('admin.hero_banner.index')
                ->with('error', 'Minimal harus ada 1 slide aktif untuk hero banner.');
        }

        self::saveAll($slides, $settings);

        return redirect()->route('admin.hero_banner.index')
            ->with('success', 'Slide berhasil dihapus.');
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'interval' => 'required|integer|min:1000|max:30000',
        ]);

        $slides = self::getSlides();
        $settings = [
            'autoplay' => $request->has('autoplay'),
            'interval' => (int) $request->input('interval', 5000),
            'direction' => $request->input('direction', 'right'),
        ];

        self::saveAll($slides, $settings);

        return redirect()->route('admin.hero_banner.index')
            ->with('success', 'Pengaturan pergeseran slider berhasil disimpan!');
    }
}
