<section class="py-5">
    <div class="container">
        <h1 class="display-4 mb-4">Your Cart</h1>

        @if($items->isEmpty())
            <p class="lead">Your cart is currently empty.</p>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Product</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Total</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>
                                <img src="{{ asset('storage/' . $item->product->image) }}" class="img-thumbnail" alt="{{ $item->product->name }}" style="width: 100px;">
                                {{ $item->product->name }}
                            </td>
                            <td>{{ number_format($item->price, 2) }} MAD</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price * $item->quantity, 2) }} MAD</td>
                            <td>
                                <button wire:click="removeFromCart({{ $item->id }})" class="btn btn-danger btn-sm">Remove</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Total and Checkout Button -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <h3 class="mb-0">Total: {{ number_format($items->sum(fn($item) => $item->price * $item->quantity), 2) }} MAD</h3>
                <a href="{{ route('checkout') }}" class="btn btn-success btn-lg">Proceed to Checkout</a>
            </div>
        @endif
    </div>
</section>
