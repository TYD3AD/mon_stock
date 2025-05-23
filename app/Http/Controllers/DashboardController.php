<?php

namespace App\Http\Controllers;

use App\Models\Antenne;
use App\Models\ZoneStock;
use Illuminate\Support\Facades\Auth;
use App\Models\Produit;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Récupère les produits de toutes les zones des antennes de l'utilisateur
        $produits = Produit::whereHas('zoneStock.antenne.accesAntennes', function ($query) {
            $query->where('id_user', 1);
        })->with(['zoneStock.antenne.accesAntennes.user'])->get();



        foreach ($produits as $produit) {
            $produit ? \Carbon\Carbon::parse($produit->date_peremption)->format('d/m/Y') : '—';
        }

        $antennes = auth()->user()->antennes()->get();
        $antenneP = Antenne::where('id', $user->antenne_id)->first();

        // retire l'antenne principale de la liste des antennes
        $antennes = $antennes->forget($antenneP->id);

        // récupère les zones de stock des antennes de l'utilisateur
        $zones = ZoneStock::whereIn('antenne_id', auth()->user()->antennes->pluck('id'))->get();




        return view('dashboard', compact('produits', 'antennes', 'antenneP', 'zones'));
    }
}
