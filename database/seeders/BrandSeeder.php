<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::create([
            'name' => 'Dell',
        ]);
        Brand::create([
            'name' => 'Samsung',
        ]);
        Brand::create([
            'name' => 'Apple',
        ]);
        Brand::create([
            'name' => 'HP',
        ]);
        Brand::create([
            'name' => 'Lenovo',
        ]);
    }
}
