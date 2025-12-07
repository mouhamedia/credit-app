<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Vérifier si l'utilisateur est actif ou si abonnement payé
            if ($user->role === 'boutiquier' && $user->status != 1) {
                Auth::logout();
                return redirect()->back()->withErrors([
                    'email' => 'Votre compte est inactif. Veuillez régler l’abonnement.',
                ]);
            }

            // Redirection selon le rôle
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'boutiquier') {
                return redirect()->route('boutiquier.dashboard');
            }

            return redirect('/'); // fallback
        }

        return redirect()->back()->withErrors([
            'email' => 'Email ou mot de passe incorrect',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
