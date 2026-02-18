# Guía de Instalación Docker - FinalCarrera E-commerce

Esta guía te ayudará a ejecutar la aplicación FinalCarrera usando Docker con 3 contenedores:
- **Base de Datos PostgreSQL** (Contenedor 1)
- **Backend Laravel** (Contenedor 2)
- **Frontend Vue.js con Vite** (Contenedor 3)

## Requisitos Previos

- Docker Desktop instalado en tu sistema
- Docker Compose (viene con Docker Desktop)
- Al menos 4GB de RAM disponible para Docker
- Make (opcional, para usar comandos del Makefile)

## Inicio Rápido

### 1. Clonar el repositorio
```bash
git clone <url-del-repositorio>
cd FinalCarrera
```

### 2. Crear archivo de configuración
```bash
cp .env.example .env
```

### 3. Iniciar todos los contenedores

#### Usando Make (Recomendado)
```bash
make init
```

Este comando construirá, iniciará y poblará la base de datos automáticamente.

#### Usando Docker Compose
```bash
docker compose up -d
```

### 4. Poblar la base de datos (si no usaste `make init`)
```bash
docker compose exec backend php artisan db:seed --class=AdminSeeder
```

### 5. Acceder a la aplicación

- **Frontend**: http://localhost:5173
- **Backend API**: http://localhost:8000
- **Panel de Administración**: http://localhost:8000/admin/login

## Comandos Make Útiles

Si tienes `make` instalado, puedes usar estos comandos:

```bash
make help              # Mostrar todos los comandos disponibles
make up                # Iniciar todos los contenedores
make up-dev            # Iniciar en modo desarrollo con recarga automática
make down              # Detener todos los contenedores
make restart           # Reiniciar todos los contenedores
make build             # Construir/reconstruir contenedores
make logs              # Mostrar logs de todos los contenedores
make logs-backend      # Mostrar logs del backend
make logs-frontend     # Mostrar logs del frontend
make clean             # Detener y eliminar todos los volúmenes
make migrate           # Ejecutar migraciones de base de datos
make seed              # Poblar la base de datos
make seed-admin        # Crear usuario administrador
make fresh             # Migraciones frescas con datos de prueba
make shell-backend     # Abrir terminal en contenedor backend
make shell-frontend    # Abrir terminal en contenedor frontend
make shell-db          # Abrir terminal PostgreSQL
make cache-clear       # Limpiar caché de Laravel
make ps                # Mostrar contenedores en ejecución
make init              # Configuración inicial completa
```

## Gestión de Contenedores con Docker Compose

### Ver contenedores en ejecución
```bash
docker compose ps
```

### Ver logs
```bash
# Todos los contenedores
docker compose logs -f

# Contenedor específico
docker compose logs -f backend
docker compose logs -f frontend
docker compose logs -f db
```

### Detener todos los contenedores
```bash
docker compose down
```

### Detener y eliminar todos los datos (incluida la base de datos)
```bash
docker compose down -v
```

### Reiniciar contenedores
```bash
docker compose restart
```

## Gestión de Base de Datos

### Ejecutar migraciones
```bash
docker compose exec backend php artisan migrate
```

### Ejecutar migraciones con datos de prueba
```bash
docker compose exec backend php artisan migrate --seed
```

### Ejecutar un seeder específico
```bash
docker compose exec backend php artisan db:seed --class=AdminSeeder
```

### Acceder directamente a PostgreSQL
```bash
docker compose exec db psql -U postgres -d finalcarrera
```

## Comandos de Laravel

### Ejecutar comandos artisan
```bash
docker compose exec backend php artisan <comando>
```

### Limpiar caché
```bash
docker compose exec backend php artisan cache:clear
docker compose exec backend php artisan config:clear
docker compose exec backend php artisan route:clear
docker compose exec backend php artisan view:clear
```

### Instalar paquetes de Composer
```bash
docker compose exec backend composer install
```

## Comandos de Frontend

### Instalar paquetes npm
```bash
docker compose exec frontend npm install
```

### Construir para producción
```bash
docker compose exec frontend npm run build
```

## Solución de Problemas

### Puerto ya en uso
Si obtienes un error de que un puerto ya está en uso, puedes cambiar los puertos en el archivo `.env`:
```env
BACKEND_PORT=8001
FRONTEND_PORT=5174
DB_PORT=5433
```

### Problemas de permisos
Si encuentras problemas de permisos con los directorios storage o cache:
```bash
docker compose exec backend chmod -R 777 storage bootstrap/cache
```

### Reconstruir contenedores
Si haces cambios en los Dockerfiles o necesitas reconstruir:
```bash
docker compose up -d --build
```

### Instalación fresca
Para comenzar completamente de cero:
```bash
docker compose down -v
docker compose up -d --build
```

## Configuración

### Variables de Entorno Importantes

Variables clave en `.env`:

- `DB_CONNECTION=pgsql` - Driver de base de datos
- `DB_HOST=db` - Nombre del contenedor de base de datos
- `DB_PORT=5432` - Puerto de PostgreSQL
- `DB_DATABASE=finalcarrera` - Nombre de la base de datos
- `DB_USERNAME=postgres` - Usuario de base de datos
- `DB_PASSWORD=secret` - Contraseña de base de datos
- `APP_URL=http://localhost:8000` - URL de la aplicación
- `BACKEND_PORT=8000` - Puerto del backend
- `FRONTEND_PORT=5173` - Puerto del frontend

### Configuración de Stripe

Agrega tus claves API de Stripe en `.env`:
```env
STRIPE_KEY=tu_clave_publica_stripe
STRIPE_SECRET=tu_clave_secreta_stripe
```

## Despliegue en Producción

Para despliegue en producción, realiza estos cambios:

1. Actualiza `.env`:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com
```

2. Construye el frontend para producción:
```bash
docker compose exec frontend npm run build
```

3. Usa el archivo de compose de producción:
```bash
docker compose -f docker-compose.prod.yml up -d
```

## Arquitectura

```
┌─────────────────┐
│   Vue.js + Vite │ (Puerto 5173)
│    Frontend     │
└────────┬────────┘
         │
         │ Peticiones HTTP
         │
┌────────▼────────┐
│  Laravel API    │ (Puerto 8000)
│    Backend      │
└────────┬────────┘
         │
         │ Consultas de Base de Datos
         │
┌────────▼────────┐
│   PostgreSQL    │ (Puerto 5432)
│  Base de Datos  │
└─────────────────┘
```

## Soporte

Para problemas o preguntas, por favor abre un issue en el repositorio de GitHub.

## Documentación Adicional

- [DOCKER.md](DOCKER.md) - Guía completa en inglés
- [DOCKER_TESTING.md](DOCKER_TESTING.md) - Guía de pruebas y validación
- [README.md](README.md) - Documentación general del proyecto
