<?php

namespace App\Http\Controllers;

use App\Models\Produits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PharmacieController extends Controller
{
    /**
     * Affiche la liste des produits en stock dans la zone Pharmacie.
     */
    public function index()
    {
        // Filtre les produits stockés dans la zone "Pharmacie"
        $produits = Produits::with(['typeProduit', 'zoneStock']) // <== ici
        ->whereHas('zoneStock', function ($query) {
            $query->where('nom', 'Pharmacie');
        })
            ->get();

        $antenne = auth()->user()->antenne;

        return view('pharmacie', compact('produits', 'antenne'));
    }

    /**
     * Met à jour les informations d’un stock de la pharmacie.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'quantite' => 'required|integer|min:0',
                'date_peremption' => 'required|date',
            ]);

            $stock = Produits::findOrFail($id);
            $stock->quantite = $request->input('quantite');
            $stock->date_peremption = $request->input('date_peremption');
            $stock->save();

            // Redirige vers la précédente page avec un message de succès
            return redirect()->back()->with('success', 'Le stock a été mis à jour avec succès.');
        }
        catch (\Exception $e) {
            // Redirige vers la précédente page avec un message d'erreur
            log::error('Erreur lors de la mise à jour du produit : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour du produit.<br>Veuillez contacter l\'administrateur.');
        }
    }
}
