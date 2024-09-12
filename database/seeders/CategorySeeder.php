<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::create(['name' => 'Electronics', 'description' => 'Electronic devices and gadgets']);
        Category::create(['name' => 'Furniture', 'description' => 'Home and office furniture']);
        Category::create(['name' => 'Clothing', 'description' => 'Apparel and accessories']);
        // Add more categories as needed
    }
}
