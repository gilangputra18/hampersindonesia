<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'subtitle',
        'description',
        'image',
        'whatsapp_number',
        'whatsapp_text',
        'pdf_path',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

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
        return asset('images/reservation-dinein.jpg');
    }

    public function getPdfUrlAttribute()
    {
        if ($this->pdf_path && file_exists(public_path($this->pdf_path))) {
            return asset($this->pdf_path);
        }
        return route('menu.pdf', $this->slug);
    }
}
