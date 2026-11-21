<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'marca',
        'stock',
        'precio',
        'imagen_url',
        'stock_minimo',
        'stock_maximo',
    ];

    protected static function booted()
    {
        static::creating(function ($producto) {
            do {
                $codigoAleatorio = str_pad((string) mt_rand(1, 9999999), 7, '0', STR_PAD_LEFT);
            } while (self::where('codigo_producto', $codigoAleatorio)->exists());

            $producto->codigo_producto = $codigoAleatorio;
        });
    }
}