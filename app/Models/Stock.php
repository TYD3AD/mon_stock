<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = ['produit_id', 'zone_stock_id', 'quantite', 'date_peremption'];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function zoneStock()
    {
        return $this->belongsTo(ZoneStock::class);
    }
}
