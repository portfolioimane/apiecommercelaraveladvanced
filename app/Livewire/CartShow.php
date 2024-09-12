<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartShow extends Component
{
    public $items = [];

    public function mount()
    {
        $this->loadCartItems();
    }

    public function loadCartItems()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $cart = $this->getCart($userId);
            $this->items = $cart ? CartItem::where('cart_id', $cart->id)->with('product')->get() : collect();
        } else {
            $this->items = $this->getGuestCartItems();
        }
    }

    public function removeFromCart($id)
    {
        if (Auth::check()) {
            CartItem::findOrFail($id)->delete();
        } else {
            $this->removeGuestCartItem($id);
        }

        $this->loadCartItems();
        
        // Emit an event to notify other components
        $this->dispatch('cartUpdated');
    }

    private function getCart($userId)
    {
        return Cart::where('user_id', $userId)->first() ?? Cart::create(['user_id' => $userId]);
    }

    private function getGuestCartItems()
    {
        $itemsArray = session('guest_cart', []);
        return collect($itemsArray)->map(function ($quantity, $productId) {
            $product = Product::find($productId);
            return (object) [
                'id' => $product->id,
                'product' => $product,
                'price' => $product->price,
                'quantity' => $quantity
            ];
        });
    }

    private function removeGuestCartItem($productId)
    {
        $cart = session('guest_cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session(['guest_cart' => $cart]);
        }
    }

    public function render()
    {
        return view('livewire.cart-show');
    }
}
