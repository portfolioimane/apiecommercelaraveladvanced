<?php
// app/Http/Controllers/Api/Frontend/CartController.php
namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function getCartItemCount()
    {
        Log::info('Getting cart item count');

        if (Auth::check()) {
            $userId = Auth::id();
            Log::info('User is authenticated. User ID: ' . $userId);

            $cart = Cart::where('user_id', $userId)->first();
            if ($cart) {
                Log::info('Cart found. Cart ID: ' . $cart->id);
                $count = CartItem::where('cart_id', $cart->id)->sum('quantity');
                Log::info('Cart item count: ' . $count);
            } else {
                Log::info('No cart found for user.');
                $count = 0;
            }
        } else {
            Log::warning('Unauthorized access attempt to get cart item count.');
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json(['count' => $count]);
    }

    public function addToCart(Request $request)
    {
        Log::info('Request data:', $request->all());

        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        Log::info('Validated data:', $validatedData);

        if (!Auth::check()) {
            Log::warning('Unauthorized access attempt to add to cart.');
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userId = Auth::id();
        $productId = $validatedData['product_id'];
        $quantity = $validatedData['quantity'];

        Log::info("User $userId is adding product $productId with quantity $quantity to the cart.");

        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        $cartItem = CartItem::where('cart_id', $cart->id)
                             ->where('product_id', $productId)
                             ->first();

        if ($cartItem) {
            Log::info("Product $productId already in cart. Updating quantity.");
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            $product = Product::findOrFail($productId);
            Log::info("Creating new cart item for product $productId with price {$product->price}.");

            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);
        }

        Log::info('Item added to cart successfully.');
        return response()->json(['message' => 'Item added to cart successfully'], 200);
    }

    public function showCart()
    {
        $items = collect();

        if (Auth::check()) {
            $userId = Auth::id();
            Log::info('Showing cart for user', ['userId' => $userId]);

            $cart = $this->getCart($userId);
            $items = $cart ? CartItem::where('cart_id', $cart->id)->with('product')->get() : collect();
        } else {
            Log::warning('Unauthorized access attempt to view cart.');
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        Log::info('Cart items retrieved', ['items' => $items]);
        return response()->json(['items' => $items]);
    }

    public function removeFromCart($id)
    {
        Log::info('Removing item from cart', ['cartItemId' => $id]);

        if (Auth::check()) {
            $cartItem = CartItem::findOrFail($id);
            $cartItem->delete();
            Log::info('Cart item removed', ['cartItemId' => $id]);
        } else {
            Log::warning('Unauthorized access attempt to remove item from cart.');
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json(['success' => 'Item removed from cart']);
    }

    private function getCart($userId)
    {
        $cart = Cart::where('user_id', $userId)->first();

        if (!$cart) {
            $cart = Cart::create(['user_id' => $userId]);
            Log::info('Created new cart', ['cartId' => $cart->id, 'userId' => $userId]);
        }

        return $cart;
    }
}
