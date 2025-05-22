<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AccueilController extends Controller
{
    public function index()
    {
        $aujourdHui = Carbon::today();

        $produits = Produit::with(['type_prozdzddduit', 'zoneszzzStocks'])
            ->whereNotNull('date_peremption')
            ->orderBy('date_peremption')
            ->get()
            ->map(function ($produit) use ($aujourdHui) {
                $diff = $aujourdHui->diffInDays(Carbon::parse($produit->date_peremption), false);

                if ($diff < 0) {
                    $produit->couleur = 'noir'; // périmé
                } elseif ($diff <= 15) {
                    $produit->couleur = 'rouge';
                } elseif ($diff <= 60) {
                    $produit->couleur = 'jaune';
                } else {
                    $produit->couleur = 'vert';
                }

                return $produit;
            });

        foreach ($produits as $produit) {
            $produit ? \Carbon\Carbon::parse($produit->date_peremption)->format('d/m/Y') : '—';
        }

        return view('accueil', compact('produits'));
    }
}
