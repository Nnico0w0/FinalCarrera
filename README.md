## E-commerce app - Laravel, Vue.js, Tailwind.css and Inertia.js with Stripe Payment

### Demo Screensho(User)
<div style="display: flex; gap: 10px;">
    <img src="public/Screenshot%20(59).png" alt="User Screenshot 1"  height="450" width="700"/>
    <img src="public/Screenshot%20(60).png" alt="User Screenshot 2" height="450" width="700" />
</div>

### Demo Screenshot (Admin)
<div style="display: flex; gap: 10px;">
    <img src="public/Screenshot%20(58).png" alt="Admin Screenshot 2"  height="450" width="700"/>
    <img src="public/Screenshot%20(57).png" alt="Admin Screenshot 1" height="450" width="700"/>
</div>


### REST API

This application includes a REST API accessible at `http://localhost:8000/api/v1/`

**Available endpoints:**
- `/api/v1/users` - User management
- `/api/v1/products` - Product management with filtering
- `/api/v1/categories` - Category management
- `/api/v1/brands` - Brand management
- `/api/v1/orders` - Order management

All endpoints support standard CRUD operations (GET, POST, PUT, DELETE).

For detailed API documentation and usage examples, see [API_DOCUMENTATION.md](API_DOCUMENTATION.md)

### Installation
## Installation

### Option 1: Docker Installation (Recommended)

**Requirements:** Docker and Docker Compose

1. Clone the repo
2. Copy `.env.example` into `.env`
3. Start the containers: `docker-compose up -d`
4. Run database migrations: `docker-compose exec backend php artisan migrate --seed`
5. Run seeders: `docker-compose exec backend php artisan db:seed --class=AdminSeeder`
6. Access the application:
   - Frontend: http://localhost:5173
   - Backend: http://localhost:8000
   - Admin: http://localhost:8000/admin/login

For detailed Docker instructions, see [DOCKER.md](DOCKER.md)

### Option 2: Traditional Installation

Requirements: MySQL/PostgreSQL, PHP 8.1, Node.js and composer.

1. Clone the repo
2. Copy .env.example into .env and configure database credentials
3. Run `composer install`
4. Set the encryption key `php artisan key:generate`
5. Run migrations `php artisan migrate --seed`
6. Run data seeder to test ``` php artisan db:seed AdminSeeder``` and and other db seeder files you can find under database/seeders
7. Start your local server `php artisan serve`
8. Open new terminal and navigate to the project root directory
   Run `npm install`
9. Run `npm run dev` to start vite server for Laravel frontend
10. For Stripe Api key, please go to .env file and replace with your api key
11. Then go to http://127.0.0.1:8000 or http://127.0.0.1:8000/admin/login 
