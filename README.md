# TecnoServi - Sistema de Gestión de Servicios Técnicos

Sistema de gestión integral para empresas de servicios de internet y telefonía a domicilio. Desarrollado con Laravel 9 y AdminLTE 3.

## Descripción

TecnoServi es una aplicación web completa para gestionar operaciones de servicios técnicos a domicilio, incluyendo:

- **Gestión de Órdenes de Trabajo**: Instalaciones, reconexiones, mantenimiento y desconexiones de servicios
- **Gestión de Clientes**: Base de datos completa de clientes residenciales, comerciales y empresariales
- **Móviles/Grupos de Trabajo**: Organización de equipos técnicos con vehículos asignados
- **Control de Stock**: Inventario de materiales (routers, modems, cables, etc.)
- **Gestión de Vehículos**: Control de la flota de vehículos
- **Sistema de Roles y Permisos**: Admin, Supervisor y Técnico
- **Reportes en PDF**: Generación de reportes profesionales de órdenes de trabajo e inventario

## Tecnologías Utilizadas

- **Backend**: Laravel 10
- **Frontend**: AdminLTE 3, Bootstrap 4
- **Base de Datos**: MySQL
- **Autenticación**: Laravel Breeze
- **Roles y Permisos**: Spatie Laravel Permission
- **PDF**: DomPDF
- **Otros**: jQuery, DataTables, SweetAlert2

## Requisitos Previos

- PHP >= 8.1
- Composer
- MySQL >= 5.7 o MariaDB >= 10.3
- Node.js >= 16 (para compilar assets)
- NPM o Yarn

## Instalación

Sigue estos pasos para instalar el proyecto en tu máquina local:

### 1. Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/loginlaravel.git
cd loginlaravel
```

### 2. Instalar dependencias de PHP

```bash
composer install
```

### 3. Instalar dependencias de Node.js

```bash
npm install
```

### 4. Configurar el archivo de entorno

Copia el archivo `.env.example` y renómbralo a `.env`:

```bash
cp .env.example .env
```

Edita el archivo `.env` y configura los siguientes valores:

```env
APP_NAME="TecnoServi"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tecnoservi
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generar la clave de aplicación

```bash
php artisan key:generate
```

### 6. Crear la base de datos

Crea una base de datos MySQL llamada `tecnoservi` (o el nombre que hayas configurado en `.env`):

```sql
CREATE DATABASE tecnoservi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 7. Ejecutar las migraciones

```bash
php artisan migrate
```

Este comando creará todas las tablas necesarias en la base de datos.

### 8. Poblar la base de datos con datos de prueba

```bash
php artisan db:seed
```

Este comando creará:
- 3 usuarios con diferentes roles (admin, supervisor, técnico)
- 10 clientes de ejemplo
- 12 vehículos
- 3 móviles/grupos de trabajo
- 16 productos en stock
- 20 órdenes de trabajo de ejemplo

### 9. Crear el enlace simbólico para almacenamiento

```bash
php artisan storage:link
```

### 10. Compilar los assets (opcional)

```bash
npm run dev
```

O para producción:

```bash
npm run build
```

### 11. Iniciar el servidor de desarrollo

```bash
php artisan serve
```

La aplicación estará disponible en: `http://localhost:8000`

## Credenciales de Acceso

Después de ejecutar los seeders, puedes acceder con las siguientes credenciales:

### Administrador
- **Email**: admin@tecnoservi.com
- **Contraseña**: password

### Supervisor
- **Email**: supervisor@tecnoservi.com
- **Contraseña**: password

### Técnico
- **Email**: tecnico@tecnoservi.com
- **Contraseña**: password

## Estructura del Proyecto

```
loginlaravel/
├── app/
│   ├── Http/
│   │   └── Controllers/    # Controladores
│   └── Models/             # Modelos Eloquent
├── database/
│   ├── migrations/         # Migraciones de base de datos
│   └── seeders/           # Seeders
├── resources/
│   └── views/             # Vistas Blade
├── routes/
│   └── web.php            # Rutas web
└── public/                # Archivos públicos
```

## Módulos Principales

### 1. Órdenes de Trabajo
- Creación y gestión de órdenes
- Estados: Pendiente, En Proceso, Esperando Repuestos, Completado, Entregado, Cancelado
- Prioridades: Baja, Media, Alta, Urgente
- Tipos de servicio: Instalación, Reconexión, Mantenimiento, Desconexión
- Asignación de técnicos y grupos de trabajo
- Generación de PDF individual

### 2. Clientes
- Gestión completa de clientes
- Tipos: Residencial, Comercial, Empresa
- Información de contacto y ubicación
- Historial de órdenes

### 3. Grupos de Trabajo (Móviles)
- Organización de equipos técnicos
- Asignación de vehículos
- Especialidades: Instalación, Fibra Óptica, Soporte, etc.
- Zonas de trabajo
- Control de capacidad

### 4. Stock/Inventario
- Control de materiales
- Categorías: Routers, Modems, Cables, Herramientas, etc.
- Stock mínimo y alertas
- Historial de movimientos

### 5. Vehículos
- Gestión de flota
- Estados: Disponible, En Uso, Mantenimiento, Fuera de Servicio
- Marcas y modelos
- Asignación a móviles

### 6. Reportes
- PDF de órdenes de trabajo individuales
- PDF de inventario completo
- Exportación de datos

## Comandos Útiles

### Reset completo de la base de datos
```bash
php artisan migrate:fresh --seed
```

### Limpiar caché
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Ver estado de migraciones
```bash
php artisan migrate:status
```

## Troubleshooting

### Error: "Please provide a valid cache path"
```bash
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
chmod -R 775 storage bootstrap/cache
```

### Error de permisos en storage
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Error de conexión a la base de datos
Verifica que:
1. MySQL esté corriendo
2. La base de datos exista
3. Las credenciales en `.env` sean correctas

## Licencia

Este proyecto es de código abierto. Puedes usarlo y modificarlo libremente.

## Contacto

Para consultas o soporte, contacta a: admin@tecnoservi.com

---

**Desarrollado con ❤️ para TecnoServi**
