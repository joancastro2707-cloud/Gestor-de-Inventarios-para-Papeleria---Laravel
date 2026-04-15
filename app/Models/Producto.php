<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    // La "Lista Blanca" de los campos que permitimos llenar desde un formulario
    protected $fillable = [
        'nombre', 
        'marca', 
        'stock', 
        'precio', 
        'imagen_url', 
        'stock_minimo', 
        'stock_maximo'
        // ¡Ojo! No pusimos 'codigo_producto' aquí porque nadie lo va a escribir a mano.
    ];

    // LA MAGIA: Esto se ejecuta en automático justo antes de guardar un producto nuevo
    protected static function booted()
    {
        static::creating(function ($producto) {
            // Genera un número aleatorio de 7 dígitos y verifica que no exista ya en la base de datos
            do {
                $codigoAleatorio = str_pad(mt_rand(1, 9999999), 7, '0', STR_PAD_LEFT);
            } while (self::where('codigo_producto', $codigoAleatorio)->exists());

            // Se lo asignamos al producto automáticamente
            $producto->codigo_producto = $codigoAleatorio;
        });
    }
}