<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeProduit extends Model
{
    use HasFactory;

    protected $table = 'type_produit';

    protected $fillable = ['nom', 'desc', 'perissable'];

    public function produits()
    {
        return $this->hasMany(Produits::class);
    }
}
