@extends('layouts.main')

@section('title', 'Checkout')

@section('content')
    <div class="container">
        <div class="success-message">
            <h1>Payment Successful!</h1>
            <p>Thank you for your purchase. Your order has been placed successfully.</p>
        </div>

        <div class="order-details">
            <h2>Order Details</h2>
            <table>
                <tr>
                    <th>Order ID</th>
                    <td>{{ $order->id }}</td>
                </tr>
                <tr>
                    <th>Amount</th>
                    <td>${{ number_format($order->total_amount, 2) }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ $order->status }}</td>
                </tr>
                <tr>
                    <th>Transaction ID</th>
                    <td>{{ $order->paypal_transaction_id ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
        
        <a href="{{ route('home') }}" class="btn btn-primary">Return to Home</a>
    </div>
@endsection
