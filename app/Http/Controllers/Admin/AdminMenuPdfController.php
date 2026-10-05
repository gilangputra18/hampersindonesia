<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReservationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AdminMenuPdfController extends Controller
{
    public function index()
    {
        $reservations = ReservationItem::orderBy('display_order')->get();

        $globalPdfPath = public_path('uploads/menu_catalog.pdf');
        $hasGlobalPdf = File::exists($globalPdfPath);
        $globalFileInfo = null;

        if ($hasGlobalPdf) {
            $globalFileInfo = [
                'name' => 'menu_catalog.pdf',
                'size' => round(File::size($globalPdfPath) / 1024 / 1024, 2) . ' MB',
                'updated_at' => date('d M Y H:i', File::lastModified($globalPdfPath)),
                'url' => asset('uploads/menu_catalog.pdf')
            ];
        }

        return view('admin.menu_pdf.index', compact('reservations', 'hasGlobalPdf', 'globalFileInfo'));
    }

    public function uploadItemPdf(Request $request, $id)
    {
        $item = ReservationItem::findOrFail($id);

        $request->validate([
            'menu_pdf' => 'required|mimes:pdf|max:20480', // max 20MB
        ], [
            'menu_pdf.required' => 'Pilih berkas PDF terlebih dahulu.',
            'menu_pdf.mimes' => 'Berkas harus berformat PDF.',
            'menu_pdf.max' => 'Ukuran berkas PDF maksimal 20 MB.',
        ]);

        $uploadDir = public_path('uploads/pdf');
        if (!File::exists($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true);
        }

        $fileName = 'menu_' . $item->slug . '_' . time() . '.pdf';
        $request->file('menu_pdf')->move($uploadDir, $fileName);

        // Delete old PDF if exists
        if ($item->pdf_path && File::exists(public_path($item->pdf_path))) {
            File::delete(public_path($item->pdf_path));
        }

        $item->pdf_path = 'uploads/pdf/' . $fileName;
        $item->save();

        return redirect()->route('admin.menu_pdf.index')->with('ok', 'PDF Menu khusus untuk "' . $item->title . '" berhasil dipublikasikan!');
    }

    public function destroyItemPdf($id)
    {
        $item = ReservationItem::findOrFail($id);
        if ($item->pdf_path && File::exists(public_path($item->pdf_path))) {
            File::delete(public_path($item->pdf_path));
        }

        $item->pdf_path = null;
        $item->save();

        return redirect()->route('admin.menu_pdf.index')->with('ok', 'PDF Kustom untuk "' . $item->title . '" berhasil dihapus. Tombol View Menu kembali menggunakan Katalog Digital.');
    }

    public function storeReservation(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'subtitle' => 'nullable|string|max:150',
            'description' => 'required|string',
            'whatsapp_number' => 'nullable|string|max:30',
            'whatsapp_text' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:4096',
            'menu_pdf' => 'nullable|mimes:pdf|max:20480',
        ]);

        $slug = Str::slug($request->title);
        if (ReservationItem::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . time();
        }

        $imgPath = 'images/reservation-dinein.jpg';
        if ($request->hasFile('image')) {
            $uploadDir = public_path('uploads/images');
            if (!File::exists($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true);
            }
            $imgName = 'res_' . $slug . '_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($uploadDir, $imgName);
            $imgPath = 'uploads/images/' . $imgName;
        }

        $pdfPath = null;
        if ($request->hasFile('menu_pdf')) {
            $uploadDir = public_path('uploads/pdf');
            if (!File::exists($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true);
            }
            $pdfName = 'menu_' . $slug . '_' . time() . '.pdf';
            $request->file('menu_pdf')->move($uploadDir, $pdfName);
            $pdfPath = 'uploads/pdf/' . $pdfName;
        }

        $maxOrder = ReservationItem::max('display_order') ?? 0;

        ReservationItem::create([
            'slug' => $slug,
            'title' => strtoupper($request->title),
            'subtitle' => strtoupper($request->subtitle),
            'description' => $request->description,
            'image' => $imgPath,
            'whatsapp_number' => $request->whatsapp_number ?: '62811152282',
            'whatsapp_text' => $request->whatsapp_text ?: 'Halo MAISON DORÉE, saya ingin melakukan reservasi ' . $request->title,
            'pdf_path' => $pdfPath,
            'display_order' => $maxOrder + 1,
            'is_active' => true,
        ]);

        return redirect()->route('admin.menu_pdf.index')->with('ok', 'Paket Reservasi Baru "' . $request->title . '" berhasil ditambahkan!');
    }

    public function updateReservation(Request $request, $id)
    {
        $item = ReservationItem::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:150',
            'subtitle' => 'nullable|string|max:150',
            'description' => 'required|string',
            'whatsapp_number' => 'nullable|string|max:30',
            'whatsapp_text' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('image')) {
            $uploadDir = public_path('uploads/images');
            if (!File::exists($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true);
            }
            $imgName = 'res_' . $item->slug . '_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($uploadDir, $imgName);
            $item->image = 'uploads/images/' . $imgName;
        }

        $item->title = strtoupper($request->title);
        $item->subtitle = strtoupper($request->subtitle);
        $item->description = $request->description;
        $item->whatsapp_number = $request->whatsapp_number ?: '62811152282';
        $item->whatsapp_text = $request->whatsapp_text;
        $item->save();

        return redirect()->route('admin.menu_pdf.index')->with('ok', 'Paket Reservasi "' . $item->title . '" berhasil diperbarui!');
    }

    public function destroyReservation($id)
    {
        $item = ReservationItem::findOrFail($id);
        if ($item->pdf_path && File::exists(public_path($item->pdf_path))) {
            File::delete(public_path($item->pdf_path));
        }
        $item->delete();

        return redirect()->route('admin.menu_pdf.index')->with('ok', 'Paket Reservasi berhasil dihapus!');
    }

    public function uploadGlobalPdf(Request $request)
    {
        $request->validate([
            'menu_pdf' => 'required|mimes:pdf|max:20480',
        ]);

        $uploadDir = public_path('uploads');
        if (!File::exists($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true);
        }

        $request->file('menu_pdf')->move($uploadDir, 'menu_catalog.pdf');

        return redirect()->route('admin.menu_pdf.index')->with('ok', 'PDF Menu Utama (Global) berhasil diperbarui!');
    }

    public function destroyGlobalPdf()
    {
        $pdfPath = public_path('uploads/menu_catalog.pdf');
        if (File::exists($pdfPath)) {
            File::delete($pdfPath);
        }

        return redirect()->route('admin.menu_pdf.index')->with('ok', 'PDF Menu Utama (Global) dihapus. Sistem menggunakan Digital Menu Otomatis Database.');
    }
}
