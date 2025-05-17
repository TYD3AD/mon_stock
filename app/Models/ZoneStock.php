<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ZoneStock extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'antenne_id'];

    public function antenne()
    {
        return $this->belongsTo(Antenne::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
