<?php

namespace App\Http\Controllers;

use App\Models\AccesAntenne;
use App\Models\Antenne;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function create()
    {
        if(!auth()->user() || auth()->user()->identifiant !== 'trialet') {
            return redirect()->route('dashboard')->with('error', 'Vous n\'êtes pas autorisé à accéder à cet page.');
        }

        $antennes = Antenne::orderBy('nom', 'asc')->get(['id', 'nom']);

        $acces = AccesAntenne::all();
        return view('admin.create-user', compact('antennes', 'acces'));
    }

    public function store(Request $request)
    {

        try {
            $validated = $request->validate([
                'prenom' => 'required|string|max:255',
                'nom' => 'required|string|max:255',
                'identifiant' => 'required|string|max:255|unique:users,identifiant',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|min:4|confirmed',
                'antenne' => 'required|exists:antennes,id',
            ]);
            User::create([
                'prenom' => $validated['prenom'],
                'nom' => $validated['nom'],
                'identifiant' => $validated['identifiant'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'doitChangerPassword' => 1,
                'antenne_id' => $validated['antenne'],
            ]);
            AccesAntenne::create([
                'id_user' => User::where('identifiant', $validated['identifiant'])->first()->id,
                'id_antenne' => $validated['antenne'],
                'est_responsable' => true,
            ]);

            return redirect()->route('dashboard')->with('success', 'Utilisateur créé avec succès.');
        } catch (\Exception $e) {
            Log::error("Erreur lors de la création de l'utilisateur : " . $e->getMessage());
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la création de l\'utilisateur. Veuillez réessayer.'])->withInput();
        }
    }

}
