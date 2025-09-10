<?php

namespace Database\Seeders;

use App\Models\Marca;
use App\Models\Modelo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModeloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modelos = [
            'Toyota' => ['Corolla', 'Camry', 'RAV4', 'Hilux', 'Yaris'],
            'Ford' => ['Focus', 'Fiesta', 'Mustang', 'F-150', 'Explorer'],
            'Chevrolet' => ['Cruze', 'Spark', 'Malibu', 'Silverado', 'Equinox'],
            'Volkswagen' => ['Golf', 'Polo', 'Passat', 'Tiguan', 'Amarok'],
            'Peugeot' => ['208', '308', '408', '2008', '3008'],
            'Renault' => ['Clio', 'Megane', 'Logan', 'Sandero', 'Duster'],
            'Fiat' => ['Palio', 'Siena', 'Uno', '500', 'Toro'],
            'Honda' => ['Civic', 'Accord', 'CR-V', 'Fit', 'Pilot'],
            'Nissan' => ['Sentra', 'Altima', 'X-Trail', 'Versa', 'Frontier'],
            'Hyundai' => ['Elantra', 'Accent', 'Tucson', 'Santa Fe', 'i10']
        ];

        foreach ($modelos as $marcaNombre => $modelosArray) {
            $marca = Marca::where('nombre', $marcaNombre)->first();
            
            if ($marca) {
                foreach ($modelosArray as $modeloNombre) {
                    Modelo::firstOrCreate([
                        'nombre' => $modeloNombre,
                        'marca_id' => $marca->id
                    ]);
                }
            }
        }
    }
}
