<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Historial extends Model
{
    protected $fillable = ['user_id', 'tipo_accion', 'descripcion'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}