<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopOrdersController extends Controller
{
    public function index()
    {
        return view('customer.order.myorder');
    }
}
