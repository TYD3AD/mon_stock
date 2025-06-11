<?php

namespace App\Http\Controllers;

use App\Models\Antenne;

use App\Models\ZoneStock;
use Hamcrest\Util;
use Illuminate\Support\Facades\Auth;
use App\Models\Produit;

class DashboardController extends Controller
{
    public function index()
    {
        // Récupère les produits de toutes les zones des antennes de l'utilisateur
        $produits = UtilsController::getProduitsZones();

        // Récupère l'antenne principale de l'utilisateur
        $antennePrincipale = UtilsController::getAntennePrincipale();

        // Récupère les antennes de l'utilisateur, en excluant l'antenne principale
        $antennes = UtilsController::getAntennesUserMoinsAntennePrinciaple();

        // récupère les zones de stock des antennes de l'utilisateur
        $zones = UtilsController::getZonesAntennes();


        return view('dashboard', compact('produits', 'antennes', 'antennePrincipale', 'zones'));
    }
}
