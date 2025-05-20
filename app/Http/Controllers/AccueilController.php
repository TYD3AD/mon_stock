<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AccueilController extends Controller
{
    public function index()
    {
        $aujourdHui = Carbon::today();

        $stocks = Stock::with(['produit', 'zoneStock'])
            ->whereNotNull('date_peremption')
            ->orderBy('date_peremption')
            ->get()
            ->map(function ($stock) use ($aujourdHui) {
                $diff = $aujourdHui->diffInDays(Carbon::parse($stock->date_peremption), false);

                if ($diff < 0) {
                    $stock->couleur = 'noir'; // périmé
                } elseif ($diff <= 15) {
                    $stock->couleur = 'rouge';
                } elseif ($diff <= 60) {
                    $stock->couleur = 'jaune';
                } else {
                    $stock->couleur = 'vert';
                }

                return $stock;
            });

        foreach ($stocks as $stock) {
            $stock ? \Carbon\Carbon::parse($stock->date_peremption)->format('d/m/Y') : '—';
        }

        return view('accueil', compact('stocks'));
    }
}
