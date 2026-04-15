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
        Schema::create('productos', function (Blueprint $table) {
            $table->id(); // ID oculto del sistema
            
            // 1.1 ID Único de 7 números
            $table->string('codigo_producto', 7)->unique(); 
            
            // 1.2 y 1.3 Nombre y Marca
            $table->string('nombre');
            $table->string('marca');
            
            // 1.4 Stock Entrante (unsignedInteger asegura que NO se puedan meter números negativos)
            $table->unsignedInteger('stock'); 
            
            // 1.5 Precio (decimal de 8 dígitos en total, 2 decimales. Ej: 999999.99)
            $table->decimal('precio', 8, 2); 
            
            // 1.6 Imagen (nullable significa que puede estar vacía temporalmente si no suben foto)
            $table->string('imagen_url')->nullable(); 
            
            // 1.7 Alertas de Stock (Mínimo y Máximo)
            $table->unsignedInteger('stock_minimo');
            $table->unsignedInteger('stock_maximo');
            
            $table->timestamps(); // Fecha de creación y edición (Para el historial)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
