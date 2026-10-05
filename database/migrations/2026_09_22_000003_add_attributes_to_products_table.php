<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('products', 'type')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('type')->nullable();
                $table->string('flavor')->nullable();
                $table->string('size')->nullable();
                $table->string('availability')->nullable()->default('in_stock');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'type')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn(['type', 'flavor', 'size', 'availability']);
            });
        }
    }
};
