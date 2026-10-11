<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Allowed status transitions per current status.
     * Delivery orders flow through out_for_delivery; pickup orders go ready_to_claim -> delivered.
     */
    protected array $transitions = [
        'pending' => ['accepted', 'cancelled'],
        'accepted' => ['preparing', 'cancelled'],
        'preparing' => ['ready_to_claim', 'ready_to_deliver', 'cancelled'],
        'ready_to_claim' => ['delivered', 'cancelled'],
        'ready_to_deliver' => ['out_for_delivery', 'cancelled'],
        'out_for_delivery' => ['delivered'],
        'delivered' => [],
        'cancelled' => [],
    ];

    public function index()
    {
        return view('admin.order.listorder');
    }

    public function show()
    {
        $data = Order::with('customer')
            ->withCount('items')
            ->orderBy('created_at', 'DESC')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_name' => $order->customer
                        ? trim($order->customer->fname . ' ' . ($order->customer->lname ?? ''))
                        : ($order->customer_name ?? 'Guest'),
                    'customer_phone' => $order->customer_phone ?? optional($order->customer)->mobileno,
                    'items_count' => $order->items_count,
                    'total' => $order->total,
                    'payment_method' => $order->payment_method,
                    'payment_status' => $order->payment_status,
                    'delivery_method' => $order->delivery_method,
                    'status' => $order->status,
                    'next_statuses' => $this->transitions[$order->status] ?? [],
                    'created_at' => $order->created_at ? $order->created_at->format('M d, Y h:i A') : '',
                ];
            });

        return response()->json(['data' => $data]);
    }

    public function details($id)
    {
        $order = Order::with(['customer', 'items'])->find($id);

        if (!$order) {
            return response()->json(['error' => true, 'message' => 'Order not found'], 404);
        }

        return response()->json([
            'success' => true,
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'customer_name' => $order->customer
                    ? trim($order->customer->fname . ' ' . ($order->customer->mname ?? '') . ' ' . ($order->customer->lname ?? ''))
                    : ($order->customer_name ?? 'Guest'),
                'customer_email' => $order->customer_email ?? optional($order->customer)->email,
                'customer_phone' => $order->customer_phone ?? optional($order->customer)->mobileno,
                'delivery_method' => $order->delivery_method,
                'delivery_address' => $order->delivery_address,
                'payment_method' => $order->payment_method,
                'payment_status' => $order->payment_status,
                'subtotal' => $order->subtotal,
                'tax' => $order->tax,
                'delivery_fee' => $order->delivery_fee,
                'discount' => $order->discount,
                'total' => $order->total,
                'notes' => $order->notes,
                'created_at' => $order->created_at ? $order->created_at->format('M d, Y h:i A') : '',
                'next_statuses' => $this->transitions[$order->status] ?? [],
                'items' => $order->items->map(fn ($item) => [
                    'product_name' => $item->product_name,
                    'variation_name' => $item->variation_name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->subtotal ?? ($item->price * $item->quantity),
                ]),
            ],
        ]);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'status' => 'required|string',
        ]);

        $order = Order::find($request->input('id'));

        if (!$order) {
            return response()->json(['error' => true, 'message' => 'Order not found'], 404);
        }

        $next = $request->input('status');
        $allowed = $this->transitions[$order->status] ?? [];

        if (!in_array($next, $allowed, true)) {
            return response()->json([
                'error' => true,
                'message' => "Cannot move order from {$order->status} to {$next}.",
            ], 422);
        }

        $timestamps = [
            'accepted' => 'accepted_at',
            'ready_to_claim' => 'ready_at',
            'ready_to_deliver' => 'ready_at',
            'delivered' => 'delivered_at',
            'cancelled' => 'cancelled_at',
        ];

        $update = ['status' => $next];

        if (isset($timestamps[$next])) {
            $update[$timestamps[$next]] = now();
        }

        if ($next === 'cancelled') {
            $update['cancellation_reason'] = $request->input('reason', 'Cancelled by admin');
        }

        try {
            $order->update($update);

            return response()->json(['success' => true, 'message' => "Order {$next} successfully!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to update order status!'], 500);
        }
    }
}
