<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    /**
     * Define la relación inversa de uno a muchos.
     * Un modelo pertenece a una marca.
     */
    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    /**
     * Define la relación de uno a muchos.
     * Un modelo puede tener muchos vehículos.
     */
    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class);
    }
}