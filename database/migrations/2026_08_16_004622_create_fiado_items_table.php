<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fiado_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fiado_id')->constrained('fiados')->cascadeOnDelete();
            $table->string('producto_nombre');
            $table->foreignId('producto_id')->nullable()->constrained('productos')->nullOnDelete();
            $table->integer('cantidad')->default(1);
            $table->decimal('precio_unitario', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fiado_items');
    }
};
