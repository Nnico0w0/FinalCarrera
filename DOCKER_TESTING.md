# Docker Testing and Validation Guide

This document describes the testing and validation performed on the Docker setup.

## Configuration Validation

All Docker configuration files have been validated:

- ✅ `docker-compose.yml` - Valid syntax
- ✅ `docker-compose.dev.yml` - Valid syntax
- ✅ `docker-compose.prod.yml` - Valid syntax
- ✅ `docker-entrypoint.sh` - Valid shell script syntax
- ✅ `Dockerfile.backend` - Valid Dockerfile
- ✅ `Dockerfile.frontend` - Valid Dockerfile

## Architecture Overview

```
┌─────────────────────────────────────────────────────────────┐
│                     Docker Environment                       │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  ┌──────────────────┐      ┌──────────────────┐            │
│  │   Frontend       │      │    Backend       │            │
│  │   Container      │◄────►│   Container      │            │
│  │                  │      │                  │            │
│  │  Node.js 18      │      │  PHP 8.1-FPM     │            │
│  │  Vite Dev Server │      │  Laravel 10      │            │
│  │  Port: 5173      │      │  Port: 8000      │            │
│  └──────────────────┘      └─────────┬────────┘            │
│                                       │                      │
│                                       │                      │
│                            ┌──────────▼────────┐            │
│                            │   Database        │            │
│                            │   Container       │            │
│                            │                   │            │
│                            │  PostgreSQL 15    │            │
│                            │  Port: 5432       │            │
│                            └───────────────────┘            │
│                                                               │
└─────────────────────────────────────────────────────────────┘
```

## Container Configuration

### 1. Database Container (PostgreSQL)
- **Image**: postgres:15-alpine
- **Container Name**: finalcarrera_db
- **Port**: 5432 (configurable via DB_PORT)
- **Volume**: Persistent storage for database data
- **Health Check**: Verifies PostgreSQL is ready before starting backend
- **Environment Variables**:
  - POSTGRES_DB
  - POSTGRES_USER
  - POSTGRES_PASSWORD

### 2. Backend Container (Laravel)
- **Base Image**: php:8.1-fpm-alpine
- **Container Name**: finalcarrera_backend
- **Port**: 8000 (configurable via BACKEND_PORT)
- **PHP Extensions**: 
  - pdo, pdo_pgsql, pgsql (PostgreSQL support)
  - mbstring, exif, pcntl, bcmath, gd, intl, zip
- **Features**:
  - Automatic dependency installation via Composer
  - Automatic .env file creation from .env.example
  - Automatic application key generation
  - Database migrations on startup
  - Configuration caching for better performance
- **Volumes**: 
  - Application code mounted for development
  - Separate volumes for vendor and node_modules

### 3. Frontend Container (Vue.js)
- **Base Image**: node:18-alpine
- **Container Name**: finalcarrera_frontend
- **Port**: 5173 (configurable via FRONTEND_PORT)
- **Features**:
  - Vite development server with HMR (Hot Module Replacement)
  - Automatic npm dependency installation
  - File watching with polling for cross-platform compatibility
- **Volumes**:
  - Application code mounted for development
  - Separate volume for node_modules

## Network Configuration

- **Network Type**: Bridge network
- **Network Name**: finalcarrera_network
- **Inter-Container Communication**: 
  - Backend connects to database using hostname `db`
  - Frontend connects to backend using hostname `backend`
  - All containers can communicate within the private network

## Volume Management

### Named Volumes:
- `postgres_data`: Persists PostgreSQL database data

### Anonymous Volumes:
- `/var/www/vendor`: Backend PHP dependencies
- `/var/www/node_modules`: Backend Node.js dependencies
- `/app/node_modules`: Frontend Node.js dependencies

## Environment Configurations

### Development Mode (docker-compose.dev.yml)
- APP_ENV=local
- APP_DEBUG=true
- Full error reporting
- Hot module reloading enabled
- File watching enabled

### Production Mode (docker-compose.prod.yml)
- APP_ENV=production
- APP_DEBUG=false
- Error logging only
- Nginx reverse proxy
- Optimized caching

## Testing Checklist

### Pre-deployment Testing

- [x] Docker Compose file syntax validation
- [x] Dockerfile syntax validation
- [x] Shell script syntax validation
- [x] Environment variable configuration
- [x] Network configuration
- [x] Volume configuration
- [x] Health check configuration

### Manual Testing Steps

When you run the containers, verify the following:

1. **Container Startup**
   ```bash
   docker compose ps
   ```
   Expected: All 3 containers should be in "running" state

2. **Database Connectivity**
   ```bash
   docker compose exec backend php artisan db:show
   ```
   Expected: Should display database connection information

3. **Backend Health**
   ```bash
   curl http://localhost:8000
   ```
   Expected: Should return the Laravel application response

4. **Frontend Health**
   ```bash
   curl http://localhost:5173
   ```
   Expected: Should return the Vite dev server response

5. **Database Migrations**
   ```bash
   docker compose exec backend php artisan migrate:status
   ```
   Expected: Should show all migrations have been run

6. **Logs Verification**
   ```bash
   docker compose logs backend
   docker compose logs frontend
   docker compose logs db
   ```
   Expected: No critical errors in logs

## Common Issues and Solutions

### Issue: Port Already in Use
**Solution**: Change ports in `.env` file:
```env
BACKEND_PORT=8001
FRONTEND_PORT=5174
DB_PORT=5433
```

### Issue: Permission Denied on Storage
**Solution**: Fix permissions:
```bash
docker compose exec backend chmod -R 777 storage bootstrap/cache
```

### Issue: Database Connection Failed
**Solution**: Verify database is healthy:
```bash
docker compose ps db
docker compose logs db
```

### Issue: Composer Dependencies Not Installed
**Solution**: Rebuild backend container:
```bash
docker compose build --no-cache backend
docker compose up -d backend
```

### Issue: Vite HMR Not Working
**Solution**: Ensure proper configuration in vite.config.js and check browser console for WebSocket connection errors.

## Performance Considerations

### Development
- Use volume mounts for live code reloading
- Enable debug mode for detailed error messages
- Use polling for file watching (works across all platforms)

### Production
- Build frontend assets: `npm run build`
- Disable debug mode
- Enable all caching mechanisms
- Use nginx for serving static files
- Consider using a CDN for static assets

## Security Considerations

### Implemented Security Measures:
1. ✅ Separated environment files for different environments
2. ✅ Database credentials via environment variables
3. ✅ No hardcoded secrets in Dockerfiles
4. ✅ Health checks for database availability
5. ✅ Proper file permissions in containers
6. ✅ .dockerignore to exclude sensitive files
7. ✅ Security headers in nginx configuration

### Recommended Additional Measures:
- Use Docker secrets for sensitive data in production
- Implement HTTPS with SSL certificates
- Use a reverse proxy (nginx/traefik) in production
- Regular security updates for base images
- Implement rate limiting
- Use PostgreSQL user with limited privileges

## Maintenance

### Regular Tasks:
1. Update base images: `docker compose pull`
2. Rebuild containers: `docker compose build --no-cache`
3. Clean up unused resources: `docker system prune -a`
4. Backup database: `docker compose exec db pg_dump -U postgres finalcarrera > backup.sql`
5. Monitor logs: `docker compose logs -f`

### Updates and Upgrades:
- PHP updates: Modify Dockerfile.backend base image version
- Node.js updates: Modify Dockerfile.frontend base image version
- PostgreSQL updates: Modify docker-compose.yml database image version
- Always test updates in development before production deployment
