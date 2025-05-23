<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccesAntenne extends Model
{
    // Par défaut, Laravel s'attend à une table `acces_antennes` (pluriel).
    // Si ta table s'appelle `acces_antenne`, précise-le :
    protected $table = 'acces_antenne';

    // Si la table n'utilise pas de timestamps (created_at, updated_at)
    public $timestamps = false;

    // Définis les colonnes remplissables
    protected $fillable = [
        'id_user',
        'id_antenne',
        // ajoute d'autres colonnes ici si besoin
    ];

    // Relations (facultatif mais recommandé)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }


    public function antenne()
    {
        return $this->belongsTo(Antenne::class, 'id_antenne');
    }
}
