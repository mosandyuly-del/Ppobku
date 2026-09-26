<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('orders');
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('trx_id')->unique();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_name');
            $table->string('product_code')->nullable();
            $table->string('phone');
            $table->decimal('price', 15, 2)->default(0);
            $table->string('status')->default('Pending');
            $table->string('sn')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
