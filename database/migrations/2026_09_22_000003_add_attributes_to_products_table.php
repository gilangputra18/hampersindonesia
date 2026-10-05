<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Attributes (type, flavor, size, availability) are created directly in create_products_table migration
    }

    public function down(): void
    {
        //
    }
};
