<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id(); // Número de Ticket
            // Vinculamos la venta con el empleado que la hizo (Admin o Vendedor)
            $table->foreignId('user_id')->constrained('users'); 
            $table->decimal('total', 10, 2); // Total de la venta
            $table->timestamps(); // Guarda la Fecha y Hora exacta automáticamente
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
