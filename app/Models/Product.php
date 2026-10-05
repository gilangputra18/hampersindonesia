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
        'is_best_seller' => 'boolean',
        'is_treat' => 'boolean',
        'price' => 'integer',
        'cost_price' => 'integer',
        'gallery' => 'array',
    ];

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
