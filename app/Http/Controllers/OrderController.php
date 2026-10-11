<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Next valid statuses for an order. A preparing order only exposes the
     * ready-state that matches its fulfilment method (no duplicate actions).
     */
    protected function nextStatuses(Order $order): array
    {
        $next = $this->transitions[$order->status] ?? [];

        if ($order->status === 'preparing') {
            $keep = $order->delivery_method === 'delivery' ? 'ready_to_deliver' : 'ready_to_claim';
            $next = array_values(array_filter(
                $next,
                fn ($s) => $s === $keep || ($s !== 'ready_to_claim' && $s !== 'ready_to_deliver')
            ));
        }

        return $next;
    }

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
                    'next_statuses' => $this->nextStatuses($order),
                    'created_at' => $order->created_at ? $order->created_at->format('M d, Y h:i A') : '',
                ];
            });

        return response()->json(['data' => $data]);
    }

    public function details($id)    {
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
                'next_statuses' => $this->nextStatuses($order),
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

    public function receipt($id)
    {
        $order = Order::with(['customer', 'items'])->find($id);

        if (!$order) {
            abort(404, 'Order not found');
        }

        return view('admin.order.receipt', compact('order'));
    }

    /**
     * Individual packing slip rendered as PDF (for modal iframe preview).
     */
    public function receiptPdf($id)
    {
        $order = Order::with(['customer', 'items'])->find($id);

        if (!$order) {
            abort(404, 'Order not found');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.order.receipt', compact('order'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('packing-slip-' . $order->order_number . '.pdf');
    }

    /**
     * Bulk packing stickers (4-up on A4) for all accepted orders, as PDF.
     */
    public function bulkStickers()
    {
        $orders = Order::with(['customer', 'items'])
            ->where('status', 'accepted')
            ->orderBy('created_at', 'ASC')
            ->get();

        if ($orders->isEmpty()) {
            return response()->view('admin.order.bulk-empty', [], 200);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.order.stickers', [
            'pages' => $orders->chunk(4),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('packing-stickers-accepted.pdf');
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

            // Live customer notification (never break the admin flow if Reverb is down)
            try {
                \App\Events\OrderStatusUpdated::dispatch($order->fresh());
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('OrderStatusUpdated broadcast failed: ' . $e->getMessage());
            }

            return response()->json(['success' => true, 'message' => "Order {$next} successfully!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to update order status!'], 500);
        }
    }
}
