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
    public function checkout(Request $request)
    {
        $customerId = Auth::guard('customer')->check() ? Auth::guard('customer')->id() : null;
        
        if (!$customerId) {
            return redirect()->route('shop.login')->with('error', 'Please login to checkout');
        }

        // Get selected items from the request
        $selectedItemIds = $request->input('selected_items');
        
        if (!$selectedItemIds) {
            return redirect()->route('cart.auth.index')->with('error', 'No items selected for checkout');
        }
        
        // Decode the JSON array of selected cart IDs
        $selectedIds = json_decode($selectedItemIds, true);
        
        if (empty($selectedIds)) {
            return redirect()->route('cart.auth.index')->with('error', 'No items selected for checkout');
        }
        
        // Get only the selected cart items
        $cartItems = Cart::with('product', 'variation')
            ->where('customer_id', $customerId)
            ->whereIn('id', $selectedIds)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.auth.index')->with('error', 'Selected items not found');
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $tax = $subtotal * 0.12;
        $total = $subtotal + $tax;

        session()->forget('buy_now');

        return view('customer.cart.checkout', array_merge(
            compact('cartItems', 'subtotal', 'tax', 'total'),
            ['savedAddress' => $this->savedAddress()]
        ));
    }

    /**
     * Customer's saved address composed from profile fields
     */
    protected function savedAddress()
    {
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            return '';
        }

        if (!empty($customer->address)) {
            return $customer->address;
        }

        $parts = array_filter([
            $customer->hnum,
            $customer->brgy,
            $customer->city,
            $customer->province,
            $customer->region,
            $customer->zcode,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Buy Now - stage a single item for direct checkout (no cart involved)
     */
    public function buyNow(Request $request)
    {
        $customerId = Auth::guard('customer')->check() ? Auth::guard('customer')->id() : null;

        if (!$customerId) {
            return response()->json(['success' => false, 'message' => 'Please login to continue', 'require_login' => true], 401);
        }

        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'variation_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        $variation = ProductVariation::where('id', $validated['variation_id'])
            ->where('variant_product_id', $validated['product_id'])
            ->first();

        if (!$variation) {
            return response()->json(['success' => false, 'message' => 'Invalid product variation'], 422);
        }

        $available = $variation->available_stock ?? $variation->variant_stock;

        if ($available <= 0) {
            return response()->json(['success' => false, 'message' => 'This item is out of stock'], 422);
        }

        if ($validated['quantity'] > $available) {
            return response()->json(['success' => false, 'message' => "Only {$available} available in stock"], 422);
        }

        session(['buy_now' => [
            'product_id' => $validated['product_id'],
            'variation_id' => $variation->id,
            'quantity' => $validated['quantity'],
        ]]);

        return response()->json(['success' => true, 'redirect' => route('cart.buyNowCheckout')]);
    }

    /**
     * Buy Now checkout page - renders the standard checkout with the staged item
     */
    public function buyNowCheckout()
    {
        $customerId = Auth::guard('customer')->check() ? Auth::guard('customer')->id() : null;

        if (!$customerId) {
            return redirect()->route('shop.login')->with('error', 'Please login to checkout');
        }

        $buyNow = session('buy_now');

        if (!$buyNow) {
            return redirect()->route('dashboard.auth.items')->with('error', 'No item selected for quick checkout');
        }

        $product = Product::find($buyNow['product_id']);
        $variation = ProductVariation::where('id', $buyNow['variation_id'])
            ->where('variant_product_id', $buyNow['product_id'])
            ->first();

        if (!$product || !$variation) {
            session()->forget('buy_now');
            return redirect()->route('dashboard.auth.items')->with('error', 'Selected item is no longer available');
        }

        $available = $variation->available_stock ?? $variation->variant_stock;
        $quantity = min($buyNow['quantity'], max($available, 0));

        if ($quantity < 1) {
            session()->forget('buy_now');
            return redirect()->route('dashboard.auth.items')->with('error', 'Selected item is out of stock');
        }

        $cartItem = new Cart([
            'product_id' => $product->id,
            'variation_id' => $variation->id,
            'customer_id' => $customerId,
            'quantity' => $quantity,
            'price' => $variation->variant_price,
        ]);
        $cartItem->setRelation('product', $product);
        $cartItem->setRelation('variation', $variation);

        $cartItems = collect([$cartItem]);

        $subtotal = $cartItem->price * $cartItem->quantity;
        $tax = $subtotal * 0.12;
        $total = $subtotal + $tax;

        return view('customer.cart.checkout', array_merge(
            compact('cartItems', 'subtotal', 'tax', 'total'),
            ['savedAddress' => $this->savedAddress()]
        ));
    }
}
