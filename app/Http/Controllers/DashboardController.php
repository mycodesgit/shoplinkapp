<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    use \App\Traits\CartTrait;

    public function index()
    {
        $cartCount = $this->getCartCount();

        return view('admin.home.dashboard', compact('cartCount'));
    }

    public function logout(Request $request)
    {
        if (\Auth::guard('web')->check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('getLogin')->with('success', 'You have been Successfully Logged Out');

        } elseif (\Auth::guard('customer')->check()) {
            Auth::guard('customer')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('dashboard.index')->with('success', 'You have been Successfully Logged Out');
        }

    }
}
