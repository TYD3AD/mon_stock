<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ZoneStock extends Model
{
    use HasFactory;

    protected $table = 'zones_stocks';

    protected $fillable = ['id', 'nom', 'antenne_id', 'categorie'];
    private mixed $id;

    public function antenne()
    {
        return $this->belongsTo(Antenne::class, 'antenne_id');
    }

    public function getId()
    {
        return $this->id;
    }

    public function produits()
    {
        return $this->hasMany(Produit::class, 'zone_stock_id');
    }
}
