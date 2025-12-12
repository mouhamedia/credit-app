<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Email ou mot de passe incorrect.',
            ])->withInput();
        }

        /** @var User $user */
        $user = Auth::user();

        // ---- Vérifier si le boutiquier est actif ----
        if ($user->role === 'boutiquier' && $user->status != 1) {
            Auth::logout();
            return back()->withErrors([
                'email' => "Votre compte est inactif. Veuillez régler l'abonnement.",
            ]);
        }

        // ---- Redirection selon le rôle ----
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'boutiquier') {
            return redirect()->route('boutiquier.dashboard');
        }

        return redirect('/');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
