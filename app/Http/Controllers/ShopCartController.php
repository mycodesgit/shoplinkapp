<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Models\Product;
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
        
        $cartItems = Cart::with('product')
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
                'quantity' => 'required|integer|min:1|max:999',
                'size' => 'nullable|string|max:10'
            ]);

            $productId = $request->product_id;
            $quantity = $request->quantity;
            $size = $request->size ?? 'M';
            $product = Product::findOrFail($productId);
            $customerId = Auth::guard('customer')->id();
            
            // Get all cart items for this product and customer
            $cartItems = Cart::where('product_id', $productId)
                ->where('customer_id', $customerId)
                ->get();
            
            $existingCartItem = null;
            
            // Loop through items to find matching size
            foreach ($cartItems as $item) {
                $options = json_decode($item->options, true);
                if (isset($options['size']) && $options['size'] === $size) {
                    $existingCartItem = $item;
                    break;
                }
            }

            if ($existingCartItem) {
                $existingCartItem->quantity += $quantity;
                $existingCartItem->save();
                $message = 'Cart updated successfully!';
            } else {
                Cart::create([
                    'product_id' => $productId,
                    'customer_id' => $customerId,
                    'quantity' => $quantity,
                    'price' => $product->prdctprice,
                    'options' => json_encode(['size' => $size])
                ]);
                $message = 'Product added to cart successfully!';
            }

            $cartCount = Cart::where('customer_id', $customerId)->count();

            return response()->json([
                'success' => true,
                'message' => $message,
                'cart_count' => $cartCount
            ]);

        } catch (\Exception $e) {
            // \Log::error('Add to cart error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add product to cart'
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
        
        $cartItems = Cart::with('product')
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
            'quantity' => $cartItem->quantity, // Add this line
            'item_total' => $itemTotal,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'cart_count' => $cartItems->count()
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

        return view('customer.checkout.index', compact('cartItems', 'subtotal', 'tax', 'total'));
    }
}
