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

class ShopRegisterController extends Controller
{
    public function index()
    {
        return view('customer.register');
    }

    public function create(Request $request) 
    {

        if ($request->isMethod('post')) {
            $request->validate([
                'lname' => 'required',
                'fname' => 'required',
                'mname' => 'required',
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:5',
            ]);

            $emailName = $request->input('email'); 
            $existingEmail = Customer::where('email', $emailName)->first();

            if ($existingEmail) {
                return response()->json(['error' => true, 'message' => 'Your account already exists'], 404);
            }

            try {
                $userid = Customer::create([
                    'lname' => $request->input('lname'),
                    'fname' => $request->input('fname'),
                    'mname' => $request->input('mname'),
                    'email' => $emailName,
                    'password' => Hash::make($request->input('password')),
                    'remember_token' => Str::random(60),
                ]);

                return response()->json(['success' => true, 'message' => 'Your account has been created successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to create account'], 404);
            }
        }
    }
}
