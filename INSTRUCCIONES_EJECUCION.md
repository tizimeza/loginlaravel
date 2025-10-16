# Instrucciones de Ejecución - Sistema TecnoServi

## Resumen de lo Implementado ✅

He implementado exitosamente la **Fase 1 - Fundamentos** de las mejoras profesionales para tu sistema de gestión:

###  Lo que se ha completado:

1. ✅ **Form Requests con Validaciones Profesionales**
   - Todos los modelos principales tienen validación completa
   - Mensajes personalizados en español
   - Reglas de negocio aplicadas

2. ✅ **Sistema de Roles y Permisos (Spatie Permission)**
   - Paquete instalado y configurado
   - 3 roles definidos: Admin, Supervisor, Técnico
   - 35 permisos granulares
   - Seeder completo con usuarios de prueba

3. ✅ **Policies de Autorización**
   - ClientePolicy ✅
   - OrdenTrabajoPolicy ✅ (con lógica avanzada)
   - VehiculoPolicy ✅
   - StockPolicy ✅
   - GrupoTrabajoPolicy ✅ (con permisos para líderes)

---

## Pasos para Activar las Mejoras

**IMPORTANTE**: Estos pasos deben ejecutarse cuando tengas tu base de datos MySQL en funcionamiento.

### Paso 1: Asegurar que MySQL está corriendo

```bash
# Verificar que MySQL esté activo
# En Windows con XAMPP/WAMP: Iniciar el servicio desde el panel de control
# En otros sistemas: sudo service mysql start
```

### Paso 2: Configurar el archivo .env

Asegúrate de que tu archivo `.env` tenga la configuración correcta de base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tizimeza
DB_USERNAME=root
DB_PASSWORD=tu_password_aqui
```

### Paso 3: Ejecutar las Migraciones

```bash
cd "C:\Users\leand\OneDrive\Escritorio\tizi\loginlaravel"

# Opción 1: Migrar sin perder datos existentes
php artisan migrate

# Opción 2: Refrescar todo (CUIDADO: Elimina todos los datos)
php artisan migrate:fresh
```

### Paso 4: Ejecutar el Seeder de Roles y Permisos

```bash
php artisan db:seed --class=RoleAndPermissionSeeder
```

Este comando creará:
- 3 Roles (admin, supervisor, tecnico)
- 35 Permisos granulares
- 3 Usuarios de prueba:
  - **Admin**: admin@tecnoservi.com / password
  - **Supervisor**: supervisor@tecnoservi.com / password
  - **Técnico**: tecnico@tecnoservi.com / password

### Paso 5: Limpiar Caché de Laravel

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
```

### Paso 6: Probar el Sistema

Inicia el servidor de desarrollo:

```bash
php artisan serve
```

Luego accede a: `http://localhost:8000`

Prueba iniciando sesión con los 3 usuarios creados para verificar los diferentes niveles de acceso.

---

## Estructura de Permisos Implementada

### Rol: Admin
- ✅ Acceso total al sistema
- ✅ Puede crear, editar y eliminar todo
- ✅ Gestiona usuarios y roles
- ✅ Acceso a todos los reportes

### Rol: Supervisor
- ✅ Gestión operativa completa
- ✅ Puede crear y asignar órdenes de trabajo
- ✅ Gestiona clientes, vehículos, stock y grupos
- ✅ Puede ver y generar reportes
- ❌ NO puede eliminar registros críticos

### Rol: Técnico
- ✅ Puede ver y actualizar sus propias órdenes
- ✅ Puede cambiar estados de órdenes asignadas
- ✅ Solo lectura en clientes, vehículos y stock
- ✅ Acceso al dashboard con sus métricas
- ❌ NO puede crear nuevas órdenes
- ❌ NO puede gestionar otros recursos

---

## Uso de Policies en Controladores

Ahora puedes proteger tus controladores usando las policies. Ejemplo:

```php
// En ClienteController.php

public function create()
{
    // Verifica si el usuario puede crear clientes
    $this->authorize('create', Cliente::class);

    return view('clientes.create');
}

public function edit(Cliente $cliente)
{
    // Verifica si puede editar este cliente específico
    $this->authorize('update', $cliente);

    return view('clientes.edit', compact('cliente'));
}

public function destroy(Cliente $cliente)
{
    // Verifica si puede eliminar
    $this->authorize('delete', $cliente);

    $cliente->delete();
    return redirect()->route('clientes.index');
}
```

---

## Uso de Policies en Blade (Vistas)

Puedes mostrar/ocultar botones según permisos:

```blade
{{-- En clientes/index.blade.php --}}

@can('create', App\Models\Cliente::class)
    <a href="{{ route('clientes.create') }}" class="btn btn-primary">
        Crear Nuevo Cliente
    </a>
@endcan

@foreach($clientes as $cliente)
    <tr>
        <td>{{ $cliente->nombre }}</td>
        <td>{{ $cliente->email }}</td>
        <td>
            {{-- Solo mostrar botón editar si tiene permiso --}}
            @can('update', $cliente)
                <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-sm btn-warning">
                    Editar
                </a>
            @endcan

            {{-- Solo mostrar botón eliminar si tiene permiso --}}
            @can('delete', $cliente)
                <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                </form>
            @endcan
        </td>
    </tr>
@endforeach
```

---

## Verificar Roles en el Código

```php
// Verificar si un usuario tiene un rol específico
if (auth()->user()->hasRole('admin')) {
    // Solo administradores
}

// Verificar si tiene alguno de varios roles
if (auth()->user()->hasAnyRole(['admin', 'supervisor'])) {
    // Admin o Supervisor
}

// Verificar permisos directos
if (auth()->user()->can('crear_clientes')) {
    // Tiene el permiso
}

// Verificar múltiples permisos
if (auth()->user()->hasAllPermissions(['ver_ordenes', 'crear_ordenes'])) {
    // Tiene todos los permisos
}
```

---

## Asignar Roles a Usuarios Existentes

Si tienes usuarios en la base de datos y quieres asignarles roles:

```php
// En tinker o en un seeder
$user = User::find(1);
$user->assignRole('admin');

// O asignar múltiples roles
$user->assignRole(['admin', 'supervisor']);

// Remover rol
$user->removeRole('tecnico');

// Sincronizar roles (reemplaza todos)
$user->syncRoles(['supervisor']);
```

---

## Próximas Mejoras Recomendadas

Una vez que hayas activado y probado la Fase 1, te recomiendo continuar con:

### Alta Prioridad:
1. **Dashboard con Estadísticas** - Métricas visuales en tiempo real
2. **Generación de Reportes PDF** - Órdenes de trabajo, inventario, etc.
3. **Observers para Auditoría** - Registrar todos los cambios importantes

### Media Prioridad:
4. **Sistema de Notificaciones** - Alertas para stock bajo, VTV vencida, etc.
5. **Optimización de Consultas** - Eliminar problemas N+1
6. **Manejo Profesional de Errores** - Páginas 404, 500 personalizadas

### Baja Prioridad:
7. **API REST** - Si necesitas integración con otras aplicaciones
8. **Tests Automatizados** - Para garantizar calidad del código
9. **Seeders Completos** - Datos de prueba realistas

---

## Solución de Problemas

### Error: "Class 'Spatie\Permission\Models\Role' not found"
```bash
composer dump-autoload
php artisan optimize:clear
```

### Error: "Table 'permissions' doesn't exist"
```bash
php artisan migrate
```

### Error: "User has no roles"
```bash
php artisan db:seed --class=RoleAndPermissionSeeder
```

### Los permisos no se actualizan
```bash
php artisan permission:cache-reset
```

---

## Archivos Clave Creados/Modificados

### Nuevos Archivos:
```
app/Policies/
├── ClientePolicy.php ✅
├── OrdenTrabajoPolicy.php ✅
├── VehiculoPolicy.php ✅
├── StockPolicy.php ✅
└── GrupoTrabajoPolicy.php ✅

database/seeders/
└── RoleAndPermissionSeeder.php ✅

config/
└── permission.php ✅

database/migrations/
└── 2025_10_16_065838_create_permission_tables.php ✅
```

### Archivos Modificados:
```
app/Models/User.php ✅ (agregado trait HasRoles)
composer.json ✅ (agregado spatie/laravel-permission)
```

---

## Contacto y Soporte

Si encuentras algún problema durante la ejecución:

1. Verifica que MySQL esté corriendo
2. Revisa el archivo `.env`
3. Verifica los logs en `storage/logs/laravel.log`
4. Ejecuta `php artisan optimize:clear`

---

**Documentado por**: Claude Code
**Fecha**: 16 de Octubre, 2025
**Versión**: 1.0

**Estado del Proyecto**: Fase 1 Completada (40%)
