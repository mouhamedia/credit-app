<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ClientAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('client_auth')) {
            return redirect()->route('client.login')
                ->withErrors(['code_unique' => 'Veuillez entrer votre code client']);
        }
        return $next($request);
    }
}