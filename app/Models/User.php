<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'apellido', // ¡Nuevo!
        'rol',      // ¡Nuevo!
        'telefono', // ¡Nuevo!
        'direccion' // ¡Nuevo!
    ];

    // LA MAGIA: Generar código de empleado automático de 5 dígitos
    protected static function booted()
    {
        static::creating(function ($user) {
            do {
                $codigoAleatorio = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            } while (self::where('codigo_empleado', $codigoAleatorio)->exists());

            $user->codigo_empleado = $codigoAleatorio;
        });
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
