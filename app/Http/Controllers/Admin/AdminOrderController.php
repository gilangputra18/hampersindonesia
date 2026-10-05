<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['items', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('tracking_number', 'like', "%{$search}%")
                    ->orWhere('courier_name', 'like', "%{$search}%")
                    ->orWhere('payment_method', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        $orders = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        $counts = [
            'all' => Order::count(),
            'pending' => Order::where('order_status', 'pending')->count(),
            'processing' => Order::where('order_status', 'processing')->count(),
            'completed' => Order::where('order_status', 'completed')->count(),
            'cancelled' => Order::where('order_status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'counts'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => 'required|in:pending,processing,completed,cancelled',
            'payment_status' => 'required|in:unpaid,paid,verified',
            'courier_name' => 'nullable|string|max:100',
            'payment_method' => 'nullable|string|max:100',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        $updateData = [
            'order_status' => $request->order_status,
            'payment_status' => $request->payment_status,
        ];

        if ($request->filled('courier_name')) {
            $updateData['courier_name'] = $request->courier_name;
        }

        if ($request->filled('payment_method')) {
            $updateData['payment_method'] = $request->payment_method;
        }

        if ($request->filled('tracking_number')) {
            $updateData['tracking_number'] = $request->tracking_number;
        }

        $order->update($updateData);

        return back()->with('success', 'Detail & status pesanan ' . $order->invoice_number . ' telah diperbarui!');
    }

    public function getNotifications(Request $request)
    {
        $pendingOrdersCount = Order::where('order_status', 'pending')->count();
        $latestOrders = Order::latest()->take(6)->get()->map(function ($order) {
            return [
                'id' => $order->id,
                'invoice_number' => $order->invoice_number,
                'customer_name' => $order->customer_name,
                'total_amount' => $order->total_amount,
                'formatted_total' => 'Rp ' . number_format($order->total_amount, 0, ',', '.'),
                'payment_status' => $order->payment_status,
                'order_status' => $order->order_status,
                'delivery_option' => strtoupper($order->delivery_option),
                'created_at_human' => $order->created_at->diffForHumans(),
                'created_at_time' => $order->created_at->format('H:i, d M'),
                'show_url' => route('order.show', $order->invoice_number),
            ];
        });

        $maxId = Order::max('id') ?? 0;

        return response()->json([
            'pending_count' => $pendingOrdersCount,
            'latest_orders' => $latestOrders,
            'max_id' => $maxId,
        ]);
    }
}
