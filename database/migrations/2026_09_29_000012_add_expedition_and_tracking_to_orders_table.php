<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // user_id, courier_name, payment_method, tracking_number are created directly in create_orders_table migration
    }

    public function down(): void
    {
        //
    }
};
