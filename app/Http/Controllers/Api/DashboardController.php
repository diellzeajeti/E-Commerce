<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Enums\AddresType;
use App\Enums\CustomerStatus;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\OrderResource;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
   public function activeCustomers()
   {
      return Customer::where('status', CustomerStatus::Active->value)->count();
    
   }

   public function activeProducts()
   {
    // TODO Implement where for active products
    return Product::where('published', '=', 1)->count();
   }

   public function paidOrders()
   {
      $fromDate = $this->getFromDate();
      $query = Order::query()->where('status', OrderStatus::Paid->value);

      if($fromDate) {
         $query->where('created_at', '>', $fromDate);
      }

      return $query->count();
   }

   public function totalIncome()
   {
      $fromDate = $this->getFromDate();  
      $query = Order::query()->where('status', OrderStatus::Paid->value);

    if ($fromDate) {
      $query->where('created_at', '>', $fromDate);
   }
    return round($query->sum('total_price'));
   }

   public function latestCustomers()
   {
   return Customer::query()
      ->select(['id','first_name', 'last_name', 'u.email', 'phone', 'u.created_at'])
      ->join('users AS u', 'u.id', '=', 'customers.user_id')
      ->where('status', CustomerStatus::Active->value)
      ->orderBy('created_at', 'desc')
      ->limit(5)
      ->get();
   }

   public function latestOrders()
   {
      return  OrderResource::collection(
         Order::query()

         ->select(['o.id','o.total_price', 'o.created_at', DB::raw('COUNT(oi.id) AS items'),
         'c.user_id','c.first_name', 'c.last_name' ])
         ->from('orders AS o')
         ->join('order_items AS oi', 'oi.order_id', '=' , 'o.id')
         ->join('customers AS c', 'c.user_id', '=', 'o.created_by')
         ->where('o.status', OrderStatus::Paid->value)
         ->limit(10)
         ->orderBy('o.created_at', 'desc')
         ->groupBy('o.id', 'o.total_price', 'o.created_at','c.user_id','c.first_name', 'c.last_name')
         ->get()
      );
   }
    
   private function getFromDate()
   {
       $request = \request();
       $paramDate = $request->get('d');
       $array = [
           '1d' => Carbon::now()->subDays(1),
           '1k' => Carbon::now()->subDays(7),
           '2k' => Carbon::now()->subDays(14),
           '1m' => Carbon::now()->subDays(30),
           '3m' => Carbon::now()->subDays(60),
           '6m' => Carbon::now()->subDays(180),
       ];

       return $array[$paramDate] ?? null;
   }
}