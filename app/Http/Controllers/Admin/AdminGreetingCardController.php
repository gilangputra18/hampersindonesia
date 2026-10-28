<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AdminGreetingCardController extends Controller
{
    public static function getStoragePath(): string
    {
        return storage_path('app/greeting_cards.json');
    }

    public static function getDefaultTemplates(): array
    {
        return [
            [
                'id' => 'natal',
                'title' => '🎄 Natal & Tahun Baru',
                'message' => 'Selamat Hari Raya Natal dan Tahun Baru. Semoga damai, sukacita, dan kehangatan Natal senantiasa melimpahi Anda dan keluarga tercinta, serta membawa awal tahun yang penuh harapan dan kesuksesan.',
                'is_active' => true,
                'display_order' => 1,
            ],
            [
                'id' => 'imlek',
                'title' => '🧧 Tahun Baru Imlek',
                'message' => 'Gong Xi Fa Cai! Selamat Tahun Baru Imlek. Semoga tahun baru ini senantiasa melimpahkan kesehatan prima, rezeki berlimpah, kebahagiaan, dan kemakmuran yang tak terputus bagi Anda sekeluarga.',
                'is_active' => true,
                'display_order' => 2,
            ],
            [
                'id' => 'fitri',
                'title' => '🌙 Idul Fitri 1447 H',
                'message' => 'Selamat Hari Raya Idul Fitri 1447 H. Minal Aidin Wal Faidzin, mohon maaf lahir dan batin. Semoga berkah, kesehatan, dan kedamaian senantiasa melimpah untuk kita sekeluarga.',
                'is_active' => true,
                'display_order' => 3,
            ],
            [
                'id' => 'ultah',
                'title' => '🎂 Selamat Ulang Tahun',
                'message' => 'Happy Birthday! Semoga senantiasa diberikan kesehatan, kebahagiaan berlimpah, rezeki yang lancar, dan terkabul semua doa di usia yang baru ini.',
                'is_active' => true,
                'display_order' => 4,
            ],
            [
                'id' => 'corporate',
                'title' => '💼 Rekan Bisnis / Corporate',
                'message' => 'Terima kasih yang sebesar-besarnya atas kemitraan dan kerja sama luar biasa yang terjalin selama ini. Semoga kemitraan ini terus berlanjut dan membawa kesuksesan bersama.',
                'is_active' => true,
                'display_order' => 5,
            ],
            [
                'id' => 'congrats',
                'title' => '🎉 Congratulations & Sukses',
                'message' => 'Selamat dan sukses atas pencapaian luar biasa ini! Sangat bangga atas prestasi dan dedikasi hebat yang telah Anda torehkan.',
                'is_active' => true,
                'display_order' => 6,
            ],
            [
                'id' => 'gws',
                'title' => '💐 Get Well Soon',
                'message' => 'Semoga lekas sembuh dan lekas pulih kembali seperti sedia kala. Tetap semangat, doa terbaik kami selalu menyertaimu.',
                'is_active' => true,
                'display_order' => 7,
            ],
            [
                'id' => 'hantaran',
                'title' => '✨ Hantaran Silaturahmi',
                'message' => 'Bingkisan hangat ini kami haturkan dengan tulus sebagai tanda silaturahmi dan rasa hormat yang mendalam. Semoga berkenan bagi sekeluarga.',
                'is_active' => true,
                'display_order' => 8,
            ],
        ];
    }

    public static function getTemplates(): array
    {
        $path = self::getStoragePath();
        if (File::exists($path)) {
            $data = json_decode(File::get($path), true);
            if (is_array($data) && !empty($data['templates'])) {
                $templates = $data['templates'];
                usort($templates, function ($a, $b) {
                    $orderA = $a['display_order'] ?? 999;
                    $orderB = $b['display_order'] ?? 999;
                    return $orderA <=> $orderB;
                });
                return $templates;
            }
        }

        return self::getDefaultTemplates();
    }

    public static function getActiveTemplates(): array
    {
        $all = self::getTemplates();
        return array_values(array_filter($all, function ($t) {
            return ($t['is_active'] ?? true);
        }));
    }

    public static function saveAll(array $templates): void
    {
        $dir = storage_path('app');
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        File::put(self::getStoragePath(), json_encode([
            'templates' => array_values($templates),
            'updated_at' => now()->toIso8601String(),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    public function index()
    {
        $templates = self::getTemplates();
        return view('admin.greeting_cards.index', compact('templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'message' => 'required|string|max:500',
            'display_order' => 'nullable|integer|min:0|max:9999',
        ]);

        $templates = self::getTemplates();
        $id = Str::slug($request->input('title'));
        if (empty($id)) {
            $id = 'tpl_' . time();
        }

        // Ensure unique ID
        $existingIds = array_column($templates, 'id');
        $originalId = $id;
        $counter = 1;
        while (in_array($id, $existingIds)) {
            $id = $originalId . '_' . $counter;
            $counter++;
        }

        $order = $request->filled('display_order') ? (int) $request->input('display_order') : (count($templates) + 1);

        $templates[] = [
            'id' => $id,
            'title' => $request->input('title'),
            'message' => $request->input('message'),
            'is_active' => true,
            'display_order' => $order,
        ];

        self::saveAll($templates);

        return redirect()->route('admin.greeting_cards.index')
            ->with('success', '✅ Template kartu ucapan baru "' . e($request->input('title')) . '" berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'message' => 'required|string|max:500',
            'display_order' => 'nullable|integer|min:0|max:9999',
        ]);

        $templates = self::getTemplates();
        $found = false;

        foreach ($templates as &$tpl) {
            if ($tpl['id'] == $id) {
                $tpl['title'] = $request->input('title');
                $tpl['message'] = $request->input('message');
                if ($request->filled('display_order')) {
                    $tpl['display_order'] = (int) $request->input('display_order');
                }
                $found = true;
                break;
            }
        }

        if ($found) {
            self::saveAll($templates);
            return redirect()->route('admin.greeting_cards.index')
                ->with('success', '✅ Template kartu ucapan "' . e($request->input('title')) . '" berhasil diperbarui!');
        }

        return redirect()->route('admin.greeting_cards.index')
            ->with('error', '⚠️ Template kartu ucapan tidak ditemukan.');
    }

    public function toggle($id)
    {
        $templates = self::getTemplates();
        $newStatus = true;

        foreach ($templates as &$tpl) {
            if ($tpl['id'] == $id) {
                $tpl['is_active'] = !($tpl['is_active'] ?? true);
                $newStatus = $tpl['is_active'];
                break;
            }
        }

        self::saveAll($templates);

        $statusText = $newStatus ? 'diaktifkan (tampil di halaman checkout)' : 'dinonaktifkan (disembunyikan)';
        return redirect()->route('admin.greeting_cards.index')
            ->with('success', '✅ Status template berhasil ' . $statusText . '!');
    }

    public function destroy($id)
    {
        $templates = self::getTemplates();
        $target = null;

        foreach ($templates as $tpl) {
            if ($tpl['id'] == $id) {
                $target = $tpl;
                break;
            }
        }

        if (!$target) {
            return redirect()->route('admin.greeting_cards.index')
                ->with('error', '⚠️ Template kartu ucapan tidak ditemukan.');
        }

        $templates = array_values(array_filter($templates, function ($t) use ($id) {
            return $t['id'] != $id;
        }));

        self::saveAll($templates);

        return redirect()->route('admin.greeting_cards.index')
            ->with('success', '🗑️ Template kartu ucapan "' . e($target['title']) . '" berhasil dihapus.');
    }

    public function resetDefaults()
    {
        self::saveAll(self::getDefaultTemplates());

        return redirect()->route('admin.greeting_cards.index')
            ->with('success', '🔄 Seluruh template kartu ucapan berhasil direset ke pengaturan bawaan (Natal, Imlek, Lebaran, dll)!');
    }
}
