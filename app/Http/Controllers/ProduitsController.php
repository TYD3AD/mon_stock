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

    public function create()
    {
        $zonesStock = ZoneStock::all();
        $typesProduits = TypeProduit::all();

        return view('produit-create', compact('zonesStock', 'typesProduits'));
    }


    public function store(Request $request)
    {

        try {
            $request->validate([
                'produits.*.produit_id' => 'required|exists:type_produit,id',
                'produits.*.zone_stock_id' => 'required|exists:zone_stocks,id',
                'produits.*.quantite' => 'required|integer|min:0',
                'produits.*.date_peremption' => 'required|date',
            ]);
            foreach ($request->input('produits') as $produitData) {
                Produits::create([
                    'produit_id' => $produitData['produit_id'],
                    'zone_stock_id' => $produitData['zone_stock_id'],
                    'quantite' => $produitData['quantite'],
                    'date_peremption' => $produitData['date_peremption'],
                    // ajouter autres champs nécessaires ici (ex: type_produit_id)
                ]);
            }// redirige vers le dashboard
            return redirect()->route('dashboard')->with('success', 'Les produits ont été ajoutés avec succès.');
        } catch (\Exception $e) {
            // Redirige vers la précédente page avec un message d'erreur
            Log::error('Erreur lors de l\'ajout des produits', [
                'message' => $e->getMessage(),
                'request' => $request->all()
            ]);
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'ajout des produits.<br>Veuillez contacter l\'administrateur.');
        }
    }



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
