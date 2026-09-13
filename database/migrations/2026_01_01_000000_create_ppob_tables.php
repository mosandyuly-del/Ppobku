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
            $table->string('sku_code')->unique();
            $table->string('name');
            $table->string('brand');
            $table->decimal('price_original', 12, 2);
            $table->decimal('price_sell', 12, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();
            $table->string('qr_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->string('customer_no');
            $table->string('sku_code');
            $table->decimal('price', 12, 2);
            $table->foreignId('payment_method_id')->constrained('payment_methods');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'expired'])->default('pending');
            $table->enum('trx_status', ['pending', 'process', 'success', 'failed'])->default('pending');
            $table->string('sn')->nullable();
            $table->string('proof_of_payment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('products');
    }
};
