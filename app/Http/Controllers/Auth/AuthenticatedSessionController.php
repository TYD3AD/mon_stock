<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     * @throws ValidationException
     */
    public function store(LoginRequest $request): RedirectResponse
    {

        $request->authenticate();

        $request->session()->regenerate();

        Log::info("L'utilisateur " . auth()->user()->identifiant . " s'est connecté avec succès.");

        // Vérification du champ 'doitChangerPassword'
        $doitChangerPassword = auth()->user()->getDoitChangerPassword();    // 1 == doit changer mot de passe

        if ($doitChangerPassword === 1) {
            return redirect()->route('password.edit')
                ->with('status', 'Vous devez changer votre mot de passe avant de continuer.');
        }

        return redirect()->intended(route('dashboard', absolute: false));

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Log::info("L'utilisateur " . auth()->user()->identifiant . " s'est déconnecté.");
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
