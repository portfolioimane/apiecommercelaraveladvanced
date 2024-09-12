<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{


    // Fetch all orders with user and order item details, ordered by creation date
    public function index()
    {
        // Get all orders with user and order items
        $orders = Order::with(['orderItems.product', 'user']) // eager loading related models (user and product in orderItems)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }

    // Show details of a specific order with all related items and user
    public function show($id)
    {
        // Get specific order by ID with order items and product details
        $order = Order::with(['orderItems.product', 'user'])
            ->findOrFail($id); // returns 404 if order is not found

        return response()->json($order);
    }

    // Update order details (status change)
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Validate the input status (ensure it matches one of the allowed values)
        $validatedData = $request->validate([
            'status' => 'required|in:pending,processing,completed,canceled',
        ]);

        // Update the order status
        $order->update($validatedData);

        return response()->json($order);
    }

    // Delete an order and its associated items
    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        // Optionally delete associated items or use cascading delete in the model
        $order->orderItems()->delete();

        // Delete the order itself
        $order->delete();

        return response()->json(null, 204);
    }

    // Fetch all order items (not usually needed but included for reference)
    public function indexItem()
    {
        // Fetch order items with product details
        $orderItems = OrderItem::with('product')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orderItems);
    }

    // Delete a specific order item (if needed for individual item removal)
    public function destroyItem($id)
    {
        $orderItem = OrderItem::findOrFail($id);
        $orderItem->delete();

        return response()->json(null, 204);
    }
}
