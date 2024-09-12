@extends('layouts.main')

@section('title', 'Checkout')

@section('content')
<section class="py-5 bg-light">
    <div class="container">
        <h1 class="text-primary mb-4">Checkout</h1>

        <!-- Cart Summary -->
        <div class="card mb-4">
            <div class="card-header">
                <h2 class="mb-0">Cart Summary</h2>
            </div>
            <div class="card-body">
                @if(count($cartItems) > 0)
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">Image</th>
                                <th scope="col">Name</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                                <tr>
                                    <td><img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="img-fluid" style="max-width: 100px; height: auto;"></td>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ number_format($item->price, 2) }} MAD</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format(($item->price * $item->quantity), 2) }} MAD</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Total:</td>
                                <td class="fw-bold">{{ number_format($total, 2) }} MAD</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Shipping:</td>
                                <td class="fw-bold">{{ number_format($shipping, 2) }} MAD</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Grand Total:</td>
                                <td class="fw-bold">{{ number_format(($total + $shipping), 2) }} MAD</td>
                            </tr>
                        </tfoot>
                    </table>
                @else
                    <p class="text-center">Your cart is empty.</p>
                @endif
            </div>
        </div>

        <!-- Payment Method Selection -->
        <div class="card mb-4">
            <div class="card-header">
                <h2 class="mb-0">Select Payment Method</h2>
            </div>
            <div class="card-body">
                <div class="form-check">
                    <input class="form-check-input" type="radio" id="stripe" name="payment_method" value="stripe">
                    <label class="form-check-label" for="stripe">
                        Pay with Stripe
                    </label>
                </div>
                <div class="form-check mt-2">
                    <input class="form-check-input" type="radio" id="paypal" name="payment_method" value="paypal">
                    <label class="form-check-label" for="paypal">
                        Pay with PayPal
                    </label>
                </div>
                <div class="form-check mt-2">
                    <input class="form-check-input" type="radio" id="cod" name="payment_method" value="cod">
                    <label class="form-check-label" for="cod">
                        Cash on Delivery
                    </label>
                </div>
            </div>
        </div>

        <!-- Stripe Payment Form -->
        <form id="stripe-payment-form" action="/process-payment" method="POST" style="display: none;">
            @csrf
            <div id="card-element" class="mb-4"><!-- A Stripe Element will be inserted here. --></div>
            <div id="card-errors" role="alert" class="text-danger mb-4"></div>
        </form>

        <!-- Place Order Button -->
        <button id="place-order-button" class="btn btn-primary btn-lg">Place Order</button>

        <!-- PayPal Form -->
        <form id="paypal-form" action="{{ route('paypal.create') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="payment_method" value="paypal">
        </form>

        <!-- Cash on Delivery Form -->
        <form id="cod-form" action="{{ route('checkout.cashOnDelivery') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="payment_method" value="cod">
        </form>

        <!-- Stripe JS -->
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var stripe = Stripe('{{ config('services.stripe.key') }}');
                var elements = stripe.elements();
                var cardElement = elements.create('card');
                cardElement.mount('#card-element');

                // Show Stripe form when "Pay with Stripe" is selected
                document.getElementById('stripe').addEventListener('change', function() {
                    document.getElementById('stripe-payment-form').style.display = 'block';
                    document.getElementById('paypal-form').style.display = 'none';
                    document.getElementById('cod-form').style.display = 'none';
                });

                // Show PayPal form when "Pay with PayPal" is selected
                document.getElementById('paypal').addEventListener('change', function() {
                    document.getElementById('stripe-payment-form').style.display = 'none';
                    document.getElementById('paypal-form').style.display = 'block';
                    document.getElementById('cod-form').style.display = 'none';
                });

                // Show COD form when "Cash on Delivery" is selected
                document.getElementById('cod').addEventListener('change', function() {
                    document.getElementById('stripe-payment-form').style.display = 'none';
                    document.getElementById('paypal-form').style.display = 'none';
                    document.getElementById('cod-form').style.display = 'block';
                });

                document.getElementById('place-order-button').addEventListener('click', function(event) {
                    event.preventDefault();

                    if (document.getElementById('stripe').checked) {
                        // Stripe payment process
                        stripe.createPaymentMethod({
                            type: 'card',
                            card: cardElement,
                        }).then(function(result) {
                            if (result.error) {
                                // Display error.message in #card-errors
                                document.getElementById('card-errors').textContent = result.error.message;
                            } else {
                                // Send the PaymentMethod ID to your server
                                fetch('/process-payment', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-Token': '{{ csrf_token() }}',
                                    },
                                    body: JSON.stringify({
                                        payment_method: 'stripe',
                                        payment_method_id: result.paymentMethod.id
                                    })
                                }).then(function(response) {
                                    return response.json();
                                }).then(function(data) {
                                    if (data.redirect_url) {
                                        window.location.href = data.redirect_url;
                                    } else {
                                        // Handle any other response or error
                                        console.error('Unexpected response:', data);
                                    }
                                });
                            }
                        });
                    } else if (document.getElementById('paypal').checked) {
                        // Submit the PayPal form
                        document.getElementById('paypal-form').submit();
                    } else if (document.getElementById('cod').checked) {
                        // Submit the COD form
                        document.getElementById('cod-form').submit();
                    }
                });
            });
        </script>
    </div>
</section>
@endsection
