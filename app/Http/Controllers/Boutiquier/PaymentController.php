<?php

namespace App\Http\Controllers\Boutiquier;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Credit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'credit_id' => 'required|exists:credits,id',
            'montant'   => 'required|numeric|min:1',
            'moyen'     => 'nullable|string',
        ]);

        $credit = Credit::whereHas('client', fn($q) => $q->where('boutiquier_id', Auth::id()))
                        ->findOrFail($request->credit_id);

        Payment::create([
            'credit_id'     => $credit->id,
            'client_id'     => $credit->client_id,
            'montant'       => $request->montant,
            'moyen'         => $request->moyen ?? 'Cash',
            'payment_date'  => now(),
        ]);

        return back()->with('success', 'Paiement enregistré !');
    }
}