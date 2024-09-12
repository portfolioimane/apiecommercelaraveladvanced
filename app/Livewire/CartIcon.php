<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartIcon extends Component
{
    public $itemCount = 0;

    protected $listeners = ['cartUpdated' => 'updateItemCount'];

    public function mount()
    {
        $this->updateItemCount();
    }

    public function updateItemCount()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $cart = Cart::where('user_id', $userId)->first();
            $this->itemCount = $cart ? CartItem::where('cart_id', $cart->id)->sum('quantity') : 0;
        } else {
            $this->itemCount = session('guest_cart', []);
            $this->itemCount = array_sum($this->itemCount);
        }
    }

    public function render()
    {
        return view('livewire.cart-icon');
    }
}
