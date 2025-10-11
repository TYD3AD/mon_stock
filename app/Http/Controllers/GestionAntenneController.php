<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\UtilsController;
use Illuminate\Support\Facades\Log;

class GestionAntenneController extends Controller
{
    // Affiche la page de gestion des antennes où pour chaque antenne dont l'utilisateur a accès, on affiche un tableau avec tous les utilisateurs de l'antenne et s'ils sont responsables ou non.
//    public function store()
//    {
//        // Récupère les antennes de l'utilisateur
//        $antennes = UtilsController::getAntennesUser();
//
//        // on créé un tableau associatif pour stocker l'antenne et si l'utilisateur est responsable ou non
//        $tableauAntennes = [];
//
//        foreach ($antennes as $antenne) {
//            // Récupère les zones de stock de chaque antenne
//            if(UtilsController::estResponsable($antenne->id))
//            {
//                $tableauAntennes[$antenne->id] = [
//                    'antenne' => $antenne,
//                    'responsable' => true
//                ];
//            } else {
//                $tableauAntennes[$antenne->id] = [
//                    'antenne' => $antenne,
//                    'responsable' => false
//                ];
//            }
//        }
//
//
//        return view('gestion-antenne-store', compact('tableauAntennes'));
//    }
    // Affiche la page de gestion des antennes où pour chaque antenne dont l'utilisateur a accès, on affiche un tableau avec tous les utilisateurs de l'antenne et s'ils sont responsables ou non.
    public function store()
    {
        // Récupère les antennes auxquelles l'utilisateur a accès
        $antennes = UtilsController::getAntennesUser();

        $tableauAntennes = [];

        foreach ($antennes as $antenne) {
            // Vérifie si l'utilisateur connecté est responsable de cette antenne
            $estResponsable = UtilsController::estResponsable($antenne->id);

            // Récupère tous les utilisateurs ayant accès à cette antenne (via la table pivot)
            $utilisateurs = $antenne->accesAntennes()->with('user')->get()->map(function ($acces) {
                return [
                    'user' => $acces->user,
                    'est_responsable' => (bool) $acces->est_responsable,
                ];
            });

            // Récupère toutes les zones des antennes dont l'utilisateur a accès
            $zones = UtilsController::getZonesAntennes();
            foreach ($zones as $zone) {
                $nomTypeZone = UtilsController::getNomTypeZone($zone->categorie);
                $zone->nomTypeZone = $nomTypeZone;
            }

            // Stocke les infos dans le tableau associatif
            $tableauAntennes[$antenne->id] = [
                'antenne' => $antenne,
                'responsable' => $estResponsable,
                'utilisateurs' => $utilisateurs,
                'zones' => $zones->where('antenne_id', $antenne->id),
            ];
        }
        $users = User::all(['id', 'identifiant', 'email', 'nom', 'prenom']);

        return view('gestion-antenne-store', compact('tableauAntennes', 'users'));
    }


    public function ajouterUtilisateur(Request $request)
    {

        return back("Membre ajouté avec succès !");
    }
}
