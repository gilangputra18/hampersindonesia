<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'user_id', 'order_id',
        'reviewer_name', 'reviewer_email', 'avatar',
        'rating', 'title', 'body',
        'is_approved', 'is_featured',
    ];

    protected $casts = [
        'rating'      => 'integer',
        'is_approved' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected $appends = [
        'avatar_url',
    ];

    public function getAvatarUrlAttribute(): string
    {
        if (!empty($this->avatar)) {
            if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
                return $this->avatar;
            }
            if (str_starts_with($this->avatar, 'uploads/') || str_starts_with($this->avatar, 'images/')) {
                return asset($this->avatar);
            }
            return asset('images/' . $this->avatar);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->reviewer_name ?: 'Pelanggan') . '&background=d97706&color=fff&rounded=true&bold=true';
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /** Render bintang sebagai string ★★★☆☆ */
    public function getStarsAttribute(): string
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }
}
