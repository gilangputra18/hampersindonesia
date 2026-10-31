<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invoice_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'delivery_option',
        'courier_name',
        'delivery_date',
        'delivery_fee',
        'address',
        'latitude',
        'longitude',
        'order_note',
        'subtotal',
        'total_amount',
        'payment_method',
        'payment_proof',
        'payment_proof_uploaded_at',
        'tracking_number',
        'payment_status',
        'order_status',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'payment_proof_uploaded_at' => 'datetime',
        'subtotal' => 'integer',
        'delivery_fee' => 'integer',
        'total_amount' => 'integer',
    ];

    public function getPaymentProofUrlAttribute()
    {
        if (!$this->payment_proof) {
            return null;
        }
        if (str_starts_with($this->payment_proof, 'http')) {
            return $this->payment_proof;
        }
        return asset('uploads/payment_proofs/' . $this->payment_proof);
    }

    public function getGoogleMapsUrlAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
        }
        return null;
    }

    public function getCourierTrackingUrlAttribute(): ?string
    {
        if (empty($this->tracking_number)) {
            return null;
        }

        $c = strtolower($this->courier_name ?? '');
        $resi = urlencode(trim($this->tracking_number));

        if (str_contains($c, 'jne')) {
            return "https://www.jne.co.id/tracking/trace?awb={$resi}";
        } elseif (str_contains($c, 'j&t') || str_contains($c, 'jnt')) {
            return "https://www.jet.co.id/track?awb={$resi}";
        } elseif (str_contains($c, 'sicepat') || str_contains($c, 'cepat')) {
            return "https://www.sicepat.com/checkAwb?awb={$resi}";
        }

        return "https://cekresi.com/?noresi={$resi}";
    }

    public function getOrderStatusLabelAttribute(): string
    {
        return match($this->order_status) {
            'pending' => 'Pesanan Baru (Pending)',
            'processing' => 'Sedang Diproses & Dipanggang',
            'shipping' => 'Sedang Dibawa Kurir Ekspedisi',
            'completed' => 'Pesanan Selesai / Diterima',
            'cancelled' => 'Pesanan Dibatalkan',
            default => ucfirst($this->order_status ?? 'Pending'),
        };
    }

    public function getCourierIconAttribute(): string
    {
        $c = strtolower($this->courier_name ?? '');
        if (str_contains($c, 'jne')) return '📦';
        if (str_contains($c, 'j&t') || str_contains($c, 'jnt')) return '🚚';
        if (str_contains($c, 'sicepat') || str_contains($c, 'cepat')) return '⚡';
        if (str_contains($c, 'gosend') || str_contains($c, 'grab')) return '🛵';
        if ($this->delivery_option === 'pickup' || str_contains($c, 'pickup') || str_contains($c, 'ambil')) return '🏪';
        return '🥖';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
