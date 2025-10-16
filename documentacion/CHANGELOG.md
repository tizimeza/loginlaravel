# Changelog

Todos los cambios notables en este proyecto serán documentados en este archivo.

El formato está basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.0.0/),
y este proyecto adhiere a [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2025-10-16

### 🎉 Release Inicial

Primera versión completa del Sistema de Gestión TecnoServi.

### ✨ Características Principales

#### Sistema de Autenticación y Autorización
- Sistema de login con Laravel UI
- Roles y permisos con Spatie Laravel Permission
  - Rol **Admin**: Acceso completo al sistema
  - Rol **Supervisor**: Gestión de órdenes y reportes
  - Rol **Técnico**: Gestión de órdenes asignadas
- Políticas de autorización para cada modelo
- Middleware de autenticación en todas las rutas protegidas

#### Módulo de Órdenes de Trabajo
- CRUD completo de órdenes de trabajo
- Estados: Pendiente, En Proceso, Completado, Cancelado
- Asignación de técnicos y grupos de trabajo
- Asignación de vehículos
- Vinculación con clientes
- Gestión de tareas asociadas
- Cálculo automático de costos

#### Módulo de Clientes
- CRUD completo de clientes
- Tipos: Particular, Empresa
- Información de contacto completa
- Historial de órdenes de trabajo
- Exportación a PDF

#### Módulo de Vehículos
- CRUD completo de vehículos
- Información detallada:
  - Patente, marca, modelo, año
  - Tipo de vehículo (Ford Transit, Renault Kangoo, Peugeot Partner)
  - Combustible (nafta, diesel, gnc, eléctrico)
  - Kilometraje y capacidad de carga
  - Estado (Disponible, En Uso, Mantenimiento, Fuera de Servicio)
- Sistema de alertas:
  - Vencimiento de VTV
  - Cambio de neumáticos
  - Servicios pendientes
- Filtros con DataTables:
  - Por estado
  - Por tipo de vehículo
  - Por combustible
  - Por alertas
- Exportación a PDF

#### Módulo de Inventario/Stock
- CRUD completo de productos
- Categorías específicas de TecnoServi:
  - Antenas y Receptores
  - Cables y Conectores
  - Control Remoto
  - Decodificadores
  - Equipos de Instalación
  - Herramientas
  - Material de Fijación
  - Otros
- Control de stock:
  - Cantidad actual
  - Cantidad mínima
  - Alertas de bajo stock
- Precios de compra y venta
- Exportación a PDF

#### Módulo de Técnicos/Usuarios
- Gestión de usuarios técnicos
- Asignación de roles
- Información de contacto
- Historial de órdenes asignadas

#### Módulo de Grupos de Trabajo
- Creación y gestión de grupos
- Asignación de técnicos a grupos
- Asignación de vehículos a grupos
- Vinculación con órdenes de trabajo

#### Dashboard Ejecutivo
- Métricas principales:
  - Total de órdenes y estados
  - Total de clientes
  - Total de productos en stock
  - Estado de vehículos
- Órdenes recientes
- Alertas de stock bajo
- Estado de vehículos
- Acceso rápido a reportes PDF

#### Sistema de Reportes PDF
- Reporte de orden de trabajo individual
- Reporte de inventario completo
- Reporte de órdenes por período
- Reporte de clientes
- Reporte de vehículos
- Diseño profesional con DomPDF

#### Sistema de Auditoría
- Registro automático de todas las acciones:
  - Creación, actualización y eliminación de registros
  - Usuario que realizó la acción
  - Timestamp de la acción
  - Modelo afectado
- Vista de auditoría solo para administradores

### 🛠️ Tecnologías Utilizadas

- **Backend**: Laravel 9.x
- **Base de Datos**: MySQL 5.7+
- **Autenticación**: Laravel UI
- **Autorización**: Spatie Laravel Permission
- **PDFs**: DomPDF (barryvdh/laravel-dompdf)
- **Frontend**:
  - AdminLTE 3
  - Bootstrap 4
  - jQuery
  - DataTables
  - Font Awesome

### 📦 Seeders Incluidos

- `RolePermissionSeeder`: Crea roles, permisos y usuarios de prueba
- `CountrySeeder`: Países
- `ProvinceSeeder`: Provincias de Argentina
- `MarcaSeeder`: Marcas de vehículos
- `ModeloSeeder`: Modelos de vehículos
- `VehiculoSeeder`: 12 vehículos de ejemplo
- `ClienteSeeder`: Clientes de prueba
- `GrupoTrabajoSeeder`: Grupos de trabajo
- `StockTecnoServiSeeder`: Productos de inventario
- `TareaSeeder`: Tareas predefinidas
- `OrdenTrabajoSeeder`: Órdenes de trabajo de ejemplo

### 📄 Documentación

- `README.md`: Guía completa de instalación y uso
- `CONTRIBUTING.md`: Guía para contribuidores
- `DEPLOYMENT.md`: Guía de despliegue en producción
- `CHECKLIST.md`: Checklist de preparación para GitHub
- `.env.example`: Template de configuración
- Scripts de instalación:
  - `install.bat` para Windows
  - `install.sh` para Linux/Mac

### 🔒 Seguridad

- Todas las rutas protegidas con middleware `auth`
- Validación de formularios con Form Requests
- Políticas de autorización implementadas
- Protección CSRF en todos los formularios
- Passwords hasheados con bcrypt
- Sistema de auditoría de acciones

### 👥 Usuarios de Prueba

- **Admin**: admin@tecnoservi.com / password
- **Supervisor**: supervisor@tecnoservi.com / password
- **Técnico**: tecnico@tecnoservi.com / password

---

## Tipos de Cambios

Para futuras versiones, usar estas categorías:

- `Added` - Nuevas características
- `Changed` - Cambios en funcionalidades existentes
- `Deprecated` - Características que serán removidas
- `Removed` - Características removidas
- `Fixed` - Corrección de bugs
- `Security` - Cambios relacionados con seguridad

---

**Nota**: Este es el release inicial. Las versiones futuras seguirán el formato de Semantic Versioning:
- MAJOR version para cambios incompatibles en la API
- MINOR version para nuevas funcionalidades compatibles
- PATCH version para correcciones de bugs compatibles
