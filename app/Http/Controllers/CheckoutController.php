<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Helpers\Cart;
use Exception;
use Illuminate\Http\Request;
use Stripe\Customer;
use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Enums\PaymentStatus;
use App\Models\CartItem;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        /** @var \App\Models\User $user */
		$user = $request->user();

        $stripe = new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));

        list($products, $cartItems) = Cart::getProductsAndCartItems();
        
        
        $lineItems = [];
        $totalPrice = 0;
        foreach($products as $product){
            $quantity = $cartItems[$product->id]['quantity'];
            $totalPrice += $product->price * $quantity;         
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                      'name' => $product->title,
                      'images' => [$product->image]
                    ],
                    'unit_amount' => $product->price * 100,
                  ],
                  'quantity' => $quantity,
            ];
        }


        $session = $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true) . "?session_id={CHECKOUT_SESSION_ID}",
            'cancel_url' => route('checkout.failure', [], true),
            'customer_creation'=>'always',
          ]);
          
            

          $orderData =[
              'total_price' => $totalPrice,
              'total' => OrderStatus::Unpaid,
              'created_by' => $user->id,
              'updated_by' => $user->id,
          ];
           $order = Order::create($orderData);
          

           $paymentData = [
             'order_id' => $order->id,
             'amount' => $totalPrice,
             'status' => PaymentStatus::Pending,
             'type' => $user->id,
             'created_by' => $user->id,
             'updated_by' => $user->id,
              'session_id' => $session->id
           ];

          Payment::create($paymentData);

        

          return redirect($session->url);
    }
    public function success(Request $request)
    {   
        $stripe = new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));
        try {

             /** @var \App\Models\User $user */
		     $user = $request->user();

           
            $session = $stripe->checkout->sessions->retrieve($_GET['session_id']);
            
            
            if(!$session){
                return view('checkout.failure', ['message' => 'Invalid Session ID']);
            }
           $payment = Payment::query()->where(['session_id' => $session->id, 'status' => PaymentStatus::Pending])->first();
          
           if(!$payment){
            return view('checkout.failure', ['message' => 'Payment Does not exist']);
        }
            $payment->status = PaymentStatus::Paid;
            $payment->update();

            $order = $payment->order;
            


            $order->status = OrderStatus::Paid;
            $order->update();

            CartItem::where(['user_id' => $user->id])->delete();

           $customer = $stripe->customers->retrieve($session->customer);

            return view('checkout.success', compact('customer'));
        } catch(Exception $e){
            return view('checkout.failure', ['message' => $e->getMessage()]);
        }
    }

    public function failure(Request $request)
    {
        
        dd($request->all());
    }
}
