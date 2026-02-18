# Docker Configuration Fix Notes

## Problem Statement
The dockerized project had issues where:
1. Port 5173 showed the default Vite/Laravel view instead of the actual frontend
2. The frontend in `resources` directory was not visible
3. APIs needed to be properly exposed on port 8000

## Root Cause
The application uses **Laravel + Inertia.js + Vue.js + Vite**. In this architecture:
- Laravel serves the HTML pages with Inertia.js
- Vite provides hot module replacement (HMR) for development
- Vite dev server (port 5173) by default only serves assets, not the full application

The documentation stated to access the frontend on port 5173, but Vite wasn't configured to proxy requests to Laravel backend.

## Solution Implemented

### 1. Vite Proxy Configuration (`vite.config.js`)
Added a proxy configuration that forwards all requests (except Vite's internal ones) to the Laravel backend:

```javascript
proxy: {
    '': {
        target: 'http://backend:8000',
        changeOrigin: true,
        bypass: function (req, res, options) {
            // Don't proxy Vite's internal requests (HMR, node_modules, etc.)
            if (req.url.includes('/@') || req.url.includes('/node_modules/')) {
                return req.url;
            }
        },
    },
},
```

**How it works:**
- User accesses `localhost:5173/` → proxied to `backend:8000/` → Laravel returns Inertia page
- User accesses `localhost:5173/api/v1/products` → proxied to `backend:8000/api/v1/products` → API response
- Vite internal requests (`/@vite/client`, etc.) → handled by Vite directly for HMR

### 2. HMR Configuration
Updated for Docker environment:
```javascript
hmr: {
    host: 'localhost',
    clientPort: 5173,
},
```

### 3. .gitignore Updates
Added standard Laravel files that should not be committed:
- `/public/hot` (generated dynamically by Vite)
- `/public/storage`
- `/storage/*.key`
- `.phpunit.result.cache`
- And other standard Laravel temporary files

### 4. Removed Static hot File
The `/public/hot` file is now generated dynamically when Vite starts, so removed the static version from the repository.

## Architecture

```
┌─────────────┐
│   Browser   │
└──────┬──────┘
       │
       ├─────────── Port 5173 (Vite Dev Server) ──────────┐
       │            - Serves JS/CSS assets               │
       │            - Proxies all other requests          │
       │                                                  │
       │                                                  ↓
       └─────────── Port 8000 (Laravel Backend) ─────────┐
                    - Serves Inertia.js pages            │
                    - Handles API requests               │
                    - Business logic & routing           │
                                                         │
                                                         ↓
                                              Database (PostgreSQL)
```

## Usage

### Starting the Application
```bash
# Start all containers
docker compose up -d --build

# Or using Make
make init
```

### Accessing the Application

**Frontend (Inertia.js Vue App):**
- http://localhost:5173/ - Main application (proxied to Laravel)
- http://localhost:8000/ - Also works (direct Laravel access)

**APIs:**
- http://localhost:5173/api/v1/* - API endpoints (proxied)
- http://localhost:8000/api/v1/* - API endpoints (direct)

**Admin Panel:**
- http://localhost:5173/admin/login - Admin login (proxied)
- http://localhost:8000/admin/login - Admin login (direct)

### Available API Endpoints

All endpoints are accessible at `/api/v1/`:

- `/api/v1/users` - User management
- `/api/v1/products` - Product management
- `/api/v1/categories` - Category management
- `/api/v1/brands` - Brand management
- `/api/v1/orders` - Order management

All endpoints support standard CRUD operations (GET, POST, PUT, DELETE).

### Example API Calls

```bash
# Get all products
curl http://localhost:5173/api/v1/products
# or
curl http://localhost:8000/api/v1/products

# Get specific product
curl http://localhost:5173/api/v1/products/1

# Get all categories
curl http://localhost:5173/api/v1/categories
```

## Development Workflow

1. Start Docker containers: `docker compose up -d`
2. Access frontend at `localhost:5173` - see your Vue.js application
3. Make changes to Vue components in `resources/js/`
4. Vite will hot reload changes automatically
5. Access APIs at `localhost:5173/api/v1/*` or `localhost:8000/api/v1/*`

## Troubleshooting

### Frontend shows blank page or error
- Check that backend container is running: `docker compose ps`
- Check backend logs: `docker compose logs backend`
- Check frontend logs: `docker compose logs frontend`
- Ensure database migrations ran: `docker compose exec backend php artisan migrate`

### HMR not working
- Verify the `public/hot` file exists and contains `http://localhost:5173`
- Check browser console for WebSocket connection errors
- Restart frontend container: `docker compose restart frontend`

### API returns 404
- Verify the route exists in `routes/api.php`
- Check backend logs for errors
- Ensure the controller exists in `app/Http/Controllers/Api/V1/`

### Database connection errors
- Wait for the database to be ready (the backend entrypoint script waits automatically)
- Check database logs: `docker compose logs db`
- Verify database credentials in `.env` match docker-compose.yml

## Technical Details

### File Sharing Between Containers
Both backend and frontend containers mount the same project directory:
- Backend: `./ → /var/www`
- Frontend: `./ → /app`

This ensures:
- The `public/hot` file created by Vite is visible to Laravel
- Code changes are reflected in both containers
- Assets built by Vite are accessible to Laravel

### Network Configuration
All containers are on the same Docker network (`finalcarrera_network`):
- Containers can communicate using service names (e.g., `backend:8000`, `frontend:5173`)
- The host machine accesses services via `localhost` with mapped ports

## Files Modified

1. `vite.config.js` - Added proxy and HMR configuration
2. `.gitignore` - Added Laravel standard temporary files
3. `public/hot` - Removed (now generated dynamically)
4. `.env` - Created from `.env.example` (not committed)

## Testing Checklist

- [ ] `docker compose up -d --build` starts all containers successfully
- [ ] `localhost:5173` shows the Vue.js frontend with products
- [ ] `localhost:5173/api/v1/products` returns JSON product list
- [ ] `localhost:8000/api/v1/products` returns JSON product list
- [ ] `localhost:5173/admin/login` shows admin login page
- [ ] Hot module replacement works when editing Vue components
- [ ] Database connection works (migrations run successfully)
- [ ] No CORS errors in browser console
