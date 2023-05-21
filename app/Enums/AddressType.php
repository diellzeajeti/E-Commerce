<?php

namespace App\Enums;

/**
 * 
 * @package App\Enum
 */

 enum AddressType: string 
 {
    case Shipping = 'shipping';
    case Billing = 'billing';

 }