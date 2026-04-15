<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = ['user_id', 'total'];

    // Relación: Una venta le pertenece a un Usuario (Cajero)
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Relación: Una venta tiene muchos detalles (productos)
    public function detalles() {
        return $this->hasMany(DetalleVenta::class);
    }
}