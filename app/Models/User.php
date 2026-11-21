<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'apellido',
        'rol',
        'telefono',
        'direccion',
        'google2fa_secret',
        'google2fa_enabled',
    ];

    protected static function booted()
    {
        static::creating(function ($user) {
            do {
                $codigoAleatorio = str_pad((string) mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            } while (self::where('codigo_empleado', $codigoAleatorio)->exists());

            $user->codigo_empleado = $codigoAleatorio;
        });
    }

    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'google2fa_secret' => 'encrypted',
            'google2fa_enabled' => 'boolean',
        ];
    }
}