<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'desc', 'perissable'];

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
