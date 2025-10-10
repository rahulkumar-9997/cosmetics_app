<?php

namespace App\Http\Controllers\Backend\CosmeticsApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
class VisitCustomerController extends Controller
{
   public function index()
    {
        $customers = Customer::select('id', 'firm_name', 'permanent_address')->get();
        return view('backend.cosmetics-app.visit-customer.index', compact('customers'));
    }

}
