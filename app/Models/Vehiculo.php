<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    protected $fillable = ['patente', 'color', 'anio', 'modelo_id'];

    /**
     * Relación: un Vehículo pertenece a un Modelo
     */
    public function modelo()
    {
        return $this->belongsTo(Modelo::class);
    }
}
