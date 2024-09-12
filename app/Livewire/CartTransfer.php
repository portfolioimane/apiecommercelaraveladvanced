<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartTransfer extends Component
{
    public function mount()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $this->transferGuestCartToUser($userId);
        }
    }

    private function transferGuestCartToUser($userId)
    {
        $guestCartItems = session('guest_cart', []);

        if (!empty($guestCartItems)) {
            $cart = $this->getCart($userId);

            foreach ($guestCartItems as $productId => $quantity) {
                $product = Product::find($productId);
                if ($product) {
                    $cartItem = CartItem::where('cart_id', $cart->id)
                                         ->where('product_id', $productId)
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
                            'product_id' => $productId,
                            'quantity' => $quantity,
                            'price' => $product->price,
                        ]);
                        Log::info('Created new cart item', [
                            'cartId' => $cart->id,
                            'productId' => $productId,
                            'quantity' => $quantity
                        ]);
                    }
                }
            }

            // Clear the guest cart session after transfer
            session()->forget('guest_cart');
        }
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

    public function render()
    {
        return view('livewire.cart-transfer');
    }
}
