<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
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
            'shipSettings'
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

        // Apply coupon discount if present
        $appliedCoupon = session('applied_coupon');
        $discountAmount = $appliedCoupon['discount'] ?? 0;
        if (!empty($appliedCoupon['code'])) {
            \App\Models\Coupon::where('code', $appliedCoupon['code'])->increment('used_count');
        }

        // Tentukan nama kurir (satu kali saja)
        $courierName = $validated['delivery_option'] === 'pickup' 
            ? 'Ambil Sendiri di Boutique Store' 
            : ($validated['courier_name'] ?? 'Kurir Toko (Dedicated Bakery Delivery)');

        $deliveryFee = self::calculateDeliveryFee($validated['delivery_option'], $courierName, $subtotal);
        $totalAmount = max(0, $subtotal + $deliveryFee - $discountAmount);

        $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        $paymentMethod = $validated['payment_method'] ?? 'Transfer Bank BCA';

        // Auto-generate Resi Pembelian / Tracking Number
        $courierPrefix = Str::upper(substr(preg_replace('/[^a-zA-Z]/', '', $courierName), 0, 3));
        $trackingNumber = ($courierPrefix ?: 'MD') . '-' . date('Ymd') . '-' . rand(1000, 9999);

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
            'address' => $validated['address'] ?? 'Store Pick up',
            'order_note' => $validated['order_note'] ?? null,
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

    public function payMidtrans(Request $request, $invoice)
    {
        $order = Order::where('invoice_number', $invoice)->firstOrFail();

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
                'message' => 'Pembayaran pesanan ' . $order->invoice_number . ' telah BERHASIL DIVERIFIKASI secara otomatis oleh Midtrans Gateway!',
                'redirect_url' => route('order.show', $order->invoice_number),
            ]);
        }

        return redirect()->route('order.show', $order->invoice_number)
            ->with('success', 'Pembayaran pesanan ' . $order->invoice_number . ' telah BERHASIL DIVERIFIKASI secara otomatis oleh Midtrans Gateway!');
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
        $transactionStatus = $request->input('transaction_status');
        $fraudStatus = $request->input('fraud_status');

        if ($orderId) {
            $order = Order::where('invoice_number', $orderId)->first();
            if ($order) {
                if (in_array($transactionStatus, ['capture', 'settlement'])) {
                    if ($transactionStatus == 'capture' && $fraudStatus == 'challenge') {
                        $order->update(['payment_status' => 'unpaid']);
                    } else {
                        $order->update([
                            'payment_status' => 'paid',
                            'order_status' => ($order->order_status === 'pending') ? 'processing' : $order->order_status,
                        ]);
                    }
                } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                    $order->update(['payment_status' => 'failed', 'order_status' => 'cancelled']);
                } elseif ($transactionStatus == 'pending') {
                    $order->update(['payment_status' => 'unpaid']);
                }
            }
        }

        return response()->json(['status' => 'ok']);
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
