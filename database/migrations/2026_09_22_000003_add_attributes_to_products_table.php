<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('type')->nullable()->after('description');
            $table->string('flavor')->nullable()->after('type');
            $table->string('size')->nullable()->after('flavor');
            $table->string('availability')->default('in_stock')->after('size');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['type', 'flavor', 'size', 'availability']);
        });
    }
};
