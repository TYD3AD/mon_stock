<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\TypeProduit;
use App\Models\ZoneStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Dashboard;
use App\Http\Controllers\DashboardController;

use function PHPSTORM_META\type;

class ProduitsController
{

    public function create()
    {
        // récupère toutes les zones de stock liés aux antennes de l'utilisateur
        $zonesStock = ZoneStock::whereIn('antenne_id', auth()->user()->antennes->pluck('id'))->get();

        $typesProduits = TypeProduit::all();

        return view('produit-create', compact('zonesStock', 'typesProduits'));
    }


    public function store(Request $request)
    {
        try {
            // Validation
            $validated = $request->validate([
                'produits' => 'required|array|min:1',
                'produits.*.type_produit_id' => 'required|exists:types_produits,id',
                'produits.*.zone_stock_id' => 'required|exists:zones_stocks,id',
                'produits.*.quantite' => 'required|integer|min:0',
                'produits.*.date_peremption' => 'nullable|date',
            ]);


            // Création des produits
            foreach ($validated['produits'] as $data) {

                if(isset($data['date_peremption']) && $data['date_peremption'] == '') {
                    $data['date_peremption'] = null; // Assurez-vous que la date d'expiration est nulle si elle n'est pas fournie
                }
                Produit::create([
                    'type_produit_id' => $data['type_produit_id'],
                    'zone_stock_id' => $data['zone_stock_id'],
                    'quantite' => $data['quantite'],
                    'date_peremption' => $data['date_peremption'],
                ]);
            }

            return redirect()->route('dashboard')
                ->with('success', 'Les produits ont été ajoutés avec succès.');
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'ajout des produits : " . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all(),
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de l\'ajout des produits.<br>Veuillez contacter l\'administrateur.')
                ->withInput(); // Pour ne pas perdre les données remplies
        }
    }




    public function edit($id)
    {
        $produit = Produit::with(['typeProduit', 'zoneStock'])->findOrFail($id);
        $zonesStock = ZoneStock::whereIn('antenne_id', auth()->user()->antennes->pluck('id'))->get();
        $typeProduits = TypeProduit::all();

        return view('produit-edit', compact('produit', 'zonesStock', 'typeProduits'));
    }

    public function update(Request $request, $id)
    {

        try {
            $request->validate([
                'type_produit_id' => 'required|exists:types_produits,id',
                'zone_stock_id' => 'required|exists:zones_stocks,id',
                'quantite' => 'required|integer|min:0',
                'date_peremption' => 'nullable|date'
            ]);

            $produit = Produit::findOrFail($id);
            $produit->type_produit_id = $request->input('type_produit_id'); // Correction ici
            $produit->zone_stock_id = $request->input('zone_stock_id');
            $produit->quantite = $request->input('quantite');
            $produit->date_peremption = $request->input('date_peremption');
            $produit->save();
            // Redirige vers la précédente page avec un message de succès
            return redirect()->route('dashboard')->with('success', 'Produit mis à jour avec succès.');

        } catch (\Exception $e) {
            // Redirige vers la précédente page avec un message d'erreur
            Log::error('Erreur lors de la mise à jour du produit', [
                'message' => $e->getMessage(),
                'request' => $request->all()
            ]);
            // Redirige vers la vue dashboard avec un message d'erreur
            return redirect()->route('dashboard')->with('error', 'Une erreur est survenue lors de la mise à jour du produit.<br>Veuillez contacter l\'administrateur.');
        }
    }

    public function delete($id)
    {
        $produit = Produit::findOrFail($id);
        $produit->delete();

        return redirect()->back()->with('success', 'Le produit a été supprimé avec succès.');
    }

    public function listAccess($antenne, $categorie)
    {
        // récupère les produits avec leur type de produit et leur zone de stock de la pharmacie de l'antenne
        $produits = Produit::with(['typeProduit', 'zoneStock']) // <== ici
        ->whereHas('zoneStock', function ($query) use ($antenne, $categorie) {
            $query->where('categorie', $categorie)
                ->where('antenne_id', $antenne);
        })
            ->get();

        $antennes = auth()->user()->antennes()->pluck('nom', 'antennes.id');

        $categorie = 'Pharmacie';

        return view('produit-list',
            compact(
                'produits',
                'antennes',
            'categorie'));
    }

}
