<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('products');
        
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('buyer_sku_code')->nullable()->unique();
            $table->string('name');
            $table->string('category')->nullable();
            $table->string('category_slug')->nullable();
            $table->string('brand')->nullable();
            $table->string('type')->nullable();
            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('price_sell', 15, 2)->default(0);
            $table->string('status')->default('Active');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
