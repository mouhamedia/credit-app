<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BoutiquierController extends Controller
{
    public function index()
    {
        $boutiquiers = User::where('role', 'boutiquier')->get();
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
            'password' => Hash::make($request->password),
            'role' => 'boutiquier',
            'status' => 1,
        ]);

        return redirect()->route('admin.boutiquiers.index')->with('success', 'Boutiquier créé');
    }

    public function edit($id)
    {
        $boutiquier = User::findOrFail($id);
        return view('admin.boutiquiers.edit', compact('boutiquier'));
    }

    public function update(Request $request, $id)
    {
        $boutiquier = User::findOrFail($id);

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
        User::findOrFail($id)->delete();
        return redirect()->route('admin.boutiquiers.index')->with('success', 'Boutiquier supprimé');
    }
}
