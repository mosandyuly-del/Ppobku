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
            $table->string('code')->nullable()->index();
            $table->string('sku')->nullable()->index();
            $table->string('name')->nullable();
            $table->string('category')->nullable();
            $table->string('brand')->nullable();
            $table->integer('price_original')->default(0);
            $table->integer('price')->default(0);
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
