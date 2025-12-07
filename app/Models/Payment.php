<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'credit_id',
        'client_id',
        'montant',
    ];

    // Le crédit concerné
    public function credit()
    {
        return $this->belongsTo(Credit::class);
    }

    // Le client qui paye
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
