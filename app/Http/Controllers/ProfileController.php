<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Affiche le formulaire de profil utilisateur
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Met à jour les informations du profil utilisateur
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Met à jour l'adresse e-mail de l'utilisateur
     */
    public function updateEmail(Request $request): RedirectResponse
    {

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
        ],
        [
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email' => 'L\'adresse e-mail doit être une adresse e-mail valide.',
            'email.max' => 'L\'adresse e-mail ne doit pas dépasser 255 caractères.',
            'email.unique' => 'Cette adresse e-mail est déjà utilisée.',
        ]);

        $request->user()->email = $validated['email'];
        $request->user()->email_verified_at = now();
        //        $request->user()->email_verified_at = null;

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Supprime le compte utilisateur ainsi que ses accès aux antennes associées.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();
        // Récupère les antennes associées à l'utilisateur
        $user->antennes()->each(function ($antenne) use ($user) {
            // Supprime l'accès de l'utilisateur à chaque antenne
            Log::warning("Suppression de l'accès de l'utilisateur {$user->id} à l'antenne {$antenne->id}");
            $antenne->accesAntennes()->where('id_user', $user->id)->delete();
        });

        Log::warning("Suppression de l'utilisateur {$user->id} et de ses accès aux antennes associées");
        $user->delete();

        $request->session()->invalidate();

        return Redirect::to('/');
    }
}
