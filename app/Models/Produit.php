<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;

class Produit extends Model
{
    use HasFactory;

    protected $table = 'produits';


    protected $fillable = ['type_produit_id', 'zone_stock_id', 'quantite', 'date_peremption'];

    public function typeProduit()
    {
        return $this->belongsTo(TypeProduit::class, 'type_produit_id');
    }


    public function zoneStock()
    {
        return $this->belongsTo(ZoneStock::class, 'zone_stock_id');
    }


    protected $casts = [
        'date_peremption' => 'date',
    ];

    // Constantes des seuils en jours
    public const SEUILS = [
        'perime' => 0,          // Périmé
        'tres_proche' => 31,    // Très proche 1 mois restant
        'proche' => 32,         // Proche 1 mois à 2 mois restants
        'correcte' => 60,       // Correcte 2 mois à 3 mois restants
        'loin' => 61            // Loin plus de 3 mois restants
    ];

    // Méthode pour récupérer la classe CSS en fonction de la date de péremption
    public function getStatus(): string
    {
        $today = Carbon::today();
        $peremptionDate = Carbon::make($this->date_peremption);

        if (!$peremptionDate) {
            return 'expire';
        }

        $daysDiff = $today->diffInDays($peremptionDate, false);
        switch ($daysDiff){
            case $daysDiff < self::SEUILS['perime']: $peremption = 'perime';
            break;
            case $daysDiff <= self::SEUILS['tres_proche']: $peremption = 'tres_proche';
            break;
            case $daysDiff <= self::SEUILS['proche']: $peremption = 'proche';
            break;
            case  $daysDiff <= self::SEUILS['correcte']: $peremption = 'correcte';
            break;
            default : $peremption = 'loin';
        }

        return $peremption;
    }



}
