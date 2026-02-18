<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Product 1: Dell Laptop
        Product::create([
            'title' => 'Dell Inspiron 15 Laptop',
            'price' => 799.99,
            'quantity' => 15,
            'category_id'=> 1, // Laptops
            'brand_id'=>1, // Dell
            'published' => true,
            'inStock' => true,
            'description'=>'The Dell Inspiron laptop offers an excellent combination of performance and portability. Ideal for both work and entertainment, it features a sleek design, long battery life, and powerful hardware. Experience smooth multitasking and stunning visuals with its high-definition display and the latest technology.'
        ]);

        // Product 2: HP Laptop
        Product::create([
            'title' => 'HP Pavilion Gaming Laptop',
            'price' => 1099.99,
            'quantity' => 10,
            'category_id'=> 1, // Laptops
            'brand_id'=>4, // HP
            'published' => true,
            'inStock' => true,
            'description'=>'Powerful gaming laptop with dedicated graphics card, perfect for gaming and content creation. Features advanced cooling system and RGB keyboard.'
        ]);

        // Product 3: Samsung Smartphone
        Product::create([
            'title' => 'Samsung Galaxy S23',
            'price' => 899.99,
            'quantity' => 25,
            'category_id'=> 2, // Smartphones
            'brand_id'=>2, // Samsung
            'published' => true,
            'inStock' => true,
            'description'=>'Latest Samsung flagship smartphone with stunning display, powerful processor, and advanced camera system. Capture life\'s moments in incredible detail.'
        ]);

        // Product 4: Apple iPhone
        Product::create([
            'title' => 'Apple iPhone 15 Pro',
            'price' => 1199.99,
            'quantity' => 20,
            'category_id'=> 2, // Smartphones
            'brand_id'=>3, // Apple
            'published' => true,
            'inStock' => true,
            'description'=>'Revolutionary iPhone with A17 Pro chip, titanium design, and advanced camera features. The most powerful iPhone ever made.'
        ]);

        // Product 5: Apple Watch
        Product::create([
            'title' => 'Apple Watch Series 9',
            'price' => 429.99,
            'quantity' => 30,
            'category_id'=> 3, // Smartwatches
            'brand_id'=>3, // Apple
            'published' => true,
            'inStock' => true,
            'description'=>'Advanced health and fitness tracking with bright always-on display. Stay connected with calls, messages, and notifications right from your wrist.'
        ]);

        // Product 6: Samsung Watch
        Product::create([
            'title' => 'Samsung Galaxy Watch 6',
            'price' => 349.99,
            'quantity' => 18,
            'category_id'=> 3, // Smartwatches
            'brand_id'=>2, // Samsung
            'published' => true,
            'inStock' => true,
            'description'=>'Premium smartwatch with comprehensive health monitoring, long battery life, and seamless integration with Samsung devices.'
        ]);

        // Product 7: Apple iPad
        Product::create([
            'title' => 'Apple iPad Air',
            'price' => 649.99,
            'quantity' => 22,
            'category_id'=> 4, // Tablets
            'brand_id'=>3, // Apple
            'published' => true,
            'inStock' => true,
            'description'=>'Powerful and versatile tablet with M2 chip, stunning Liquid Retina display, and all-day battery life. Perfect for creativity and productivity.'
        ]);

        // Product 8: Samsung Tablet
        Product::create([
            'title' => 'Samsung Galaxy Tab S9',
            'price' => 799.99,
            'quantity' => 12,
            'category_id'=> 4, // Tablets
            'brand_id'=>2, // Samsung
            'published' => true,
            'inStock' => true,
            'description'=>'Premium Android tablet with AMOLED display, S Pen included, and powerful performance for work and entertainment.'
        ]);

        // Product 9: Lenovo Laptop
        Product::create([
            'title' => 'Lenovo ThinkPad X1 Carbon',
            'price' => 1399.99,
            'quantity' => 8,
            'category_id'=> 1, // Laptops
            'brand_id'=>5, // Lenovo
            'published' => true,
            'inStock' => true,
            'description'=>'Business-class laptop with lightweight carbon fiber design, excellent keyboard, and enterprise-grade security features.'
        ]);

        // Product 10: Samsung Phone (another smartphone option)
        Product::create([
            'title' => 'Samsung Galaxy A54 5G',
            'price' => 449.99,
            'quantity' => 18,
            'category_id'=> 2, // Smartphones
            'brand_id'=>2, // Samsung
            'published' => true,
            'inStock' => true,
            'description'=>'Mid-range smartphone with excellent camera, long battery life, and 5G connectivity. Perfect balance of features and affordability.'
        ]);
    }
}
