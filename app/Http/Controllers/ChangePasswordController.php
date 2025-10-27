<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class ChangePasswordController extends Controller
{
    public function edit()
    {
        return view('changement-password');
    }

    public function update(Request $request)
    {

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed'],
        ], [
            'current_password.required' => 'Veuillez saisir votre mot de passe actuel.',
            'current_password.current_password' => 'Le mot de passe actuel est incorrect.',
            'password.required' => 'Veuillez saisir un nouveau mot de passe.',
            'password.confirmed' => 'Les deux nouveaux mots de passe ne correspondent pas.',
        ]);

        $user = $request->user();
        $user->update([
            'password' => Hash::make($request->password),
            'doitChangerPassword' => 0, // on remet à zéro
        ]);

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
