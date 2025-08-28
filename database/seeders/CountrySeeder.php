<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Country::create(['nombre' => 'Argentina']);
        Country::create(['nombre' => 'Brasil']);
        Country::create(['nombre' => 'Chile']);
        Country::create(['nombre' => 'Colombia']);
        Country::create(['nombre' => 'Uruguay']);
    }
}
