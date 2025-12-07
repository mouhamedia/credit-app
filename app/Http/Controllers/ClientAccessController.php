<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ClientAccessController extends Controller
{
    // 1. Page de connexion avec le champ code
    public function showLoginForm()
    {
        return view('client.login');
    }

    // 2. MÉTHODE APPELEE PAR LA ROUTE → loginWithCode()
    public function loginWithCode(Request $request)
    {
        $request->validate([
            'code_unique' => 'required|string'
        ]);

        $code = strtoupper(trim($request->code_unique));

        $client = Client::whereRaw('UPPER(code_unique) = ?', [$code])
                        ->orWhere('code_unique', $request->code_unique)
                        ->first();

        if (!$client) {
            return back()->withErrors([
                'code_unique' => 'Code incorrect ou client introuvable.'
            ])->withInput();
        }

        // On connecte le client
        Session::put('client_auth', $client->id);

        return redirect()->route('client.dashboard')
            ->with('success', 'Bienvenue ' . $client->name . ' !');
    }

    // 3. Dashboard du client
    public function dashboard()
    {
        if (!Session::has('client_auth')) {
            return redirect()->route('client.login');
        }

        $client = Client::with('credits')->findOrFail(Session::get('client_auth'));

        return view('client.dashboard', compact('client'));
    }

    // 4. Déconnexion client
    public function logout()
    {
        Session::forget('client_auth');
        Session::flush();

        return redirect()->route('client.login')
            ->with('success', 'Vous êtes déconnecté.');
    }
}