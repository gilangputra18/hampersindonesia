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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
