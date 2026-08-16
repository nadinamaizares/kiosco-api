<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fiados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('pagado', 10, 2)->default(0);
            $table->string('estado')->default('pendiente');
            $table->text('notas')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['cliente_id', 'estado']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fiados');
    }
};
