<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariation;

class ProductsController extends Controller
{
    public function index()
    {
        $categories = Category::where('pcstatus', '=', '1')->get();

        return view('admin.items.products', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'catid'       => 'required|integer',
            'subcatid'    => 'required|integer',
            'prdctname'   => 'required|string|max:255',
            'prdctdesc'   => 'nullable|string',
            'prdcttag'    => 'required|string',
            'prdctimage.*' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            
            // Variations validation
            'variations' => 'required|array|min:1',
            'variations.*.name' => 'required|string',
            'variations.*.value' => 'required|string',
            'variations.*.sku' => 'required|string|distinct',
            'variations.*.price' => 'required|numeric|min:0',
            'variations.*.stock' => 'required|integer|min:0',
        ]);

        try {
            // Store main product images
            $productImages = [];
            if ($request->hasFile('prdctimage')) {
                foreach ($request->file('prdctimage') as $image) {
                    $productImages[] = $image->store('products', 'public');
                }
            }

            // Create the product (NO price, stock, sku here)
            $product = Product::create([
                'catid'       => $request->catid,
                'subcatid'    => $request->subcatid,
                'prdctname'   => $request->prdctname,
                'prdctdesc'   => $request->prdctdesc,
                'prdctimage'  => json_encode($productImages),
                'prdcttag'    => $request->prdcttag,
                'postedBy'    => auth()->id(),
            ]);

            // Create variations (ALL pricing, stock, sku info goes here)
            foreach ($request->variations as $variation) {
                // Handle variation image if uploaded
                $variationImagePath = null;
                if (isset($variation['image']) && $variation['image'] instanceof \Illuminate\Http\UploadedFile) {
                    $variationImagePath = $variation['image']->store('product-variations', 'public');
                }
                
                ProductVariation::create([
                    'variant_product_id' => $product->id,
                    'variation_name' => $variation['name'],
                    'variation_value' => $variation['value'],
                    'variant_sku' => $variation['sku'],
                    'variant_price' => $variation['price'],
                    'variant_stock' => $variation['stock'],
                    'variant_image' => $variationImagePath,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Product uploaded successfully!',
                'product_id' => $product->id,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Upload failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show()
    {
        try {
            // Get all products with their variations
            $products = Product::with(['variations', 'category'])
                ->join('category', 'products.catid', '=', 'category.id')
                ->select(
                    'products.*',
                    'category.catname'
                )
                ->get();
            
            // Transform to show each variation as a separate row
            $data = [];
            
            foreach ($products as $product) {
                // If product has variations, create a row for each variation
                if ($product->variations->count() > 0) {
                    foreach ($product->variations as $variation) {
                        // Calculate available stock for this variation
                        $orderedQuantity = \App\Models\OrderItem::where('variation_id', $variation->id)
                            ->whereHas('order', function($query) {
                                $query->whereNotIn('status', ['cancelled', 'delivered']);
                            })
                            ->sum('quantity');
                        
                        $availableStock = $variation->variant_stock - $orderedQuantity;
                        
                        // Determine which image to use (variation image > product image)
                        $imageToUse = $variation->variant_image ?? $product->prdctimage;
                        
                        $data[] = [
                            'id' => $product->id,
                            'variation_id' => $variation->id,
                            'prdctname' => $product->prdctname . ' - ' . $variation->variation_name . ': ' . $variation->variation_value,
                            'prdctdesc' => $product->prdctdesc,
                            'prdctimage' => $product->prdctimage,
                            'variant_image' => $variation->variant_image, // Add variation image
                            'display_image' => $imageToUse, // Image to display (priority to variation image)
                            'catid' => $product->catid,
                            'subcatid' => $product->subcatid,
                            'catname' => $product->catname,
                            'variant_price' => $variation->variant_price,
                            'variant_sku' => $variation->variant_sku,
                            'variant_stock' => $variation->variant_stock,
                            'available_stock' => $availableStock,
                            'variation_name' => $variation->variation_name,
                            'variation_value' => $variation->variation_value,
                            'product_name' => $product->prdctname,
                        ];
                    }
                } else {
                    // If no variations, create a single row for the product
                    $data[] = [
                        'id' => $product->id,
                        'variation_id' => null,
                        'prdctname' => $product->prdctname,
                        'prdctdesc' => $product->prdctdesc,
                        'prdctimage' => $product->prdctimage,
                        'variant_image' => null,
                        'display_image' => $product->prdctimage,
                        'catid' => $product->catid,
                        'subcatid' => $product->subcatid,
                        'catname' => $product->catname,
                        'variant_price' => $product->variations->min('variant_price') ?? 0,
                        'variant_sku' => 'N/A',
                        'variant_stock' => 0,
                        'available_stock' => 0,
                        'variation_name' => null,
                        'variation_value' => null,
                        'product_name' => $product->prdctname,
                    ];
                }
            }
            
            return response()->json(['data' => $data]);
            
        } catch (\Exception $e) {
            \Log::error('Product show error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to fetch products',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request) 
    {
        $user = Product::find($request->id);
        
        $request->validate([
            'id' => 'required',
            'catid'  => 'required|integer',
            'subcatid'  => 'required|integer',
            'prdctname'   => 'required|string|max:255',
            'prdctdesc'   => 'required|string',
            'prdctcode'   => 'required|string|max:100',
            'prdctprice'  => 'required|numeric',
            'prdctstock'  => 'required|integer',
            'prdctimage'   => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        try {
            $prdct = Product::findOrFail($request->input('id'));

            if ($request->hasFile('prdctimage')) {
                if ($prdct->prdctimage && Storage::disk('public')->exists(str_replace('storage/', '', $prdct->prdctimage))) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $prdct->prdctimage));
                }


                $path = $request->file('prdctimage')->store('products', 'public');
                $prdct->prdctimage = $path;
            }

            $prdct->update([
                'catid' => $request->catid,
                'subcatid' => $request->subcatid,
                'prdctname'  => $request->prdctname,
                'prdctdesc'  => $request->prdctdesc,
                'prdctcode'  => $request->prdctcode,
                'prdctprice' => $request->prdctprice,
                'prdctstock' => $request->prdctstock,
                'prdctimage' => $prdct->prdctimage,
            ]);

            return response()->json(['success' => true, 'message' => 'User Password updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to update User Password!']);
        }
    }

    public function destroy($id) {
        $prdct = Product::find($id);
        if ($prdct) {
            $prdct->pstatus = 3;
            $prdct->save();
            return response()->json(['success'=> true, 'message'=>'Product deleted successfully']);
        }
        return response()->json(['error'=> true, 'message'=>'Product not found']);
    }
}
