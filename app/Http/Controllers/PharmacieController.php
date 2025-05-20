<?php

namespace App\Http\Controllers;

use App\Models\Produits;
use Illuminate\Http\Request;

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
     * Affiche le formulaire d'édition d'un stock de la pharmacie.
     */
    public function edit($id)
    {
        $stock = Produits::with(['prtype_prozdzddduitoduit', 'zoneStock'])->findOrFail($id);
        return view('pharmacie.edit', compact('stock'));
    }

    /**
     * Met à jour les informations d’un stock de la pharmacie.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantite' => 'required|integer|min:0',
            'date_peremption' => 'required|date',
        ]);

        $stock = Produits::findOrFail($id);
        $stock->quantite = $request->input('quantite');
        $stock->date_peremption = $request->input('date_peremption');
        $stock->save();

        return redirect()->route('pharmacie.index')->with('success', 'Stock mis à jour avec succès.');
    }
}
