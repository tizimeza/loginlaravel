@echo off
echo ========================================
echo    TecnoServi - Instalacion Automatica
echo ========================================
echo.

REM Verificar si composer esta instalado
where composer >nul 2>nul
if %errorlevel% neq 0 (
    echo [ERROR] Composer no esta instalado. Por favor instala Composer primero.
    echo Descarga Composer desde: https://getcomposer.org/download/
    pause
    exit /b 1
)

REM Verificar si PHP esta instalado
where php >nul 2>nul
if %errorlevel% neq 0 (
    echo [ERROR] PHP no esta instalado. Por favor instala PHP primero.
    echo Descarga PHP desde: https://windows.php.net/download/
    pause
    exit /b 1
)

echo [1/8] Instalando dependencias de Composer...
call composer install
if %errorlevel% neq 0 (
    echo [ERROR] Fallo la instalacion de dependencias de Composer
    pause
    exit /b 1
)

echo.
echo [2/8] Verificando archivo .env...
if not exist .env (
    echo Copiando .env.example a .env...
    copy .env.example .env
) else (
    echo .env ya existe, saltando...
)

echo.
echo [3/8] Generando clave de aplicacion...
call php artisan key:generate
if %errorlevel% neq 0 (
    echo [ERROR] Fallo la generacion de clave
    pause
    exit /b 1
)

echo.
echo [4/8] Limpiando caches...
call php artisan config:clear
call php artisan cache:clear
call php artisan view:clear

echo.
echo [5/8] Publicando archivos de Spatie Permission...
call php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
call php artisan config:clear

echo.
echo ========================================
echo   CONFIGURACION DE BASE DE DATOS
echo ========================================
echo.
echo IMPORTANTE: Antes de continuar, asegurate de:
echo   1. Haber creado la base de datos 'tecnoservi' en MySQL
echo   2. Haber configurado las credenciales en el archivo .env
echo.
echo Si aun no has creado la base de datos, ejecuta en MySQL:
echo    CREATE DATABASE tecnoservi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
echo.
set /p continuar="¿Deseas continuar con las migraciones? (S/N): "
if /i not "%continuar%"=="S" (
    echo.
    echo Instalacion pausada. Configura tu base de datos y ejecuta:
    echo   php artisan migrate
    echo   php artisan db:seed
    echo   php artisan storage:link
    pause
    exit /b 0
)

echo.
echo [6/8] Ejecutando migraciones...
call php artisan migrate
if %errorlevel% neq 0 (
    echo [ERROR] Fallo la ejecucion de migraciones
    echo Verifica las credenciales de base de datos en el archivo .env
    pause
    exit /b 1
)

echo.
echo [7/8] Poblando base de datos con datos de prueba...
call php artisan db:seed
if %errorlevel% neq 0 (
    echo [ERROR] Fallo la poblacion de datos
    pause
    exit /b 1
)

echo.
echo [8/8] Creando enlace simbolico para storage...
call php artisan storage:link
if %errorlevel% neq 0 (
    echo [ADVERTENCIA] Fallo la creacion del enlace simbolico
    echo Puedes necesitar ejecutar como Administrador
)

echo.
echo ========================================
echo       INSTALACION COMPLETADA
echo ========================================
echo.
echo El sistema ha sido instalado exitosamente!
echo.
echo Usuarios de prueba:
echo   Admin:      admin@tecnoservi.com / password
echo   Supervisor: supervisor@tecnoservi.com / password
echo   Tecnico:    tecnico@tecnoservi.com / password
echo.
echo Para iniciar el servidor de desarrollo, ejecuta:
echo   php artisan serve
echo.
echo Luego accede a: http://localhost:8000
echo.
pause
