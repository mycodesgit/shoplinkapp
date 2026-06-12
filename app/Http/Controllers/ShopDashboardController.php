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
        // Get products with variations and calculate available stock
        $products = Product::with(['variations' => function($query) {
            $query->with(['orderItems' => function($q) {
                $q->whereHas('order', function($q2) {
                    $q2->whereNotIn('status', ['cancelled', 'delivered']);
                });
            }]);
        }])->get();
        
        // Calculate available stock for each variation
        foreach ($products as $product) {
            foreach ($product->variations as $variation) {
                $orderedQuantity = $variation->orderItems->sum('quantity');
                $variation->available_stock = $variation->variant_stock - $orderedQuantity;
            }
        }
        $categories = Category::where('pcstatus', '=', 1)->orderBy('subcategory', 'ASC')->get();

        return view('customer.shop.items', compact('products', 'categories'));
    }

    public function about()
    {
        return view('customer.aboutapp.aboutappinfo');
    }
}
