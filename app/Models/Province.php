<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 
        'country_id',

    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
