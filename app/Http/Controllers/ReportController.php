<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ReportTrait;

class ReportController extends Controller
{
    use ReportTrait;
    public function orders()
   {
    $fromDate = $this->getFromDate();  
   }

   public function customers()
   {

   }
}