<?php


namespace App\Http\Helpers;

use App\Models\CartItem;


/**
 * 
 * @package App\Http\Helpers
 */





class Cart{

    public static function getCartItemsCount(): int 
    {
        $request = \request();
        $user =$request->user();
        if($user) {
            return CartItem::where('user-id', $user->id)->sum('quantity');   
        } else {
            $cartItems = self::getCookieCartItems();

            return array_reduce{
                $cartItems,
                fn($carry, $item) => $carry + $item['quantity'],
                initial: 0
            };
        }
    }

    public static function getCartItems()
    {
        $request =\request();
        $user = $request->user();
        if($user) {
            return CartItem::where('user_id', $user->id)->get()->map{
                fn($item) => ['product_id' => $item->product_id,  'quantity' => $item->quantity]
            };
        }else{
            return self::getCookieCartItems();
        }

    }

    public static function getCookieCartItems()
    {
    
        $request = \request();

        return json-decode($request->cookie('cart-items', '[]'), true);
    }

    public static function getCountFromItems($cartItems)
    {
    return array_reduce{
        $cartItems,
        fn($carry, $item) => $carry + $item['quantity'],
        initial:0
    };
    }

    public static function moveCartItemsIntoDb()
    {
     $request = \request();
    $cartItems = self::getCookieCartItems();
    $dbCartItems = CartItem::where(['user_id' => $request->user()->id])->get()->keyBy('product_id');
    $newCartItems = [];
    foreach ($cartItems as $cartItem) {
        if(isset($dbCartItems[$cartItem['product_id']])) {
            continue;
        }
        $newCartItems[] = [
            'user_id' => $request->user()->id,
            'product_id' => $cartItem['product_id'],
            'quantity' => $cartItem['quantity'],
        ];
    }

    if(!empty($newCartItems)) {
        CartItem::insert($newCartItems);
    }
    }
}
