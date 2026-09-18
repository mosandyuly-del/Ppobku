<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('blacklists')) {
            Schema::create('blacklists', function (Blueprint $table) {
                $table->id();
                $table->string('target_no')->unique();
                $table->string('reason')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('blacklists');
    }
};
