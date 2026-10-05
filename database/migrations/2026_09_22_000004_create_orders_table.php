<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone');
            $table->string('delivery_option')->default('delivery'); // 'delivery' or 'pickup'
            $table->date('delivery_date')->nullable();
            $table->unsignedBigInteger('delivery_fee')->default(0);
            $table->text('address')->nullable();
            $table->text('order_note')->nullable();
            $table->unsignedBigInteger('subtotal')->default(0);
            $table->unsignedBigInteger('total_amount')->default(0);
            $table->string('payment_status')->default('unpaid'); // 'unpaid', 'paid', 'verified'
            $table->string('order_status')->default('pending'); // 'pending', 'processing', 'completed', 'cancelled'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
