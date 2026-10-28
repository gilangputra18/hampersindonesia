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
        'thumbnail_url',
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

        if (str_contains($slugLower, 'moon hampers') || str_contains($nameLower, 'moon hampers') || str_contains($slugLower, 'moon-hampers')) {
            return [
                '🏮 1x Exclusive Imperial Lantern Gift Box with Ambient Light',
                '🥮 2x Royal White Lotus Mooncake (Single Yolk)',
                '🥮 2x Signature Red Bean Sweet Paste Mooncake',
                '🍵 1x Premium Artisan Osmanthus Oolong Tea Tin',
                '📜 Kartu Ucapan Eksklusif & Pita Satin Gold',
            ];
        }

        if (str_contains($slugLower, 'yue') || str_contains($nameLower, 'yue')) {
            return [
                '🥮 1x Royal Double Yolk White Lotus Mooncake',
                '🥮 1x Traditional Pandan Jade Mooncake',
                '🥮 1x Sweet Red Bean & Custard Mooncake',
                '🥮 1x Black Sesame Golden Walnut Mooncake',
                '🍵 1x Artisan Jasmine Dragon Pearl Tea (50g)',
                '📜 Hardbox Mooncake Edisi Spesial Festival Yue',
            ];
        }

        if (str_contains($slugLower, 'kue-bulan') || str_contains($nameLower, 'kue bulan') || str_contains($nameLower, 'mooncake')) {
            return [
                '🥮 4x Mooncake Klasik Pilihan (Lotus, Red Bean, Pandan, Sesame)',
                '🍪 1x Jar Butter Cookies Spesial Festival',
                '🍵 1x Premium Roasted Tie Guan Yin Oolong Tea',
                '📜 Kartu Ucapan Festival & Luxury Box',
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

    public function reviews()
    {
        return $this->hasMany(ProductReview::class)->where('is_approved', true);
    }

    public function getImageUrlAttribute()
    {
        return $this->resolveSafeImageUrl($this->image, true);
    }

    public function getThumbnailUrlAttribute(): string
    {
        $val = $this->image;
        if (!empty($val) && is_string($val)) {
            $baseName = basename(ltrim($val, '/'));
            $thumbPath = 'images/thumbs/' . $baseName;
            if (file_exists(public_path($thumbPath))) {
                return asset($thumbPath);
            }
        }

        if (!empty($this->slug)) {
            $thumbSlug = 'images/thumbs/' . $this->slug . '.jpg';
            if (file_exists(public_path($thumbSlug))) {
                return asset($thumbSlug);
            }
        }

        return $this->image_url;
    }

    public function getSecondaryImageUrlAttribute()
    {
        if (!empty($this->gallery) && is_array($this->gallery) && count($this->gallery) > 0) {
            $second = $this->gallery[0];
            return $this->resolveSafeImageUrl($second, false);
        }

        return $this->image_url;
    }

    public function getGalleryUrlsAttribute()
    {
        $urls = [$this->image_url];
        if (!empty($this->gallery) && is_array($this->gallery)) {
            foreach ($this->gallery as $g) {
                $resolved = $this->resolveSafeImageUrl($g, false);
                if ($resolved && !in_array($resolved, $urls)) {
                    $urls[] = $resolved;
                }
            }
        }
        return array_values(array_unique($urls));
    }

    public function resolveSafeImageUrl($val, bool $isPrimary = true): string
    {
        // 1. External HTTP/HTTPS URL (filter out unrenderable .heic/.heif)
        if (!empty($val) && is_string($val) && (str_starts_with($val, 'http://') || str_starts_with($val, 'https://'))) {
            if (!preg_match('/\.(heic|heif)$/i', $val)) {
                return $val;
            }
        }

        // Known static library of existing images in public/images/
        $knownImages = [
            'cat-hampers.jpg', 'cat-mooncake.jpg', 'cat-cakes.jpg', 'cat-cookies.jpg',
            'cat-breads.jpg', 'cat-light-bites.jpg', 'box-of-6.jpg', 'box-of-4.jpg',
            'rose.jpg', 'lily.jpg', 'tulip.jpg', 'calla.jpg', 'iris.jpg',
            'basque-burnt-cheesecake.jpg', 'berry-tart.jpg', 'black-forest.jpg',
            'tiramisu.jpg', 'chocolate-hazelnut.jpg', 'butter-cookies-jar.jpg',
            'nastar-pineapple.jpg', 'kaastengel.jpg', 'croissant.jpg', 'sourdough-loaf.jpg',
            'milk-bun.jpg', 'focaccia-chicken-pesto-sandwich.jpg', 'arem-arem.jpg',
            'bacang.jpg', 'mini-bacang.jpg', 'chicken-lemper.jpg', 'onigiri.jpg',
            'mini-burger.jpg', 'tortilla.jpg', 'bitterballen.jpg', 'rice-dumpling.jpg',
            'events.jpg', 'feature.jpg', 'hero-poster.jpg'
        ];

        // 2. Local file path check (filter out .heic/.heif)
        if (!empty($val) && is_string($val)) {
            $isHeic = preg_match('/\.(heic|heif)$/i', $val);
            if (!$isHeic) {
                $clean = ltrim($val, '/');

                if (str_starts_with($clean, 'uploads/')) {
                    return asset($clean);
                }
                if (str_starts_with($clean, 'images/')) {
                    $baseName = basename($clean);
                    if (in_array($baseName, $knownImages)) {
                        return asset('images/' . $baseName);
                    }
                    if (file_exists(public_path($clean))) {
                        return asset($clean);
                    }
                }

                $baseName = basename($clean);
                if (in_array($baseName, $knownImages)) {
                    return asset('images/' . $baseName);
                }

                if (file_exists(public_path('images/' . $clean))) {
                    return asset('images/' . $clean);
                }
                if (file_exists(public_path($clean))) {
                    return asset($clean);
                }
            }
        }

        // 3. Fallback to slug.jpg or slug.png if exists
        if (!empty($this->slug)) {
            $slugBase = $this->slug . '.jpg';
            if (in_array($slugBase, $knownImages)) {
                return asset('images/' . $slugBase);
            }
            if (file_exists(public_path('images/' . $slugBase))) {
                return asset('images/' . $slugBase);
            }
        }

        // 4. Smart contextual fallback by keyword matching on product name / slug / category
        $nameLower = strtolower(($this->name ?? '') . ' ' . ($this->slug ?? ''));
        $catLower = strtolower($this->category->slug ?? '');

        // Mooncake & Lantern keywords
        if (str_contains($nameLower, 'yue') || str_contains($nameLower, 'box of 4') || str_contains($nameLower, 'box-of-4')) {
            return asset('images/box-of-4.jpg');
        }
        if (str_contains($nameLower, 'box of 6') || str_contains($nameLower, 'box-of-6')) {
            return asset('images/box-of-6.jpg');
        }
        if (str_contains($nameLower, 'moon') || str_contains($nameLower, 'bulan') || $catLower === 'mooncake') {
            return asset($isPrimary ? 'images/cat-mooncake.jpg' : 'images/box-of-6.jpg');
        }

        // Luxury Hampers keywords
        if (str_contains($nameLower, 'rose')) {
            return asset('images/rose.jpg');
        }
        if (str_contains($nameLower, 'lily')) {
            return asset('images/lily.jpg');
        }
        if (str_contains($nameLower, 'tulip')) {
            return asset('images/tulip.jpg');
        }
        if (str_contains($nameLower, 'calla')) {
            return asset('images/calla.jpg');
        }
        if (str_contains($nameLower, 'iris')) {
            return asset('images/iris.jpg');
        }
        if (str_contains($nameLower, 'hamper') || $catLower === 'hampers') {
            return asset($isPrimary ? 'images/cat-hampers.jpg' : 'images/rose.jpg');
        }

        // Cookies keywords
        if (str_contains($nameLower, 'nastar') || str_contains($nameLower, 'pineapple')) {
            return asset('images/nastar-pineapple.jpg');
        }
        if (str_contains($nameLower, 'kaas') || str_contains($nameLower, 'edam')) {
            return asset('images/kaastengel.jpg');
        }
        if (str_contains($nameLower, 'cookie') || str_contains($nameLower, 'butter') || $catLower === 'cookies') {
            return asset('images/butter-cookies-jar.jpg');
        }

        // Cake keywords
        if (str_contains($nameLower, 'cheese') || str_contains($nameLower, 'burnt')) {
            return asset('images/basque-burnt-cheesecake.jpg');
        }
        if (str_contains($nameLower, 'forest')) {
            return asset('images/black-forest.jpg');
        }
        if (str_contains($nameLower, 'tiramisu')) {
            return asset('images/tiramisu.jpg');
        }
        if (str_contains($nameLower, 'tart') || str_contains($nameLower, 'berry')) {
            return asset('images/berry-tart.jpg');
        }
        if (str_contains($nameLower, 'choco')) {
            return asset('images/chocolate-hazelnut.jpg');
        }
        if ($catLower === 'cakes') {
            return asset('images/cat-cakes.jpg');
        }

        // Breads keywords
        if (str_contains($nameLower, 'croissant')) {
            return asset('images/croissant.jpg');
        }
        if (str_contains($nameLower, 'sourdough')) {
            return asset('images/sourdough-loaf.jpg');
        }
        if (str_contains($nameLower, 'milk bun') || str_contains($nameLower, 'bun')) {
            return asset('images/milk-bun.jpg');
        }
        if ($catLower === 'breads') {
            return asset('images/cat-breads.jpg');
        }

        // Light bites / savory
        if (str_contains($nameLower, 'bacang')) {
            return asset('images/bacang.jpg');
        }
        if (str_contains($nameLower, 'lemper')) {
            return asset('images/chicken-lemper.jpg');
        }
        if (str_contains($nameLower, 'arem')) {
            return asset('images/arem-arem.jpg');
        }
        if (str_contains($nameLower, 'onigiri')) {
            return asset('images/onigiri.jpg');
        }
        if (str_contains($nameLower, 'sandwich') || str_contains($nameLower, 'focaccia')) {
            return asset('images/focaccia-chicken-pesto-sandwich.jpg');
        }
        if (str_contains($nameLower, 'burger')) {
            return asset('images/mini-burger.jpg');
        }
        if (str_contains($nameLower, 'tortilla')) {
            return asset('images/tortilla.jpg');
        }
        if (str_contains($nameLower, 'bitterballen')) {
            return asset('images/bitterballen.jpg');
        }
        if (str_contains($nameLower, 'dumpling')) {
            return asset('images/rice-dumpling.jpg');
        }

        // 5. Ultimate safe fallback
        return asset('images/cat-hampers.jpg');
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
