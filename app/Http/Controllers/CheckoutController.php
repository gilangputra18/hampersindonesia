<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $deliveryOption = $request->get('delivery_option', 'delivery');
        $courierName = $request->get('courier_name', 'Kurir Toko (Dedicated Bakery Delivery)');
        $deliveryDate = $request->get('delivery_date', date('Y-m-d', strtotime('+1 day')));
        $orderNote = $request->get('order_note', '');

        $deliveryFee = self::calculateDeliveryFee($deliveryOption, $courierName, $subtotal);
        $appliedCoupon = session('applied_coupon');
        $discountAmount = $appliedCoupon['discount'] ?? 0;
        $totalAmount = max(0, $subtotal + $deliveryFee - $discountAmount);

        $shipSettings = \App\Http\Controllers\Admin\AdminShippingController::getShippingSettings();
        $greetingTemplates = \App\Http\Controllers\Admin\AdminGreetingCardController::getActiveTemplates();

        return view('checkout', compact(
            'cart',
            'subtotal',
            'deliveryOption',
            'courierName',
            'deliveryDate',
            'orderNote',
            'deliveryFee',
            'discountAmount',
            'appliedCoupon',
            'totalAmount',
            'shipSettings',
            'greetingTemplates'
        ));
    }

    public static function calculateDeliveryFee($deliveryOption, $courierName, $subtotal)
    {
        if ($deliveryOption === 'pickup') {
            return 0;
        }

        $shipSettings = \App\Http\Controllers\Admin\AdminShippingController::getShippingSettings();
        $freeMin = $shipSettings['free_shipping_min'] ?? 500000;

        if ($subtotal >= $freeMin) {
            return 0; // GRATIS ONGKIR
        }

        $courier = strtolower($courierName ?? '');
        if (str_contains($courier, 'jne')) {
            return $shipSettings['jne_rate'] ?? 15000;
        } elseif (str_contains($courier, 'j&t') || str_contains($courier, 'jnt')) {
            return $shipSettings['jnt_rate'] ?? 16000;
        } elseif (str_contains($courier, 'gosend') || str_contains($courier, 'grab')) {
            return $shipSettings['gosend_rate'] ?? 25000;
        }

        return $shipSettings['store_courier_rate'] ?? 20000;
    }

    public function process(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_email' => 'nullable|email|max:100',
            'customer_phone' => 'required|string|max:30',
            'delivery_option' => 'required|in:delivery,pickup',
            'courier_name' => 'nullable|string|max:100',
            'payment_method' => 'nullable|string|max:100',
            'delivery_date' => ['required', 'date', 'after_or_equal:' . now()->addDay()->format('Y-m-d')],
            'address' => 'required_if:delivery_option,delivery|nullable|string',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:15',
            'address_notes' => 'nullable|string|max:255',
            'is_gift' => 'nullable|in:0,1',
            'recipient_name' => 'nullable|string|max:100',
            'recipient_phone' => 'nullable|string|max:30',
            'card_to' => 'nullable|string|max:100',
            'card_from' => 'nullable|string|max:100',
            'card_message' => 'nullable|string|max:1000',
            'custom_order_note' => 'nullable|string|max:1000',
            'order_note' => 'nullable|string',
            'terms' => 'required',
        ], [
            'terms.required' => 'Anda harus menyetujui Syarat & Ketentuan Pesanan sebelum melanjutkan.',
            'address.required_if' => 'Alamat pengiriman wajib diisi untuk opsi Local Delivery.',
            'delivery_date.after_or_equal' => 'Tanggal pengiriman harus minimal hari esok (' . now()->addDay()->format('d M Y') . ').',
        ]);

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Tentukan nama kurir (satu kali saja)
        $courierName = $validated['delivery_option'] === 'pickup' 
            ? 'Ambil Sendiri di Boutique Store' 
            : ($validated['courier_name'] ?? 'Kurir Toko (Dedicated Bakery Delivery)');

        $deliveryFee = self::calculateDeliveryFee($validated['delivery_option'], $courierName, $subtotal);

        // Validasi ulang kupon secara aman di database
        $appliedCoupon = session('applied_coupon');
        $discountAmount = 0;
        $couponToIncrement = null;

        if (!empty($appliedCoupon['code'])) {
            $coupon = \App\Models\Coupon::where('code', $appliedCoupon['code'])->first();
            if ($coupon && $coupon->isValidFor($subtotal)) {
                $discountAmount = $coupon->calculateDiscount($subtotal);
                $couponToIncrement = $coupon;
            } else {
                session()->forget('applied_coupon');
            }
        }

        $totalAmount = max(0, $subtotal + $deliveryFee - $discountAmount);
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(4));
        $paymentMethod = $validated['payment_method'] ?? 'Transfer Bank BCA';

        // Auto-generate Resi Pembelian / Tracking Number
        $courierPrefix = Str::upper(substr(preg_replace('/[^a-zA-Z]/', '', $courierName), 0, 3));
        $trackingNumber = ($courierPrefix ?: 'MD') . '-' . date('Ymd') . '-' . rand(1000, 9999);

        // Format alamat lengkap dengan Kota & Kode Pos jika tersedia
        $finalAddress = $validated['address'] ?? 'Store Pick up';
        if ($validated['delivery_option'] === 'delivery') {
            $addr = trim($validated['address'] ?? '');
            $city = trim($request->input('city', ''));
            $postalCode = trim($request->input('postal_code', ''));

            $extras = [];
            if ($city !== '' && !str_contains(strtolower($addr), strtolower($city))) {
                $extras[] = $city;
            }
            if ($postalCode !== '' && !str_contains($addr, $postalCode)) {
                $extras[] = 'Kode Pos: ' . $postalCode;
            }
            if (!empty($extras)) {
                $finalAddress = $addr . ' (' . implode(', ', $extras) . ')';
            } else {
                $finalAddress = $addr;
            }
        }

        // Format Catatan Pesanan & Kartu Ucapan Hantaran
        $noteSections = [];

        // 1. Kartu Ucapan Hantaran
        $cardTo = trim($request->input('card_to', ''));
        $cardFrom = trim($request->input('card_from', ''));
        $cardMsg = trim($request->input('card_message', ''));
        if ($cardTo !== '' || $cardFrom !== '' || $cardMsg !== '') {
            $cardText = "🎁 [KARTU UCAPAN HAMPERS]\n";
            if ($cardTo !== '') $cardText .= "Kepada: " . $cardTo . "\n";
            if ($cardFrom !== '') $cardText .= "Dari: " . $cardFrom . "\n";
            if ($cardMsg !== '') $cardText .= "Pesan: " . $cardMsg;
            $noteSections[] = trim($cardText);
        }

        // 2. Penerima Hadiah Berbeda
        if ($request->input('is_gift') == '1') {
            $recName = trim($request->input('recipient_name', ''));
            $recPhone = trim($request->input('recipient_phone', ''));
            if ($recName !== '' || $recPhone !== '') {
                $giftText = "🎀 [KIRIM SEBAGAI HADIAH (PENERIMA BERBEDA)]\n";
                if ($recName !== '') $giftText .= "Nama Penerima: " . $recName . "\n";
                if ($recPhone !== '') $giftText .= "No. Telepon / WA: " . $recPhone . "\n";
                $giftText .= "(Catatan Toko: Jangan sertakan nota harga fisik dalam paket)";
                $noteSections[] = trim($giftText);
            }
        }

        // 3. Patokan Alamat Pengiriman
        $addrNotes = trim($request->input('address_notes', ''));
        if ($addrNotes !== '') {
            $noteSections[] = "📍 [PATOKAN ALAMAT]: " . $addrNotes;
        }

        // 4. Catatan Pesanan Tambahan (dari form checkout atau keranjang)
        $customNote = trim($request->input('custom_order_note', ''));
        $oldNote = trim($request->input('order_note', ''));
        $freeNote = $customNote !== '' ? $customNote : $oldNote;
        if ($freeNote !== '' && $freeNote !== '-') {
            $noteSections[] = "📝 [CATATAN TAMBAHAN]: " . $freeNote;
        }

        $finalOrderNote = !empty($noteSections) ? implode("\n\n", $noteSections) : null;

        // Eksekusi secara atomik dalam transaksi database
        $order = DB::transaction(function () use (
            $validated,
            $invoiceNumber,
            $courierName,
            $deliveryFee,
            $subtotal,
            $totalAmount,
            $paymentMethod,
            $trackingNumber,
            $cart,
            $couponToIncrement,
            $finalAddress,
            $finalOrderNote
        ) {
            $order = Order::create([
                'user_id' => auth()->check() ? auth()->id() : null,
                'invoice_number' => $invoiceNumber,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'] ?? null,
                'customer_phone' => $validated['customer_phone'],
                'delivery_option' => $validated['delivery_option'],
                'courier_name' => $courierName,
                'delivery_date' => $validated['delivery_date'],
                'delivery_fee' => $deliveryFee,
                'address' => $finalAddress,
                'order_note' => $finalOrderNote,
                'subtotal' => $subtotal,
                'total_amount' => $totalAmount,
                'payment_method' => $paymentMethod,
                'tracking_number' => $trackingNumber,
                'payment_status' => 'unpaid',
                'order_status' => 'pending',
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            if ($couponToIncrement) {
                $couponToIncrement->increment('used_count');
            }

            return $order;
        });

        // Clear cart and coupon session
        session()->forget('cart');
        session()->forget('applied_coupon');

        return redirect()->route('order.show', $order->invoice_number)
            ->with('success', 'Pesanan Anda telah berhasil dibuat!');
    }

    public function showOrder($invoice)
    {
        $order = Order::with(['items', 'items.product'])->where('invoice_number', $invoice)->firstOrFail();

        // Build WhatsApp Confirmation Message
        $itemsText = "";
        foreach ($order->items as $item) {
            $itemsText .= "• " . $item->product_name . " (" . $item->quantity . "x) - Rp " . number_format($item->subtotal, 0, ',', '.') . "\n";
        }

        $waMessage = "Halo PUSAT HAMPERS INDONESIA,\n"
            . "Saya ingin mengirimkan bukti pembayaran untuk pesanan:\n\n"
            . "*No. Invoice:* " . $order->invoice_number . "\n"
            . "*Nama:* " . $order->customer_name . "\n"
            . "*No. WA:* " . $order->customer_phone . "\n"
            . "*Opsi:* " . strtoupper($order->delivery_option) . "\n"
            . "*Tanggal:* " . date('d M Y', strtotime($order->delivery_date)) . "\n\n"
            . "*Detail Pesanan:*\n" . $itemsText . "\n"
            . "*Total Bayar:* Rp " . number_format($order->total_amount, 0, ',', '.') . "\n\n"
            . "Berikut terlampir bukti transfer saya. Mohon diproses, terima kasih!";

        $waNumber = "62811152282";
        $waLink = "https://wa.me/" . $waNumber . "?text=" . urlencode($waMessage);

        return view('order_detail', compact('order', 'waLink'));
    }

    public function printInvoice(string $invoice)
    {
        $order = Order::with(['items', 'items.product'])->where('invoice_number', $invoice)->firstOrFail();
        return view('order-invoice-pdf', compact('order'));
    }

    public function payMidtrans(Request $request, $invoice)
    {
        $order = Order::where('invoice_number', $invoice)->firstOrFail();

        $paySettings = \App\Http\Controllers\Admin\AdminPaymentController::getPaymentSettings();
        $serverKey = $paySettings['midtrans_server_key'] ?? '';
        $mode = $paySettings['midtrans_mode'] ?? 'sandbox';

        $baseUrl = ($mode === 'production') 
            ? 'https://api.midtrans.com/v2/' 
            : 'https://api.sandbox.midtrans.com/v2/';

        $isRealVerified = false;
        $statusMessage = '';

        if (!empty($serverKey)) {
            try {
                $response = \Illuminate\Support\Facades\Http::withBasicAuth($serverKey, '')
                    ->timeout(8)
                    ->get($baseUrl . $order->invoice_number . '/status');

                if ($response->successful()) {
                    $statusData = $response->json();
                    $transactionStatus = $statusData['transaction_status'] ?? '';
                    $fraudStatus = $statusData['fraud_status'] ?? '';

                    if (in_array($transactionStatus, ['capture', 'settlement'])) {
                        if (!($transactionStatus == 'capture' && $fraudStatus == 'challenge')) {
                            $isRealVerified = true;
                        }
                    } else {
                        $statusMessage = 'Status di gateway pembayaran masih: ' . strtoupper($transactionStatus ?: 'pending');
                    }
                } else {
                    $statusMessage = 'Mutasi pembayaran belum ditemukan di server Midtrans.';
                }
            } catch (\Throwable $e) {
                // If API call fails or times out
                $isRealVerified = false;
                $statusMessage = 'Gagal memverifikasi status pembayaran Midtrans: ' . $e->getMessage();
            }
        } else {
            // Server key is not configured, customer must upload payment proof
            $isRealVerified = false;
            $statusMessage = 'Midtrans belum dikonfigurasi. Silakan unggah bukti transfer di formulir faktur.';
        }

        if ($isRealVerified) {
            $order->update([
                'payment_status' => 'paid',
                'order_status' => ($order->order_status === 'pending') ? 'processing' : $order->order_status,
                'payment_method' => 'Midtrans Gateway (Verifikasi Otomatis)',
            ]);

            if (empty($order->tracking_number)) {
                $prefix = ($order->delivery_option === 'pickup') ? 'PICK' : 'MID';
                $order->update([
                    'tracking_number' => $prefix . '-' . date('Ymd') . '-' . rand(1000, 9999)
                ]);
            }

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran pesanan ' . $order->invoice_number . ' telah BERHASIL DIVERIFIKASI secara otomatis oleh sistem!',
                    'redirect_url' => route('order.show', $order->invoice_number),
                ]);
            }

            return redirect()->route('order.show', $order->invoice_number)
                ->with('success', 'Pembayaran pesanan ' . $order->invoice_number . ' telah BERHASIL DIVERIFIKASI!');
        }

        // Return error if payment has NOT been received in bank/gateway mutation
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => '⚠️ Pembayaran Belum Diterima: ' . ($statusMessage ?: 'Mutasi rekening / QRIS belum ditemukan di sistem. Harap selesaikan pembayaran terlebih dahulu.'),
            ], 422);
        }

        return redirect()->route('order.show', $order->invoice_number)
            ->with('error', '⚠️ Pembayaran Belum Diterima: ' . ($statusMessage ?: 'Mutasi rekening / QRIS belum ditemukan di sistem.'));
    }

    public function checkStatus($invoice)
    {
        $order = Order::where('invoice_number', $invoice)->first();
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        return response()->json([
            'success' => true,
            'payment_status' => $order->payment_status,
            'order_status' => $order->order_status,
            'is_paid' => in_array($order->payment_status, ['paid', 'verified']),
            'invoice_number' => $order->invoice_number,
        ]);
    }

    public function midtransNotification(Request $request)
    {
        $orderId = $request->input('order_id') ?? $request->input('invoice_number');
        $statusCode = $request->input('status_code');
        $grossAmount = $request->input('gross_amount');
        $signatureKey = $request->input('signature_key');
        $transactionStatus = $request->input('transaction_status');
        $fraudStatus = $request->input('fraud_status');

        if (!$orderId) {
            return response()->json(['error' => 'Missing order ID'], 400);
        }

        $paySettings = \App\Http\Controllers\Admin\AdminPaymentController::getPaymentSettings();
        $serverKey = $paySettings['midtrans_server_key'] ?? '';

        // Verifikasi keaslian signature jika server key dikonfigurasi
        if (!empty($serverKey) && !empty($signatureKey) && !empty($statusCode) && !empty($grossAmount)) {
            $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
            if ($signatureKey !== $expectedSignature) {
                return response()->json(['error' => 'Signature key invalid'], 403);
            }
        }

        $order = Order::where('invoice_number', $orderId)->first();
        if ($order) {
            DB::transaction(function () use ($order, $transactionStatus, $fraudStatus) {
                if (in_array($transactionStatus, ['capture', 'settlement'])) {
                    if ($transactionStatus == 'capture' && $fraudStatus == 'challenge') {
                        $order->update(['payment_status' => 'unpaid']);
                    } else {
                        $order->update([
                            'payment_status' => 'paid',
                            'order_status' => ($order->order_status === 'pending') ? 'processing' : $order->order_status,
                            'payment_method' => 'Midtrans Gateway',
                        ]);
                    }
                } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                    $order->update(['payment_status' => 'failed', 'order_status' => 'cancelled']);
                } elseif ($transactionStatus == 'pending') {
                    $order->update(['payment_status' => 'unpaid']);
                }
            });
        }

        return response()->json(['status' => 'ok']);
    }

    public function uploadPaymentProof(Request $request, $invoice)
    {
        $order = Order::where('invoice_number', $invoice)->firstOrFail();

        $request->validate([
            'payment_proof' => 'required|file|mimes:jpeg,png,jpg,webp,pdf|max:5120',
        ], [
            'payment_proof.required' => 'Silakan pilih foto struk atau file bukti transfer terlebih dahulu.',
            'payment_proof.mimes' => 'Format file bukti pembayaran harus berupa gambar (JPG, PNG, WEBP) atau PDF.',
            'payment_proof.max' => 'Ukuran file bukti pembayaran maksimal adalah 5MB.',
        ]);

        $file = $request->file('payment_proof');
        $extension = $file->extension() ?: ($file->getClientOriginalExtension() ?: 'jpg');
        $fileName = 'proof_' . Str::slug($order->invoice_number) . '_' . time() . '.' . $extension;

        $destinationPath = public_path('uploads/payment_proofs');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $file->move($destinationPath, $fileName);

        $order->update([
            'payment_proof' => $fileName,
            'payment_proof_uploaded_at' => now(),
            'payment_status' => 'waiting_verification',
        ]);

        return redirect()->route('order.show', $order->invoice_number)
            ->with('success', '✅ Bukti pembayaran berhasil diunggah! Mohon menunggu verifikasi dari admin butik.');
    }

    public function myOrders()
    {
        $orders = Order::with('items')
            ->where('user_id', auth()->id())
            ->orderBy('id', 'desc')
            ->paginate(8);

        return view('my_orders', compact('orders'));
    }
}
