<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Stock;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Récupérer tous les stocks des zones liées à l’antenne de l’utilisateur
        $stocks = Stock::whereHas('zoneStock', function ($query) use ($user) {
            $query->where('antenne_id', $user->antenne_id);
        })->with(['produit', 'zoneStock'])->get();

        return view('dashboard', compact('stocks'));
    }
}
