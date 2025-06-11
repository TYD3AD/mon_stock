<?php

namespace App\Http\Controllers;

use App\Models\AccesAntenne;
use App\Models\Antenne;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AccesAntenneController extends Controller
{
    public function rechercherUtilisateur(Request $request)
    {
        $query = $request->input('q');
        $antenneId = $request->input('antenne');

        $usersDejaPresents = AccesAntenne::where('id_antenne', $antenneId)->pluck('id_user');

        $users = User::whereNotIn('id', $usersDejaPresents)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%$query%")
                    ->orWhere('email', 'like', "%$query%");
            })
            ->limit(10)
            ->get(['id', 'name', 'email']);

        return response()->json($users);
    }

    public function ajouterUtilisateur(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'antenne_id' => 'required|exists:antennes,id',
        ]);

        $existe = AccesAntenne::where('id_user', $request->user_id)
            ->where('id_antenne', $request->antenne_id)
            ->exists();

        if ($existe) {
            return response()->json(['success' => false, 'message' => 'Utilisateur déjà ajouté.']);
        }

        AccesAntenne::create([
            'id_user' => $request->user_id,
            'id_antenne' => $request->antenne_id,
            'est_responsable' => false
        ]);

        try {
            $user = User::find($request->user_id);
            if (!$user) {
                log::error("Utilisateur ID: {$request->user_id} non trouvé.");
                return response()->json(['success' => false, 'message' => 'Utilisateur non trouvé.'], 404);
            }
        } catch (\Exception $e) {
            log::error("Erreur lors de la récupération de l'utilisateur ID: {$request->user_id} - " . $e->getMessage());
        }

        log::info("Utilisateur : {$user->name} (ID: {$user->id}) ajouté à l'antenne ID: {$request->antenne_id}");
        return response()->json([
            'success' => true,
            'user' => $user,
            'est_responsable' => false,
            'peut_modifier_responsable' => true // ou selon une logique de permission
        ]);
    }




    public function toggleResponsable(Request $request, int $antenneId, int $userId)
    {
        $currentUser = Auth::user();

        // Récupérer l'antenne
        $antenne = Antenne::find($antenneId);
        if (!$antenne) {
            return response()->json(['success' => false, 'message' => 'Antenne non trouvée.'], 404);
        }

        // Récupérer l'utilisateur ciblé
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Utilisateur non trouvé.'], 404);
        }

        // Vérifier que l'utilisateur courant est responsable dans cette antenne
        $accesCurrentUser = AccesAntenne::where('id_user', $currentUser->id)
            ->where('id_antenne', $antenne->id)
            ->where('est_responsable', true)
            ->first();

        if (!$accesCurrentUser) {
            return response()->json(['success' => false, 'message' => 'Accès refusé.'], 403);
        }

        // Empêcher de se retirer soi-même
        if ($currentUser->id === $user->id) {
            return response()->json(['success' => false, 'message' => 'Vous ne pouvez pas vous retirer vous-même.'], 403);
        }

        // Récupérer la relation AccesAntenne de l'utilisateur ciblé
        $acces = AccesAntenne::where('id_user', $user->id)
            ->where('id_antenne', $antenne->id)
            ->first();

        if (!$acces) {
            return response()->json(['success' => false, 'message' => 'Utilisateur non lié à cette antenne.'], 404);
        }

        // Récupérer la nouvelle valeur 'est_responsable' depuis la requête JSON
        $newValue = $request->input('est_responsable');

        // Si la valeur est absente ou non booléenne, erreur
        if (!is_bool($newValue)) {
            return response()->json(['success' => false, 'message' => 'Valeur invalide pour est_responsable.'], 422);
        }

        $acces->est_responsable = $newValue;
        $acces->save();

        return response()->json(['success' => true, 'newValue' => $acces->est_responsable]);
    }




}
