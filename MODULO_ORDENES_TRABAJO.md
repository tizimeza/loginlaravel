# Módulo de Órdenes de Trabajo

## Descripción
Este módulo permite gestionar órdenes de trabajo para un sistema de taller mecánico o servicio técnico. Incluye funcionalidades completas de CRUD con una interfaz moderna usando AdminLTE.

## Características Principales

### 📋 Gestión Completa de Órdenes
- **Crear** nuevas órdenes de trabajo
- **Visualizar** lista paginada con filtros
- **Editar** órdenes existentes
- **Eliminar** órdenes no necesarias
- **Cambio rápido de estado** desde la lista

### 🏷️ Estados de Orden
- **Pendiente**: Orden recién creada
- **En Proceso**: Trabajo en curso
- **Esperando Repuestos**: Pausa por falta de materiales
- **Completado**: Trabajo terminado
- **Entregado**: Vehículo entregado al cliente
- **Cancelado**: Orden cancelada

### ⚡ Niveles de Prioridad
- **Baja**: Sin urgencia
- **Media**: Prioridad normal
- **Alta**: Requiere atención prioritaria
- **Urgente**: Máxima prioridad

### 🔍 Sistema de Filtros
- Búsqueda por número de orden, cliente o descripción
- Filtro por estado
- Filtro por prioridad
- Indicador visual de órdenes atrasadas

## Estructura del Módulo

### Modelo (OrdenTrabajo)
**Ubicación**: `app/Models/OrdenTrabajo.php`

**Campos principales**:
- `numero_orden`: Número único generado automáticamente
- `vehiculo_id`: Relación con vehículo
- `cliente_nombre`, `cliente_telefono`, `cliente_email`: Datos del cliente
- `descripcion_problema`: Descripción detallada del problema
- `fecha_ingreso`, `fecha_estimada_entrega`, `fecha_entrega_real`: Fechas importantes
- `estado` y `prioridad`: Estados y prioridades predefinidos
- `costo_estimado`, `costo_final`: Costos del trabajo
- `user_id`: Técnico asignado

**Relaciones**:
- `vehiculo()`: Pertenece a un vehículo
- `tecnico()`: Pertenece a un usuario (técnico)
- `tareas()`: Tiene muchas tareas relacionadas

### Controlador (OrdenTrabajoController)
**Ubicación**: `app/Http/Controllers/OrdenTrabajoController.php`

**Métodos principales**:
- `index()`: Lista con filtros y paginación
- `create()`: Formulario de creación
- `store()`: Almacenar nueva orden
- `show()`: Vista detallada de orden
- `edit()`: Formulario de edición
- `update()`: Actualizar orden existente
- `destroy()`: Eliminar orden
- `cambiarEstado()`: Cambio rápido de estado

### Validación (Form Requests)
**Ubicación**: `app/Http/Requests/`
- `StoreOrdenTrabajoRequest.php`: Validación para crear
- `UpdateOrdenTrabajoRequest.php`: Validación para actualizar

### Vistas (AdminLTE)
**Ubicación**: `resources/views/ordenes_trabajo/`

#### index.blade.php
- Lista paginada de órdenes
- Filtros por estado, prioridad y búsqueda
- Estadísticas rápidas en cards
- Cambio rápido de estado con dropdown
- Indicadores visuales de estado y prioridad
- Alertas para órdenes atrasadas

#### create.blade.php
- Formulario organizado en secciones:
  - Información del cliente
  - Información del vehículo
  - Descripción del problema
  - Fechas y costos
- Validación en tiempo real
- Auto-completado de fechas

#### show.blade.php
- Vista detallada de la orden
- Información completa del cliente y vehículo
- Cambio rápido de estado
- Acciones rápidas (imprimir, enviar email)
- Tareas relacionadas
- Información del técnico asignado

#### edit.blade.php
- Formulario de edición con datos precargados
- Misma estructura que create
- Validación de fechas
- Confirmación para cambios críticos

## Base de Datos

### Migración Principal
**Archivo**: `database/migrations/[timestamp]_create_ordenes_trabajo_table.php`

**Campos**:
```sql
- id (bigint, primary key)
- numero_orden (string, unique)
- vehiculo_id (foreign key)
- cliente_nombre (string)
- cliente_telefono (string, nullable)
- cliente_email (string, nullable)
- descripcion_problema (text)
- fecha_ingreso (datetime)
- fecha_estimada_entrega (datetime, nullable)
- fecha_entrega_real (datetime, nullable)
- estado (enum)
- prioridad (enum)
- costo_estimado (decimal, nullable)
- costo_final (decimal, nullable)
- observaciones (text, nullable)
- user_id (foreign key, nullable)
- timestamps
```

**Índices**:
- `numero_orden` (único)
- `[estado, fecha_ingreso]` (compuesto)
- `[prioridad, fecha_estimada_entrega]` (compuesto)

### Migración Adicional
**Archivo**: `database/migrations/[timestamp]_add_orden_trabajo_id_to_tareas_table.php`

Agrega relación entre tareas y órdenes de trabajo.

## Datos de Prueba

### Factory
**Ubicación**: `database/factories/OrdenTrabajoFactory.php`

Genera órdenes con:
- Números de orden únicos
- Fechas realistas
- Estados y prioridades variados
- Datos de cliente ficticios
- Costos estimados

### Seeder
**Ubicación**: `database/seeders/OrdenTrabajoSeeder.php`

Crea 20 órdenes de prueba con diferentes estados:
- 5 pendientes
- 3 completadas
- 2 urgentes
- 4 de prioridad alta
- 6 con estados variados

## Rutas

### Rutas de Recursos
```php
Route::resource('ordenes_trabajo', OrdenTrabajoController::class);
```

**Rutas generadas**:
- `GET /ordenes_trabajo` - Lista de órdenes
- `GET /ordenes_trabajo/create` - Formulario de creación
- `POST /ordenes_trabajo` - Crear orden
- `GET /ordenes_trabajo/{id}` - Ver orden
- `GET /ordenes_trabajo/{id}/edit` - Formulario de edición
- `PUT /ordenes_trabajo/{id}` - Actualizar orden
- `DELETE /ordenes_trabajo/{id}` - Eliminar orden

### Ruta Adicional
```php
Route::patch('ordenes_trabajo/{ordenTrabajo}/cambiar_estado', [OrdenTrabajoController::class, 'cambiarEstado'])
    ->name('ordenes_trabajo.cambiar_estado');
```

## Integración con AdminLTE

### Menú de Navegación
El módulo se integra automáticamente en:
- **Navbar superior**: Enlace directo a órdenes
- **Sidebar**: Menú con icono y estado activo
- **Breadcrumbs**: Navegación contextual

### Componentes UI Utilizados
- **Cards**: Organización del contenido
- **DataTables**: Tablas interactivas
- **Badges**: Estados y prioridades
- **Dropdowns**: Cambio rápido de estado
- **Modals**: Confirmaciones
- **Alerts**: Mensajes de éxito/error
- **Forms**: Formularios estilizados
- **Buttons**: Acciones consistentes

## Características Técnicas

### Validación
- Validación del lado del servidor con Form Requests
- Mensajes de error personalizados en español
- Validación de fechas lógicas
- Validación de relaciones existentes

### Seguridad
- Protección CSRF en formularios
- Validación de autorización
- Sanitización de entrada de datos
- Protección contra SQL injection (usando Eloquent)

### Performance
- Paginación eficiente
- Carga eager de relaciones
- Índices de base de datos optimizados
- Consultas optimizadas con filtros

### UX/UI
- Interfaz responsiva
- Indicadores visuales claros
- Confirmaciones para acciones destructivas
- Auto-completado de fechas
- Búsqueda en tiempo real
- Estados visuales consistentes

## Instalación y Uso

### 1. Ejecutar Migraciones
```bash
php artisan migrate
```

### 2. Ejecutar Seeders (Opcional)
```bash
php artisan db:seed --class=OrdenTrabajoSeeder
```

### 3. Acceder al Módulo
Navegar a `/ordenes_trabajo` en el navegador después de autenticarse.

## Extensibilidad

### Posibles Mejoras Futuras
1. **Reportes**: Módulo de reportes y estadísticas
2. **Notificaciones**: Alertas por email/SMS
3. **API**: Endpoints REST para aplicaciones móviles
4. **Archivos**: Subida de fotos y documentos
5. **Historial**: Log de cambios de estado
6. **Plantillas**: Plantillas de órdenes frecuentes
7. **Integración**: Conexión con sistemas de facturación
8. **Dashboard**: Panel de control con métricas

### Personalización
El módulo está diseñado para ser fácilmente personalizable:
- Estados y prioridades configurables
- Campos adicionales en migraciones
- Validaciones personalizadas
- Vistas extendibles
- Lógica de negocio adaptable

## Soporte y Mantenimiento

### Logs
- Errores registrados en `storage/logs/laravel.log`
- Actividad de usuario rastreable

### Backup
- Importante respaldar la tabla `ordenes_trabajo`
- Considerar relaciones con `vehiculos` y `users`

### Actualizaciones
- Usar migraciones para cambios de esquema
- Mantener compatibilidad con versiones anteriores
- Documentar cambios importantes

---

**Versión**: 1.0.0  
**Fecha**: Septiembre 2025  
**Compatibilidad**: Laravel 9.x, AdminLTE 3.x  
**Estado**: Producción Ready ✅
