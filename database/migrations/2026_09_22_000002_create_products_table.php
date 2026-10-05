<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('cost_price')->nullable()->default(0);
            $table->string('type')->nullable();
            $table->string('flavor')->nullable();
            $table->string('size')->nullable();
            $table->string('availability')->nullable()->default('in_stock');
            $table->string('image')->nullable();
            $table->json('gallery')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_best_seller')->default(false);
            $table->boolean('is_treat')->default(false);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
