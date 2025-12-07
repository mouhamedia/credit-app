<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $fillable = [
        'client_id',
        'created_by',
        'article',
        'montant',
        'statut',  // 🔥 OBLIGATOIRE !
    ];

    // Belongs to client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Boutiquier qui a créé le crédit
    public function boutiquier()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Paiements du crédit
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
