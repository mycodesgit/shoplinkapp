<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopLoginController extends Controller
{
    public function index()
    {
        return view('customer.login');
    }
}
