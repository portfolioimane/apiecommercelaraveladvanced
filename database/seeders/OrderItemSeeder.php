<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;

class OrderItemSeeder extends Seeder
{
    public function run()
    {
        // Add items to the customer's order (order ID 1)
        OrderItem::create([
            'order_id' => 1,
            'product_id' => 1, // Product ID 1
            'quantity' => 2, // 2 units of Product 1
            'price' => 100, // Price per unit
        ]);

        OrderItem::create([
            'order_id' => 1,
            'product_id' => 3, // Product ID 3
            'quantity' => 1, // 1 unit of Product 3
            'price' => 200, // Price per unit
        ]);
    }
}
