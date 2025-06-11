<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('query');
        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->get(['id', 'name', 'email']);

        return response()->json($users);
    }

    public function ajouterUtilisateur(Request $request, Antenne $antenne)
    {
        $userId = $request->input('user_id');
        if (!$antenne->users()->where('user_id', $userId)->exists()) {
            $antenne->users()->attach($userId);
        }
        return response()->noContent(); // 204
    }


}
