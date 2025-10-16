# TecnoServi - Guía de Instalación

## Requisitos Previos

- PHP >= 8.0.2
- Composer
- MySQL >= 5.7
- Node.js y NPM (opcional, para assets)

## Instalación Rápida (Recomendada)

### Opción A: Script Automático

**Windows:**
```bash
install.bat
```

**Linux/Mac:**
```bash
chmod +x install.sh
./install.sh
```

El script instalará todo automáticamente y creará usuarios de prueba.

---

## Instalación Manual (Paso a Paso)

### 0. Verificar Requisitos (Opcional pero Recomendado)

Antes de empezar, ejecuta el script de verificación:

```bash
php check-requirements.php
```

Este script verificará que tu sistema tenga todo lo necesario.

### 1. Clonar el Repositorio

```bash
git clone https://github.com/tizimeza/loginlaravel.git
cd loginlaravel
```

### 2. Instalar Dependencias de PHP

```bash
composer install
```

### 3. Configurar Variables de Entorno

```bash
# En Windows
copy .env.example .env

# En Linux/Mac
cp .env.example .env
```

Edita el archivo `.env` y configura tu base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tecnoservi
DB_USERNAME=root
DB_PASSWORD=tu_password_aqui
```

### 4. Crear la Base de Datos

Crea la base de datos en MySQL:

```sql
CREATE DATABASE tecnoservi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Generar Key de la Aplicación

```bash
php artisan key:generate
```

### 6. Ejecutar Migraciones

```bash
php artisan migrate
```

### 7. Ejecutar Seeders (Datos de Prueba)

```bash
php artisan db:seed
```

### 8. Crear Storage Link

```bash
php artisan storage:link
```

### 9. Publicar Assets de Spatie Permission

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

### 10. Iniciar el Servidor

```bash
php artisan serve --port=8001
```

Visita: http://localhost:8001

## Credenciales de Acceso por Defecto

- **Email**: admin@tecnoservi.com
- **Password**: password

## Usuarios de Prueba

El seeder crea los siguientes usuarios:

1. **Administrador**
   - Email: admin@tecnoservi.com
   - Password: password
   - Rol: Administrador

2. **Técnico**
   - Email: tecnico@tecnoservi.com
   - Password: password
   - Rol: Técnico

3. **Supervisor**
   - Email: supervisor@tecnoservi.com
   - Password: password
   - Rol: Supervisor

## Script de Instalación Automática

### Para Windows (install.bat)

```bash
install.bat
```

### Para Linux/Mac (install.sh)

```bash
chmod +x install.sh
./install.sh
```

## Problemas Comunes

### Error: "SQLSTATE[42S22]: Column not found"

Solución: Ejecuta las migraciones nuevamente:

```bash
php artisan migrate:fresh
php artisan db:seed
```

### Error: "No application encryption key has been specified"

Solución:

```bash
php artisan key:generate
```

### Error: Permisos en storage/

Solución (Linux/Mac):

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Error al ejecutar seeders

Solución:

```bash
composer dump-autoload
php artisan db:seed
```

## Estructura de Base de Datos

El sistema incluye las siguientes tablas:

- users (usuarios del sistema)
- roles y permissions (Spatie Permission)
- clientes
- vehiculos (marcas, modelos)
- ordenes_trabajo
- grupos_trabajo
- tareas
- stock
- activity_logs (auditoría)

## Módulos del Sistema

1. **Gestión de Usuarios y Roles**
2. **Gestión de Clientes**
3. **Gestión de Vehículos**
4. **Órdenes de Trabajo**
5. **Grupos de Trabajo (Móviles)**
6. **Tareas**
7. **Stock/Inventario**
8. **Reportes PDF**
9. **Logs de Actividad**

## Tecnologías Utilizadas

- Laravel 9
- MySQL
- AdminLTE 3
- Spatie Laravel Permission
- DomPDF (reportes)
- Bootstrap 4
- jQuery + DataTables

## Soporte

Para problemas o consultas, contactar a: 34883248@roquegonzalez.org
