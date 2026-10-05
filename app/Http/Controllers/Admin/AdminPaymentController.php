<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminPaymentController extends Controller
{
    private function getSettingsPath()
    {
        return storage_path('app/payments.json');
    }

    public static function getPaymentSettings()
    {
        $path = storage_path('app/payments.json');
        if (File::exists($path)) {
            $data = json_decode(File::get($path), true);
            if (is_array($data)) {
                return $data;
            }
        }
        return [
            'bca_name' => 'BCA Virtual Account',
            'bca_number' => '880123811152282',
            'bca_holder' => 'PT PUSAT HAMPERS INDONESIA',
            'mandiri_name' => 'Bank Mandiri Transfer',
            'mandiri_number' => '123-00-998877-1',
            'mandiri_holder' => 'PT PUSAT HAMPERS INDONESIA',
            'bri_name' => 'Bank BRI Virtual Account',
            'bri_number' => '990088776655',
            'bri_holder' => 'PT PUSAT HAMPERS INDONESIA',
            'qris_number' => 'ID1020088776655',
            'qris_holder' => 'PUSAT HAMPERS INDONESIA',
            'midtrans_client_key' => 'SB-Mid-client-W_17bXa892xKL',
            'midtrans_server_key' => 'SB-Mid-server-P_98zLm001xOP',
            'midtrans_merchant_id' => 'G109827364',
            'midtrans_mode' => 'sandbox',
            'payment_instructions' => 'Silakan melakukan pembayaran penuh ke rekening yang dipilih. Setelah transfer, kirimkan foto bukti pembayaran ke WhatsApp Concierge.'
        ];
    }

    public function index()
    {
        $settings = self::getPaymentSettings();
        return view('admin.payments.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'bca_name' => 'required|string|max:100',
            'bca_number' => 'required|string|max:100',
            'bca_holder' => 'required|string|max:100',
            'mandiri_name' => 'required|string|max:100',
            'mandiri_number' => 'required|string|max:100',
            'mandiri_holder' => 'required|string|max:100',
            'bri_name' => 'nullable|string|max:100',
            'bri_number' => 'nullable|string|max:100',
            'bri_holder' => 'nullable|string|max:100',
            'qris_number' => 'nullable|string|max:100',
            'qris_holder' => 'nullable|string|max:100',
            'midtrans_client_key' => 'nullable|string|max:100',
            'midtrans_server_key' => 'nullable|string|max:100',
            'midtrans_merchant_id' => 'nullable|string|max:100',
            'midtrans_mode' => 'nullable|string|in:sandbox,production',
            'payment_instructions' => 'nullable|string',
        ]);

        File::put($this->getSettingsPath(), json_encode($validated, JSON_PRETTY_PRINT));

        return redirect()->back()->with('success', 'Pengaturan Metode Pembayaran berhasil diperbarui.');
    }
}
