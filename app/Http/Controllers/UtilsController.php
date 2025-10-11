<?php

namespace App\Http\Controllers;

use App\Models\AccesAntenne;
use App\Models\Antenne;
use App\Models\Produit;
use App\Models\User;
use App\Models\ZoneStock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UtilsController extends Controller
{

    public static function getAntennePrincipale()
    {
        // Récupère l'antenne principale de l'utilisateur connecté
        $antennePrincipale = self::getUser()->antennePrincipale()->get();

        // Vérifie si l'utilisateur a une antenne principale
        if (!$antennePrincipale) {
            return response()->json(['error' => 'Aucune antenne principale trouvée.'], 404);
        }

        // Retourne les détails de l'antenne principale
        return $antennePrincipale;
    }

    public static function getAntennesUser()
    {
        // Récupère les antennes de l'utilisateur connecté
        $antennes = self::getUser()->antennes()->get();

        // Vérifie si l'utilisateur a des antennes
        if ($antennes->isEmpty()) {
            return response()->json(['error' => 'Aucune antenne trouvée pour cet utilisateur.'], 404);
        }

        // Retourne les détails des antennes de l'utilisateur
        return $antennes;
    }

    public static function getAntennesUserMoinsAntennePrinciaple()
    {
        // Récupère les antennes de l'utilisateur connecté
        $antennes = self::getAntennesUser();
        $antennes = $antennes->where('id', '!=', self::getUser()->antenne_id);
        // Vérifie si l'utilisateur a des antennes
        if ($antennes->isEmpty()) {
            return response()->json(['error' => 'Aucune antenne trouvée pour cet utilisateur.'], 404);
        }

        // Retourne les détails des antennes de l'utilisateur
        return $antennes;
    }

    public static function getUser()
    {
        $user = Auth::user();

        // Vérifie si l'utilisateur est connecté
        if (!$user) {
            return response()->json(['error' => 'Utilisateur non connecté.'], 401);
        }
        // Retourne les détails de l'utilisateur
        return $user;
    }

    public static function getZonesAntennes()
    {
        $zones = ZoneStock::whereIn('antenne_id', self::getAntennesUser()->pluck('id'))->get();

        // Vérifie si l'utilisateur a des zones de stock
        if ($zones->isEmpty()) {
            return response()->json(['error' => 'Aucune zone de stock trouvée pour les antennes de cet utilisateur.'], 404);
        }

        return $zones;
    }

    public static function getNomTypeZone($id) : string
    {
        $nomTypeZone = "null";
        switch ($id) {
            case 1:
                $nomTypeZone = "Pharmacie";
                break;
            case 2:
                $nomTypeZone = "VTU";
                break;
            case 3:
                $nomTypeZone = "VPSP";
                break;
            case 4:
                $nomTypeZone = "Autre";
                break;
            default:
                $nomTypeZone = "Inconnu";
                break;
        }

        return $nomTypeZone;
    }


    public static function getProduitsZones()
    {
        // Récupère les produits de toutes les zones des antennes de l'utilisateur
        $produits = Produit::whereHas('zoneStock.antenne.accesAntennes', function ($query) {
            $query->where('id_user', Auth::id());
        })->with(['zoneStock.antenne.accesAntennes.user'])->get();



        foreach ($produits as $produit) {
            $produit ? Carbon::parse($produit->date_peremption)->format('d/m/Y') : '—';
        }

        return $produits;
    }

    public static function estResponsable(int $idAntenne)
    {
        $user = self::getUser();
        if (!$user) {
            return false;
        }

        $acces = AccesAntenne::where('id_user', $user->id)
            ->where('id_antenne', $idAntenne)
            ->first();

        return $acces && $acces->est_responsable == 1;
    }


}
