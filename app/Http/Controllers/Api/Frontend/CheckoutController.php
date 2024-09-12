<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Facades\Cookie;

class CheckoutController extends Controller
{
       private $paypal;

    public function __construct()
    {
        $this->middleware('auth');
        $this->paypal = new PayPalClient;
        $this->paypal->setApiCredentials(config('paypal'));
        $this->paypal->setAccessToken($this->paypal->getAccessToken());
        Log::info('PayPal Client initialized', ['api_credentials' => config('paypal')]);
    }

    public function checkout()
    {
        $userId = Auth::id();
        Log::info('Checkout initiated', ['user_id' => $userId]);

        $cart = Cart::where('user_id', $userId)->first();
        if (!$cart) {
            Log::error('Cart not found', ['user_id' => $userId]);
            return response()->json(['error' => 'Cart not found'], 404);
        }

        $cartItems = CartItem::where('cart_id', $cart->id)->with('product')->get();
        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $shipping = 50; // Example shipping rate

        Log::info('Checkout successful', [
            'cart_items' => $cartItems->toArray(),
            'total' => $total,
            'shipping' => $shipping
        ]);

        return response()->json([
            'cartItems' => $cartItems,
            'total' => $total,
            'shipping' => $shipping
        ]);
    }

    public function processPayment(Request $request)
{
    Log::info('processPayment called', ['user_id' => Auth::id()]);

    Stripe::setApiKey(config('services.stripe.secret'));

    $amountInCents = $this->calculateAmountInCents();
    $totalAmount = $this->calculateAmount();

    Log::info('Calculated amount', ['amount_in_cents' => $amountInCents, 'total_amount' => $totalAmount]);

    if ($amountInCents < 50) {
        Log::error('Amount is below minimum', ['amount' => $amountInCents]);
        return response()->json(['error' => 'The amount is below the minimum charge amount allowed.'], 400);
    }

    try {
        $paymentIntent = PaymentIntent::create([
            'amount' => $amountInCents,
            'currency' => 'mad',
            'payment_method' => $request->payment_method_id,
            'confirmation_method' => 'manual',
            'confirm' => true,
            'return_url' => route('payment.return'),
        ]);

        Log::info('PaymentIntent created', ['paymentIntent' => $paymentIntent]);

        $order = $this->storeOrder($request, $request->payment_method, $totalAmount, 'pending');
        if ($order) {
            Log::info('Order stored', ['order_id' => $order->id]);
        } else {
            Log::error('Order could not be stored');
            return response()->json(['error' => 'Order could not be created.'], 500);
        }

        if ($paymentIntent->status === 'requires_action' || $paymentIntent->status === 'requires_source_action') {
            Log::info('Payment requires additional action', ['next_action_url' => $paymentIntent->next_action->redirect_to_url->url]);
            return response()->json(['client_secret' => $paymentIntent->client_secret, 'order_id' => $order->id]);
        } else {
            $this->clearUserCart();
            Log::info('Immediate success. Cart cleared', ['orderId' => $order->id]);
            return response()->json(['client_secret' => $paymentIntent->client_secret, 'order_id' => $order->id]);
        }
    } catch (\Exception $e) {
        Log::error('Payment processing failed', ['error' => $e->getMessage()]);
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


    public function handlePaymentReturn(Request $request)
    {
        $paymentIntentId = $request->query('payment_intent');
        Log::info('handlePaymentReturn called', ['payment_intent_id' => $paymentIntentId]);

        if (!$paymentIntentId) {
            Log::error('Payment Intent ID missing');
            return response()->json(['error' => 'Payment failed.'], 400);
        }

        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

            Log::info('PaymentIntent retrieved', ['paymentIntent' => $paymentIntent]);

            if ($paymentIntent->status === 'succeeded') {
                $order = $this->storeOrder($request, $request->payment_method, $this->calculateAmount(), 'completed');

                if (!$order) {
                    Log::error('Order could not be created');
                    return response()->json(['error' => 'Order could not be created.'], 500);
                }

                $this->clearUserCart();
                Log::info('Cart cleared after successful payment', ['orderId' => $order->id]);

                return response()->json(['redirect_url' => route('success', ['orderId' => $order->id])]);
            } else {
                Log::error('Payment failed', ['status' => $paymentIntent->status]);
                return response()->json(['error' => 'Payment failed.'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Payment Error', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


public function createPayment(Request $request)
{
    $totalAmount = $this->calculateAmount(); // Amount in MAD
    $totalAmountInDollars = number_format($totalAmount / 10, 2); // Convert MAD to dollars

    Log::info('Creating PayPal Payment', ['amount' => $totalAmountInDollars]);

    $paymentMethod = $request->input('payment_method');
    Log::info('Payment Method', ['payment_method' => $paymentMethod]);

    $orderId = uniqid('order_', true);
    Log::info('OrderID', ['order_id' => $orderId]);

    // Set cookies for order_id and payment_method
    Cookie::queue(Cookie::make('order_id', $orderId, 60)); // 60 minutes
    Cookie::queue(Cookie::make('payment_method', $paymentMethod, 60));

    try {
        $paypalOrder = $this->paypal->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'reference_id' => 'transaction_test_number_' . $request->user()->id,
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => $totalAmountInDollars
                    ]
                ]
            ],
            'application_context' => [
                'cancel_url' => route('cancel'),
                'return_url' => route('paypalsuccess')
            ]
        ]);

        Log::info('PayPal Order Created', ['paypalOrder' => $paypalOrder]);

        if (!isset($paypalOrder['id'])) {
            Log::error('PayPal Order Creation Failed', ['paypalOrder' => $paypalOrder]);
            return response()->json(['error' => 'PayPal Order Creation Failed.'], 500);
        }

        return response()->json(['redirect_url' => $paypalOrder['links'][1]['href']]);
    } catch (\Exception $e) {
        Log::error('Exception in createPayment', ['error' => $e->getMessage()]);

        return response()->json(['error' => $e->getMessage()], 500);
    }
}



public function paypalsuccess(Request $request)
{
    $paymentId = $request->input('token');
    $orderId = $request->cookie('order_id');
    $paymentMethod = $request->cookie('payment_method');
    $totalAmount = $this->calculateAmount();

    Log::info('PayPal Success Handler Called', ['paymentId' => $paymentId, 'orderId' => $orderId, 'paymentMethod' => $paymentMethod]);

    if (!$paymentId) {
        Log::error('Invalid Payment ID', ['paymentId' => $paymentId]);
        return response()->json(['error' => 'Invalid payment ID.'], 400);
    }

    try {
        $payment = $this->paypal->capturePaymentOrder($paymentId);

        Log::info('PayPal Payment Captured', ['payment' => $payment]);

        if ($payment['status'] === 'COMPLETED') {
            $order = $this->storeOrder($request, $paymentMethod, $totalAmount, 'completed');

            if (!$order) {
                Log::error('Order Creation Failed', ['payment' => $payment]);
                return response()->json(['error' => 'Order could not be created.'], 500);
            }

            Log::info('Order Created Successfully', ['order' => $order]);

            $this->clearUserCart();
            Log::info('Cart Cleared');

            // Clear cookies
            Cookie::queue(Cookie::forget('order_id'));
            Cookie::queue(Cookie::forget('payment_method'));

            $successUrl = "/success/{$order->id}";
            return response()->json(['redirect_url' => $successUrl]);
                    } else {
            Log::error('PayPal Payment Not Completed', ['status' => $payment['status']]);
            return response()->json(['error' => 'Payment not completed.'], 400);
        }
    } catch (\Exception $e) {
        Log::error('Exception in PayPal Success Handler', ['error' => $e->getMessage()]);
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


  private function storeOrder(Request $request, $paymentMethod, $amount, $status)
{
    $userId = Auth::id();
    $cart = Cart::where('user_id', $userId)->first();
    if (!$cart) {
        throw new \Exception('Cart not found');
    }

    $cartItems = CartItem::where('cart_id', $cart->id)->get();
    if ($cartItems->isEmpty()) {
        throw new \Exception('No items in cart');
    }

    // Create the order
    $order = new Order();
    $order->user_id = $userId;
    $order->payment_method = $paymentMethod;
    $order->total_amount = $amount;
    $order->status = $status;
    $order->save();

    // Store the cart items in the order_items table
    foreach ($cartItems as $item) {
        $orderItem = new OrderItem();
        $orderItem->order_id = $order->id;
        $orderItem->product_id = $item->product_id;
        $orderItem->quantity = $item->quantity;
        $orderItem->price = $item->price;
        $orderItem->save();
    }

    // Optionally clear the cart
    $cart->delete();

    return $order;
}


    private function clearUserCart()
    {
        $userId = Auth::id();
        $cart = Cart::where('user_id', $userId)->first();
        if ($cart) {
            CartItem::where('cart_id', $cart->id)->delete();
            $cart->delete();
            Log::info('User cart cleared', ['user_id' => $userId]);
        } else {
            Log::info('No cart found for user', ['user_id' => $userId]);
        }
    }

    private function calculateAmountInCents()
    {
        $amount = $this->calculateAmount();
        return $amount * 100; // Convert MAD to cents
    }

    private function calculateAmount()
    {
        $userId = Auth::id();
        $cart = Cart::where('user_id', $userId)->first();
        $cartItems = CartItem::where('cart_id', $cart->id)->get();
        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $shipping = 50; // Example shipping rate
        return $total + $shipping;
    }

public function handleCashOnDelivery(Request $request)
{
    // Log the request for debugging
    Log::info('Cash on Delivery requested', ['user_id' => Auth::id()]);

    // Calculate the total amount
    $totalAmount = $this->calculateAmount();

    // Store the order with 'pending' status
    $order = $this->storeOrder($request, 'cash_on_delivery', $totalAmount, 'pending');

    // Check if the order is stored successfully
    if (!$order) {
        Log::error('Order could not be stored.');
        return response()->json(['message' => 'Order could not be created.'], 500);
    }

    // Clear the user's cart
    $this->clearUserCart();

    Log::info('Order created and cart cleared', ['order_id' => $order->id]);

    // Return a success response with order details
    return response()->json([
        'message' => 'Order created successfully',
        'order_id' => $order->id,
    ], 200);
}




}
