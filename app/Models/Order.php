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
        'tracking_number',
        'payment_status',
        'order_status',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'subtotal' => 'integer',
        'delivery_fee' => 'integer',
        'total_amount' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
