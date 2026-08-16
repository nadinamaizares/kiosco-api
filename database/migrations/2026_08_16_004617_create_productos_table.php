<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_barras')->nullable()->unique();
            $table->string('nombre');
            $table->string('marca')->nullable();
            $table->string('categoria')->nullable();
            $table->string('especificacion')->nullable();
            $table->decimal('precio_costo', 12, 2)->nullable();
            $table->decimal('precio_venta', 12, 2)->nullable();
            $table->integer('stock_actual')->default(0);
            $table->integer('stock_minimo')->default(5);
            $table->string('proveedor')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
