# Guía de Contribución - TecnoServi

Gracias por tu interés en contribuir al sistema TecnoServi. Esta guía te ayudará a configurar tu entorno de desarrollo y seguir las mejores prácticas del proyecto.

## 📋 Tabla de Contenidos

- [Configuración del Entorno de Desarrollo](#configuración-del-entorno-de-desarrollo)
- [Estructura del Código](#estructura-del-código)
- [Convenciones de Código](#convenciones-de-código)
- [Proceso de Desarrollo](#proceso-de-desarrollo)
- [Testing](#testing)
- [Commits y Pull Requests](#commits-y-pull-requests)

## 🛠️ Configuración del Entorno de Desarrollo

### Requisitos

- PHP >= 8.0
- Composer
- MySQL >= 5.7 o MariaDB >= 10.3
- Git

### Instalación

1. Fork el repositorio en GitHub

2. Clona tu fork localmente:
```bash
git clone https://github.com/tu-usuario/tecnoservi.git
cd tecnoservi
```

3. Ejecuta el script de instalación:
```bash
# Windows
install.bat

# Linux/Mac
chmod +x install.sh
./install.sh
```

4. Configura tu base de datos en `.env`

5. Ejecuta las migraciones y seeders:
```bash
php artisan migrate
php artisan db:seed
```

## 📁 Estructura del Código

```
app/
├── Http/
│   ├── Controllers/    # Lógica de controladores
│   ├── Requests/       # Validación de formularios
│   └── Middleware/     # Middleware personalizado
├── Models/             # Modelos Eloquent
└── Policies/           # Políticas de autorización

database/
├── migrations/         # Migraciones de BD
└── seeders/            # Seeders de datos

resources/
└── views/              # Vistas Blade
```

## 💻 Convenciones de Código

### PHP (PSR-12)

- Usar 4 espacios para indentación
- Nombres de clases en `PascalCase`
- Nombres de métodos en `camelCase`
- Nombres de variables en `snake_case`
- Constantes en `UPPER_CASE`

**Ejemplo:**

```php
<?php

namespace App\Models;

class OrdenTrabajo extends Model
{
    protected $table = 'ordenes_trabajo';

    public function obtenerEstadoFormateado(): string
    {
        return ucfirst($this->estado);
    }
}
```

### Blade Templates

- Usar directivas Blade en lugar de PHP puro
- Escapar salidas con `{{ }}` (excepto HTML confiable con `{!! !!}`)
- Usar `@` para directivas Blade

**Ejemplo:**

```blade
@if($ordenes->count() > 0)
    @foreach($ordenes as $orden)
        <div class="orden">
            <h3>{{ $orden->numero_orden }}</h3>
        </div>
    @endforeach
@else
    <p>No hay órdenes disponibles</p>
@endif
```

### Base de Datos

- Nombres de tablas en plural y `snake_case`: `ordenes_trabajo`
- Nombres de columnas en `snake_case`: `fecha_creacion`
- Usar migraciones para todos los cambios de BD
- Siempre incluir `up()` y `down()` en migraciones

## 🔄 Proceso de Desarrollo

### Crear una Nueva Funcionalidad

1. **Crear una rama desde main:**
```bash
git checkout main
git pull origin main
git checkout -b feature/nombre-funcionalidad
```

2. **Desarrollar la funcionalidad:**
   - Escribir el código siguiendo las convenciones
   - Agregar validaciones en Form Requests
   - Implementar políticas de autorización si es necesario
   - Actualizar vistas según sea necesario

3. **Crear migraciones si es necesario:**
```bash
php artisan make:migration crear_tabla_ejemplo
```

4. **Crear seeders para datos de prueba:**
```bash
php artisan make:seeder EjemploSeeder
```

5. **Probar localmente:**
```bash
php artisan migrate:fresh --seed
php artisan serve
```

### Corregir un Bug

1. **Crear una rama desde main:**
```bash
git checkout main
git pull origin main
git checkout -b fix/descripcion-bug
```

2. **Corregir el bug**

3. **Agregar tests si es posible**

4. **Verificar que no se rompa nada:**
```bash
php artisan test
```

## 🧪 Testing

### Ejecutar Tests

```bash
# Todos los tests
php artisan test

# Tests específicos
php artisan test --filter NombreTest
```

### Crear Tests

```bash
php artisan make:test OrdenTrabajoTest
```

**Ejemplo de test:**

```php
public function test_usuario_puede_crear_orden_trabajo()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post('/ordenes_trabajo', [
        'numero_orden' => 'OT-001',
        'estado' => 'pendiente',
        // ... más datos
    ]);

    $response->assertStatus(302);
    $this->assertDatabaseHas('ordenes_trabajo', [
        'numero_orden' => 'OT-001'
    ]);
}
```

## 📝 Commits y Pull Requests

### Convención de Commits

Usar mensajes de commit claros y descriptivos siguiendo el formato:

```
tipo: Descripción corta

Descripción más detallada si es necesario
```

**Tipos de commit:**

- `feat`: Nueva funcionalidad
- `fix`: Corrección de bug
- `docs`: Cambios en documentación
- `style`: Cambios de formato (sin afectar código)
- `refactor`: Refactorización de código
- `test`: Agregar o modificar tests
- `chore`: Cambios en configuración, build, etc.

**Ejemplos:**

```bash
git commit -m "feat: Agregar módulo de reportes PDF"
git commit -m "fix: Corregir validación en formulario de vehículos"
git commit -m "docs: Actualizar README con instrucciones de instalación"
```

### Crear un Pull Request

1. **Push de tu rama:**
```bash
git push origin feature/nombre-funcionalidad
```

2. **Crear PR en GitHub:**
   - Ir a tu fork en GitHub
   - Clic en "New Pull Request"
   - Seleccionar tu rama
   - Completar el template del PR

3. **Template de PR:**

```markdown
## Descripción
Breve descripción de los cambios

## Tipo de cambio
- [ ] Nueva funcionalidad
- [ ] Corrección de bug
- [ ] Refactorización
- [ ] Documentación

## Testing
- [ ] Tests existentes pasan
- [ ] Se agregaron nuevos tests
- [ ] Se probó manualmente

## Screenshots (si aplica)
```

## 🔒 Seguridad

- **NUNCA** commitear archivos `.env` o credenciales
- Usar `Hash::make()` para passwords
- Validar TODOS los inputs del usuario
- Implementar políticas de autorización para recursos sensibles
- Usar middleware de autenticación en todas las rutas protegidas

## 📚 Recursos Adicionales

- [Documentación de Laravel](https://laravel.com/docs/9.x)
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)
- [AdminLTE Documentation](https://adminlte.io/docs/)
- [PSR-12 Coding Standard](https://www.php-fig.org/psr/psr-12/)

## 🆘 Ayuda

Si tienes preguntas o necesitas ayuda:
- Abre un issue en GitHub
- Contacta al equipo de desarrollo
- Revisa la documentación del proyecto

---

¡Gracias por contribuir a TecnoServi! 🚀
