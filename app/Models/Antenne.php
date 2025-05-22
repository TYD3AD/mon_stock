<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Antenne extends Model
{
    use HasFactory;

    protected $fillable = ['nom'];

    public function typesProduits()
    {
        return $this->hasMany(TypeProduit::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'acces_antenne', 'id_antenne', 'id_user');
    }
}
