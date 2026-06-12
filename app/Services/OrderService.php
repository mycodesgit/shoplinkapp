<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariation;

class OrderService
{
    /**
     * Create an order from cart items
     */
    public function createOrderFromCart($customerId, $cartItems, $orderData)
    {
        return DB::transaction(function () use ($customerId, $cartItems, $orderData) {
            // Calculate totals
            $subtotal = $this->calculateSubtotal($cartItems);
            $tax = $subtotal * 0.12;
            $deliveryFee = $orderData['delivery_method'] === 'delivery' ? 50 : 0;
            $total = $subtotal + $tax + $deliveryFee;
            
            // Create order
            $order = Order::create([
                'customer_id' => $customerId,
                'order_number' => $this->generateOrderNumber(),
                'status' => Order::STATUS_PENDING,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'delivery_fee' => $deliveryFee,
                'discount' => $orderData['discount'] ?? 0,
                'total' => $total,
                'customer_name' => $orderData['customer_name'],
                'customer_email' => $orderData['customer_email'] ?? null,
                'customer_phone' => $orderData['customer_phone'],
                'delivery_method' => $orderData['delivery_method'],
                'delivery_address' => $orderData['delivery_address'] ?? null,
                'payment_method' => $orderData['payment_method'],
                'payment_status' => Order::PAYMENT_STATUS_PENDING,
                'payment_details' => $orderData['payment_details'] ?? null,
                'notes' => $orderData['notes'] ?? null,
                'estimated_minutes' => 25
            ]);
            
            // Create order items
            foreach ($cartItems as $item) {
                $this->createOrderItem($order->id, $item);
                
                // Delete from cart
                Cart::where('id', $item['cart_id'])->delete();
            }
            
            return $order;
        });
    }
    
    /**
     * Create a single order item
     */
    public function createOrderItem($orderId, $item)
    {
        // Log the incoming item data
        //Log::info('Creating order item with data:', ['item' => $item]);
        
        // Get product name for snapshot
        $product = Product::find($item['product_id']);
        $variationName = null;
        $variationId = null;
        
        // Check if variation_id exists and is not empty
        if (isset($item['variation_id']) && !empty($item['variation_id'])) {
            $variationId = $item['variation_id'];
            Log::info('Variation ID found:', ['variation_id' => $variationId]);
            
            $variation = ProductVariation::find($variationId);
            if ($variation) {
                $variationName = $variation->variation_name . ': ' . $variation->variation_value;
                Log::info('Variation found:', ['variation_name' => $variationName]);
            } else {
                Log::warning('Variation not found in database:', ['variation_id' => $variationId]);
            }
        } else {
            Log::warning('No variation_id in item data:', ['item_keys' => array_keys($item)]);
        }
        
        $orderItemData = [
            'order_id' => $orderId,
            'product_id' => $item['product_id'],
            'variation_id' => $variationId, // Use the variable we set
            'product_name' => $product ? $product->prdctname : 'Product',
            'variation_name' => $variationName,
            'quantity' => $item['quantity'],
            'price' => $item['price'],
            'special_instructions' => $item['special_instructions'] ?? null
        ];
        
        //Log::info('Order item data to save:', $orderItemData);
        
        return OrderItem::create($orderItemData);
    }
    
    /**
     * Calculate subtotal from items
     */
    public function calculateSubtotal($items)
    {
        return collect($items)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
    }
    
    /**
     * Validate payment details based on payment method
     */
    public function validatePaymentMethod($paymentMethod, $paymentDetails)
    {
        if ($paymentMethod === 'gcash') {
            return !empty($paymentDetails['gcash_number']) && !empty($paymentDetails['gcash_name']);
        }
        
        if ($paymentMethod === 'maya') {
            return !empty($paymentDetails['maya_number']) && !empty($paymentDetails['maya_name']);
        }
        
        if ($paymentMethod === 'card') {
            return !empty($paymentDetails['card_number']) && 
                   !empty($paymentDetails['card_expiry']) && 
                   !empty($paymentDetails['card_cvc']) && 
                   !empty($paymentDetails['card_name']);
        }
        
        return true; // Cash payment always valid
    }
    
    /**
     * Generate unique order number
     */
    public function generateOrderNumber()
    {
        do {
            $orderNumber = 'ORD-' . strtoupper(substr(uniqid(), -6)) . rand(100, 999);
        } while (Order::where('order_number', $orderNumber)->exists());
        
        return $orderNumber;
    }
    
    /**
     * Update order status
     */
    public function updateOrderStatus($orderId, $status, $reason = null)
    {
        $order = Order::findOrFail($orderId);
        
        $updateData = ['status' => $status];
        
        switch ($status) {
            case Order::STATUS_ACCEPTED:
                $updateData['accepted_at'] = now();
                break;
            case Order::STATUS_READY_TO_CLAIM:
            case Order::STATUS_READY_TO_DELIVER:
                $updateData['ready_at'] = now();
                break;
            case Order::STATUS_DELIVERED:
                $updateData['delivered_at'] = now();
                break;
            case Order::STATUS_CANCELLED:
                $updateData['cancelled_at'] = now();
                $updateData['cancellation_reason'] = $reason;
                break;
        }
        
        $order->update($updateData);
        
        return $order;
    }
    
    /**
     * Calculate order totals
     */
    public function calculateOrderTotals($subtotal, $deliveryMethod)
    {
        $tax = $subtotal * 0.12;
        $deliveryFee = $deliveryMethod === 'delivery' ? 50 : 0;
        $total = $subtotal + $tax + $deliveryFee;
        
        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'delivery_fee' => $deliveryFee,
            'total' => $total
        ];
    }
    
    /**
     * Send order confirmation (email, SMS, etc.)
     */
    public function sendOrderConfirmation($order)
    {
        // Send email notification
        // Send SMS notification
        // Push notification
        // This can be extended with mail/SMS services
        //Log::info('Order confirmation sent for order: ' . $order->order_number);
        
        return true;
    }
}