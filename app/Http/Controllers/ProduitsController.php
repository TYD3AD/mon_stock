<?php

namespace App\Http\Controllers;

use App\Models\Produits;
use App\Models\TypeProduit;
use App\Models\ZoneStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use function PHPSTORM_META\type;

class ProduitsController
{
    public function edit($id)
    {
        $produit = Produits::with(['typeProduit', 'zoneStock'])->findOrFail($id);
        $zonesStock = ZoneStock::all();
        $typeProduits = TypeProduit::all();

        return view('produit-edit', compact('produit', 'zonesStock', 'typeProduits'));
    }

    public function update(Request $request, $id)
    {

        try {
            $request->validate([
                'type_produit_id' => 'required|exists:type_produit,id',
                'zone_stock_id' => 'required|exists:zone_stocks,id',
                'quantite' => 'required|integer|min:0',
                'date_peremption' => 'required|date'
            ]);
            $produit = Produits::findOrFail($id);
            $produit->produit_id = $request->input('type_produit_id');
            $produit->zone_stock_id = $request->input('zone_stock_id');
            $produit->quantite = $request->input('quantite');
            $produit->date_peremption = $request->input('date_peremption');
            $produit->save();
            // Redirige vers la précédente page avec un message de succès
            return redirect()->back()->with('success', 'Le produit a été mis à jour avec succès.');

        } catch (\Exception $e) {
            // Redirige vers la précédente page avec un message d'erreur
            Log::error('Erreur lors de la mise à jour du produit', [
                'message' => $e->getMessage(),
                'request' => $request->all()
            ]);
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour du produit.<br>Veuillez contacter l\'administrateur.');
        }
    }

}
