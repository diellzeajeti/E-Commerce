<?php

namespace App\Http\Controllers;

use App\Enums\AddressType;
use App\Http\Requests\PasswordUpdateRequest;
use App\Http\Requests\ProfileRequest;
use App\Models\Country;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ProfileController extends Controller
{
   public function view(Request $request){

      /** @var \App\Models\User $user */
      $user = $request->user();
      /** @var \App\Models\Customer $customer */
       $customer = $user->customer;
       $shippingAddress = $customer->shippingAddress ?: new CustomerAddress(['type' => AddressType::Shipping]);
       $billingAddress = $customer->billingAddress ?: new CustomerAddress(['type' => AddressType::Billing]);
     //  dd($customer, $shippingAddress->attributesToArray(), $billingAddress, $billingAddress->customer);
       $countries = Country::query()->orderBy('name')->get();

            
       return view('profile.view', compact('customer','user','shippingAddress','billingAddress','countries'));
   }
}
