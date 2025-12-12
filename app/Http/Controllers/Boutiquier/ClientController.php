<?php

namespace App\Http\Controllers\Boutiquier;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * 1. Liste des clients (avec totaux)
     */
    public function index()
    {
        $clients = Auth::user()->clients()
            ->withSum(['credits as total_impaye' => function ($q) {
                $q->where('statut', 'impaye');
            }], 'montant')
            ->withSum(['credits as total_paye' => function ($q) {
                $q->where('statut', 'paye');
            }], 'montant')
            ->latest()
            ->get();

        return view('boutiquier.clients.index', compact('clients'));
    }

    /**
     * 2. Formulaire création client
     */
    public function create()
    {
        return view('boutiquier.clients.create');
    }

    /**
     * 3. Enregistrement client + code unique
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $count       = Auth::user()->clients()->count() + 1;
        $code_unique = 'CLI-' . date('Y') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        Client::create([
            'boutiquier_id' => Auth::id(),
            'name'          => $request->name,
            'phone'         => $request->phone,
            'address'       => $request->address,
            'code_unique'   => $code_unique,
        ]);

        return redirect()
            ->route('boutiquier.clients.index')
            ->with('success', "Client créé ! Code : <strong>$code_unique</strong>");
    }

    /**
     * 4. Voir crédits d’un client
     */
    public function credits($clientId)
    {
        $client = Auth::user()->clients()
            ->with('credits') // Charger tous les crédits d’un coup
            ->findOrFail($clientId);

        // Calcul des totaux côté serveur pour la vue
        $total_impaye  = $client->credits->where('statut', 'impaye')->sum('montant');
        $total_paye    = $client->credits->where('statut', 'paye')->sum('montant');
        $total_credit  = $client->credits->sum('montant');

        return view('boutiquier.clients.credits', compact(
            'client',
            'total_impaye',
            'total_paye',
            'total_credit'
        ));
    }

    /**
     * 5. Formulaire édition client
     */
    public function edit($clientId)
    {
        $client = Auth::user()->clients()->findOrFail($clientId);
        return view('boutiquier.clients.edit', compact('client'));
    }

    /**
     * 6. Mise à jour client
     */
    public function update(Request $request, $clientId)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $client = Auth::user()->clients()->findOrFail($clientId);
        $client->update($request->only('name', 'phone', 'address'));

        return redirect()
            ->route('boutiquier.clients.index')
            ->with('success', 'Client mis à jour avec succès');
    }

    /**
     * 7. Suppression client
     */
    public function destroy($clientId)
    {
        $client = Auth::user()->clients()->findOrFail($clientId);
        $client->delete();

        return redirect()
            ->route('boutiquier.clients.index')
            ->with('success', 'Client supprimé');
    }
}
