<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('orders');
    }

    public function down(): void
    {
        //
    }
};
