<?php

namespace Database\Seeders;

use App\Models\OrdenTrabajo;
use App\Models\Vehiculo;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrdenTrabajoSeeder extends Seeder
{
    protected $faker;
    
    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Verificar que hay vehículos y usuarios existentes
        $vehiculosCount = Vehiculo::count();
        $usuariosCount = User::count();
        
        if ($vehiculosCount === 0) {
            $this->command->info('No hay vehículos disponibles. Por favor, ejecuta primero el seeder de vehículos.');
            return;
        }
        
        if ($usuariosCount === 0) {
            $this->command->info('No hay usuarios disponibles. Por favor, crea usuarios primero.');
            return;
        }

        // Obtener IDs existentes para usar en las órdenes
        $vehiculoIds = Vehiculo::pluck('id')->toArray();
        $userIds = User::pluck('id')->toArray();

        // Crear órdenes de trabajo con diferentes estados
        for ($i = 0; $i < 5; $i++) {
            OrdenTrabajo::factory()->pendiente()->create([
                'vehiculo_id' => $this->faker->randomElement($vehiculoIds),
                'user_id' => $this->faker->randomElement($userIds),
            ]);
        }
        
        for ($i = 0; $i < 3; $i++) {
            OrdenTrabajo::factory()->completado()->create([
                'vehiculo_id' => $this->faker->randomElement($vehiculoIds),
                'user_id' => $this->faker->randomElement($userIds),
            ]);
        }
        
        for ($i = 0; $i < 2; $i++) {
            OrdenTrabajo::factory()->urgente()->create([
                'vehiculo_id' => $this->faker->randomElement($vehiculoIds),
                'user_id' => $this->faker->randomElement($userIds),
            ]);
        }
        
        for ($i = 0; $i < 4; $i++) {
            OrdenTrabajo::factory()->prioridadAlta()->create([
                'vehiculo_id' => $this->faker->randomElement($vehiculoIds),
                'user_id' => $this->faker->randomElement($userIds),
            ]);
        }
        
        // Crear algunas órdenes adicionales con estados variados
        for ($i = 0; $i < 6; $i++) {
            OrdenTrabajo::factory()->create([
                'vehiculo_id' => $this->faker->randomElement($vehiculoIds),
                'user_id' => $this->faker->randomElement($userIds),
            ]);
        }

        $this->command->info('Órdenes de trabajo creadas exitosamente.');
    }
}
