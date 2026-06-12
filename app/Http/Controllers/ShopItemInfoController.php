<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\OrderItem;

class ShopItemInfoController extends Controller
{
    public function index($id)
    {
        $decryptedId = Crypt::decryptString($id);
        // Get product with variations and calculate available stock
        $product = Product::with(['variations' => function($query) {
            $query->with(['orderItems' => function($q) {
                $q->whereHas('order', function($q2) {
                    $q2->whereNotIn('status', ['cancelled', 'delivered']);
                });
            }]);
        }, 'category'])->findOrFail($decryptedId);
        
        // Calculate available stock for each variation
        $variationsArray = [];
        foreach ($product->variations as $variation) {
            $orderedQuantity = $variation->orderItems->sum('quantity');
            $availableStock = $variation->variant_stock - $orderedQuantity;
            
            // Store the available stock on the variation object
            $variation->available_stock = $availableStock;
            $variation->physical_stock = $variation->variant_stock;
            
            // Add to array for JavaScript
            $variationsArray[] = [
                'id' => $variation->id,
                'variation_name' => $variation->variation_name,
                'variation_value' => $variation->variation_value,
                'variant_price' => $variation->variant_price,
                'variant_stock' => $variation->variant_stock,
                'available_stock' => $availableStock,
                'variant_image' => $variation->variant_image
            ];
        }
        
        // Calculate total available stock for the product
        $totalAvailableStock = $product->variations->sum('available_stock');
        $product->total_available_stock = $totalAvailableStock;
        
        // Collect ALL variation images from ALL variations with available stock info
        $allVariationImages = [];
        foreach($product->variations as $variation) {
            $availableStock = $variation->available_stock ?? $variation->variant_stock;
            
            if ($variation->variant_image && $availableStock > 0) {
                // Parse variant_image (could be JSON or string)
                $varImages = [];
                if (is_string($variation->variant_image)) {
                    if (str_starts_with($variation->variant_image, '[')) {
                        $varImages = json_decode($variation->variant_image, true);
                    } else {
                        $varImages = [$variation->variant_image];
                    }
                } elseif (is_array($variation->variant_image)) {
                    $varImages = $variation->variant_image;
                }
                
                // Add to collection with variation info
                foreach($varImages as $img) {
                    if (!empty($img)) {
                        $allVariationImages[] = [
                            'image' => $img,
                            'variation_name' => $variation->variation_value,
                            'variation_type' => $variation->variation_name,
                            'price' => $variation->variant_price,
                            'stock' => $availableStock,
                            'variation_id' => $variation->id
                        ];
                    }
                }
            }
        }
        
        // Get product images
        $images = json_decode($product->prdctimage, true);
        $firstImage = is_array($images) ? ($images[0] ?? null) : $product->prdctimage;
        
        return view('customer.shop.details', compact('product', 'allVariationImages', 'variationsArray', 'images', 'firstImage'));
    }
}