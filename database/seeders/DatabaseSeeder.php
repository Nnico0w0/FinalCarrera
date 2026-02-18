<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed brands first (products depend on brands)
        $this->call(BrandSeeder::class);
        
        // Seed categories (products depend on categories)
        $this->call(CategorySeeder::class);
        
        // Seed products (depends on brands and categories)
        $this->call(ProductSeeder::class);
        
        // Seed users if needed (avoid duplicating the test account)
        if (! User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }
    }
}
