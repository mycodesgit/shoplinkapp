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
use App\Models\ProductVariation;

class ShopDashboardController extends Controller
{
    public function index()
    {
        $banner = WelcomeBanner::where('welcomestatus', 'Active')->first();
        $categories = Category::where('pcstatus', '=', 1)->orderBy('subcategory', 'ASC')->get();
        $products = Product::with('variations')->get();

        return view('customer.home.dashboard', compact('banner', 'categories', 'products'));
    }

    public function store()
    {
        $products = Product::with('variations')->get();
        $categories = Category::where('pcstatus', '=', 1)->orderBy('subcategory', 'ASC')->get();

        return view('customer.shop.items', compact('products', 'categories'));
    }

    public function about()
    {
        return view('customer.aboutapp.aboutappinfo');
    }
}
