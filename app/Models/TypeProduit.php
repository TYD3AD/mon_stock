<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeProduit extends Model
{
    use HasFactory;

    protected $table = 'types_produits';

    protected $fillable = ['nom', 'desc', 'perissable'];

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }
}
