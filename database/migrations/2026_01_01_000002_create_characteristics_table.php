<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('characteristics', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('product_characteristic', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('characteristic_id')->constrained()->cascadeOnDelete();
            $table->string('value');
            $table->timestamps();
            $table->unique(['product_id', 'characteristic_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_characteristic');
        Schema::dropIfExists('characteristics');
    }
};
