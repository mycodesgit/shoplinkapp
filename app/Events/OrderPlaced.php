<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderPlaced implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Order $order) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('admin.orders')];
    }

    public function broadcastAs(): string
    {
        return 'order.placed';
    }

    public function broadcastWith(): array
    {
        $order = $this->order;

        return [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'customer' => $order->customer_name,
            'total' => (float) $order->total,
            'items_count' => $order->items_count ?? $order->items()->count(),
            'status' => $order->status,
            'placed_at' => $order->created_at?->format('M d, Y h:i A'),
        ];
    }
}
