<?php

namespace App\Http\Controllers;

use App\Services\OrderService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariation;


class ShopOrdersController extends Controller
{
    protected $orderService;
    
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function placeOrder(Request $request)
    {
        try {
            $customerId = Auth::guard('customer')->check() ? Auth::guard('customer')->id() : null;
            
            if (!$customerId) {
                return response()->json(['success' => false, 'message' => 'Please login'], 401);
            }
            
            // Validate request
            $validated = $request->validate([
                'full_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'nullable|email',
                'delivery_method' => 'required|in:pickup,delivery',
                'delivery_address' => 'required_if:delivery_method,delivery',
                'payment_method' => 'required|in:cash,gcash,maya,card',
                'payment_details' => 'nullable|array',
                'items' => 'required|array|min:1'
            ]);
            
            // Validate payment using service
            if (!$this->orderService->validatePaymentMethod(
                $validated['payment_method'], 
                $validated['payment_details'] ?? []
            )) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid payment details'
                ], 422);
            }
            
            // Prepare order data
            $orderData = [
                'customer_name' => $validated['full_name'],
                'customer_email' => $validated['email'] ?? null,
                'customer_phone' => $validated['phone'],
                'delivery_method' => $validated['delivery_method'],
                'delivery_address' => $validated['delivery_address'] ?? null,
                'payment_method' => $validated['payment_method'],
                'payment_details' => $validated['payment_details'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ];
            
            // Create order using service
            $order = $this->orderService->createOrderFromCart(
                $customerId,
                $validated['items'],
                $orderData
            );
            
            // Send confirmation using service
            $this->orderService->sendOrderConfirmation($order);
            
            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order_id' => $order->id,
                'order_number' => $order->order_number
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $customerId = Auth::guard('customer')->check() ? Auth::guard('customer')->id() : null;
        
        if (!$customerId) {
            return redirect()->route('shop.login')->with('error', 'Please login to view your orders');
        }

        $orders = Order::where('customer_id', $customerId)
            ->with(['items.product', 'items.variation'])
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($orders as $order) {
            $order->encrypted_id = Crypt::encryptString($order->id);
        }

        return view('customer.order.myorder', compact('orders'));
    }

    public function show($orderId)
    {
        $customerId = Auth::guard('customer')->check() ? Auth::guard('customer')->id() : null;
        
        if (!$customerId) {
            return redirect()->route('shop.login')->with('error', 'Please login to view order');
        }

        try {
            $decryptedId = Crypt::decryptString($orderId);
        } catch (\Exception $e) {
            abort(404, 'Invalid order ID');
        }

        $order = Order::where('customer_id', $customerId)
            ->with('items.product', 'items.variation')
            ->where('id', $decryptedId)
            ->firstOrFail();

        return view('customer.order.ordershow', compact('order'));
    }

    public function cancel($orderId)
    {
        try {
            $customerId = Auth::guard('customer')->check() ? Auth::guard('customer')->id() : null;
            
            if (!$customerId) {
                return response()->json(['success' => false, 'message' => 'Please login'], 401);
            }

            $order = Order::where('customer_id', $customerId)->findOrFail($orderId);

            if (!$order->canCancel()) {
                return response()->json(['success' => false, 'message' => 'This order cannot be cancelled'], 422);
            }

            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => 'Cancelled by customer'
            ]);

            return response()->json(['success' => true, 'message' => 'Order cancelled successfully']);
        } catch (\Exception $e) {
            Log::error('Order cancellation failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to cancel order'], 500);
        }
    }

    public function reorder($orderId)
    {
        try {
            $customerId = Auth::guard('customer')->check() ? Auth::guard('customer')->id() : null;
            
            if (!$customerId) {
                return response()->json(['success' => false, 'message' => 'Please login'], 401);
            }

            $order = Order::where('customer_id', $customerId)->with('items')->findOrFail($orderId);

            // Clear existing cart
            Cart::where('customer_id', $customerId)->delete();

            // Add items to cart
            foreach ($order->items as $item) {
                Cart::create([
                    'product_id' => $item->product_id,
                    'variation_id' => $item->variation_id,
                    'customer_id' => $customerId,
                    'quantity' => $item->quantity,
                    'price' => $item->price
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Items added to cart']);
        } catch (\Exception $e) {
            Log::error('Reorder failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to reorder'], 500);
        }
    }

    
}
