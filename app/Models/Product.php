<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'price',
        'cost_price',
        'image',
        'gallery',
        'description',
        'type',
        'flavor',
        'size',
        'availability',
        'is_best_seller',
        'is_treat',
        'display_order',
    ];

    protected $casts = [
        'gallery' => 'array',
        'is_best_seller' => 'boolean',
        'is_treat' => 'boolean',
    ];

    protected $appends = [
        'included_items',
        'image_url',
        'secondary_image_url',
        'gallery_urls',
    ];

    public function getIncludedItemsAttribute()
    {
        if (!empty($this->attributes['description'])) {
            $lines = array_filter(array_map('trim', explode("\n", $this->attributes['description'])));
            if (count($lines) > 0) {
                return array_values($lines);
            }
        }

        $nameLower = strtolower($this->name);
        $slugLower = strtolower($this->slug);

        // Custom hampers / gift box contents breakdown
        if (str_contains($slugLower, 'rose') || str_contains($nameLower, 'rose')) {
            return [
                '🍪 1 Jar Nastar Pineapple Jam (450g)',
                '🧀 1 Jar Kaastengel Edam Cheese (400g)',
                '🧈 1 Jar French Butter Cookies (350g)',
                '🥮 1 Box Premium Mooncake / Sourdough',
                '🍾 1 Bottle Artisan Sparkling Tea Gourmet',
                '📜 Kartu Ucapan Eksklusif & Hardbox Royal Emerald Gold',
            ];
        }

        if (str_contains($slugLower, 'lily') || str_contains($nameLower, 'lily')) {
            return [
                '🍪 1 Jar Nastar Pineapple Jam (450g)',
                '🧈 1 Jar French Butter Cookies (350g)',
                '🧀 1 Jar Kaastengel Edam Cheese (400g)',
                '🍞 1 Pack Premium Milk Buns (6 pcs)',
                '📜 Kartu Ucapan VIP & Luxury Hardbox Emerald',
            ];
        }

        if (str_contains($slugLower, 'tulip') || str_contains($nameLower, 'tulip')) {
            return [
                '🧈 1 Jar French Butter Cookies (350g)',
                '🍪 1 Jar Nastar Pineapple Jam (450g)',
                '🥐 1 Box Croissant Viennoiserie (4 pcs)',
                '📜 Kartu Ucapan & Gift Box Premium',
            ];
        }

        if (str_contains($slugLower, 'calla') || str_contains($nameLower, 'calla')) {
            return [
                '🧈 1 Jar French Butter Cookies (350g)',
                '🧀 1 Jar Kaastengel Edam Cheese (400g)',
                '🥖 1 Pack Artisan Sourdough Loaf (500g)',
                '📜 Kartu Ucapan & Ribbon Hardbox',
            ];
        }

        if (str_contains($slugLower, 'iris') || str_contains($nameLower, 'iris')) {
            return [
                '🧈 1 Jar French Butter Cookies Jar (350g)',
                '🥐 1 Pack French Butter Croissant (2 pcs)',
                '📜 Kartu Ucapan & Hardbox Gift',
            ];
        }

        if (str_contains($slugLower, 'box-of-6') || str_contains($nameLower, 'box of 6')) {
            return [
                '🥮 1x Single Yolk Lotus Mooncake',
                '🥮 1x Double Yolk Royal Lotus Mooncake',
                '🥮 1x Red Bean Sweet Paste Mooncake',
                '🥮 1x Pandan Leaf Lotus Mooncake',
                '🥮 1x Black Sesame Custard Mooncake',
                '🥮 1x Mixed Nuts & Seeds Mooncake',
                '📜 Hardbox Mooncake Edisi Spesial Festival',
            ];
        }

        if (str_contains($slugLower, 'box-of-4') || str_contains($nameLower, 'box of 4')) {
            return [
                '🥮 1x Single Yolk White Lotus Mooncake',
                '🥮 1x Red Bean Sweet Paste Mooncake',
                '🥮 1x Pandan Leaf Lotus Mooncake',
                '🥮 1x Black Sesame Custard Mooncake',
                '📜 Hardbox Mooncake Edisi Spesial Festival',
            ];
        }

        return [
            '✨ Dibuat segar (freshly baked) secara artisanal dari bahan impor pilihan',
            '📦 Dikemas secara higienis & mewah cocok untuk santapan maupun bingkisan',
            '🌿 Bebas bahan pengawet kimia buatan',
            '⭐ Rasa: ' . ($this->flavor ?: 'Original Gourmet'),
            '📐 Ukuran/Porsi: ' . ($this->size ?: 'Standar Porsi'),
        ];
    }

    public function getEffectiveCostPriceAttribute()
    {
        return $this->cost_price ?: (int) round($this->price * 0.35);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
                return $this->image;
            }
            if (str_starts_with($this->image, 'uploads/') || str_starts_with($this->image, 'images/')) {
                return asset($this->image);
            }
            return asset('images/' . $this->image);
        }

        $slugJpg = 'images/' . $this->slug . '.jpg';
        $slugPng = 'images/' . $this->slug . '.png';
        if (file_exists(public_path($slugJpg))) {
            return asset($slugJpg);
        }
        if (file_exists(public_path($slugPng))) {
            return asset($slugPng);
        }

        return asset('images/cat-cakes.jpg');
    }

    public function getSecondaryImageUrlAttribute()
    {
        if (!empty($this->gallery) && is_array($this->gallery) && count($this->gallery) > 0) {
            $second = $this->gallery[0];
            if (str_starts_with($second, 'http://') || str_starts_with($second, 'https://')) {
                return $second;
            }
            if (str_starts_with($second, 'uploads/') || str_starts_with($second, 'images/')) {
                return asset($second);
            }
            return asset('images/' . $second);
        }

        $catSlug = $this->category->slug ?? 'cakes';
        return asset('images/cat-' . $catSlug . '.jpg');
    }

    public function getGalleryUrlsAttribute()
    {
        $urls = [$this->image_url];
        if (!empty($this->gallery) && is_array($this->gallery)) {
            foreach ($this->gallery as $g) {
                if (str_starts_with($g, 'http://') || str_starts_with($g, 'https://')) {
                    $urls[] = $g;
                } else if (str_starts_with($g, 'uploads/') || str_starts_with($g, 'images/')) {
                    $urls[] = asset($g);
                } else {
                    $urls[] = asset('images/' . $g);
                }
            }
        } else {
            $urls[] = $this->secondary_image_url;
        }
        return array_values(array_unique($urls));
    }

    public function getAvailabilityLabelAttribute()
    {
        return match ($this->availability) {
            'pre_order' => 'Pre-Order',
            'out_of_stock' => 'Habis',
            default => 'In Stock',
        };
    }

    public function scopeFilter($query, array $filters)
    {
        if (!empty($filters['category'])) {
            $query->whereHas('category', function ($q) use ($filters) {
                $q->where('slug', $filters['category']);
            });
        }

        if (!empty($filters['types']) && is_array($filters['types'])) {
            $query->whereIn('type', $filters['types']);
        } elseif (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['flavors']) && is_array($filters['flavors'])) {
            $query->whereIn('flavor', $filters['flavors']);
        } elseif (!empty($filters['flavor'])) {
            $query->where('flavor', $filters['flavor']);
        }

        if (!empty($filters['sizes']) && is_array($filters['sizes'])) {
            $query->whereIn('size', $filters['sizes']);
        } elseif (!empty($filters['size'])) {
            $query->where('size', $filters['size']);
        }

        if (!empty($filters['availabilities']) && is_array($filters['availabilities'])) {
            $query->whereIn('availability', $filters['availabilities']);
        } elseif (!empty($filters['availability'])) {
            $query->where('availability', $filters['availability']);
        }

        if (isset($filters['min_price']) && is_numeric($filters['min_price'])) {
            $query->where('price', '>=', (int) $filters['min_price']);
        }

        if (isset($filters['max_price']) && is_numeric($filters['max_price'])) {
            $query->where('price', '<=', (int) $filters['max_price']);
        }

        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'latest':
                default:
                    $query->orderBy('id', 'desc');
                    break;
            }
        } else {
            $query->orderBy('display_order', 'asc')->orderBy('id', 'desc');
        }

        return $query;
    }
}
