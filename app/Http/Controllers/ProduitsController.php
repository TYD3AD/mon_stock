<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\TypeProduit;
use App\Models\ZoneStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Dashboard;

use function PHPSTORM_META\type;
use function Psy\debug;

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

            Log::info("Produits ajoutés avec succès par l'utilisateur ". auth()->user()->identifiant . " : " . json_encode($validated['produits']));
            return redirect()->route('dashboard')
                ->with('success', 'Les produits ont été ajoutés avec succès.');
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'ajout des produits par l'utilisateur ". auth()->user()->identifiant . " : " . json_encode($validated['produits']) . " - Erreur : " . $e->getMessage() . " - Requête : " . json_encode($request->all()));

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

            Log::info("Produit mis à jour avec succès par l'utilisateur ". auth()->user()->identifiant. " : Produit ID : ". $produit->id . " - Quantité : ". $produit->quantite . " - Date d'expiration : ". $produit->date_peremption);
            // Redirige vers la précédente page avec un message de succès
            return redirect()->route('dashboard')->with('success', 'Produit mis à jour avec succès.');

        } catch (\Exception $e) {
            Log::error("Erreur lors de la mise à jour du produit ID : " . $id . "Par l'utilisateur " . auth()->user()->identifiant . " - Erreur : " . $e->getMessage() . " - Requête : " . json_encode($request->all()));

            // Redirige vers la vue dashboard avec un message d'erreur
            return redirect()->route('dashboard')->with('error', 'Une erreur est survenue lors de la mise à jour du produit.<br>Veuillez contacter l\'administrateur.');
        }
    }

    public function delete($id)
    {
        $produit = Produit::findOrFail($id);
        $produit->delete();

        Log::info("Produit supprimé avec succès par l'utilisateur ". auth()->user()->identifiant. " : Produit ID : ". $produit->id);
        return redirect()->back()->with('success', 'Le produit a été supprimé avec succès.');
    }

    public function listAccess($antenne, $categorie, $id=null)
    {
        // Vérifie si l'utilisateur a accès à l'antenne
        if (!auth()->user()->antennes->contains($antenne)) {
            return redirect()->route('dashboard')->with('error', 'Vous n\'avez pas accès à cette antenne.');
        }
        // Vérifie si la catégorie est valide
        $zonesCategories = ZoneStock::where('antenne_id', $antenne)->pluck('categorie')->unique();
        if (!$zonesCategories->contains($categorie)) {
            return redirect()->route('dashboard')->with('error', 'La catégorie demandé n\'existe pas.');
        }

        // récupère les produits avec leur type de produit et leur zone de stock de la pharmacie de l'antenne
        $produits = Produit::with(['typeProduit', 'zoneStock']) // <== ici
        ->whereHas('zoneStock', function ($query) use ($antenne, $categorie) {
            $query->where('categorie', $categorie)
                ->where('antenne_id', $antenne);
        })
            ->get();
        if($id != null)
        {
            $produits = $produits->where('zone_stock_id', $id);
        }

        $antennes = auth()->user()->antennes()->pluck('nom', 'antennes.id');

        $categorie = 'Pharmacie';

        return view('produit-list',
            compact(
                'produits',
                'antennes',
            'categorie'));
    }

    public function transferView($id)
    {
        $produit = Produit::with(['typeProduit', 'zoneStock'])->findOrFail($id);
        $zonesStock = ZoneStock::whereIn('antenne_id', auth()->user()->antennes->pluck('id'))->get();
        $typeProduits = TypeProduit::all();

        // Retire la zone actuelle du produit de la liste des zones de stock
        $zonesStock = $zonesStock->where('id', '!=', $produit->zone_stock_id);

        return view('produit-transferView', compact('produit', 'zonesStock', 'typeProduits'));
    }

    public function transfertUpdate(Request $request, Produit $produit)
    {

        $request->validate([
            'zone_stock_id' => 'required|exists:zones_stocks,id',
            'quantite' => 'required|integer|min:1|max:' . $produit->quantite,
        ]);



        $quantiteATransferer = $request->quantite;
        $zoneCibleId = $request->zone_stock_id;

        try {
            DB::transaction(function () use ($produit, $quantiteATransferer, $zoneCibleId) {
                // 1. Décrémenter la quantité de la ligne source
                $produit->quantite -= $quantiteATransferer;
                $produit->save();

                // 2. Chercher une ligne cible
                $query = Produit::where('type_produit_id', $produit->type_produit_id)
                    ->where('zone_stock_id', $zoneCibleId);

                if ($produit->date_peremption) {
                    $query->whereDate('date_peremption', $produit->date_peremption);
                } else {
                    $query->whereNull('date_peremption');
                }

                $ligneCible = $query->first();

                if ($ligneCible) {
                    // 3. Ajouter la quantité
                    $ligneCible->quantite += $quantiteATransferer;
                    $ligneCible->save();
                    Log::info("Mise à jour de la ligne cible ID : " . $ligneCible->id . " avec la nouvelle quantité : " . $ligneCible->quantite);
                } else {
                    // 4. Créer une nouvelle ligne
                    Produit::create([
                        'type_produit_id' => $produit->type_produit_id,
                        'zone_stock_id' => $zoneCibleId,
                        'quantite' => $quantiteATransferer,
                        'date_peremption' => $produit->date_peremption, // peut être null
                    ]);
                    Log::info("Création d'une nouvelle ligne pour le produit ID : " . $produit->id . " dans la zone cible ID : " . $zoneCibleId);
                }
                // 5. Si la quantité devient 0, on supprime la ligne source
                Log::info("Quantité après transfert : " . $produit->quantite);
                if ($produit->quantite <= 0) {
                    Log::info("Suppression du produit ID : " . $produit->id);
                    $produit->delete();
                }
            });
        } catch (\Exception $e) {
            Log::error("Erreur lors du transfert de produit ID : " . $produit->id . " de la zone ID : " . $produit->zone_stock_id . " vers la zone ID : " . $zoneCibleId . " avec une quantité de : " . $quantiteATransferer);

            return redirect()->route('dashboard')->with('error', 'Une erreur est survenue lors du transfert du produit.<br>Veuillez contacter l\'administrateur.');
        }

        Log::info("Transfert de produit ID : " . $produit->id . " de la zone ID : " . $produit->zone_stock_id . " vers la zone ID : " . $zoneCibleId . " avec une quantité de : " . $quantiteATransferer . " par l'utilisateur " . auth()->user()->identifiant);
        return redirect()->route('dashboard')->with('success', 'Transfert effectué.');
    }

}
