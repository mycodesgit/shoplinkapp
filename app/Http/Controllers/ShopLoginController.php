<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

use App\Models\Customer;
use App\Models\Cart;

class ShopLoginController extends Controller
{
    protected function authenticated(Request $request, $user)
    {
        // You can also merge on the server side if you store guest cart in session
        // But since you don't use sessions, this is handled in JavaScript
        
        return redirect()->intended();
    }
    public function index()
    {
        return view('customer.login');
    }

    public function customerlogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required|min:5|max:20',
        ]);

        $customer = Customer::where('email', $request->email)->first();

        if (!$customer) {
            return redirect()->back()->with('error', 'Invalid Credentials');
        }

        $validatedCustomer = auth()->guard('customer')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if($validatedCustomer) {
            return redirect()->route('dashboard.auth.index')->with('success', 'You have successfully logged in.');
        } 
        else {
            return redirect()->back()->with('error', 'Invalid Credentials');
        }
    }
}
