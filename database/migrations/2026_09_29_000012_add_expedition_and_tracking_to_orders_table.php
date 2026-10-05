<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('orders', 'courier_name')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
                $table->string('courier_name')->nullable()->after('delivery_option')->default('Kurir Toko (Dedicated Bakery Delivery)');
                $table->string('payment_method')->nullable()->after('total_amount')->default('Transfer Bank BCA');
                $table->string('tracking_number')->nullable()->after('payment_method');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'courier_name')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn(['user_id', 'courier_name', 'payment_method', 'tracking_number']);
            });
        }
    }
};
