# Docker Setup Guide for FinalCarrera E-commerce

This guide will help you run the FinalCarrera e-commerce application using Docker with 3 containers:
- **PostgreSQL Database** (Container 1)
- **Laravel Backend** (Container 2)
- **Vue.js Frontend with Vite** (Container 3)

## Prerequisites

- Docker Desktop installed on your system
- Docker Compose (comes with Docker Desktop)
- At least 4GB of RAM available for Docker
- Make (optional, for using Makefile commands)

## Quick Start

### 1. Clone the repository
```bash
git clone <repository-url>
cd FinalCarrera
```

### 2. Create environment file
```bash
cp .env.example .env
```

### 3. Start all containers

#### Using Make (Recommended)
```bash
make init
```

This will build, start, and seed the database automatically.

#### Using Docker Compose
```bash
docker-compose up -d
```

This command will:
- Build the Docker images
- Start the PostgreSQL database
- Start the Laravel backend
- Start the Vite development server
- Run database migrations automatically

### 4. Seed the database (if not using `make init`)
```bash
docker-compose exec backend php artisan db:seed --class=AdminSeeder
```

### 5. Access the application

- **Frontend**: http://localhost:5173
- **Backend API**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin/login

## Using Make Commands

If you have `make` installed, you can use these convenient commands:

```bash
make help              # Show all available commands
make up                # Start all containers
make up-dev            # Start in development mode with hot-reload
make down              # Stop all containers
make restart           # Restart all containers
make build             # Build/rebuild containers
make logs              # Show logs from all containers
make logs-backend      # Show backend logs
make logs-frontend     # Show frontend logs
make clean             # Stop and remove all volumes
make migrate           # Run database migrations
make seed              # Seed the database
make seed-admin        # Seed admin user
make fresh             # Fresh migrations with seed
make shell-backend     # Open shell in backend container
make shell-frontend    # Open shell in frontend container
make shell-db          # Open PostgreSQL shell
make cache-clear       # Clear Laravel cache
make ps                # Show running containers
make init              # Complete initial setup
```

## Container Management

### View running containers
```bash
docker-compose ps
```

### View logs
```bash
# All containers
docker-compose logs -f

# Specific container
docker-compose logs -f backend
docker-compose logs -f frontend
docker-compose logs -f db
```

### Stop all containers
```bash
docker-compose down
```

### Stop and remove all data (including database)
```bash
docker-compose down -v
```

### Restart containers
```bash
docker-compose restart
```

## Database Management

### Run migrations
```bash
docker-compose exec backend php artisan migrate
```

### Run migrations with seeders
```bash
docker-compose exec backend php artisan migrate --seed
```

### Run specific seeder
```bash
docker-compose exec backend php artisan db:seed --class=AdminSeeder
```

### Access PostgreSQL database directly
```bash
docker-compose exec db psql -U postgres -d finalcarrera
```

## Laravel Commands

### Run artisan commands
```bash
docker-compose exec backend php artisan <command>
```

### Clear cache
```bash
docker-compose exec backend php artisan cache:clear
docker-compose exec backend php artisan config:clear
docker-compose exec backend php artisan route:clear
docker-compose exec backend php artisan view:clear
```

### Install Composer packages
```bash
docker-compose exec backend composer install
```

## Frontend Commands

### Install npm packages
```bash
docker-compose exec frontend npm install
```

### Build for production
```bash
docker-compose exec frontend npm run build
```

## Troubleshooting

### Port already in use
If you get an error that a port is already in use, you can change the ports in the `.env` file:
```env
BACKEND_PORT=8001
FRONTEND_PORT=5174
DB_PORT=5433
```

### Permission issues
If you encounter permission issues with storage or cache directories:
```bash
docker-compose exec backend chmod -R 777 storage bootstrap/cache
```

### Rebuild containers
If you make changes to Dockerfiles or need to rebuild:
```bash
docker-compose up -d --build
```

### Fresh installation
To start completely fresh:
```bash
docker-compose down -v
docker-compose up -d --build
```

## Configuration

### Environment Variables

Key environment variables in `.env`:

- `DB_CONNECTION=pgsql` - Database driver
- `DB_HOST=db` - Database container name
- `DB_PORT=5432` - PostgreSQL port
- `DB_DATABASE=finalcarrera` - Database name
- `DB_USERNAME=postgres` - Database user
- `DB_PASSWORD=secret` - Database password
- `APP_URL=http://localhost:8000` - Application URL
- `BACKEND_PORT=8000` - Backend port
- `FRONTEND_PORT=5173` - Frontend port

### Stripe Configuration

Add your Stripe API keys to `.env`:
```env
STRIPE_KEY=your_stripe_public_key
STRIPE_SECRET=your_stripe_secret_key
```

## Production Deployment

For production deployment, make these changes:

1. Update `.env`:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
```

2. Build frontend for production:
```bash
docker-compose exec frontend npm run build
```

3. Use production-ready web server (nginx) instead of `php artisan serve`

## Architecture

```
┌─────────────────┐
│   Vue.js + Vite │ (Port 5173)
│    Frontend     │
└────────┬────────┘
         │
         │ HTTP Requests
         │
┌────────▼────────┐
│  Laravel API    │ (Port 8000)
│    Backend      │
└────────┬────────┘
         │
         │ Database Queries
         │
┌────────▼────────┐
│   PostgreSQL    │ (Port 5432)
│    Database     │
└─────────────────┘
```

## Support

For issues or questions, please open an issue on the GitHub repository.
