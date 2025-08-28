<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Province;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Province::create([
            'nombre'     => 'Misiones',
            'country_id' => 1 //Argentina
            ]);

        Province::create([
            'nombre'     => 'Entre Rios',
            'country_id' => 1 //Argentina
             ]);

        Province::create([
            'nombre'     => 'Corrientes',
            'country_id' => 1 //Argentina
            ]);
    }
}