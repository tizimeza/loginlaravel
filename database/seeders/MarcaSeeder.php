<?php

namespace Database\Seeders;

use App\Models\Marca;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MarcaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $marcas = [
            'Toyota',
            'Ford',
            'Chevrolet',
            'Volkswagen',
            'Peugeot',
            'Renault',
            'Fiat',
            'Honda',
            'Nissan',
            'Hyundai'
        ];

        foreach ($marcas as $marca) {
            Marca::firstOrCreate(['nombre' => $marca]);
        }
    }
}
