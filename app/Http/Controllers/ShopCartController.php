<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Cart;

class ShopCartController extends Controller
{
    use \App\Traits\CartTrait;

    /**
     * View cart contents
     */
    public function index()
    {
        $cartCount = $this->getCartCount();

        $customerId = Auth::guard('customer')->check() ? Auth::guard('customer')->id() : null;
        
        $cartItems = Cart::with('product', 'variation')
            ->where('customer_id', $customerId)
            ->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $tax = $subtotal * 0.12; // 12% VAT
        $total = $subtotal + $tax;

        return view('customer.cart.list', compact('cartItems', 'subtotal', 'tax', 'total'));
    }

    public function addToCart(Request $request)
    {
        try {
            if (!Auth::guard('customer')->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login to add items to cart',
                    'require_login' => true
                ], 401);
            }

            $request->validate([
                'product_id' => 'required|exists:products,id',
                'variation_id' => 'required|exists:product_variations,id',
                'quantity' => 'required|integer|min:1|max:999',
                'price' => 'required|numeric|min:0'
            ]);

            $variationId = $request->variation_id;
            $quantity = $request->quantity;
            $customerId = Auth::guard('customer')->id();
            
            // Get the variation to check stock
            $variation = ProductVariation::findOrFail($variationId);
            
            // Check if enough stock
            if ($variation->variant_stock < $quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough stock available. Only ' . $variation->variant_stock . ' left.'
                ], 400);
            }
            
            // Check if same variation already exists in cart
            $existingCartItem = Cart::where('variation_id', $variationId)
                ->where('customer_id', $customerId)
                ->first();
            
            if ($existingCartItem) {
                $newQuantity = $existingCartItem->quantity + $quantity;
                if ($variation->variant_stock < $newQuantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot add more. Only ' . $variation->variant_stock . ' items available.'
                    ], 400);
                }
                
                $existingCartItem->quantity = $newQuantity;
                $existingCartItem->save();
                $message = 'Cart updated successfully!';
            } else {
                Cart::create([
                    'product_id' => $request->product_id,
                    'variation_id' => $variationId,
                    'customer_id' => $customerId,
                    'quantity' => $quantity,
                    'price' => $request->price
                ]);
                $message = 'Product added to cart successfully!';
            }
            
            $cartCount = Cart::where('customer_id', $customerId)->sum('quantity');
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'cart_count' => $cartCount
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add product to cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request)
    {
        $cartItem = Cart::findOrFail($request->cart_id);
        
        // Update quantity
        $cartItem->quantity = $request->quantity;
        $cartItem->save();
        
        // Recalculate totals
        $customerId = Auth::guard('customer')->check() ? Auth::guard('customer')->id() : null;
        
        $cartItems = Cart::with('product', 'variation')
            ->where('customer_id', $customerId)
            ->get();
        
        $subtotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        
        $tax = $subtotal * 0.12;
        $total = $subtotal + $tax;
        $itemTotal = $cartItem->price * $cartItem->quantity;
        
        return response()->json([
            'success' => true,
            'cart_id' => $cartItem->id,
            'price' => (float) $cartItem->price,        // ADD THIS - critical!
            'quantity' => (int) $cartItem->quantity,
            'item_total' => (float) $itemTotal,
            'subtotal' => (float) $subtotal,
            'tax' => (float) $tax,
            'total' => (float) $total,
            'cart_count' => (int) $cartItems->count()
        ]);
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request)
    {
        $cartItem = Cart::findOrFail($request->cart_id);
        $cartItem->delete();
        
        // Recalculate totals
        $customerId = Auth::guard('customer')->check() ? Auth::guard('customer')->id() : null;
        
        $cartItems = Cart::with('product')
            ->where('customer_id', $customerId)
            ->get();
        
        $subtotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        
        $tax = $subtotal * 0.12;
        $total = $subtotal + $tax;
        
        return response()->json([
            'success' => true,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'cart_count' => $cartItems->sum('quantity')
        ]);
    }

    /**
     * Get cart count (for navbar badge)
     */
    public function getCartCount()
    {
        try {
            $customerId = Auth::guard('customer')->check() ? Auth::guard('customer')->id() : null;
            $cartCount = Cart::where('customer_id', $customerId)->sum('quantity');

            return response()->json([
                'success' => true,
                'cart_count' => $cartCount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'cart_count' => 0
            ]);
        }
    }

    /**
     * Clear entire cart
     */
    public function clearCart()
    {
        try {
            $customerId = Auth::guard('customer')->check() ? Auth::guard('customer')->id() : null;
            Cart::where('customer_id', $customerId)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cart'
            ], 500);
        }
    }

    /**
     * Get all cart items with product details
     */
    public function getCartItems()
    {
        try {
            $customerId = Auth::guard('customer')->check() ? Auth::guard('customer')->id() : null;
            
            $cartItems = Cart::with('product')
                ->where('customer_id', $customerId)
                ->get()
                ->map(function ($item) {
                    $options = json_decode($item->options, true);
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'name' => $item->product->prdctname ?? 'N/A',
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'formatted_price' => '₱' . number_format($item->price, 2),
                        'subtotal' => $item->price * $item->quantity,
                        'formatted_subtotal' => '₱' . number_format($item->price * $item->quantity, 2),
                        'options' => $options['options'] ?? 'M',
                        'image' => $item->product && $item->product->prdctimage 
                            ? asset('storage/' . json_decode($item->product->prdctimage, true)[0] ?? '') 
                            : asset('storage/products/default.png')
                    ];
                });

            $subtotal = $cartItems->sum('subtotal');
            $tax = $subtotal * 0.12;
            $total = $subtotal + $tax;

            return response()->json([
                'success' => true,
                'items' => $cartItems,
                'subtotal' => $subtotal,
                'formatted_subtotal' => '₱' . number_format($subtotal, 2),
                'tax' => $tax,
                'formatted_tax' => '₱' . number_format($tax, 2),
                'total' => $total,
                'formatted_total' => '₱' . number_format($total, 2),
                'item_count' => $cartItems->count(),
                'total_quantity' => $cartItems->sum('quantity')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch cart items'
            ], 500);
        }
    }

    /**
     * Checkout - Prepare cart for checkout
     */
    public function checkout()
    {
        $customerId = Auth::guard('customer')->check() ? Auth::guard('customer')->id() : null;
        
        if (!$customerId) {
            return redirect()->route('shop.login')->with('error', 'Please login to checkout');
        }

        $cartItems = Cart::with('product')
            ->where('customer_id', $customerId)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty');
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $tax = $subtotal * 0.12;
        $total = $subtotal + $tax;

        return view('customer.cart.checkout', compact('cartItems', 'subtotal', 'tax', 'total'));
    }
}
