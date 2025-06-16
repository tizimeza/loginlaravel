<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tarea;

class TareaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $tareas = [
            [
                'nombre' => 'Instalacion',
                'completada' => false,
            ],
            [
                'nombre' => 'service',
                'completada' => true,
            ],
            [
                'nombre' => 'Reconexión',
                'completada' => false,
            ],
            [
                'nombre' => 'Desconexion',
                'completada' => false,
            ],
           
        ];

        foreach ($tareas as $tarea) {
            Tarea::create($tarea);
        }
    }
}
