<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected array $messages = [
        'accepted' => 'Your order was accepted and is now in queue.',
        'preparing' => 'Your order is being prepared.',
        'ready_to_claim' => 'Your order is ready for pickup!',
        'ready_to_deliver' => 'Your order is ready and waiting for delivery.',
        'out_for_delivery' => 'Your order is out for delivery!',
        'delivered' => 'Your order has been delivered. Enjoy!',
        'cancelled' => 'Your order was cancelled.',
    ];

    public function __construct(public Order $order) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('customer.orders.' . $this->order->customer_id)];
    }

    public function broadcastAs(): string
    {
        return 'order.status';
    }

    public function broadcastWith(): array
    {
        $order = $this->order;

        return [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'status' => $order->status,
            'message' => $this->messages[$order->status] ?? 'Your order status changed to ' . $order->status . '.',
            'updated_at' => $order->updated_at?->format('M d, Y h:i A'),
        ];
    }
}
