<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        return view('cart', compact('cart', 'subtotal'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = (int) ($request->quantity ?? 1);

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image_url' => $product->image_url,
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $product->name . ' ditambahkan ke keranjang!',
                'cart_count' => array_sum(array_column($cart, 'quantity')),
            ]);
        }

        return redirect()->route('cart.index')->with('success', $product->name . ' berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'action' => 'required|in:increase,decrease,remove',
        ]);

        $productId = $request->product_id;
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            if ($request->action === 'increase') {
                $cart[$productId]['quantity']++;
            } elseif ($request->action === 'decrease') {
                $cart[$productId]['quantity']--;
                if ($cart[$productId]['quantity'] <= 0) {
                    unset($cart[$productId]);
                }
            } elseif ($request->action === 'remove') {
                unset($cart[$productId]);
            }
        }

        session()->put('cart', $cart);

        if ($request->ajax() || $request->wantsJson()) {
            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            $itemQty = isset($cart[$productId]) ? $cart[$productId]['quantity'] : 0;
            $itemSubtotal = isset($cart[$productId]) ? $cart[$productId]['price'] * $cart[$productId]['quantity'] : 0;
            
            $shipSettings = \App\Http\Controllers\Admin\AdminShippingController::getShippingSettings();
            $freeMin = $shipSettings['free_shipping_min'] ?? 500000;

            return response()->json([
                'success' => true,
                'action' => $request->action,
                'product_id' => $productId,
                'cart' => $cart,
                'subtotal' => $subtotal,
                'subtotal_formatted' => 'Rp ' . number_format($subtotal, 0, ',', '.'),
                'item_quantity' => $itemQty,
                'item_subtotal_formatted' => 'Rp ' . number_format($itemSubtotal, 0, ',', '.'),
                'cart_count' => array_sum(array_column($cart, 'quantity')),
                'is_empty' => count($cart) === 0,
                'is_free_shipping' => ($subtotal >= $freeMin),
            ]);
        }

        return redirect()->route('cart.index');
    }

    public function remove(Request $request)
    {
        $request->validate(['product_id' => 'required']);
        $cart = session()->get('cart', []);
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        if ($request->ajax() || $request->wantsJson()) {
            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            $shipSettings = \App\Http\Controllers\Admin\AdminShippingController::getShippingSettings();
            $freeMin = $shipSettings['free_shipping_min'] ?? 500000;

            return response()->json([
                'success' => true,
                'action' => 'remove',
                'product_id' => $productId,
                'cart' => $cart,
                'subtotal' => $subtotal,
                'subtotal_formatted' => 'Rp ' . number_format($subtotal, 0, ',', '.'),
                'item_quantity' => 0,
                'cart_count' => array_sum(array_column($cart, 'quantity')),
                'is_empty' => count($cart) === 0,
                'is_free_shipping' => ($subtotal >= $freeMin),
            ]);
        }

        return redirect()->route('cart.index');
    }
}
