<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Antenne extends Model
{
    use HasFactory;

    protected $fillable = ['nom'];

    public function zonesStock()
    {
        return $this->hasMany(ZoneStock::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
