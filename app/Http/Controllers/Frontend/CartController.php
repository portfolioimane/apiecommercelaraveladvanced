<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    // Get the total count of items in the cart
    public function getCartItemCount()
    {
        $count = 0;
        if (Auth::check()) {
            $userId = Auth::id();
            $cart = $this->getCart($userId);
            $count = $cart ? CartItem::where('cart_id', $cart->id)->sum('quantity') : 0;
        } else {
            $count = $this->getGuestCartCount();
        }

        Log::info('Cart item count', ['count' => $count]);

        return $count;
    }

    // Add item to the cart
    public function addToCart(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $quantity = (int) $request->input('quantity', 1);

        if ($quantity <= 0) {
            return redirect()->back()->withErrors('Quantity must be greater than zero.');
        }

        if (Auth::check()) {
            $userId = Auth::id();
            Log::info('Adding item to cart', [
                'productId' => $id,
                'quantity' => $quantity,
                'userId' => $userId
            ]);

            $cart = $this->getCart($userId);

            if (!$cart) {
                Log::error('Failed to create or retrieve cart', ['userId' => $userId]);
                return redirect()->back()->withErrors('Unable to create or retrieve the cart.');
            }

            $cartItem = CartItem::where('cart_id', $cart->id)
                                 ->where('product_id', $id)
                                 ->first();

            if ($cartItem) {
                $cartItem->quantity += $quantity;
                $cartItem->save();
                Log::info('Updated cart item quantity', [
                    'cartItemId' => $cartItem->id,
                    'newQuantity' => $cartItem->quantity
                ]);
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ]);

                Log::info('Created new cart item', [
                    'cartId' => $cart->id,
                    'productId' => $id,
                    'quantity' => $quantity
                ]);
            }
        } else {
            $this->addGuestCartItem($id, $quantity);
        }

        return redirect()->route('cart.show');
    }

    // Show the cart
    public function showCart()
    {
        $items = collect(); // Initialize as an empty collection

        if (Auth::check()) {
            $userId = Auth::id();
            Log::info('Showing cart for user', ['userId' => $userId]);

            $cart = $this->getCart($userId);
            $items = $cart ? CartItem::where('cart_id', $cart->id)->with('product')->get() : collect();
        } else {
            $itemsArray = $this->getGuestCartItems();
            $items = collect($itemsArray)->map(function ($quantity, $productId) {
                $product = Product::find($productId);
                return (object) [
                    'id' => $product->id,
                    'product' => $product,
                    'price' => $product->price,
                    'quantity' => $quantity
                ];
            });
        }

        Log::info('Cart items retrieved', ['items' => $items]);

        return view('cart', compact('items'));
    }

    // Remove item from the cart
    public function removeFromCart($id)
    {
        Log::info('Removing item from cart', ['cartItemId' => $id]);

        if (Auth::check()) {
            $cartItem = CartItem::findOrFail($id);
            $cartItem->delete();
            Log::info('Cart item removed', ['cartItemId' => $id]);
        } else {
            $this->removeGuestCartItem($id);
        }

        return redirect()->route('cart.show');
    }

    // Helper function to get the appropriate cart for the user
    private function getCart($userId)
    {
        $cart = Cart::where('user_id', $userId)->first();

        if (!$cart) {
            $cart = Cart::create(['user_id' => $userId]);
            Log::info('Created new cart', ['cartId' => $cart->id, 'userId' => $userId]);
        }

        return $cart;
    }

    // Handle guest cart using session
    private function getGuestCartItems()
    {
        return session('guest_cart', []);
    }

    private function addGuestCartItem($productId, $quantity)
    {
        $cart = session('guest_cart', []);
        $cart[$productId] = ($cart[$productId] ?? 0) + $quantity;
        session(['guest_cart' => $cart]);
    }

    private function removeGuestCartItem($productId)
    {
        $cart = session('guest_cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session(['guest_cart' => $cart]);
        }
    }

    private function getGuestCartCount()
    {
        $cart = session('guest_cart', []);
        return array_sum($cart);
    }
}




