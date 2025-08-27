<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    use HasFactory;

<<<<<<< HEAD
    protected $table = 'tareas';
    
    protected $fillable = ['titulo', 'completada'];

    protected $casts = [
        'completada' => 'boolean'
    ];
}
=======
    protected $fillable = ['titulo', 'completada'];
    
    protected $casts = [
        'completada' => 'boolean',
    ];
}
>>>>>>> 6480bc3 (git)
