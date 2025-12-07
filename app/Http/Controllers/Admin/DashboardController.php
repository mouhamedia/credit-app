<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Récupérer tous les utilisateurs qui sont des boutiquiers
        $boutiquiers = User::where('role', 'boutiquier')->get();

        // Passer la variable à la vue
        return view('admin.dashboard', compact('boutiquiers'));
    }
}
