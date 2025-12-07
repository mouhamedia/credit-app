<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name', 'phone', 'address', 'code_unique', 'boutiquier_id'
    ];

    // On désactive les timestamps si tu n’en as pas dans la table
    // public $timestamps = false; // retire si tu as created_at/updated_at

    // Relations
    public function boutiquier()
    {
        return $this->belongsTo(User::class, 'boutiquier_id');
    }

    public function credits()
    {
        return $this->hasMany(Credit::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}