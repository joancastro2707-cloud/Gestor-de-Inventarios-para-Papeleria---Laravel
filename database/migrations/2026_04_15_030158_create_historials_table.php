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
        Schema::create('historials', function (Blueprint $table) {
            $table->id();
            // ¿Quién lo hizo? (Lo conectamos con el Usuario/Empleado)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // ¿Qué acción fue? (Registro, Edición, Eliminación, Venta)
            $table->string('tipo_accion'); 

            // Los detalles exactos
            $table->text('descripcion'); 

            // ¿Cuándo lo hizo? (Laravel llena esto automático con created_at)
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historials');
    }
};
