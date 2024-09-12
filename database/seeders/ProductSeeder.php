<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Fetch existing categories
        $categories = Category::all();

        // Define product data
        $products = [
            [
                'name' => 'Smartphone',
                'description' => 'Latest model smartphone with all the features.',
                'price' => 699.99,
                'stock' => 50,
                'image' => 'smartphone.jpg',
                'category_name' => 'Electronics'
            ],
            [
                'name' => 'Sofa',
                'description' => 'Comfortable leather sofa.',
                'price' => 899.99,
                'stock' => 20,
                'image' => 'sofa.jpg',
                'category_name' => 'Furniture'
            ],
            [
                'name' => 'Jacket',
                'description' => 'Warm winter jacket.',
                'price' => 129.99,
                'stock' => 30,
                'image' => 'jacket.jpg',
                'category_name' => 'Clothing'
            ],
            [
                'name' => 'Laptop',
                'description' => 'High-performance laptop with 16GB RAM.',
                'price' => 1199.99,
                'stock' => 15,
                'image' => 'laptop.jpg',
                'category_name' => 'Electronics'
            ],
            [
                'name' => 'Dining Table',
                'description' => 'Elegant wooden dining table.',
                'price' => 499.99,
                'stock' => 10,
                'image' => 'dining_table.jpg',
                'category_name' => 'Furniture'
            ],
            [
                'name' => 'Sneakers',
                'description' => 'Comfortable and stylish sneakers.',
                'price' => 79.99,
                'stock' => 40,
                'image' => 'sneakers.jpg',
                'category_name' => 'Clothing'
            ],
            [
                'name' => 'Washing Machine',
                'description' => 'Energy-efficient washing machine.',
                'price' => 499.99,
                'stock' => 25,
                'image' => 'washing_machine.jpg',
                'category_name' => 'Electronics'
            ],
            [
                'name' => 'Bed Frame',
                'description' => 'Queen-sized bed frame with storage.',
                'price' => 299.99,
                'stock' => 12,
                'image' => 'bed_frame.jpg',
                'category_name' => 'Furniture'
            ],
            [
                'name' => 'Winter Coat',
                'description' => 'Heavy-duty winter coat for extreme weather.',
                'price' => 199.99,
                'stock' => 22,
                'image' => 'winter_coat.jpg',
                'category_name' => 'Clothing'
            ],
            [
                'name' => 'Smart TV',
                'description' => '4K Ultra HD Smart TV with built-in apps.',
                'price' => 799.99,
                'stock' => 18,
                'image' => 'smart_tv.jpg',
                'category_name' => 'Electronics'
            ],
            [
                'name' => 'Bookshelf',
                'description' => 'Wooden bookshelf with adjustable shelves.',
                'price' => 149.99,
                'stock' => 30,
                'image' => 'bookshelf.jpg',
                'category_name' => 'Furniture'
            ],
            [
                'name' => 'Yoga Pants',
                'description' => 'Comfortable yoga pants for daily workouts.',
                'price' => 49.99,
                'stock' => 35,
                'image' => 'yoga_pants.jpg',
                'category_name' => 'Clothing'
            ],
            [
                'name' => 'Microwave Oven',
                'description' => 'Compact microwave oven with multiple settings.',
                'price' => 89.99,
                'stock' => 45,
                'image' => 'microwave.jpg',
                'category_name' => 'Electronics'
            ],
            [
                'name' => 'Armchair',
                'description' => 'Modern armchair with plush cushions.',
                'price' => 359.99,
                'stock' => 14,
                'image' => 'armchair.jpg',
                'category_name' => 'Furniture'
            ],
            [
                'name' => 'Scarf',
                'description' => 'Warm and stylish winter scarf.',
                'price' => 29.99,
                'stock' => 60,
                'image' => 'scarf.jpg',
                'category_name' => 'Clothing'
            ],
            [
                'name' => 'Bluetooth Speaker',
                'description' => 'Portable Bluetooth speaker with great sound quality.',
                'price' => 59.99,
                'stock' => 40,
                'image' => 'bluetooth_speaker.jpg',
                'category_name' => 'Electronics'
            ],
            [
                'name' => 'Coffee Table',
                'description' => 'Elegant coffee table with glass top.',
                'price' => 199.99,
                'stock' => 25,
                'image' => 'coffee_table.jpg',
                'category_name' => 'Furniture'
            ],
            [
                'name' => 'Leather Jacket',
                'description' => 'Classic leather jacket with a stylish cut.',
                'price' => 159.99,
                'stock' => 20,
                'image' => 'leather_jacket.jpg',
                'category_name' => 'Clothing'
            ],
            [
                'name' => 'Air Conditioner',
                'description' => 'Energy-efficient air conditioner for home cooling.',
                'price' => 399.99,
                'stock' => 8,
                'image' => 'air_conditioner.jpg',
                'category_name' => 'Electronics'
            ],
            [
                'name' => 'Wardrobe',
                'description' => 'Spacious wardrobe with multiple compartments.',
                'price' => 699.99,
                'stock' => 5,
                'image' => 'wardrobe.jpg',
                'category_name' => 'Furniture'
            ],
            [
                'name' => 'Gloves',
                'description' => 'Warm winter gloves made of soft material.',
                'price' => 19.99,
                'stock' => 50,
                'image' => 'gloves.jpg',
                'category_name' => 'Clothing'
            ],
            [
                'name' => 'Electric Kettle',
                'description' => 'Quick boiling electric kettle with auto shut-off.',
                'price' => 34.99,
                'stock' => 55,
                'image' => 'electric_kettle.jpg',
                'category_name' => 'Electronics'
            ],
            [
                'name' => 'Dining Chair',
                'description' => 'Comfortable dining chair with padded seat.',
                'price' => 89.99,
                'stock' => 40,
                'image' => 'dining_chair.jpg',
                'category_name' => 'Furniture'
            ],
            [
                'name' => 'Sweater',
                'description' => 'Cozy sweater made of warm wool.',
                'price' => 69.99,
                'stock' => 45,
                'image' => 'sweater.jpg',
                'category_name' => 'Clothing'
            ],
        ];

        // Insert products into database
        foreach ($products as $product) {
            Product::create([
                'name' => $product['name'],
                'description' => $product['description'],
                'price' => $product['price'],
                'stock' => $product['stock'],
                'image' => $product['image'],
                'category_id' => $categories->where('name', $product['category_name'])->first()->id
            ]);
        }
    }
}
