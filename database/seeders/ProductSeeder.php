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
        Product::create([
            'title' => 'Dell Inspiron Laptop',
            'price' => 19000.03, // You may want to update the price as well
            'quantity' => 3,
            'category_id'=> 1,
            'brand_id'=>1,
            'description'=>'The Dell Inspiron laptop offers an excellent combination of performance and portability. Ideal for both work and entertainment, it features a sleek design, long battery life, and powerful hardware. Experience smooth multitasking and stunning visuals with its high-definition display and the latest technology.'
        ]);
    }
}
