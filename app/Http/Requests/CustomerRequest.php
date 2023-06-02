<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * 
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'phone' => ['required', 'min:7'],
            'email' => ['required', 'email'],
            'status' => ['required', new Enum(CustomerStatus::class)],


            'shipping.address1' => ['required'],
            'shipping.address2' => ['required'],
            'shipping.city' => ['required'],
            'shipping.state' => ['required'],
            'shipping.zipcode' => ['required'],
            'shipping.country_code' => ['required', 'exists:countries,code'],

            'billing.address1' => ['required'],
            'billing.address2' => ['required'],
            'billing.city' => ['required'],
            'billing.state' => ['required'],
            'billing.zipcode' => ['required'],
            'billing.country_code' => ['required', 'exists:countries,code'],

        ];
    }
    public function attributes()
    {
       return[
          'billing.address1' => 'address 1',
          'billing.address2' => 'address 2',
          'billing.city' => 'city',
          'billing.state' => 'state',
          'billing.zipcode' => 'zip code',
          'billing.country_code' => 'country',
          'shipping.address1' => 'address 1',
          'shipping.address2' => 'address 2',
          'shipping.city' => 'city',
          'shipping.state' => 'state',
          'shipping.zipcode' => 'zip code',
          'shipping.country_code' => 'country',
       ];
    }
}
