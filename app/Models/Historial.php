<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Historial extends Model
{
    protected $fillable = ['user_id', 'tipo_accion', 'descripcion'];

    // Relación: Un registro del historial le pertenece a un Usuario
    public function user() {
        return $this->belongsTo(User::class);
    }
}