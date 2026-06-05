<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\WelcomeBanner;
use App\Models\Category;
use App\Models\Product;

class ShopDashboardController extends Controller
{
    public function index()
    {
        $banner = WelcomeBanner::where('welcomestatus', 'Active')->first();
        $categories = Category::where('pcstatus', '=', 1)->orderBy('catname', 'ASC')->get();
        $products = Product::all();

        return view('customer.home.dashboard', compact('banner', 'categories', 'products'));
        //return view('customer.layouts.app', compact('banner', 'categories', 'products'));
    }

    public function store()
    {
        $products = Product::all();
        $categories = Category::all();

        return view('customer.shop.items', compact('products', 'categories'));
    }

    public function about()
    {
        return view('customer.aboutapp.aboutappinfo');
    }
}
