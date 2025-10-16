# 📦 Guía de Instalación - TecnoServi

Sistema de gestión integral para empresas de servicios de internet y telefonía a domicilio.

---

## 📋 Requisitos Previos

Antes de comenzar, asegúrate de tener instalado:

- ✅ PHP >= 8.0.2
- ✅ Composer
- ✅ MySQL >= 5.7 o MariaDB >= 10.3
- ✅ Node.js >= 16
- ✅ NPM o Yarn
- ✅ Git

### Verificar versiones instaladas

```bash
php --version
composer --version
mysql --version
node --version
npm --version
```

---

## 🚀 Pasos de Instalación

### 1️⃣ Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/loginlaravel.git
cd loginlaravel
```

---

### 2️⃣ Instalar dependencias de PHP

```bash
composer install
```

Este comando instalará todas las dependencias de Laravel incluyendo:
- Laravel Framework
- Spatie Laravel Permission (roles y permisos)
- DomPDF (generación de PDFs)

---

### 3️⃣ Instalar dependencias de Node.js

```bash
npm install
```

Esto instalará las dependencias de frontend (Bootstrap, jQuery, etc.)

---

### 4️⃣ Configurar el archivo de entorno

Copia el archivo de ejemplo `.env.example` a `.env`:

**Windows:**
```bash
copy .env.example .env
```

**Linux/Mac:**
```bash
cp .env.example .env
```

Luego, edita el archivo `.env` y configura los datos de tu base de datos:

```env
APP_NAME="TecnoServi"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tecnoservi
DB_USERNAME=root
DB_PASSWORD=tu_contraseña_mysql
```

---

### 5️⃣ Generar la clave de aplicación

```bash
php artisan key:generate
```

Este comando genera automáticamente una clave de encriptación en tu archivo `.env`

---

### 6️⃣ Crear la base de datos

Abre tu cliente MySQL (phpMyAdmin, MySQL Workbench, o línea de comandos) y ejecuta:

```sql
CREATE DATABASE tecnoservi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**Desde línea de comandos:**

```bash
mysql -u root -p
```

Luego dentro de MySQL:

```sql
CREATE DATABASE tecnoservi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

---

### 7️⃣ Ejecutar las migraciones

```bash
php artisan migrate
```

Este comando creará todas las tablas necesarias (35 migraciones):
- Usuarios
- Clientes
- Vehículos
- Órdenes de trabajo
- Stock/Inventario
- Grupos de trabajo
- Tareas
- Roles y permisos
- Y más...

---

### 8️⃣ Poblar la base de datos con datos de prueba

```bash
php artisan db:seed
```

Esto creará automáticamente:
- ✅ **3 usuarios** con diferentes roles (admin, supervisor, técnico)
- ✅ **10 clientes** de ejemplo (residenciales, comerciales, empresas)
- ✅ **12 vehículos** para los móviles
- ✅ **3 móviles/grupos de trabajo** especializados
- ✅ **16 productos** en stock (routers, modems, cables, etc.)
- ✅ **20 órdenes de trabajo** de ejemplo

---

### 9️⃣ Crear el enlace simbólico para archivos

```bash
php artisan storage:link
```

Este comando crea un enlace simbólico de `storage/app/public` a `public/storage` para que las imágenes subidas sean accesibles.

---

### 🔟 Compilar assets (Opcional)

Para desarrollo:
```bash
npm run dev
```

Para producción:
```bash
npm run build
```

---

### 1️⃣1️⃣ Iniciar el servidor de desarrollo

```bash
php artisan serve
```

La aplicación estará disponible en: **http://localhost:8000**

---

## 🔐 Credenciales de Acceso

Después de ejecutar los seeders, puedes acceder con las siguientes credenciales:

### 👨‍💼 Administrador
- **Email**: `admin@tecnoservi.com`
- **Contraseña**: `password`
- **Permisos**: Acceso total al sistema

### 👷 Supervisor
- **Email**: `supervisor@tecnoservi.com`
- **Contraseña**: `password`
- **Permisos**: Gestión operativa completa

### 🔧 Técnico
- **Email**: `tecnico@tecnoservi.com`
- **Contraseña**: `password`
- **Permisos**: Acceso limitado a sus tareas asignadas

---

## ⚡ Instalación Rápida (Todo en uno)

Si prefieres ejecutar todos los comandos de una vez:

**Windows:**
```bash
git clone https://github.com/tu-usuario/loginlaravel.git && cd loginlaravel && composer install && npm install && copy .env.example .env && php artisan key:generate && php artisan migrate && php artisan db:seed && php artisan storage:link && php artisan serve
```

**Linux/Mac:**
```bash
git clone https://github.com/tu-usuario/loginlaravel.git && cd loginlaravel && composer install && npm install && cp .env.example .env && php artisan key:generate && php artisan migrate && php artisan db:seed && php artisan storage:link && php artisan serve
```

> ⚠️ **Nota**: Debes crear la base de datos manualmente antes de ejecutar este comando.

---

## 🔄 Reset Completo de la Base de Datos

Si necesitas resetear completamente la base de datos (eliminar todas las tablas y volver a crear todo):

```bash
php artisan migrate:fresh --seed
```

> ⚠️ **Advertencia**: Este comando eliminará TODOS los datos de la base de datos.

---

## 🛠️ Comandos Útiles

### Limpiar cachés
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Ver estado de las migraciones
```bash
php artisan migrate:status
```

### Crear directorios de almacenamiento
```bash
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
```

### Dar permisos (Linux/Mac)
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

---

## ❗ Solución de Problemas Comunes

### Error: "Please provide a valid cache path"

**Solución:**
```bash
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
```

**Windows:**
```bash
mkdir storage\framework\cache\data
mkdir storage\framework\sessions
mkdir storage\framework\views
```

---

### Error de permisos en storage

**Linux/Mac:**
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

**Windows:**
- Click derecho en las carpetas `storage` y `bootstrap/cache`
- Propiedades → Seguridad → Editar
- Dar permisos de "Control total" al usuario actual

---

### Error: "Class 'Spatie\Permission\Models\Role' not found"

**Solución:**
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

---

### Error de conexión a la base de datos

Verifica que:
1. ✅ MySQL esté ejecutándose
2. ✅ La base de datos `tecnoservi` exista
3. ✅ Las credenciales en `.env` sean correctas
4. ✅ El puerto sea el correcto (por defecto: 3306)

**Verificar MySQL:**
```bash
mysql -u root -p -e "SHOW DATABASES;"
```

---

### La página no carga estilos CSS/JS

**Solución:**
```bash
npm run dev
php artisan cache:clear
```

---

## 📚 Estructura del Proyecto

```
loginlaravel/
├── app/
│   ├── Http/Controllers/     # Controladores
│   ├── Models/               # Modelos Eloquent
│   └── Policies/             # Políticas de autorización
├── database/
│   ├── migrations/           # Migraciones de BD
│   ├── seeders/              # Seeders (datos de prueba)
│   └── factories/            # Factories
├── resources/
│   └── views/                # Vistas Blade
├── routes/
│   └── web.php               # Rutas web
├── public/                   # Archivos públicos
└── storage/                  # Almacenamiento
```

---

## 🎯 Módulos Disponibles

Una vez instalado, tendrás acceso a:

- 📋 **Órdenes de Trabajo**: Gestión completa de servicios a domicilio
- 👥 **Clientes**: Base de datos de clientes
- 🚗 **Vehículos**: Control de flota
- 👨‍🔧 **Móviles/Grupos de Trabajo**: Organización de equipos técnicos
- 📦 **Stock/Inventario**: Control de materiales
- 📊 **Reportes PDF**: Generación de documentos profesionales
- 🔐 **Roles y Permisos**: Sistema de autorización

---

## 📧 Soporte

Si tienes problemas durante la instalación:

1. Verifica que cumples con todos los requisitos previos
2. Revisa la sección de "Solución de Problemas"
3. Consulta la documentación de Laravel: https://laravel.com/docs/9.x

---

## 📝 Notas Importantes

- 🔒 **Seguridad**: Cambia las contraseñas por defecto en producción
- 🗄️ **Base de Datos**: Haz backups regulares de tu base de datos
- 🔧 **Mantenimiento**: Mantén Laravel y las dependencias actualizadas
- 🌍 **Producción**: Configura `APP_DEBUG=false` y `APP_ENV=production` en el servidor

---

## ✅ Checklist de Instalación

- [ ] PHP >= 8.0.2 instalado
- [ ] Composer instalado
- [ ] MySQL instalado y ejecutándose
- [ ] Node.js y NPM instalados
- [ ] Repositorio clonado
- [ ] Dependencias instaladas (`composer install` y `npm install`)
- [ ] Archivo `.env` configurado
- [ ] Clave de aplicación generada (`php artisan key:generate`)
- [ ] Base de datos creada
- [ ] Migraciones ejecutadas (`php artisan migrate`)
- [ ] Seeders ejecutados (`php artisan db:seed`)
- [ ] Enlace simbólico creado (`php artisan storage:link`)
- [ ] Servidor iniciado (`php artisan serve`)
- [ ] Login exitoso con credenciales de prueba

---

**¡Listo! Tu sistema TecnoServi está funcionando.** 🎉

Accede a **http://localhost:8000** y comienza a gestionar tus servicios técnicos.
