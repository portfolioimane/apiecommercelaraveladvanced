<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;

class OrderSeeder extends Seeder
{
    public function run()
    {
        // Create an order for the customer (user ID 2)
        Order::create([
            'user_id' => 2, // Customer user
            'total_amount' => 450, // Sum of products
            'payment_method' => 'stripe', // Payment method: stripe, paypal, cash_on_delivery
            'status' => 'completed', // Order status: pending, completed, canceled
        ]);
    }
}
