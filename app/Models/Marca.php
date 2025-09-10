<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    /**
     * Define la relación de uno a muchos.
     * Una marca puede tener muchos modelos.
     */
    public function modelos()
    {
        return $this->hasMany(Modelo::class);
    }

    /**
     * Define la relación de uno a muchos a través de modelos.
     * Una marca puede tener muchos vehículos a través de sus modelos.
     */
    public function vehiculos()
    {
        return $this->hasManyThrough(Vehiculo::class, Modelo::class);
    }
}