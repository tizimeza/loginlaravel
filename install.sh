#!/bin/bash

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo "========================================"
echo "   TecnoServi - Instalación Automática"
echo "========================================"
echo ""

# Verificar si composer está instalado
if ! command -v composer &> /dev/null; then
    echo -e "${RED}[ERROR]${NC} Composer no está instalado. Por favor instala Composer primero."
    echo "Descarga Composer desde: https://getcomposer.org/download/"
    exit 1
fi

# Verificar si PHP está instalado
if ! command -v php &> /dev/null; then
    echo -e "${RED}[ERROR]${NC} PHP no está instalado. Por favor instala PHP primero."
    exit 1
fi

echo -e "${GREEN}[1/7]${NC} Instalando dependencias de Composer..."
composer install
if [ $? -ne 0 ]; then
    echo -e "${RED}[ERROR]${NC} Falló la instalación de dependencias de Composer"
    exit 1
fi

echo ""
echo -e "${GREEN}[2/7]${NC} Verificando archivo .env..."
if [ ! -f .env ]; then
    echo "Copiando .env.example a .env..."
    cp .env.example .env
else
    echo ".env ya existe, saltando..."
fi

echo ""
echo -e "${GREEN}[3/7]${NC} Generando clave de aplicación..."
php artisan key:generate
if [ $? -ne 0 ]; then
    echo -e "${RED}[ERROR]${NC} Falló la generación de clave"
    exit 1
fi

echo ""
echo -e "${GREEN}[4/7]${NC} Limpiando cachés..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo ""
echo "========================================"
echo "   CONFIGURACIÓN DE BASE DE DATOS"
echo "========================================"
echo ""
echo "IMPORTANTE: Antes de continuar, asegúrate de:"
echo "  1. Haber creado la base de datos 'tecnoservi' en MySQL"
echo "  2. Haber configurado las credenciales en el archivo .env"
echo ""
echo "Si aún no has creado la base de datos, ejecuta en MySQL:"
echo "   CREATE DATABASE tecnoservi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
echo ""
read -p "¿Deseas continuar con las migraciones? (s/n): " -n 1 -r
echo ""
if [[ ! $REPLY =~ ^[Ss]$ ]]; then
    echo ""
    echo "Instalación pausada. Configura tu base de datos y ejecuta:"
    echo "  php artisan migrate"
    echo "  php artisan db:seed"
    echo "  php artisan storage:link"
    exit 0
fi

echo ""
echo -e "${GREEN}[5/7]${NC} Ejecutando migraciones..."
php artisan migrate
if [ $? -ne 0 ]; then
    echo -e "${RED}[ERROR]${NC} Falló la ejecución de migraciones"
    echo "Verifica las credenciales de base de datos en el archivo .env"
    exit 1
fi

echo ""
echo -e "${GREEN}[6/7]${NC} Poblando base de datos con datos de prueba..."
php artisan db:seed
if [ $? -ne 0 ]; then
    echo -e "${RED}[ERROR]${NC} Falló la población de datos"
    exit 1
fi

echo ""
echo -e "${GREEN}[7/7]${NC} Creando enlace simbólico para storage..."
php artisan storage:link
if [ $? -ne 0 ]; then
    echo -e "${YELLOW}[ADVERTENCIA]${NC} Falló la creación del enlace simbólico"
    echo "Puedes necesitar ejecutar con sudo o ajustar permisos"
fi

# Ajustar permisos en Linux/Mac
echo ""
echo "Ajustando permisos de carpetas..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || echo -e "${YELLOW}[ADVERTENCIA]${NC} No se pudieron ajustar permisos automáticamente"

echo ""
echo "========================================"
echo "       INSTALACIÓN COMPLETADA"
echo "========================================"
echo ""
echo -e "${GREEN}El sistema ha sido instalado exitosamente!${NC}"
echo ""
echo "Usuarios de prueba:"
echo "  Admin:      admin@tecnoservi.com / password"
echo "  Supervisor: supervisor@tecnoservi.com / password"
echo "  Técnico:    tecnico@tecnoservi.com / password"
echo ""
echo "Para iniciar el servidor de desarrollo, ejecuta:"
echo "  php artisan serve"
echo ""
echo "Luego accede a: http://localhost:8000"
echo ""
