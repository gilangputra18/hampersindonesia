<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'type', 'value',
        'min_purchase', 'max_discount',
        'usage_limit', 'used_count',
        'valid_from', 'valid_until',
        'is_active', 'description',
    ];

    protected $casts = [
        'valid_from'   => 'date',
        'valid_until'  => 'date',
        'is_active'    => 'boolean',
        'value'        => 'integer',
        'min_purchase' => 'integer',
        'max_discount' => 'integer',
        'usage_limit'  => 'integer',
        'used_count'   => 'integer',
    ];

    /** Hitung besaran diskon berdasarkan subtotal */
    public function calculateDiscount(int $subtotal): int
    {
        if ($this->type === 'percent') {
            $discount = (int) round($subtotal * $this->value / 100);
            if ($this->max_discount) {
                $discount = min($discount, $this->max_discount);
            }
            return $discount;
        }

        // fixed amount
        return min($this->value, $subtotal);
    }

    /** Cek apakah kupon ini valid untuk subtotal tertentu */
    public function isValidFor(int $subtotal): bool
    {
        if (!$this->is_active) return false;
        if ($subtotal < $this->min_purchase) return false;
        if ($this->valid_from && now()->lt($this->valid_from->startOfDay())) return false;
        if ($this->valid_until && now()->gt($this->valid_until->endOfDay())) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        return true;
    }

    /** Label diskon untuk display */
    public function getDiscountLabelAttribute(): string
    {
        if ($this->type === 'percent') {
            $label = "Diskon {$this->value}%";
            if ($this->max_discount) {
                $label .= " (maks Rp " . number_format($this->max_discount, 0, ',', '.') . ")";
            }
            return $label;
        }
        return "Potongan Rp " . number_format($this->value, 0, ',', '.');
    }
}
