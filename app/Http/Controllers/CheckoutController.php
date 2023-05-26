<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        /** @var \App\Models\User $user */
		$user = $request->user();

        $stripe = new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));

        list($products, $cartItems) = Cart::getProductsAndCartItems();
        
        $orderItems = [];
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
                     // 'images' => [$product->image]
                    ],
                    'unit_amount' => $product->price * 100,
                  ],
                  'quantity' => $quantity,
            ];
            $orderItems[] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price'=> $product->price
            ];
        }


        $session = $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true) . "?session_id={CHECKOUT_SESSION_ID}",
            'cancel_url' => route('checkout.failure', [], true),
            'customer_creation'=>'always',
          ]);
          
            // Create Order

          $orderData =[
              'total_price' => $totalPrice,
              'total' => OrderStatus::Unpaid,
              'created_by' => $user->id,
              'updated_by' => $user->id,
          ];

           $order = Order::create($orderData);

            // Create Order Items

            foreach ($orderItems as $orderItem){
                $orderItem['order_id'] = $order->id;
                OrderItem::create($orderItem);

            }

            //Create Payment

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

          CartItem::where(['user_id' => $user->id])->delete();

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
           
           $payment = Payment::query()
           ->where(['session_id' => $session_id])
           whereIn( 'status', [PaymentStatus::Pending, PaymentStatus::Paid])
           ->first();
           if (!$payment) {
            throw new NotFoundedHttpException();
           }

           if($payment->status === PaymentStatus::Pending) {
            $this->updateOrderAndSession($payment);
            
           }

           
           $customer = $stripe->customers->retrieve($session->customer);

            return view('checkout.success', compact('customer'));
        } catch(Exception $e){
            throw $e;
            return view('checkout.failure', ['message' => $e->getMessage()]);
        }
    }

    public function failure(Request $request)
    {
        
        return view('checkout.failure', ['message' => ""]);
    }

    public function checkoutOrder(Order $order, Request $request)
    {
            
             $stripe = new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));

             $lineItems = [];
             foreach ($order->items as $item){
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                          'name' =>$item->product->title,
                         // 'images' => [$product->image]
                        ],
                        'unit_amount' => $item->unit_price * 100,
                      ],
                      'quantity' => $item->quantity,

                    ];

           }

             $session = $stripe->checkout->sessions->create([
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('checkout.success', [], true) . "?session_id={CHECKOUT_SESSION_ID}",
                'cancel_url' => route('checkout.failure', [], true),
                'customer_creation'=>'always',
              ]);

        $order->payment->session_id = $session->id;
        $order->payment->save();

        return redirect($session->url);
    }

    public function webhook()
    {
        
        $stripe = new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));

        $endpoint_secret = 'whsec_6de6916c6587d69e8e84fd6bfe9ca64e25d12bf5c750b149f090228b262c33c6'; 

       $payload = @file_get_contents('php://input');
       $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
       $event = null;

  try {
       $event = \Stripe\Webhook::constructEvent(
          $payload, $sig_header, $endpoint_secret
  );
} catch(\UnexpectedValueException $e) {
  // Invalid payload
  return response('', 401);
  exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
  // Invalid signature
  return response('', 402);
  exit();
}

// Handle the event
switch ($event->type) {
  case 'checkout.session.completed':
    $paymentIntent = $event->data->object;
    $sessionId = $paymentIntent['id'];

    $payment = Payment::query()
    ->where(['session_id' => $sessionId, 'status' => PaymentStatus::Pending])
    ->first();
           if ($payment) {
            $this->updateOrderAndSession($payment);
           }

   

  // ... handle other event types
  default:
    echo 'Received unknown event type ' . $event->type;
}

     return response('', 200);

  }

  private function updateOrderAndSession(Payment $payment)
  {
    
            $payment->status = PaymentStatus::Paid;
            $payment->update();

            $order = $payment->order;
            


            $order->status = OrderStatus::Paid;
            $order->update();
  }


}
