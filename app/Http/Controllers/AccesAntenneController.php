<?php

namespace App\Http\Controllers;

use App\Models\AccesAntenne;
use App\Models\Antenne;
use App\Models\User;
use DragonCode\Support\Helpers\Boolean;
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

    public function ajouterUtilisateur(Request $request, int $antenneId, int $userId)
    {

        // Validation explicite des paramètres
        $validatedData = $request->validate([
            // Rien dans le corps ici, car tout est dans l'URL
        ]);

        try {

            // Vérifier l'existence de l'antenne et de l'utilisateur
            $antenne = Antenne::where('id', $antenneId)->first();
            $user = User::find($userId);
            if (!$antenne || !$user) {
                Log::error("Antenne ID: {$antenneId} ou Utilisateur ID: {$userId} non trouvé.");
                return redirect()->with('error', 'Antenne ou utilisateur non trouvé.');
            }

            $existe = AccesAntenne::where('id_user', $userId)
                ->where('id_antenne', $antenneId)
                ->exists();

            if ($existe) {
                return redirect()->back()->with(['success', 'Utilisateur déjà ajouté.']);
            }

            AccesAntenne::create([
                'id_user' => $userId,
                'id_antenne' => $antenneId,
                'est_responsable' => false
            ]);


            if (!$user) {
                log::error("Utilisateur ID: {$userId} non trouvé.");
                return response()->json(['success' => false, 'message' => 'Utilisateur non trouvé.'], 404);
            }

        } catch (\Exception $e) {
            log::error("Erreur lors de la récupération de l'utilisateur ID: {$userId} - " . $e->getMessage());
        }

        log::info("Utilisateur : {$user->identifiant} (ID: {$user->id}) ajouté à l'antenne ID: {$antenneId}");
        return redirect()->back()->with('success', 'Utilisateur ajouté avec succès.');
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

    public function deleteUser(int $idAntenne, User $user)
    {
        $accesAntenne = AccesAntenne::where('id_user', $user->id)
            ->where('id_antenne', $idAntenne)
            ->first();
        if ($accesAntenne != null) {
            $accesAntenne->delete();
            Log::info("Utilisateur ID: {$user->id} supprimé de l'antenne ID: {$idAntenne}");
            return back()->with('success', 'Utilisateur supprimé avec succès.');
        } else {
            Log::error("Accès à l'antenne non trouvé pour l'utilisateur ID: {$user->id} et antenne ID: {$idAntenne}");
            return back()->with('error', 'Accès à l\'antenne non trouvé.');
        }
    }

}
