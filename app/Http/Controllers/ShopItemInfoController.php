<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\Category;
use App\Models\Product;

class ShopItemInfoController extends Controller
{
    public function index($id)
    {
        $product = Product::findOrFail($id);
        
        return view('customer.shop.details', compact('product'));
    }
}
