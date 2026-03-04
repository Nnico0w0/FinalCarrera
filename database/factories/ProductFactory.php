<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'title' => ucfirst($this->faker->unique()->words(3, true)) . ' ' . $this->faker->randomElement(['Pro', 'Max', 'Lite']),
            'description' => $this->faker->paragraph(3),
            'published' => true,
            'inStock' => true,
            'price' => $this->faker->randomFloat(2, 200, 3500),
            'sales_count' => $this->faker->numberBetween(0, 250),
            'quantity' => $this->faker->numberBetween(5, 50),
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
        ];
    }
}
