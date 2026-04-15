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
        Schema::create('detalle_ventas', function (Blueprint $table) {
            $table->id();
            // Lo unimos al Ticket General
            $table->foreignId('venta_id')->constrained('ventas')->onDelete('cascade');
            // Lo unimos al Producto que se llevó
            $table->foreignId('producto_id')->constrained('productos');
            
            $table->unsignedInteger('cantidad'); // Cuántas piezas
            $table->decimal('precio_unitario', 8, 2); // A cómo se lo dimos
            $table->decimal('subtotal', 10, 2); // Cantidad x Precio Unitario
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_ventas');
    }
};
