<?php

namespace App\Http\Controllers;

use App\Models\Antenne;
use Illuminate\Support\Facades\Auth;
use App\Models\Produit;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Récupérer tous les stocks des zones liées à l’antenne de l’utilisateur
        $produits = Produit::whereHas('zoneStock', function ($query) use ($user) {
            $query->where('antenne_id', $user->antenne_id);
        })->with(['typeProduit', 'zoneStock'])->get();

        foreach ($produits as $produit) {
            $produit ? \Carbon\Carbon::parse($produit->date_peremption)->format('d/m/Y') : '—';
        }

        $antennes = auth()->user()->antennes()->pluck('nom', 'antennes.id');





        return view('dashboard', compact('produits', 'antennes'));
    }
}
