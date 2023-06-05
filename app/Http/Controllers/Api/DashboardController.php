<?php

namespace App\Http\Controllers\Api;

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
    return Product::count();
   }

   public function paidOrders()
   {
    return Order::where('status', OrderStatus::Paid->value)->count();
   }

   public function totalIncome()
   {
    return Order::where('status', OrderStatus::Paid->value)->sum('total_price');
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
}
