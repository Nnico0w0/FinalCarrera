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


### Installation

Requirements: MySQL, PHP 8.1, Node.js and composer.

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
