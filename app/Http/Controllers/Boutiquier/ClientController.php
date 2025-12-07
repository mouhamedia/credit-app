<?php

namespace App\Http\Controllers\Boutiquier;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    // 1. Liste de tous mes clients
    public function index()
    {
        $clients = Auth::user()->clients()->latest()->get();
        return view('boutiquier.clients.index', compact('clients'));
    }

    // 2. Formulaire création client
    public function create()
    {
        return view('boutiquier.clients.create');
    }

    // 3. Enregistrer un nouveau client + génération code CLI-2025-0001
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        // Génération du code unique séquentiel
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

    // 4. Voir tous les crédits d’un client (la méthode qui manquait !)
    public function credits($clientId)
    {
        $client = Auth::user()->clients()
            ->with('credits')
            ->findOrFail($clientId);

        return view('boutiquier.clients.credits', compact('client'));
    }

    // 5. Éditer un client (optionnel mais utile)
    public function edit($clientId)
    {
        $client = Auth::user()->clients()->findOrFail($clientId);
        return view('boutiquier.clients.edit', compact('client'));
    }

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

    // 6. Supprimer un client
    public function destroy($clientId)
    {
        $client = Auth::user()->clients()->findOrFail($clientId);
        $client->delete();

        return redirect()
            ->route('boutiquier.clients.index')
            ->with('success', 'Client supprimé');
    }
}