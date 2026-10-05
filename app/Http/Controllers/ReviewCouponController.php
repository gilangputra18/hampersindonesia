<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\ProductReview;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewCouponController extends Controller
{
    // ─────────────────────────────────────────────────────────────────
    //  REVIEW: Submit ulasan produk
    // ─────────────────────────────────────────────────────────────────
    public function submitReview(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        $validated = $request->validate([
            'reviewer_name'  => 'required|string|max:80',
            'reviewer_email' => 'nullable|email|max:100',
            'rating'         => 'required|integer|min:1|max:5',
            'title'          => 'nullable|string|max:120',
            'body'           => 'nullable|string|max:2000',
        ], [
            'rating.required' => 'Pilih rating bintang terlebih dahulu.',
            'rating.min'      => 'Rating minimal 1 bintang.',
            'rating.max'      => 'Rating maksimal 5 bintang.',
        ]);

        ProductReview::create([
            'product_id'     => $product->id,
            'user_id'        => auth()->id(),
            'reviewer_name'  => $validated['reviewer_name'],
            'reviewer_email' => $validated['reviewer_email'] ?? null,
            'rating'         => $validated['rating'],
            'title'          => $validated['title'] ?? null,
            'body'           => $validated['body'] ?? null,
            'is_approved'    => false, // Perlu disetujui admin
        ]);

        return back()->with('review_success', 'Ulasan Anda telah dikirim dan sedang menunggu moderasi. Terima kasih! 🎉');
    }

    // ─────────────────────────────────────────────────────────────────
    //  COUPON: Validasi & terapkan kode kupon via AJAX
    // ─────────────────────────────────────────────────────────────────
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code'     => 'required|string',
            'subtotal' => 'required|integer|min:0',
        ]);

        $code     = strtoupper(trim($request->code));
        $subtotal = (int) $request->subtotal;

        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => "Kode kupon \"$code\" tidak ditemukan."]);
        }

        if (!$coupon->isValidFor($subtotal)) {
            if (!$coupon->is_active) {
                $msg = "Kode kupon \"$code\" sudah tidak aktif.";
            } elseif ($subtotal < $coupon->min_purchase) {
                $msg = "Minimum pembelian Rp " . number_format($coupon->min_purchase, 0, ',', '.') . " untuk menggunakan kupon ini.";
            } elseif ($coupon->valid_until && now()->gt($coupon->valid_until)) {
                $msg = "Kode kupon \"$code\" sudah kadaluarsa.";
            } elseif ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
                $msg = "Kupon \"$code\" sudah habis digunakan.";
            } else {
                $msg = "Kode kupon \"$code\" tidak dapat digunakan saat ini.";
            }
            return response()->json(['success' => false, 'message' => $msg]);
        }

        $discount = $coupon->calculateDiscount($subtotal);

        // Simpan kupon di session
        session(['applied_coupon' => [
            'code'     => $coupon->code,
            'type'     => $coupon->type,
            'value'    => $coupon->value,
            'discount' => $discount,
            'label'    => $coupon->discount_label,
        ]]);

        return response()->json([
            'success'       => true,
            'message'       => "🎉 Kupon \"{$coupon->code}\" berhasil diterapkan! {$coupon->discount_label}.",
            'discount'      => $discount,
            'discount_fmt'  => 'Rp ' . number_format($discount, 0, ',', '.'),
            'label'         => $coupon->discount_label,
            'code'          => $coupon->code,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────
    //  COUPON: Hapus kupon dari session
    // ─────────────────────────────────────────────────────────────────
    public function removeCoupon(Request $request)
    {
        session()->forget('applied_coupon');
        return response()->json(['success' => true, 'message' => 'Kupon berhasil dihapus.']);
    }
}
