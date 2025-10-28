<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Exception;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        Log::info("Demande d'envoi d'un nouvel email de vérification pour l'utilisateur : " . $request->user()->identifiant);
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        try {
            $request->user()->sendEmailVerificationNotification();
            Log::info("Email de vérification envoyé avec succès à l'utilisateur : " . $request->user()->identifiant);
            return back()->with('status', 'verification-link-sent');
        }
        catch (Exception $e)
        {
            Log::error("Erreur lors de l'envoi du mail de vérification pour l'utilisateur " . $request->user()->identifiant . " erreur : " . $e->getMessage());
            return back()->withErrors(['email' => 'Une erreur est survenue lors de l\'envoi de l\'email de vérification. Veuillez réessayer plus tard.']);
        }


    }
}
