<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopCartController extends Controller
{
    public function index()
    {
        return view('customer.cart.list');
    }
}
