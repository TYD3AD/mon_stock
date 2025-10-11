<?php

namespace App\Http\Controllers;

use App\Models\Antenne;
use App\Models\ZoneStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ZoneController extends Controller
{
    public function addZone(Request $request, $antenneId)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'type_zone' => 'nullable|integer',
        ]);
        $antenneId = intval($antenneId);

//        Si l'antenne existe
        if(Antenne::where('id', $antenneId)->first())
        {
            try {

                $zoneStock = ZoneStock::create([
                    'nom' => $validatedData['nom'],
                    'antenne_id' => $antenneId,
                    'categorie' => $validatedData['type_zone'] ?? null,
                ]);


                Log::info("L'utilisateur " . auth()->user()->identifiant . " a créé une nouvelle zone : ID {$zoneStock->id}, Nom: {$zoneStock->nom}, Antenne ID: {$zoneStock->antenne_id}, Catégorie: {$zoneStock->categorie}");
                return back()->with('success', 'La zone a été ajoutée avec succès.');
            } catch (\Exception $e) {
                Log::error("L'utilisateur " . auth()->user()->identifiant . " a rencontré une erreur lors de la création de la zone pour l'antenne ID: {$antenneId}. Erreur: " . $e->getMessage());
                return back()->with('error', "Une erreur est survenue lors de l'ajout de la zone.");
            }
        }
        Log::error("L'utilisateur " . auth()->user()->identifiant . " a tenté de créer une zone pour une antenne ID: {$antenneId} qui n'existe pas.");
        return back()->with('error', "Une erreur est survenue lors de l'ajout de la zone.");
    }

    public function deleteZone(int $zoneId)
    {
        $zoneStock = ZoneStock::where('id', $zoneId)
            ->first();

        if ($zoneStock != null) {
            $zoneStock->delete();
            Log::info("L'utilisateur " . auth()->user()->identifiant . " a supprimé la zone ID: {$zoneId}, Nom: {$zoneStock->nom}, Antenne ID: {$zoneStock->antenne_id}, Catégorie: {$zoneStock->categorie}");
            return back()->with('success', 'La zone a été supprimée avec succès.');
        } else {
            Log::error("L'utilisateur " . auth()->user()->identifiant . " a tenté de supprimer une zone ID: {$zoneId} qui n'existe pas.");
            return back()->with('error', 'Une erreur est survenue au moment de la suppression de la zone.');
        }
    }
}
