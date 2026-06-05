<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

trait CartTrait
{
    /**
     * Get cart count (number of rows/items in cart)
     * Each unique product+size combination counts as 1
     */
    public function getCartCount()
    {
        try {
            $customerId = Auth::guard('customer')->check() ? Auth::guard('customer')->id() : null;
            
            // If not logged in, return 0
            if (!$customerId) {
                return 0;
            }
            
            // Count number of rows (distinct items)
            return Cart::where('customer_id', $customerId)->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get cart count as JSON response (for AJAX real-time updates)
     * Counts number of rows/items
     */
    public function getCartCountJson()
    {
        try {
            $customerId = Auth::guard('customer')->check() ? Auth::guard('customer')->id() : null;
            
            if (!$customerId) {
                return response()->json([
                    'success' => true,
                    'cart_count' => 0,
                    'is_logged_in' => false
                ]);
            }
            
            // Count number of rows (distinct items)
            $cartCount = Cart::where('customer_id', $customerId)->count();

            return response()->json([
                'success' => true,
                'cart_count' => $cartCount,
                'is_logged_in' => true
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'cart_count' => 0,
                'message' => 'Failed to fetch cart count'
            ]);
        }
    }
}