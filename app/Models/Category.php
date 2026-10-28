<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['slug', 'title', 'image', 'display_order'];

    public function products()
    {
        return $this->hasMany(Product::class)->orderBy('display_order', 'asc')->orderBy('id', 'desc');
    }

    public function getImageUrlAttribute(): string
    {
        if (!empty($this->image)) {
            if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
                return $this->image;
            }
            if (str_starts_with($this->image, '/')) {
                return asset(ltrim($this->image, '/'));
            }
            if (File::exists(public_path('uploads/categories/' . $this->image))) {
                return asset('uploads/categories/' . $this->image);
            }
            if (File::exists(public_path($this->image))) {
                return asset($this->image);
            }
            return asset($this->image);
        }

        // Automatic fallback to existing convention images if available
        $conventionFile = 'images/cat-' . $this->slug . '.jpg';
        if (File::exists(public_path($conventionFile))) {
            return asset($conventionFile);
        }

        return asset('images/cat-hampers.jpg');
    }
}
