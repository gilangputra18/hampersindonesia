<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class AdminCouponReviewController extends Controller
{
    // ─────────────────────────────────────────────────────────────────
    //  COUPONS
    // ─────────────────────────────────────────────────────────────────
    public function couponsIndex()
    {
        $coupons = Coupon::orderBy('id', 'desc')->paginate(15);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function couponStore(Request $request)
    {
        $validated = $request->validate([
            'code'         => 'required|string|max:30|unique:coupons,code',
            'type'         => 'required|in:percent,fixed',
            'value'        => 'required|integer|min:1',
            'min_purchase' => 'nullable|integer|min:0',
            'max_discount' => 'nullable|integer|min:0',
            'usage_limit'  => 'nullable|integer|min:1',
            'valid_from'   => 'nullable|date',
            'valid_until'  => 'nullable|date|after_or_equal:valid_from',
            'description'  => 'nullable|string|max:255',
            'is_active'    => 'nullable|boolean',
        ]);

        Coupon::create([
            'code'         => strtoupper($validated['code']),
            'type'         => $validated['type'],
            'value'        => $validated['value'],
            'min_purchase' => $validated['min_purchase'] ?? 0,
            'max_discount' => $validated['max_discount'] ?? null,
            'usage_limit'  => $validated['usage_limit'] ?? null,
            'valid_from'   => $validated['valid_from'] ?? null,
            'valid_until'  => $validated['valid_until'] ?? null,
            'description'  => $validated['description'] ?? null,
            'is_active'    => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Kupon berhasil ditambahkan!');
    }

    public function couponToggle(Coupon $coupon)
    {
        $coupon->update(['is_active' => !$coupon->is_active]);
        $status = $coupon->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Kupon {$coupon->code} berhasil {$status}.");
    }

    public function couponDestroy(Coupon $coupon)
    {
        $code = $coupon->code;
        $coupon->delete();
        return back()->with('success', "Kupon {$code} berhasil dihapus.");
    }

    // ─────────────────────────────────────────────────────────────────
    //  REVIEWS
    // ─────────────────────────────────────────────────────────────────
    public function reviewsIndex(Request $request)
    {
        $query = ProductReview::with(['product', 'user'])->orderBy('id', 'desc');

        if ($request->filled('status')) {
            $query->where('is_approved', $request->status === 'approved');
        }
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->paginate(15)->withQueryString();
        $pendingCount = ProductReview::where('is_approved', false)->count();
        $products = Product::orderBy('name')->get();

        return view('admin.reviews.index', compact('reviews', 'pendingCount', 'products'));
    }

    public function reviewStore(Request $request)
    {
        $validated = $request->validate([
            'product_id'    => 'required|exists:products,id',
            'reviewer_name'  => 'required|string|max:100',
            'reviewer_email' => 'nullable|email|max:150',
            'rating'        => 'required|integer|between:1,5',
            'title'         => 'nullable|string|max:150',
            'body'          => 'required|string',
            'avatar_file'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'avatar_url'    => 'nullable|string|max:255',
            'is_approved'   => 'nullable|boolean',
            'is_featured'   => 'nullable|boolean',
        ]);

        $avatarName = null;
        if ($request->hasFile('avatar_file')) {
            $file = $request->file('avatar_file');
            $avatarName = 'avatar-' . time() . '-' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $avatarName);
        } elseif (!empty($validated['avatar_url'])) {
            $avatarName = $validated['avatar_url'];
        }

        ProductReview::create([
            'product_id'    => $validated['product_id'],
            'reviewer_name'  => $validated['reviewer_name'],
            'reviewer_email' => $validated['reviewer_email'] ?? null,
            'avatar'        => $avatarName,
            'rating'        => $validated['rating'],
            'title'         => $validated['title'] ?? null,
            'body'          => $validated['body'],
            'is_approved'   => $request->has('is_approved') ? true : true, // Default approved when created by admin
            'is_featured'   => $request->has('is_featured'),
        ]);

        return back()->with('success', 'Ulasan produk baru dengan foto pembeli berhasil ditambahkan!');
    }

    public function reviewApprove(ProductReview $review)
    {
        $review->update(['is_approved' => !$review->is_approved]);
        $status = $review->is_approved ? 'disetujui' : 'disembunyikan';
        return back()->with('success', "Ulasan oleh {$review->reviewer_name} berhasil {$status}.");
    }

    public function reviewToggleFeatured(ProductReview $review)
    {
        $review->update(['is_featured' => !$review->is_featured]);
        return back()->with('success', 'Status unggulan ulasan diperbarui.');
    }

    public function reviewDestroy(ProductReview $review)
    {
        $review->delete();
        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
