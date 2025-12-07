<?php

namespace App\Http\Controllers\Boutiquier;

use App\Http\Controllers\Controller;
use App\Models\Credit;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreditController extends Controller
{
    // Page pour créer un crédit depuis un client
    public function create($clientId)
    {
        $client = Client::where('boutiquier_id', Auth::id())
                        ->findOrFail($clientId);

        return view('boutiquier.credits.create', compact('client'));
    }

    // Enregistrer un nouveau crédit
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'article'   => 'required|string|max:255',
            'montant'   => 'required|numeric|min:1',
        ]);

        // Sécurité : le client doit appartenir au boutiquier connecté
        Client::where('boutiquier_id', Auth::id())
              ->where('id', $request->client_id)
              ->firstOrFail();

        Credit::create([
            'client_id'  => $request->client_id,
            'article'    => $request->article,
            'montant'    => $request->montant,
            'statut'     => 'impaye',
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('boutiquier.clients.index')
            ->with('success', 'Crédit ajouté avec succès !');
    }

    // MARQUER COMME PAYÉ → FONCTIONNE À 
    public function marquerCommePaye($id)
    {
        // Sécurité : le crédit doit appartenir à un client du boutiquier connecté
        $credit = Credit::whereHas('client', function ($query) {
            $query->where('boutiquier_id', Auth::id());
        })->findOrFail($id);

        // Mise à jour du statut
        $credit->update(['statut' => 'paye']);

        // Retour avec message
        return back()->with('success', 'Crédit marqué comme PAYÉ avec succès !');
    }
}