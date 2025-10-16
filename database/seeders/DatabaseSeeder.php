<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 1. Primero: Crear roles, permisos y usuarios
        $this->call(RolePermissionSeeder::class);

        // 2. Datos geográficos
        $this->call(CountrySeeder::class);
        $this->call(ProvinceSeeder::class);

        // 3. Datos de vehículos (marcas, modelos, vehículos)
        $this->call(MarcaSeeder::class);
        $this->call(ModeloSeeder::class);
        $this->call(VehiculoSeeder::class);

        // 4. Clientes
        $this->call(ClienteSeeder::class);

        // 5. Grupos de trabajo
        $this->call(GrupoTrabajoSeeder::class);

        // 6. Stock/Inventario
        $this->call(StockTecnoServiSeeder::class);

        // 7. Tareas predefinidas
        $this->call(TareaSeeder::class);

        // 8. Finalmente: Órdenes de trabajo (depende de todo lo anterior)
        $this->call(OrdenTrabajoSeeder::class);

        $this->command->info('✅ Base de datos poblada exitosamente con todos los datos de prueba');
    }
}
