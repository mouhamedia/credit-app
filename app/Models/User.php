<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'role', 'status'
    ];

    protected $hidden = [
        'password'
    ];

    // Mutateur pour hasher automatiquement le mot de passe
    public function setPasswordAttribute($value)
    {
        if ($value !== null) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    // ✔ Un boutiquier possède plusieurs clients
    public function clients()
    {
        return $this->hasMany(Client::class, 'boutiquier_id');
    }

    // ✔ Crédits créés par le boutiquier
    public function createdCredits()
    {
        return $this->hasMany(Credit::class, 'created_by');
    }
}
