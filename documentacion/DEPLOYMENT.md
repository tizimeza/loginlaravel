# 🚀 Guía de Despliegue - TecnoServi

Esta guía te ayudará a desplegar el sistema TecnoServi en diferentes entornos.

## 📋 Checklist Pre-Despliegue

Antes de subir el proyecto a GitHub o desplegarlo en producción, verifica:

### Archivos de Configuración
- [x] `.env.example` existe y está actualizado
- [x] `.gitignore` configurado correctamente
- [x] `README.md` con instrucciones completas
- [x] `CONTRIBUTING.md` para guía de desarrollo
- [x] Scripts de instalación (`install.bat`, `install.sh`)

### Base de Datos
- [x] Todas las migraciones están creadas
- [x] Seeders configurados en `DatabaseSeeder.php`
- [x] Datos de ejemplo para testing

### Seguridad
- [ ] No hay credenciales hardcodeadas en el código
- [ ] Archivo `.env` NO está en el repositorio
- [ ] `APP_KEY` se genera con `php artisan key:generate`
- [ ] Validaciones en todos los formularios
- [ ] Políticas de autorización implementadas

## 🌐 Despliegue en Servidor de Producción

### 1. Requisitos del Servidor

- PHP >= 8.0 con extensiones requeridas
- Composer
- MySQL >= 5.7 o MariaDB >= 10.3
- Nginx o Apache
- SSL/TLS (certificado HTTPS)

### 2. Configuración Inicial

```bash
# Clonar el repositorio
git clone https://github.com/tu-usuario/tecnoservi.git
cd tecnoservi

# Instalar dependencias
composer install --optimize-autoloader --no-dev

# Configurar permisos
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### 3. Configuración del Entorno

Crear y configurar el archivo `.env`:

```bash
cp .env.example .env
nano .env
```

**Variables importantes para producción:**

```env
APP_NAME="TecnoServi"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tudominio.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tecnoservi_prod
DB_USERNAME=usuario_db
DB_PASSWORD=contraseña_segura

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-contraseña-app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@tudominio.com"
```

### 4. Preparar la Base de Datos

```bash
# Generar clave de aplicación
php artisan key:generate

# Ejecutar migraciones
php artisan migrate --force

# Poblar con datos iniciales (opcional)
php artisan db:seed --force
```

### 5. Optimizar para Producción

```bash
# Cachear configuración
php artisan config:cache

# Cachear rutas
php artisan route:cache

# Cachear vistas
php artisan view:cache

# Crear enlace simbólico
php artisan storage:link

# Optimizar autoload de Composer
composer dump-autoload --optimize
```

### 6. Configuración de Nginx

Archivo de configuración ejemplo (`/etc/nginx/sites-available/tecnoservi`):

```nginx
server {
    listen 80;
    server_name tudominio.com www.tudominio.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name tudominio.com www.tudominio.com;
    root /var/www/tecnoservi/public;

    ssl_certificate /etc/letsencrypt/live/tudominio.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/tudominio.com/privkey.pem;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Activar el sitio:

```bash
sudo ln -s /etc/nginx/sites-available/tecnoservi /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 7. Configuración de Apache

Archivo `.htaccess` en `/public`:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### 8. Configurar Cron Jobs

Editar crontab:

```bash
crontab -e
```

Agregar:

```
* * * * * cd /var/www/tecnoservi && php artisan schedule:run >> /dev/null 2>&1
```

### 9. Configurar Supervisor (para Queues)

Si usas queues, configurar supervisor (`/etc/supervisor/conf.d/tecnoservi-worker.conf`):

```ini
[program:tecnoservi-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/tecnoservi/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/tecnoservi/storage/logs/worker.log
stopwaitsecs=3600
```

Reiniciar supervisor:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start tecnoservi-worker:*
```

## 🔄 Actualización en Producción

```bash
# Entrar en modo mantenimiento
php artisan down

# Actualizar código
git pull origin main

# Instalar dependencias
composer install --optimize-autoloader --no-dev

# Ejecutar migraciones
php artisan migrate --force

# Limpiar cachés
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Cachear todo nuevamente
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Salir del modo mantenimiento
php artisan up
```

## 🔒 Seguridad en Producción

### Checklist de Seguridad

- [ ] `APP_DEBUG=false` en producción
- [ ] SSL/TLS configurado (HTTPS)
- [ ] Firewall configurado (solo puertos 80, 443, 22)
- [ ] Actualizar PHP y dependencias regularmente
- [ ] Backups automáticos de base de datos
- [ ] Logs monitoreados
- [ ] Autenticación de dos factores para usuarios admin (opcional)

### Backups Automáticos

Script de backup (`/root/backup-tecnoservi.sh`):

```bash
#!/bin/bash

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups/tecnoservi"
DB_NAME="tecnoservi_prod"
DB_USER="usuario_db"
DB_PASS="contraseña_segura"

# Crear directorio de backups
mkdir -p $BACKUP_DIR

# Backup de base de datos
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/db_$DATE.sql.gz

# Backup de archivos
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/tecnoservi/storage/app/public

# Eliminar backups de más de 30 días
find $BACKUP_DIR -type f -mtime +30 -delete

echo "Backup completado: $DATE"
```

Hacer ejecutable y agregar a crontab:

```bash
chmod +x /root/backup-tecnoservi.sh

# En crontab (ejecutar diariamente a las 2 AM)
0 2 * * * /root/backup-tecnoservi.sh >> /var/log/backup-tecnoservi.log 2>&1
```

## 📊 Monitoreo

### Logs Importantes

```bash
# Logs de Laravel
tail -f storage/logs/laravel.log

# Logs de Nginx
tail -f /var/log/nginx/error.log
tail -f /var/log/nginx/access.log

# Logs de PHP
tail -f /var/log/php8.0-fpm.log
```

### Comandos Útiles de Diagnóstico

```bash
# Ver estado del sistema
php artisan about

# Verificar conectividad de BD
php artisan db:show

# Listar rutas
php artisan route:list

# Ver configuración
php artisan config:show

# Verificar permisos
ls -la storage/
ls -la bootstrap/cache/
```

## 🆘 Solución de Problemas en Producción

### Error 500 - Internal Server Error

1. Verificar logs: `tail -f storage/logs/laravel.log`
2. Verificar permisos: `sudo chown -R www-data:www-data storage bootstrap/cache`
3. Limpiar caché: `php artisan config:clear && php artisan cache:clear`

### Base de datos no conecta

1. Verificar credenciales en `.env`
2. Verificar que MySQL esté corriendo: `sudo systemctl status mysql`
3. Probar conexión manual: `mysql -u usuario_db -p`

### Archivos no se suben

1. Verificar enlace simbólico: `php artisan storage:link`
2. Verificar permisos: `sudo chmod -R 775 storage`
3. Verificar tamaño máximo en `php.ini`: `upload_max_filesize` y `post_max_size`

---

¿Preguntas? Consulta la [Guía de Contribución](CONTRIBUTING.md) o abre un issue en GitHub.
