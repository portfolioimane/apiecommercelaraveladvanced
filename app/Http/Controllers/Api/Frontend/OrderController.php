<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Get order details by order ID.
     */
    public function getOrderDetails($orderId)
    {
        // Fetch order with related orderItems and their products
        $order = Order::with('orderItems.product')->find($orderId);
        $shipping = 50; // Example shipping rate

        if (!$order) {
            Log::error('Order not found', ['order_id' => $orderId]);
            return response()->json(['message' => 'Order not found.'], 404);
        }

        Log::info('Order details retrieved', ['order' => $order->toArray()]);

        return response()->json([
            'order' => $order,
            'shipping' => $shipping
        ], 200);
    }
}
