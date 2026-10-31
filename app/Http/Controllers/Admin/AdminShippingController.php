<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminShippingController extends Controller
{
    private function getSettingsPath()
    {
        return storage_path('app/shipping.json');
    }

    public static function getShippingSettings()
    {
        $path = storage_path('app/shipping.json');
        if (File::exists($path)) {
            $data = json_decode(File::get($path), true);
            if (is_array($data)) {
                return $data;
            }
        }
        return [
            'free_shipping_min' => 500000, // Rp 500.000
            'jne_rate' => 15000,
            'jnt_rate' => 16000,
            'sicepat_rate' => 15000,
            'store_courier_rate' => 20000,
            'gosend_rate' => 25000,
            'aggregator_partner' => 'Biteship API (Aggregator JNE, J&T, Grab, GoSend)',
            'api_key' => 'biteship_live_sec_XXXXXXXXXXXXXX',
            'corporate_discount_percent' => 25,
            'pickup_address' => 'Boutique Surabaya: Jl. Raya Darmo No. 45 | Boutique Bandung: Jl. LLRE Martadinata (Riau) No. 68',
            'shipping_instructions' => 'Pengiriman menggunakan box khusus pengjaga suhu artisan bakery. Gratis ongkir otomatis berlaku untuk pembelanjaan di atas Rp 500.000.'
        ];
    }

    public function index()
    {
        $settings = self::getShippingSettings();
        return view('admin.shipping.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'free_shipping_min' => 'required|numeric|min:0',
            'jne_rate' => 'required|numeric|min:0',
            'jnt_rate' => 'required|numeric|min:0',
            'sicepat_rate' => 'required|numeric|min:0',
            'store_courier_rate' => 'required|numeric|min:0',
            'gosend_rate' => 'required|numeric|min:0',
            'aggregator_partner' => 'nullable|string|max:100',
            'api_key' => 'nullable|string|max:100',
            'corporate_discount_percent' => 'nullable|numeric|min:0|max:100',
            'pickup_address' => 'nullable|string',
            'shipping_instructions' => 'nullable|string',
        ]);

        File::put($this->getSettingsPath(), json_encode($validated, JSON_PRETTY_PRINT));

        return redirect()->back()->with('success', 'Pengaturan Ongkir & Kerjasama Ekspedisi berhasil diperbarui!');
    }
}
