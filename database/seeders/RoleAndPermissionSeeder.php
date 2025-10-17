<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Resetear roles y permisos en caché
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos
        $permissions = [
            // Clientes
            'ver_clientes',
            'crear_clientes',
            'editar_clientes',
            'eliminar_clientes',

            // Órdenes de Trabajo
            'ver_ordenes',
            'crear_ordenes',
            'editar_ordenes',
            'eliminar_ordenes',
            'asignar_ordenes',
            'cambiar_estado_ordenes',

            // Vehículos
            'ver_vehiculos',
            'crear_vehiculos',
            'editar_vehiculos',
            'eliminar_vehiculos',

            // Stock/Inventario
            'ver_stock',
            'crear_stock',
            'editar_stock',
            'eliminar_stock',
            'ajustar_stock',

            // Grupos de Trabajo
            'ver_grupos',
            'crear_grupos',
            'editar_grupos',
            'eliminar_grupos',
            'asignar_miembros_grupos',

            // Reportes
            'ver_reportes',
            'generar_reportes',

            // Administración
            'administrar_usuarios',
            'administrar_roles',
            'ver_dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Crear Roles y asignar permisos

        // 1. ADMIN - Acceso total
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleAdmin->syncPermissions(Permission::all());

        // 2. SUPERVISOR - Gestión operativa completa
        $roleSupervisor = Role::firstOrCreate(['name' => 'supervisor']);
        $roleSupervisor->syncPermissions([
            'ver_clientes', 'crear_clientes', 'editar_clientes',
            'ver_ordenes', 'crear_ordenes', 'editar_ordenes', 'asignar_ordenes', 'cambiar_estado_ordenes',
            'ver_vehiculos', 'crear_vehiculos', 'editar_vehiculos',
            'ver_stock', 'crear_stock', 'editar_stock', 'ajustar_stock',
            'ver_grupos', 'crear_grupos', 'editar_grupos', 'asignar_miembros_grupos',
            'ver_reportes', 'generar_reportes',
            'ver_dashboard',
        ]);

        // 3. TÉCNICO - Acceso limitado a sus tareas
        $roleTecnico = Role::firstOrCreate(['name' => 'tecnico']);
        $roleTecnico->syncPermissions([
            'ver_clientes',
            'ver_ordenes', 'editar_ordenes', 'cambiar_estado_ordenes',
            'ver_vehiculos',
            'ver_stock',
            'ver_dashboard',
        ]);

        // Crear usuario administrador por defecto
        $admin = User::firstOrCreate(
            ['email' => 'admin@tecnoservi.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
            ]
        );
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        // Crear usuario supervisor de ejemplo
        $supervisor = User::firstOrCreate(
            ['email' => 'supervisor@tecnoservi.com'],
            [
                'name' => 'Supervisor Principal',
                'password' => Hash::make('password'),
            ]
        );
        if (!$supervisor->hasRole('supervisor')) {
            $supervisor->assignRole('supervisor');
        }

        // Crear usuario técnico de ejemplo
        $tecnico = User::firstOrCreate(
            ['email' => 'tecnico@tecnoservi.com'],
            [
                'name' => 'Técnico 1',
                'password' => Hash::make('password'),
            ]
        );
        if (!$tecnico->hasRole('tecnico')) {
            $tecnico->assignRole('tecnico');
        }

        $this->command->info('Roles y permisos creados exitosamente.');
        $this->command->info('Usuario Admin: admin@tecnoservi.com / password');
        $this->command->info('Usuario Supervisor: supervisor@tecnoservi.com / password');
        $this->command->info('Usuario Técnico: tecnico@tecnoservi.com / password');
    }
}
