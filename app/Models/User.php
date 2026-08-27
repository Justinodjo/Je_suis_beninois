<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * Attributs pouvant être remplis.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'bio',
        'statut',
        'date_derniere_connexion',
        'date_inscription',
    ];

    /**
     * Attributs cachés lors de la sérialisation.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts des attributs.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Identifiant stocké dans le JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Claims supplémentaires du JWT.
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }
}