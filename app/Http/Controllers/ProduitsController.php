<?php

namespace App\Http\Controllers;

use App\Models\Produits;
use Illuminate\Http\Request;


class ProduitsController
{
    public function edit($id)
    {
        $produit = Produits::with(['typeProduit', 'zoneStock'])->findOrFail($id);
        return view('stocks-edit', compact('produit'));
    }

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

        return redirect()->route('pharmacie.index')->with('success', 'Stock mis à jour.');
    }

}
