<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BoutiquierController extends Controller
{
    /**
     * Affiche la liste des boutiquiers avec le compte client.
     */
    public function index()
    {
        $boutiquiers = User::where('role', 'boutiquier')
            // AJOUT : Compte le nombre de clients
            ->withCount('clients') 
            ->get();

        return view('admin.boutiquiers.index', compact('boutiquiers'));
    }

    public function create()
    {
        return view('admin.boutiquiers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6', 
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            
            // LIGNE CORRIGÉE : On passe la valeur en clair. 
            // C'est le setPasswordAttribute dans le Modèle qui hache.
            'password' => $request->password, 
            
            'role' => 'boutiquier',
            'status' => 1,
        ]);

        return redirect()->route('admin.boutiquiers.index')->with('success', 'Boutiquier créé');
    }

    public function edit($id)
    {
        $boutiquier = User::where('role', 'boutiquier')->findOrFail($id);
        return view('admin.boutiquiers.edit', compact('boutiquier'));
    }

    public function update(Request $request, $id)
    {
        $boutiquier = User::where('role', 'boutiquier')->findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => "required|email|unique:users,email,{$id}", 
        ]);

        $boutiquier->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.boutiquiers.index')->with('success', 'Boutiquier modifié');
    }

    public function destroy($id)
    {
        User::where('role', 'boutiquier')->findOrFail($id)->delete();
        return redirect()->route('admin.boutiquiers.index')->with('success', 'Boutiquier supprimé');
    }
    
    // ... (Vos méthodes showResetPasswordForm et resetPassword restent inchangées)
}