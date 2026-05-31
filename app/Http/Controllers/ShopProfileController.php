<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopProfileController extends Controller
{
    public function index()
    {
        return view('customer.profile.account');
    }
}
