<?php

namespace Database\Seeders;

use App\Models\Vehiculo;
use App\Models\Modelo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $colores = ['Blanco', 'Negro', 'Gris', 'Rojo', 'Azul', 'Verde', 'Amarillo', 'Plata', 'Dorado', 'Marrón'];
        $patentes = [
            'ABC123', 'XYZ789', 'DEF456', 'GHI012', 'JKL345',
            'MNO678', 'PQR901', 'STU234', 'VWX567', 'YZA890',
            'BCD123', 'EFG456', 'HIJ789', 'KLM012', 'NOP345'
        ];
        
        $modelos = Modelo::all();
        
        if ($modelos->count() > 0) {
            foreach ($patentes as $index => $patente) {
                Vehiculo::firstOrCreate([
                    'patente' => $patente
                ], [
                    'color' => $colores[array_rand($colores)],
                    'anio' => rand(2015, 2024),
                    'modelo_id' => $modelos->random()->id
                ]);
            }
        }
    }
}
