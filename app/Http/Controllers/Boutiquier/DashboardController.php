<?php
namespace App\Http\Controllers\Boutiquier;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Credit;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();

        // Clients du boutiquier
        $clients = Client::where('boutiquier_id', $user_id)->get();

        // Crédits créés par ce boutiquier
        $credits = Credit::where('created_by', $user_id)->get();

        return view('boutiquier.dashboard', compact('clients', 'credits'));
    }
}

