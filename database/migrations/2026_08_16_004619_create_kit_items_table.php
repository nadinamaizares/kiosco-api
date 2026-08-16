<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kit_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kit_id')->constrained('kits')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->integer('cantidad')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kit_items');
    }
};
