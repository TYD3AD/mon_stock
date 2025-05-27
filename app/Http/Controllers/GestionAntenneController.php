<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GestionAntenneController extends Controller
{
    public function store()
    {
        return view('gestion-antenne-store');
    }
}
