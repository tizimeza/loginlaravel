<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    // Campos que se pueden llenar masivamente
    protected $fillable = ['nombre'];

    /**
     * Relación: Una Marca tiene muchos Modelos
     */
    public function modelos()
    {
        return $this->hasMany(Modelo::class);
    }
}
